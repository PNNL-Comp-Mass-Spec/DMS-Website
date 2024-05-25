<?php
namespace App\Libraries;

// Not required because we are telling CodeIgniter to also use the Composer autoload.
// require 'vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Spreadsheet_loader {

    private $ss_rows = array();
    private $rowStyle = false;
    private $auxInfoCol = -1;
    private $headerRow = -1;
    private $tracking_info_fields = array();
    private $aux_info_fields = array();
    private $aux_info_groups = array();
    private $entity_type = '';
    private $entity_list = array();

    /**
     * Constructor
     */
    function __construct() {

    }

    /**
     * Get tracking info fields
     * @return type
     */
    function get_tracking_info_fields() {
        return $this->tracking_info_fields;
    }

    /**
     * Get Aux Info fields
     * @return type
     */
    function get_aux_info_fields() {
        return $this->aux_info_fields;
    }

    /**
     * Read given spreadsheet TSV formatted file into an internal two-dimensional
     * array and parse it into supplementary arrays
     * @param type $fname
     * @category AJAX
     */
    function load($fname) {
        $filePath = WRITEPATH . "uploads/$fname";

        $mimeType = mime_content_type($filePath);
        $isSpreadsheet = false;

        if (strpos($mimeType, 'spreadsheetml') > 0 || strpos($mimeType, 'ms-excel')) {
            // Excel file (either .xls or .xlsx)
//            throw new \Exception(
//                "Save the Excel file as a tab-delimited text file: "
//                . "Choose File, then Save As, then for Type select Text");
            $isSpreadsheet = true;
        }

        if (strpos($mimeType, 'opendocument.spreadsheet') > 0) {
            // OpenOffice .ODS file
//            throw new \Exception(
//                "Save the spreadsheet as a tab-delimited text file: "
//                . "Choose File, then Save As, then for Type select Text CSV; "
//                . "for the Field Delimiter select {TAB}");
            $isSpreadsheet = true;
        }

        if (!$isSpreadsheet && strpos($mimeType, 'octet-stream') > 0) {
            // Likely a unicode text file
            throw new \Exception(
            "Unicode text files are not supported. Save as a plain text file with ASCII or UTF-8 encoding");
        }

        if (!$isSpreadsheet && $mimeType !== 'text/plain') {
            throw new \Exception("Upload a plain text file, not a file of type: $mimeType");
        }

        if ($isSpreadsheet) {
            // Handle a spreadsheet file.
            $this->load_spreadsheet_file($filePath);
        } else {
            // Handle a plain text file.
            $this->load_text_file($filePath);
        }

        // Examine the data to replace ascii characters above ascii 127 with the corresponding HTML code string
        // For example, the non-breaking space hex A0 is replaced with &#160;

        $loadedRowCount = count($this->ss_rows);
        $stripCharsLineNum = -1;
        $stripCharsColNum = -1;

        for ($i = 0; $i < $loadedRowCount; $i++) {
            $colCount = count($this->ss_rows[$i]);
            if ($colCount == 0) {
                continue;
            }

            if (trim(strtoupper($this->ss_rows[$i][0])) == "TRACKING INFORMATION") {
                for ($j = 1; $j < $colCount; $j++) {
                    $contents = trim(strtoupper($this->ss_rows[$i][$j]));
                    if ($contents == "AUXILIARY INFORMATION") {
                        $this->rowStyle = true;
                        $this->headerRow = $i + 1;
                        $this->auxInfoCol = $j;
                    } else if ($contents == "ROWS") {
                        $this->rowStyle = true;
                        $this->headerRow = $i + 1;
                    }
                }
                // The next line will have experiment names, dataset names, etc.
                // Strip out characters above ascii 127 since those aren't allowed in DMS
                if ($this->rowStyle) {
                    $stripCharsColNum = 0;
                } else {
                    $stripCharsLineNum = $i + 1;
                }
            }

            for ($j = 0; $j < $colCount; $j++) {
                if (($i == $stripCharsLineNum && $j > 0) || $j == $stripCharsColNum) {
                    $sanitized = filter_var(trim($this->ss_rows[$i][$j]), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                } else {
                    $sanitized = filter_var(trim($this->ss_rows[$i][$j]), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
                }

                if ($this->ss_rows[$i][$j] != $sanitized) {
                    $this->ss_rows[$i][$j] = $sanitized;
                }
            }
        }

        // Figure out where things are and build supplemental arrays
        $this->find_tracking_info_fields();
        $this->find_aux_info_fields();
        $this->extract_entity_type();
        $this->extract_entity_list();
    }

    private function load_text_file($filePath) {
        // Enable auto-detection of line endings
        // This is especially important for reading text files saved from Excel on a Mac
        ini_set("auto_detect_line_endings", "1");

        // Read the TSV file into an array of rows of fields
        $this->ss_rows = array();
        $handle = fopen($filePath, "r");

        $rowCount = 0;
        while (($fields = fgetcsv($handle, 0, "\t")) !== false) {
            $rowCount++;
            if ($rowCount == 1 && count($fields) > 0 && strlen($fields[0]) > 3) {
                // Check for a UTF-8 file, which starts with:
                // ASCII 239, ï
                // ASCII 187, »
                // ASCII 191, ¿
                if (ord($fields[0][0]) == 239 &&
                        ord($fields[0][1]) == 187 &&
                        ord($fields[0][2]) == 191) {
                    // This is a UTF-8 file; remove the first three characters from the first field
                    $fields[0] = substr($fields[0], 3);
                }
            }

            $this->ss_rows[] = $fields;
        }
        fclose($handle);
    }

    private function load_spreadsheet_file($filePath) {
        // Identify the file type
        $readerType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filePath);
        // Create a reader for that file type
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($readerType);
        // Set reader options
        //$reader ->setReadDataOnly(true); // don't enable this - it will break proper date reading
        // Load the file
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $this->ss_rows = $worksheet->toArray("");
    }

    /**
     * Get list of tracking info fields and their row number
     * @throws exception
     */
    private function find_tracking_info_fields() {
        $this->tracking_info_fields = array();
        $grab_it = false;
        if ($this->rowStyle) {
            $headerRow = -1;
            for ($i = 0; $i < count($this->ss_rows); $i++) {
                if ($this->ss_rows[$i][0] == "TRACKING INFORMATION") {
                    $headerRow = $i + 1;
                    $grab_it = true;
                    // Find AUXILIARY INFORMATION
                    // if ($this->ss_rows[$i][0] == "AUXILIARY INFORMATION") {
                    //     $end_it = true;
                    //     break;
                    // }
                    break;
                }
            }
            if ($grab_it) {
                for ($j = 0; $j < $this->auxInfoCol; $j++) {
                    if ($this->ss_rows[$headerRow][$j] == '') {
                        throw new \Exception("Blanks are not permitted in the header row in the tracking info section (column $j)");
                    }
                    $this->tracking_info_fields[$this->ss_rows[$headerRow][$j]] = $j;
                }
            }
        } else {
            $end_it = false;
            for ($i = 0; $i < count($this->ss_rows); $i++) {
                if ($this->ss_rows[$i][0] == "AUXILIARY INFORMATION") {
                    $end_it = true;
                    break;
                }
                if ($grab_it) {
                    if ($this->ss_rows[$i][0] == '') {
                        throw new \Exception("Blanks are not permitted in the first column in the tracking info section (row $i)");
                    }
                    $this->tracking_info_fields[$this->ss_rows[$i][0]] = $i;
                }
                if ($this->ss_rows[$i][0] == "TRACKING INFORMATION") {
                    $grab_it = true;
                }
            }
        }
        if (!$grab_it) {
            throw new \Exception('"TRACKING INFORMATION" header not found');
        }
    }

    /**
     * Get list of aux info items, including their category, subcategory,
     * and item names and the row number in the main data array.
     * this builds the $this->aux_info_fields array (flat array of items labeled with category and subcategory)
     *  and the $this->aux_info_groups (arrays of items nested within category/subcategory pairs)
     * @throws exception
     */
    private function find_aux_info_fields() {
        $this->aux_info_fields = array();
        $in_section = false;
        $in_data = false;
        $mark = false;
        $category = '';
        $subcategory = '';
        $rowCount = count($this->ss_rows);

        if ($this->rowStyle) {
            $headerRow = -1;
            for ($i = 0; $i < $rowCount; $i++) {
                if ($this->ss_rows[$i][0] == "TRACKING INFORMATION") {
                    $in_section = true;
                    $headerRow = $i + 1;
                    // Find AUXILIARY INFORMATION
                }
            }
            if ($in_section) {
                $colCount = count($this->ss_rows[$headerRow]);
                for ($j = $this->auxInfoCol; $j < $colCount; $j++) {
                    $rowCount = count($this->ss_rows);
                    $colHasData = false;
                    for ($i = $this->headerRow; $i < $rowCount; $i++) {
                        if (trim($this->ss_rows[$i][$j]) != '') {
                            $colHasData = true;
                            break;
                        }
                    }

                    // Skip the column if it only has whitespace
                    if (!$colHasData) {
                        continue;
                    }

                    $name = $this->ss_rows[$headerRow][$j];
                    $has_data = ($rowCount > 1 && $this->ss_rows[$headerRow + 1][$j] != '');
                    if ($has_data) {
                        if (!$in_data) {
                            throw new \Exception("Possible missing category or subcategory ('$name' near column $j)" . "<br><br>" . $this->sup_mes['header']);
                        }
                        $mark = false;
                        $p = new \stdClass();
                        $p->category = $category;
                        $p->subcategory = $subcategory;
                        $p->item = $name;
                        $p->column = $j;
                        $this->aux_info_fields[] = $p;
                    } else
                    if (!$mark) {
                        $category = $name;
                        $mark = true;
                        $in_data = false;
                    } else {
                        if ($in_data) {
                            throw new \Exception("Possible extra subcategory ('$name' near column $j)" . "<br><br>" . $this->sup_mes['header']);
                        }
                        $subcategory = $name;
                        $o = new \stdClass();
                        $o->category = $category;
                        $o->subcategory = $subcategory;
                        $this->aux_info_groups[] = $o;
                        $in_data = true;
                    }
                }
            }
        } else {
            for ($i = 0; $i < $rowCount; $i++) {
                if ($in_section) {
                    $colCount = count($this->ss_rows[$i]);
                    $rowHasData = false;
                    for ($j = 0; $j < $colCount; $j++) {
                        if (trim($this->ss_rows[$i][$j]) != '') {
                            $rowHasData = true;
                            break;
                        }
                    }

                    // Skip the row if it only has whitespace
                    if (!$rowHasData) {
                        continue;
                    }

                    $name = $this->ss_rows[$i][0];
                    $has_data = ($colCount > 1 && $this->ss_rows[$i][1] != '');
                    if ($has_data) {
                        if (!$in_data) {
                            throw new \Exception("Possible missing category or subcategory ('$name' near row $i)" . "<br><br>" . $this->sup_mes['header']);
                        }
                        $mark = false;
                        $p = new \stdClass();
                        $p->category = $category;
                        $p->subcategory = $subcategory;
                        $p->item = $name;
                        $p->row = $i;
                        $this->aux_info_fields[] = $p;
                    } else
                    if (!$mark) {
                        $category = $name;
                        $mark = true;
                        $in_data = false;
                    } else {
                        if ($in_data) {
                            throw new \Exception("Possible extra subcategory ('$name' near row $i)" . "<br><br>" . $this->sup_mes['header']);
                        }
                        $subcategory = $name;
                        $o = new \stdClass();
                        $o->category = $category;
                        $o->subcategory = $subcategory;
                        $this->aux_info_groups[] = $o;
                        $in_data = true;
                    }
                }
                if ($this->ss_rows[$i][0] == "AUXILIARY INFORMATION") {
                    $in_section = true;
                }
            }
        }
    }

    /**
     * Supplemental messages
     */
    private $sup_mes = array(
        'header' => "The usual reason for this is either forgetting to include both category and subcategory headers for each aux info section, or leaving the value blank for an aux info item for the first entity",
    );

    /**
     * Get array of field/values for the tracking information for the given entity
     * @param type $id
     * @return type
     */
    function get_entity_tracking_info($id) {
        $info = array();
        if ($this->rowStyle) {
            $row = array_search($id, $this->entity_list);
            if (!($row === false)) {
                $row = $row + $this->headerRow + 1;
                foreach ($this->tracking_info_fields as $field => $col) {
                    if (count($this->ss_rows[$row]) <= $col) {
                        $info[$field] = '';
                    } else {
                        $info[$field] = $this->ss_rows[$row][$col];
                    }
                }
            }
        } else {
            $col = array_search($id, $this->entity_list);
            if (!($col === false)) {
                $col++;
                foreach ($this->tracking_info_fields as $field => $row) {
                    if (count($this->ss_rows[$row]) <= $col) {
                        $info[$field] = '';
                    } else {
                        $info[$field] = $this->ss_rows[$row][$col];
                    }
                }
            }
        }
        return $info;
    }

    /**
     * Get aux info for given entity
     * @param type $id
     * @return type
     */
    function get_entity_aux_info($id) {
        $info = array();
        if ($this->rowStyle) {
            $row = array_search($id, $this->entity_list);
            if (!($row === false)) {
                $i = $this->headerRow + 1 + $row;
                foreach ($this->aux_info_fields as $obj) {
                    $obj->value = $this->ss_rows[$i][$obj->column];
                    $info[] = $obj;
                }
            }
        } else {
            $col = array_search($id, $this->entity_list);
            if (!($col === false)) {
                $col++;
                foreach ($this->aux_info_fields as $obj) {
                    $obj->value = $this->ss_rows[$obj->row][$col];
                    $info[] = $obj;
                }
            }
        }
        return $info;
    }

    /**
     * Repackage given aux info into array of category/subcategory groups
     * with array of item/values for each category/subcategory
     * @param type $aux_info_fields
     * @return type
     */
    function group_aux_info_items($aux_info_fields) {
        $out = array();
        foreach ($this->aux_info_groups as $g) {
            $group = clone($g);
            $items = array();
            foreach ($aux_info_fields as $f) {
                if ($g->category == $f->category && $g->subcategory == $f->subcategory) {
                    $items[$f->item] = $f->value;
                }
            }
            $group->items = $items;
            $out[] = $group;
        }
        return $out;
    }

    /**
     * What type of entity was defined in the spreadsheet
     * @throws exception
     */
    private function extract_entity_type() {
        foreach ($this->ss_rows as $row) {
            $s = $row[0];
            if ($s != '') {
                if ($s == 'TRACKING INFORMATION') {
                    throw new \Exception("Entity type is missing");
                }
                $this->entity_type = $s;
                break;
            }
        }
        if ($this->entity_type == '') {
            throw new \Exception("Entity type is not defined");
        }
        if ($this->entity_type != strtoupper($this->entity_type)) {
            throw new \Exception("Entity type '$this->entity_type' must be upper case");
        }
    }

    /**
     * Get list of entities that are defined in spreadsheet
     */
    private function extract_entity_list() {
        // The entity is, by definition, the first field in the list, so get its row number
        $row = current($this->tracking_info_fields);

        if ($this->rowStyle) {
            // Use column number to get row of entity values from parsed main data array
            $this->entity_list = array();
            $count = 0;
            for ($i = $this->headerRow + 1; $i < count($this->ss_rows); $i++) {
                $this->entity_list[$count] = $this->ss_rows[$i][$row];
                $count++;
            }
        } else {
            // Use row number to get row of entity values from parsed main data array
            $this->entity_list = $this->ss_rows[$row];

            // First field is header column, get rid of it
            array_shift($this->entity_list);
        }

        // Sometimes spreadsheet has extra empty columns,
        // so remove trailing blank entries starting at end of list and working forward
        while (!empty($this->entity_list)) {
            if (trim(end($this->entity_list)) == '') {
                array_pop($this->entity_list);
            } else {
                break;
            }
        }
    }

    /**
     * Get extracted data
     * @return type
     */
    function get_extracted_data() {
        return $this->ss_rows;
    }

    /**
     * Get entity type
     * @return type
     */
    function get_entity_type() {
        return $this->entity_type;
    }

    /**
     * Get entity list
     * @return type
     */
    function get_entity_list() {
        return $this->entity_list;
    }
}
?>
