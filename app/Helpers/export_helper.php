<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

    /**
     * Export a list report to a tab-separated values file (TSV)
     * Note: code is adapted from http://codeigniter.com/wiki/Excel_Plugin/
     * @param type $result
     * @param type $filename
     * @param array $col_filter
     */
    function export_to_excel_tsv($result, $filename='excel_download', array $col_filter = array())
    {
        if(empty($col_filter)) {
            $cols = array_keys(current($result));
        } else {
            $cols = $col_filter;
        }

        $headers = implode("\t", fix_ID_column($cols));

        $data = get_tab_delimited_text($result, $cols);

        // Use a file extension of .tsv for tab-separated
        // Prior to June 2016 we used .xls but that's not an accurate file extension given the actual data
        \Config\Services::response()->setContentType("application/x-msdownload");
        \Config\Services::response()->setHeader("Content-Disposition", "attachment; filename=$filename.tsv");
        echo "$headers\n$data";
    }

    /**
     * Export a list report to an Excel file
     * @param type $result
     * @param type $filename
     * @param array $col_filter If an empty array, export all columns; otherwise, list of column names to export
     * @param array $col_alignment Horizontal alignment for each column (keys are column name, values are default, left, right, or center)
     */
    function export_to_excel($result, $filename='excel_download', array $col_filter = array(), array $col_alignment = array())
    {
        $startTime = hrtime(true);

        if(empty($col_filter)) {
            $cols = array_keys(current($result));
        } else {
            $cols = $col_filter;
        }

        $autoSize = array();
        $label_formatter = new \App\Libraries\Label_formatter();

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->setActiveSheetIndex(0);

        // Add the header names
        $rowNumber = 1;
        $colNumber = 1;
        foreach($cols as $header) {
            $autoSize[$colNumber - 1] = true;
            $cell = $worksheet->getCellByColumnAndRow($colNumber, $rowNumber);
            $cell->setValue($label_formatter->format($header));
            $cell->getStyle()->getFont()->setBold(true);

            if (array_key_exists($header, $col_alignment)) {
                set_cell_alignment($cell, $col_alignment[$header]);
            }

            $colNumber++;
        }

        // Add the data
        $rowNumber++;
        foreach($result as $row) {
            $colNumber = 1;
            foreach($cols as $header) {
                $value = $row[$header];
                if (isset($value)) {
                    $cell = $worksheet->getCellByColumnAndRow($colNumber, $rowNumber);

                    if (preg_match('/##FORMAT_\[([a-z0-9]+)\]_\[([a-z0-9]+)\]_\[([a-z0-9]+)\]##(.*)/i', $value, $matches)) {
                        if (isset($matches[4]) && strlen($matches[4]) > 0) {
                            store_formatted_cell($cell, $matches);
                        }

                        $charCount = strlen($matches[4]);
                    } else {
                        $cell->setValue($value);
                        $charCount = strlen($value);
                    }

                    if ($charCount > 60) {
                        $autoSize[$colNumber - 1] = false;
                    }

                    if (array_key_exists($header, $col_alignment)) {
                        set_cell_alignment($cell, $col_alignment[$header]);
                    }
                }

                $colNumber++;
            }
            $rowNumber++;
        }

        // Auto-size the columns
        $colCount = count($cols);
        for ($columnIndex = 0; $columnIndex < $colCount; $columnIndex++) {
            $colDimension = $worksheet->getColumnDimensionByColumn($columnIndex + 1);
            if ($autoSize[$columnIndex] == true) {
                $colDimension->setAutoSize(true);
            } else {
                $colDimension->setWidth(50);
            }
        }

        // Freeze the top row
        $worksheet->freezePane('A2');

        // Select cell A2
        $worksheet->setSelectedCellByColumnAndRow(1, 2);

        $endTime = hrtime(true);
        $elapsedTime = 'Elapsed time: ' . ($endTime - $startTime) / 1000000 . ' msec';

        // Uncomment to store times in the first row of the worksheet
        // $worksheet->setCellValueByColumnAndRow(1, 1, $startTime);
        // $worksheet->setCellValueByColumnAndRow(2, 1, $endTime);
        // $worksheet->setCellValueByColumnAndRow(3, 1, $elapsedTime);

        \Config\Services::response()->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        \Config\Services::response()->setHeader('Content-Disposition', 'attachment;filename="'.$filename.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * Optionally override the default cell alignment
     * @param type $cell
     * @param type $alignment
     */
    function set_cell_alignment($cell, $alignment) {

        switch (strtolower($alignment)) {
            case 'left':
                $cell->getStyle()->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                break;
            case 'center':
                $cell->getStyle()->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                break;
            case 'right':
                $cell->getStyle()->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                break;
        }
    }

    /**
     * Store a value in an Excel cell and format its color
     * @param type $cell
     * @param type $matches
     */
    function store_formatted_cell($cell, $matches) {
        $textColor = $matches[1];
        $fillColor = $matches[2];
        $textStyle = $matches[3];

        $cell->setValue($matches[4]);

        if((empty($textColor) || $textColor == 'default') &&
           (empty($fillColor) || $fillColor == 'default') &&
           (empty($textStyle) || $textStyle == 'default')) {
            return;
        }

        $cellStyle = $cell->getStyle();

        if (!empty($textColor) && $textColor !== 'default') {
            if (strlen($textColor) == 6) {
                // Define 100% opacity by prepending the RGB value with FF
                $textColor = 'FF' . $textColor;
            }

            $cellStyle->getFont()
                ->getColor()->setARGB($textColor);
        }

        if (!empty($fillColor) && $fillColor !== 'default') {
            if (strlen($fillColor) == 6) {
                // Define 100% opacity by prepending the RGB value with FF
                $fillColor = 'FF' . $fillColor;
            }

            $cellStyle->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB($fillColor);
        }

        if (!empty($textStyle) && $textStyle !== 'default') {
            if ($textStyle == 'bold') {
                $cellStyle->getFont()->setBold(true);
            } else if ($textStyle == 'italic') {
                $cellStyle->getFont()->setItalic(true);
            }
        }
    }

    function get_column_name($columnIndex) {
        $indexValue = $columnIndex;
        $base26 = null;
        do {
            $characterValue = ($indexValue % 26) ?: 26;
            $indexValue = ($indexValue - $characterValue) / 26;
            $base26 = chr($characterValue + 64) . ($base26 ?: '');
        } while ($indexValue > 0);

        return $base26;
    }

    /**
     * Export a list report to a tab-delimited file
     * @param type $result
     * @param type $filename
     * @param array $col_filter
     */
    function export_to_tab_delimited_text($result, $filename='tsv_download', array $col_filter = array())
    {
        if(empty($col_filter)) {
            $cols = array_keys(current($result));
        } else {
            $cols = $col_filter;
        }

        $headers = implode("\t", fix_ID_column($cols));

        $data = get_tab_delimited_text($result, $cols);

        \Config\Services::response()->setContentType("text/plain");
        \Config\Services::response()->setHeader("Content-Disposition", "attachment; filename=$filename.txt");
        echo "$headers\n$data";
    }

    /**
     * Convert data in $result into tab-delimited text
     * @param type $result
     * @param type $cols
     * @return type
     */
    function get_tab_delimited_text($result, $cols)
    {

        $data = '';

        // Field data
        foreach($result as $row) {
            $line = '';
            foreach($cols as $name) {
                $value = $row[$name];
                if (!isset($value) || $value == "") {
                    $value = "\t";
                }
                else {
                    $value = quote_if_contains_tab($value) . "\t";
                }
                $line .= $value;
            }
            $data .= trim($line)."\n";
        }

        $dataNoCR = str_replace("\r","",$data);

        return $dataNoCR;
    }

    /**
     * Export a detail report to a tab-delimited file
     * @param type $result
     * @param type $aux_info
     * @param type $filename
     */
    function export_detail_to_tab_delimited_text($result, $aux_info, $filename='tsv_download')
    {
        $label_formatter = new \App\Libraries\Label_formatter();

        // Detail report for tracking entity
        $data = '';
        $data .= "Parameter" . "\t" . "Value" . "\n";
        foreach($result as $name => $value) {
            if (!isset($value) || $value == "") {
                $value = "\t";
            } else {
                $value = quote_if_contains_tab($value) . "\t";
            }
            $data .= trim($label_formatter->format($name) ."\t" . $value)."\n";
        }

        // Detail report for aux info (if any)
        $ai = '';
        if(count($aux_info) > 0) {
            $fields = array("category", "subcategory", "item", "value");
            $ai .= "\n" . "Category" . "\t" . "Subcategory" . "\t" . "Item" . "\t" . "Value" . "\n";
            foreach($aux_info as $row) {
                $line = '';
                foreach($fields as $field) {
                    $value = $row[$field];
                    if (!isset($value) || $value == "") {
                        $value = "\t";
                    } else {
                        $value = quote_if_contains_tab($value) . "\t";
                    }
                    $line .= $value;
                }
                $ai .= trim($line)."\n";
            }
        }

        \Config\Services::response()->setContentType("text/plain");
        \Config\Services::response()->setHeader("Content-Disposition", "attachment; filename=$filename.txt");
        echo $data;
        echo $ai;
    }

    /**
     * Export a detail report to an Excel file
     * @param type $result
     * @param type $aux_info
     * @param type $filename
     */
    function export_detail_to_excel($result, $aux_info, $filename='xlsx_download')
    {
        $label_formatter = new \App\Libraries\Label_formatter();

        // Detail report for tracking entity
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->setActiveSheetIndex(0);
        $data = array();
        $rowIndex = 1;
        $data[0][0] = "Parameter";
        $data[0][1] = "Value";

        foreach($result as $name => $value) {
            $data[$rowIndex][0] = $label_formatter->format($name);
            if (isset($value)) {
                $data[$rowIndex][1] = $value;
            } else {
                $data[$rowIndex][1] = '';
            }
            $rowIndex++;
        }

        // Detail report for aux info (if any)
        if(count($aux_info) > 0) {
            $fields = array("");
            $data[$rowIndex] = $fields;
            $rowIndex++;

            $fields = array("Category", "Subcategory", "Item", "Value");
            $data[$rowIndex] = $fields;
            $rowIndex++;

            $fields = array("category", "subcategory", "item", "value");
            foreach($aux_info as $row) {
                $colIndex = 0;
                foreach($fields as $field) {
                    $value = $row[$field];
                    if (!isset($value) || $value == "") {
                        $data[$rowIndex][$colIndex] = '';
                    } else {
                        $data[$rowIndex][$colIndex] = $value;
                    }
                    $colIndex++;
                }
                $rowIndex++;
            }
        }

        $worksheet->fromArray($data, '');

        \Config\Services::response()->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        \Config\Services::response()->setHeader('Content-Disposition', 'attachment;filename="'.$filename.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * Export XML text to .dot file
     * @param type $scriptName
     * @param type $description
     * @param type $script
     */
    function export_xml_to_dot($scriptName, $description, $script)
    {
        // Build contents of dot file
        $s = convert_script_to_dot($script);

        // Set up file names
        $dir = "generated/";
        $fn = $dir.$scriptName.'.dot';
        $typ = "png";
        $fo = $dir.$scriptName.'.'.$typ;

        // Create dot file
        file_put_contents($fn, $s);

        // Generate graph image from dot file
        $output = shell_exec("dot -T$typ -o $fo $fn");
        echo "<pre>$output</pre>";

        // Display graph image
        echo "<h2>Workflow for $scriptName Script</h2>";
        echo "<div style='width:60em;'>$description</div>";
        echo "<div style='height:1em;'></div>";
        echo '<img src="'.base_url($fo).'" ></img>';
    }

    /**
     * Converts a job script from XML to a dot graphic file
     * @param type $script
     * @return string
     */
    function convert_script_to_dot($script) {
        $dom = new \DOMDocument();
        $dom->loadXML($script);
        $xp = new \DOMXPath($dom);

        $dot_cmds = "digraph graphname {\n";
        $dot_cmds .= 'node [ shape = "record"  color=black fontname = "Verdana" fontsize = 10 ]';
        $dot_cmds .= 'edge [ color=black fontname = "Verdana" fontsize = 10 ]';
        $dot_cmds .= "\n";

        $steps = $xp->query("//Step");
        foreach ($steps as $step) {
             $description = "";
             $desc_items = $step->getElementsByTagName( "Description" );
             if($desc_items->length > 0) {
                 $description = ($desc_items)?$desc_items->item(0)->nodeValue:"";
                 $description = str_replace("\n", "\\l", $description);
             }
             $step_number = $step->getAttribute('Number');
             $tool = $step->getAttribute('Tool');
             $special = $step->getAttribute('Special');
             $shape = "box";
             // $color = "black";
             switch ($special) {
                  case "Clone":
                       $shape = "trapezium";
                       break;
             }
             //$dot_cmds .= "$step_number [label=\"$step_number $tool\"] [shape=$shape, color=$color]" . ";\n";
             $dot_cmds .= "$step_number [label = \"{ $step_number $tool| $description }\"]";
        }

        $dependencies = $xp->query("//Depends_On");
        foreach ($dependencies as $dependency) {
            $step_number = $dependency->parentNode->getAttribute('Number');
            $target_step_number = $dependency->getAttribute('Step_Number');
            $test = $dependency->getAttribute('Test');
            // $value = $dependency->getAttribute('Value');
            $enable_only = $dependency->getAttribute('Enable_Only');
            $label = "";
            $line = "";
            if($test) {
                $label = "[label=\"Skip if:$test\"]";
            }
            if($enable_only) {
                $line = " [style=dotted]";
            }
            $dot_cmds .= "$target_step_number -> $step_number $label $line" . "\n";
        }
        $dot_cmds .= "}";
        return $dot_cmds;
    }

    /**
     * Export data in the format supported by the spreadsheet loader page (https://dms2.pnl.gov/upload/main)
     * @param type $entity
     * @param type $result
     * @param type $aux_info
     * @param type $rowStyle
     * @param type $ext
     * @param type $filename
     */
    function export_spreadsheet($entity, $result, $aux_info, $rowStyle = false, $ext = "tsv", $filename='tsv_download')
    {
        $validatedRowStyle = filter_var($rowStyle, FILTER_VALIDATE_BOOLEAN);

        if (strtolower($ext) != "tsv" && strtolower($ext) != "txt") {
            export_spreadsheet_binary($entity, $result, $aux_info, $validatedRowStyle, $ext, $filename);
        } else {
            export_spreadsheet_text($entity, $result, $aux_info, $validatedRowStyle, $ext, $filename);
        }
    }

    /**
     * Export data in the format supported by the spreadsheet loader page (https://dms2.pnl.gov/upload/main)
     * @param type $entity
     * @param type $result
     * @param type $aux_info
     * @param type $rowStyle
     * @param type $ext
     * @param type $filename
     */
    function export_spreadsheet_text($entity, $result, $aux_info, $rowStyle = false, $ext = "tsv", $filename='tsv_download')
    {
        $validatedRowStyle = filter_var($rowStyle, FILTER_VALIDATE_BOOLEAN);

        // Detail report for tracking entity
        $data = strtoupper(str_replace('_', ' ', $entity)). "\n";
        $data .= "\n";
        if ($validatedRowStyle) {
            $markers = "TRACKING INFORMATION";
            $headers = "";
            $values = "";

            foreach($result as $name => $value) {
                if (!isset($value) || $value == "") {
                    $value = "\t";
                } else {
                    $value .= "\t";
                }
                $markers .= "\t";
                $headers .= trim($name)."\t";
                $values .= trim($value)."\t";
            }

            // Detail report for aux info (if any)
            $markers .= "AUXILIARY INFORMATION";
            $firstItem = True;
            $prevCategory = '';
            $prevSubCategory = '';
            if(count($aux_info) > 0) {
                // $fields = array("category", "subcategory", "item", "value");
                foreach($aux_info as $item) {
                    $line = '';

                    if (!$firstItem) {
                        $prevCategory = $item['category'];
                        $prevSubCategory = $item['subcategory'];
                        $firstItem = False;
                    }

                    $itemCategory = fix_data($item['category']);
                    $itemSubCategory = fix_data($item['subcategory']);
                    $itemName = fix_data($item['item']);
                    $itemValue = fix_data($item['value']);

                    if ($item['category'] != $prevCategory) {
                        $markers .= "\t\t\t";
                        $headers .= trim($itemCategory)."\t".trim($itemSubCategory)."\t".trim($itemName)."\t";
                        $values .= "\t\t".trim($itemValue)."\t";
                    } else {
                        if ($item['subcategory'] != $prevSubCategory) {
                            $markers .= "\t\t\t";
                            $headers .= trim($itemCategory)."\t".trim($itemSubCategory)."\t".trim($itemName)."\t";
                            $values .= "\t\t".trim($itemValue)."\t";
                        } else {
                            $markers .= "\t";
                            $headers .= trim($itemName)."\t";
                            $values .= trim($itemValue)."\t";
                        }
                    }

                    $prevCategory = $item['category'];
                    $prevSubCategory = $item['subcategory'];

                }
            }

            $data = $markers."\n".$headers."\n".$values."\n";
            $ai = "";
        } else {
            $data .= "TRACKING INFORMATION" . "\n";

            foreach($result as $name => $value) {
                if (!isset($value) || $value == "") {
                    $value = "\t";
                } else {
                    $value .= "\t";
                }
                $data .= trim($name ."\t" . $value)."\n";
            }

            // Detail report for aux info (if any)
            $ai = '';
            $ai .= "AUXILIARY INFORMATION" . "\n";
            $firstRow = True;
            $prevCategory = '';
            $prevSubCategory = '';
            if(count($aux_info) > 0) {
                // $fields = array("category", "subcategory", "item", "value");
                foreach($aux_info as $row) {
                    $line = '';

                    if (!$firstRow) {
                        $prevCategory = $row['category'];
                        $prevSubCategory = $row['subcategory'];
                        $firstRow = False;
                    }

                    $rowCategory = fix_data($row['category']);
                    $rowSubCategory = fix_data($row['subcategory']);
                    $rowItem = fix_data($row['item']);
                    $rowValue = fix_data($row['value']);

                    if ($row['category'] != $prevCategory) {
                        $ai .= trim($rowCategory)."\n".trim($rowSubCategory)."\n".trim($rowItem)."\t".trim($rowValue)."\n";
                    } else {
                        if ($row['subcategory'] != $prevSubCategory) {
                            $ai .= trim($rowCategory)."\n".trim($rowSubCategory)."\n".trim($rowItem)."\t".trim($rowValue)."\n";
                        } else {
                            $ai .= trim($rowItem)."\t".trim($rowValue)."\n";
                        }
                    }

                    $prevCategory = $row['category'];
                    $prevSubCategory = $row['subcategory'];

                }
            }
        }

        // Use a file extension of .tsv for tab-separated
        // Prior to June 2016 we used .xls but that's not an accurate file extension given the actual data
        \Config\Services::response()->setContentType("application/x-msdownload");
        \Config\Services::response()->setHeader("Content-Disposition", "attachment; filename=$filename.tsv");
        echo $data;
        echo $ai;
    }

    /**
     * Export data in the format supported by the spreadsheet loader page (https://dms2.pnl.gov/upload/main)
     * @param type $entity
     * @param type $result
     * @param type $aux_info
     * @param type $rowStyle
     * @param type $ext
     * @param type $filename
     */
    function export_spreadsheet_binary($entity, $result, $aux_info, $rowStyle = false, $ext = "xlsx", $filename='tsv_download')
    {
        $validatedRowStyle = filter_var($rowStyle, FILTER_VALIDATE_BOOLEAN);

        if ($ext[0] == '.') {
            $ext = substr($ext,1);
        }

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->setActiveSheetIndex(0);

        $worksheet->setCellValue('A1', strtoupper(str_replace('_', ' ', $entity)));
        $data = array();
        $data[0][0] = "TRACKING INFORMATION";

        if ($validatedRowStyle) {
            $colIndex = 0;
            foreach($result as $name => $value) {
                $data[1][$colIndex] = $name;
                if (isset($value)) {
                    $data[2][$colIndex] = $value;
                } else {
                    $data[2][$colIndex] = '';
                }
                $data[0][$colIndex] = '';
                $colIndex++;
            }

            $data[0][0] = "TRACKING INFORMATION";

            // Detail report for aux info (if any)
            $data[0][$colIndex] = "AUXILIARY INFORMATION";
            $firstItem = True;
            $prevCategory = '';
            $prevSubCategory = '';
            if(count($aux_info) > 0) {
                // $fields = array("category", "subcategory", "item", "value");
                foreach($aux_info as $item) {
                    $line = '';

                    if (!$firstItem) {
                        $prevCategory = $item['category'];
                        $prevSubCategory = $item['subcategory'];
                        $firstItem = False;
                    }

                    $itemCategory = fix_data($item['category']);
                    $itemSubCategory = fix_data($item['subcategory']);
                    $itemName = fix_data($item['item']);
                    $itemValue = fix_data($item['value']);

                    if ($item['category'] != $prevCategory) {
                        $data[1][$colIndex] = trim($itemCategory);
                        $data[2][$colIndex] = '';
                        $colIndex++;
                        $data[1][$colIndex] = trim($itemSubCategory);
                        $data[2][$colIndex] = '';
                        $colIndex++;
                        $data[1][$colIndex] = trim($itemName);
                        $data[2][$colIndex] = trim($itemValue);
                        $colIndex++;
                    } else {
                        if ($item['subcategory'] != $prevSubCategory) {
                            $data[1][$colIndex] = trim($itemCategory);
                            $data[2][$colIndex] = '';
                            $colIndex++;
                            $data[1][$colIndex] = trim($itemSubCategory);
                            $data[2][$colIndex] = '';
                            $colIndex++;
                            $data[1][$colIndex] = trim($itemName);
                            $data[2][$colIndex] = trim($itemValue);
                            $colIndex++;
                        } else {
                            $data[1][$colIndex] = trim($itemName);
                            $data[2][$colIndex] = trim($itemValue);
                            $colIndex++;
                        }
                    }

                    $prevCategory = $item['category'];
                    $prevSubCategory = $item['subcategory'];
                }
            }
        } else {
            $rowIndex = 1;
            foreach($result as $name => $value) {
                $data[$rowIndex][0] = $name;
                if (isset($value)) {
                    $data[$rowIndex][1] = $value;
                } else {
                    $data[$rowIndex][1] = '';
                }
                $rowIndex++;
            }

            // Detail report for aux info (if any)
            $data[$rowIndex][0] = "AUXILIARY INFORMATION";
            $rowIndex++;
            $firstItem = True;
            $prevCategory = '';
            $prevSubCategory = '';
            if(count($aux_info) > 0) {
                // $fields = array("category", "subcategory", "item", "value");
                foreach($aux_info as $item) {
                    $line = '';

                    if (!$firstItem) {
                        $prevCategory = $item['category'];
                        $prevSubCategory = $item['subcategory'];
                        $firstItem = False;
                    }

                    $itemCategory = fix_data($item['category']);
                    $itemSubCategory = fix_data($item['subcategory']);
                    $itemName = fix_data($item['item']);
                    $itemValue = fix_data($item['value']);

                    if ($item['category'] != $prevCategory) {
                        $data[$rowIndex][0] = trim($itemCategory);
                        $data[$rowIndex][1] = '';
                        $rowIndex++;
                        $data[$rowIndex][0] = trim($itemSubCategory);
                        $data[$rowIndex][1] = '';
                        $rowIndex++;
                        $data[$rowIndex][0] = trim($itemName);
                        $data[$rowIndex][1] = trim($itemValue);
                        $rowIndex++;
                    } else {
                        if ($item['subcategory'] != $prevSubCategory) {
                            $data[$rowIndex][0] = trim($itemCategory);
                            $data[$rowIndex][1] = '';
                            $rowIndex++;
                            $data[$rowIndex][0] = trim($itemSubCategory);
                            $data[$rowIndex][1] = '';
                            $rowIndex++;
                            $data[$rowIndex][0] = trim($itemName);
                            $data[$rowIndex][1] = trim($itemValue);
                            $rowIndex++;
                        } else {
                            $data[$rowIndex][0] = trim($itemName);
                            $data[$rowIndex][1] = trim($itemValue);
                            $rowIndex++;
                        }
                    }

                    $prevCategory = $item['category'];
                    $prevSubCategory = $item['subcategory'];
                }
            }
        }

        $worksheet->fromArray($data, '', 'A3');

        $writerType = $ext;
        switch (strtolower($ext)) {
            case 'xml':
                $ext = 'xlsx';
            case 'xlsx': // Excel (OfficeOpenXML) Spreadsheet
            case 'xlsm': // Excel (OfficeOpenXML) Macro Spreadsheet (macros will be discarded)
            case 'xltx': // Excel (OfficeOpenXML) Template
            case 'xltm': // Excel (OfficeOpenXML) Macro Template (macros will be discarded)
                \Config\Services::response()->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $writerType = 'Xlsx';
                break;
            case 'xls': // Excel (BIFF) Spreadsheet
            case 'xlt': // Excel (BIFF) Template
                \Config\Services::response()->setContentType('application/vnd.ms-excel');
                $writerType = 'Xls';
                break;
            case 'ods': // Open/Libre Offic Calc
            case 'ots': // Open/Libre Offic Calc Template
                \Config\Services::response()->setContentType('application/vnd.oasis.opendocument.spreadsheet');
                $writerType = 'Ods';
                break;
            case 'htm':
            case 'html':
                \Config\Services::response()->setContentType('text/html');
                $writerType = 'Html';
                break;
            case 'csv':
                \Config\Services::response()->setContentType('application/x-msdownload');
                $writerType = 'Csv';
                break;
            case 'pdf':
                //\Config\Services::response()->setContentType('application/pdf');
                //$writerType = 'Tcpdf';
                //$writerType = 'Dompdf';
                //$writerType = 'Mpdf';

                // Extra libraries required.
                \Config\Services::response()->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $writerType = 'Xlsx';
                $ext = 'xlsx';
                break;
            default:
                \Config\Services::response()->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $writerType = 'Xlsx';
                $ext = 'xlsx';
                break;
        }

        \Config\Services::response()->setHeader('Content-Disposition', 'attachment;filename="'.$filename.".".$ext.'"');
        $writer = IOFactory::createWriter($spreadsheet, $writerType);
        $writer->save('php://output');
    }

    /**
     *
     * @param type $entity_info
     * @param type $aux_info
     */
    function dump_spreadsheet($entity_info, $aux_info)
    {
        $table = new \CodeIgniter\View\Table();
        $table->setTemplate(array ('table_open'  => '<table class="EPag">'));
        foreach($entity_info as $fld => $val) {
            $table->addRow($fld, $val);
        }
        $ti = $table->generate();
        $table->clear();
        foreach($aux_info as $row) {
            $table->addRow($row);
        }
        $ai = $table->generate();
        $data['title'] = 'Spreadsheet Loader Template Contents';
        $data['content'] = $ti . $ai;
        echo view('basic', $data);
    }

    /**
     *
     * @param string|null $rowValue
     * @return string
     */
    function fix_data(?string $rowValue)
    {
        if (!isset($rowValue) || $rowValue == "") {
            $rowValue = "\t";
        } else {
            $rowValue = quote_if_contains_tab($rowValue) . "\t";
        }
        return $rowValue;
    }

    /**
     *
     * @param type $cols
     * @return string
     */
    function reformat_headers($cols)
    {
        // Make a copy of the $cols array
        $colsCopy = array();
        $label_formatter = new \App\Libraries\Label_formatter();

        foreach($cols as $header) {
            $colsCopy[] = $label_formatter->format($header);
        }

        return $colsCopy;
    }

    /**
     *
     * @param type $cols
     * @return string
     */
    function fix_ID_column($cols)
    {
        // Make a copy of the $cols array
        $colsCopy = reformat_headers($cols);

        if (strtoupper(substr($colsCopy[0], 0, 2)) == "ID") {
            // The first column's name starts with ID
            // Excel will interpret this as meaning the file is an SYLK file (http://support.microsoft.com/kb/323626)
            // To avoid this, change the column name to Id
            $colsCopy[0] = 'Id';
        }

        return $colsCopy;
    }

    /**
     * Surround $value with double quotes if it contains a tab character
     * @param type $value
     * @return type
     */
    function quote_if_contains_tab($value)
    {
        // Convert any newlines
        $valueNoCrLf = str_replace(array("\r\n", "\r", "\n"), "; ", $value);

        // Look for a tab character in $value
        $pos = strpos($valueNoCrLf, "\t");

        // Note that you must use !== instead of !=
        // See http://www.php.net/manual/en/function.strpos.php
        if ($pos !== false) {
            // Match found; surround with double quotes
            // However, first replace double quotes with ""
            return '"' . str_replace('"', '""', $valueNoCrLf) . '"';
        }

        return $valueNoCrLf;
    }
