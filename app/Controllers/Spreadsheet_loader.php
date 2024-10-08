<?php
namespace App\Controllers;

use CodeIgniter\Database\SQLite3\Connection;

class Spreadsheet_loader extends DmsBase {

    // Tracks names of entities currently supported by the spreadsheet loader.
    // Comes from column config_source in table loadable_entities in spreadsheet_loader.db
    private $supported_entities = array ();
    private $my_url_tag = '';

    function __construct()
    {
        $this->my_tag = "spreadsheet_loader"; // Links to the help page; also used by the restricted_actions table in master_authorization

        // Get the exact name (no namespace) of this class, since it's also the URL path to this controller
        //$this->my_url_tag = strtolower(get_class($this)); // This won't work because we are in a namespace
        $this->my_url_tag = strtolower((new \ReflectionClass($this))->getShortName());

        // Include the String operations methods
        $this->helpers = array_merge($this->helpers, ['string']);
    }

    /**
     * CodeIgniter 4 Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        /**
         * spreadsheet_loader.db: Not a standard config DB!
         * Has a single table 'loadable_entities', with the following columns (excluding the identity column):
         *   entity_type:           key normally used for reference, each entry needs to be unique in the table
         *   config_source:         page family name for entity_type, used primarily for entry_sproc and sproc_args
         *   aux_info_target:       if not supported for entity_type: empty string
         *                            if supported for entity_type:     the value that should be entered in the 'Target' column of aux_info_def for this entity
         *   existence_check_sql:   sql query to check for existence of the row in the database
         *                            should be of the form "SELECT (identity_column_name) AS ID FROM (table_name) WHERE '(key_name)' = KEY"
         *   key:                   column name in existence_check_sql/uploaded spreadsheet that is used as the match value for existence_check_sql
         */
        $this->get_config_info('spreadsheet_loader.db');
    }

    // --------------------------------------------------------------------
    function index()
    {
        $this->main();
    }

    /**
     * Get config source for given entity type
     * @param type $entity_type
     * @return type
     */
    private
    function get_config_source($entity_type)
    {
        $config_source = '';
        if(array_key_exists($entity_type, $this->supported_entities)) {
            $config_source = $this->supported_entities[$entity_type]['config_source'];
        }
        return $config_source;
    }

    /**
     * Establish the spreadsheet loader page
     */
    function main()
    {
        helper('user');

        $data['tag'] = $this->my_url_tag;
        $data['my_tag'] = $this->my_url_tag;
        $data['title'] = 'Spreadsheet Loader';

        helper(['menu']);
        $data['nav_bar_menu_items']= set_up_nav_bar('List_Reports', $this);

        helper(['url']);
        echo view("uploader/upload", $data);
    }

    /**
     * Upload the file identified via the POST
     * Overwrite any existing copies
     * Return Javascript that will execute immediately on being inserted into an iframe
     * @category AJAX
     */
    function load()
    {
        $fieldName = 'myfile';

        $destination_path = WRITEPATH . 'uploads/';
        $file_name = basename($_FILES[$fieldName]['name']);
        $target_path = $destination_path . $file_name;

        if (IsNullOrWhiteSpace($file_name)) {
            $error = 'Filename is empty';
        } else {

            $result = $_FILES['myfile']['error'];
            if ($result == 0) {
                if (file_exists($target_path) && $target_path !== $destination_path) {
                    // Delete the existing file
                    unlink($target_path);
                }
                $result = !move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);
            }
            $error = '';
            if($result) {
                $error = 'File upload failed';
            }
        }

        // Output is headed for an iframe
        // This script will automatically run when put into it and will inform elements on main page that operation has completed
        // Surround $file_name with double quotes in case the filename has an apostrophe
        echo "<script type='text/javascript'>dmsUpload.report_upload_results(\"$file_name\", \"$error\")</script>";
    }

    /**
     * Extract data from given file already uploaded to server
     * and return HTML table listing entities found
     * @throws exception
     * @category AJAX
     */
    function extract_data()
    {
        $fname = $this->request->getPost('file_name');

        try {
            $this->spreadsheet_loader = new \App\Libraries\Spreadsheet_loader();
            $this->spreadsheet_loader->load($fname);

            $entity_type = $this->spreadsheet_loader->get_entity_type();
            $config_source = $this->get_config_source($entity_type);
            if(!$config_source) throw new \Exception("'$entity_type' is not currently supported");

            $err = $this->cross_check_fields_with_entity($entity_type, $config_source);
            if($err) throw new \Exception($err);

            $entity_list = $this->spreadsheet_loader->get_entity_list();
            $entity_count = count($entity_list);

            // Format entity list for display
            $rows = array();
            foreach($entity_list as $entity) {
                $base_name = preg_replace ('/[!"#$%&()*+, .\/:;<=>?@^`{|}~]/', '_', strtolower($entity));
                $results_container = $base_name . '_results';
                $obj = new \stdClass();
                $obj->entity = $entity;
                $obj->container = $results_container;
                $val = json_encode($obj);
                $row = array();
                $row[] = "<input type='checkbox' value='$val' name='ckbx' class='lr_ckbx'>";
                $row[] = $entity;
                $url = site_url($this->my_url_tag."/entity/$fname/$entity");
                $tooltip = 'Show details of this entity from spreadsheet';
                $row[] = "<a href='javascript:void(0)' onclick='window.open(\"$url\", \"EW\", \"scrollbars,resizable,height=600,width=600,menubar\")' title='$tooltip'>Examine</a>";
                $row[] = "<span id='$results_container' class='entity_results_container'></span>";
                $rows[] = $row;
            }

            // Table dump
            $this->table = new \CodeIgniter\View\Table();
            $this->table->setTemplate(array ('table_open'  => '<table class="EPag">'));

            $this->table->setHeading('', '<span id="entity_type">'.$entity_type.'</span>', 'Details', 'Results');
            echo $this->table->generate($rows);
            echo "<div>Number of entities:$entity_count<div>";
        } catch (\Exception $e) {
            $message = $e->getMessage();
            echo "<div class='EPag_message'>$message</div>";
        }
    }

    /**
     * Extract data from given file already uploaded to server
     * and return HTML table of whole spreadsheet
     * @throws exception
     * @category AJAX
     */
    function extract_table()
    {
        $fname = $this->request->getPost('file_name');

        try {
            $this->spreadsheet_loader = new \App\Libraries\Spreadsheet_loader();
            $this->spreadsheet_loader->load($fname);

            $entity_type = $this->spreadsheet_loader->get_entity_type();
            $entity_list = $this->spreadsheet_loader->get_entity_list();
            $entity_count = count($entity_list);

            // Table dump
            $this->table = new \CodeIgniter\View\Table();
            $this->table->setTemplate(array ('table_open'  => '<table class="EPag">'));

            $rows = $this->spreadsheet_loader->get_extracted_data();
            $i = 0;
            foreach($rows as &$row) {
                array_unshift($row, $i++);
            }
            echo $this->table->generate($rows);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            echo "<div class='EPag_message'>$message</div>";
        }
    }


    /**
     * Extract data from given file already uploaded to server
     * and show details of given entity
     * @param type $fname
     * @param type $id
     */
    function entity($fname, $id)
    {
//      $fname = $this->request->getPost('file_name');
//      $id= $this->request->getPost('id');
        $this->spreadsheet_loader = new \App\Libraries\Spreadsheet_loader();
        $this->spreadsheet_loader->load($fname);

        $entity_type = $this->spreadsheet_loader->get_entity_type();
        $entity_list = $this->spreadsheet_loader->get_entity_list();

        $tracking_info = $this->spreadsheet_loader->get_entity_tracking_info($id);
        $aux_info = $this->spreadsheet_loader->get_entity_aux_info($id);
        $grouped_aux_info = $this->spreadsheet_loader->group_aux_info_items($aux_info);

        // Format tracking info for table display
        $rows = array();
        foreach($tracking_info as $field => $value) {
            $row = array();
            $row[] = $field;
            $row[] = $value;
            $rows[] = $row;
        }

        $arows = array();

        // Format aux info for table display
        foreach($aux_info as $obj) {
            $row = array();
            $row[] = $obj->category;
            $row[] = $obj->subcategory;
            $row[] = $obj->item;
            $row[] = $obj->value;
            $arows[] = $row;
        }

        // Table dump
        $data['title'] = "Details Of '$id' From Spreadsheet";
        $data['content'] = "File:$fname <br>";

        $this->table = new \CodeIgniter\View\Table();
        $this->table->setTemplate(array ('table_open'  => '<table class="EPag">'));

        $this->table->setHeading('Field', 'Value');
        $data['content'] .= $this->table->generate($rows);

        if(!empty($aux_info)) {
            $this->table->clear();
            $this->table->setHeading('Category', 'Subcategory',  'Item', 'Value');
            $data['content'] .= $this->table->generate($arows);
        }

        echo view('uploader/upload_supplemental', $data);
    }

    /**
     * Update tracking info for given entity in DMS (and optionally its aux info)
     * @throws exception
     */
    function update() ///$fname, $id, $mode
    {
        $fname = $this->request->getPost('file_name');
        $id= $this->request->getPost('id');
        $mode = $this->request->getPost('mode');  // Comes from radio buttons, can be 'add', 'update', 'check_add', 'check_update', 'check_exists'; see Views/uploader/upload_controls.php
        $incTrackinfo = $this->request->getPost('incTrackinfo') == 'true';
        $incAuxinfo = $this->request->getPost('incAuxinfo') == 'true';

        try {
            $this->spreadsheet_loader = new \App\Libraries\Spreadsheet_loader();
            $this->spreadsheet_loader->load($fname);

            $entity_type = $this->spreadsheet_loader->get_entity_type();
            $entity_list = $this->spreadsheet_loader->get_entity_list();
            $tracking_info = $this->spreadsheet_loader->get_entity_tracking_info($id);
            $aux_info = $this->spreadsheet_loader->get_entity_aux_info($id);
            $grouped_aux_info = $this->spreadsheet_loader->group_aux_info_items($aux_info);

            if($entity_type == 'DATASET' && $mode == 'add') {
                // Datasets: don't use 'add', which bypasses the data import manager
                $mode = 'add_dataset_create_task';
            }

            //---- tracking info update ---------------------------
            if($incTrackinfo) {
                $config_source = $this->get_config_source($entity_type);
                if(!$config_source) throw new \Exception("'$entity_type' is not currently supported");

                $current_values = $this->get_current_field_values($id, $entity_type, $config_source, $mode);
                $calling_params = $this->make_tracking_info_params($tracking_info, $config_source, $mode, $current_values);

                // Call stored procedure to update tracking info
                $this->sproc_model = model('App\Models\S_model');
                $ok = $this->sproc_model->init('entry_sproc', $config_source);
                if(!$ok) throw new \Exception($this->sproc_model->get_error_text());

                $ok = $this->sproc_model->execute_sproc($calling_params);
                if(!$ok) throw new \Exception($this->sproc_model->get_error_text());
            }

            //---- aux info update ---------------------------
            if($incAuxinfo && !empty($aux_info)) {
                $this->aux_model = model('App\Models\S_model');
                $ok = $this->aux_model->init('operations_sproc', 'aux_info_def');
                if(!$ok) throw new \Exception($this->aux_model->get_error_text());

                foreach($grouped_aux_info as $ai) {
                    $obj = $this->make_aux_info_params($id, $entity_type, $ai, $mode);
                    $ok = $this->aux_model->execute_sproc($obj);
                    if(!$ok) throw new \Exception($this->aux_model->get_error_text());
                }
            }

            $message = "Operation '$mode' was successful. ";
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        echo $message;
    }

    /**
     * Get current field values from existing entity
     * (allows us to have partial field coverage from spreadsheet in update mode)
     * @param type $id
     * @param type $entity_type
     * @param type $config_source
     * @param type $mode
     * @return stdClass
     * @throws exception
     */
    private
    function get_current_field_values($id, $entity_type, $config_source, $mode)
    {
        $current_values = new \stdClass();

        // Only look for current values in update mode
        if($mode === 'update' || $mode === 'check_update') {
            $key = 0;
            $message = '';
            $result = $this->get_entity_key($id, $entity_type, $key, $message);
            if(!$result) throw new \Exception($message);

            $this->load_mod('Q_model', 'input_model', 'entry_page', $config_source);
            $field_values =  $this->input_model->get_item($key, $this);
            if(empty($field_values)) throw new \Exception("Could not get field values for $entity_type '$key'");
            foreach($field_values as $fn => $v) {
                $current_values->$fn = $v;
            }
        }
        return $current_values;
    }

    /**
     * Does given entity exist in database?
     */
    function exists()
    {
        $fname = $this->request->getPost('file_name');
        $id= $this->request->getPost('id');
        $mode = $this->request->getPost('mode');
        $entity_type= $this->request->getPost('entity_type');

        $key = 0;
        $message = '';
        $result = $this->get_entity_key($id, $entity_type, $key, $message);

        if($result) {
            helper(['url']);
            $cfs = $this->supported_entities[$entity_type]['config_source'];
            $url = site_url("$cfs/show/$key");
            $lnk = "<a href='javascript:void(0)' onclick='window.open(\"$url\", \"DW\", \"scrollbars,resizable,height=900,width=600,menubar\")' >Details</a>";
//          $lnk = anchor("$cfs/show/$key", "details");
            echo "$entity_type $id exists ($lnk)";
        } else {
            echo $message;
        }
    }

    // --------------------------------------------------------------------
    private
    function get_entity_key($id, $entity_type, &$key, &$message)
    {
        $exists = false;
        try {
            if (!array_key_exists($entity_type, $this->supported_entities)) {
                throw new \Exception('Error:Unrecognized entity type');
            }

            $sql = $this->supported_entities[$entity_type]['existence_check_sql'];
            if(!$sql) throw new \Exception('Error:Existence query not defined');
            $sql = str_replace('@@', $id, $sql);

            $this->db = \Config\Database::connect();
            $this->updateSearchPath($this->db);
            $result = $this->db->query($sql);
            if($result->getNumRows() > 0) {
                $row = $result->getRow();
                $keyColumn = $this->supported_entities[$entity_type]['key'];
                if (strcasecmp($keyColumn, 'id') === 0) {
                    $key = $row->$keyColumn;
                } else {
                    $key = $id;
                }

                $exists = true;
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return $exists;
    }

    /**
     * Build parameter object for calling stored procedure that updates the tracking entity.
     * The parameters pulled from the spreadsheet (defined by labels) will be mapped
     * to calling parameter names matching the stored procedure arguments
     * @param type $tracking_info
     * @param type $config_source
     * @param type $mode
     * @param type $current_values
     * @return type
     */
    private
    function make_tracking_info_params($tracking_info, $config_source, $mode, $current_values)
    {
        // Use $entity_type to get definition of field/labels for entry sproc
        // entry form specifications
        $this->form_model = model('App\Models\E_model');
        $this->form_model->init('na', $config_source);
        $form_def = $this->form_model->get_form_def(array('specs'));

        helper('user');
        $calling_params = $current_values;
        $calling_params->mode = $mode;
        $calling_params->callingUser = get_user();
        foreach($form_def->specs as $name => $spec) {
            $label = trim($spec['label']);
            if(array_key_exists($label, $tracking_info)) {
                $calling_params->$name = $tracking_info[$label];
            }
        }
        return $calling_params;
    }

    // --------------------------------------------------------------------
    // Build parameter object for calling stored procedure that updates aux info
    private
    function make_aux_info_params($id, $entity_type, $ai, $mode)
    {
        $obj = new \stdClass();
        $obj->targetName = $entity_type;
        $obj->targetEntityName = $id;
        $obj->categoryName = $ai->category;
        $obj->subCategoryName = $ai->subcategory;
        $obj->itemNameList = implode('!', array_keys($ai->items));
        $obj->itemValueList = implode('!', array_values($ai->items));
        $obj->mode = $mode;
        $obj->message = '';
        return $obj;
    }

    // --------------------------------------------------------------------
    private
    function get_aux_info_target($config_source)
    {
        $aux_info_target = '';
        $entity_type = '';
        foreach($this->supported_entities as $et => $spec) {
            if($spec['config_source'] == $config_source) {
                $entity_type = $et;
                if(array_key_exists('aux_info_target', $spec)) {
                    $aux_info_target = $spec['aux_info_target'];
                }
            }
        }
        return $aux_info_target;
    }

    /**
     * Generate generic spreadsheet template for given entity type
     * @param type $config_source
     */
    function template($config_source, $rowStyle = false, $ext = "tsv")
    {
        // Tracking info
        $this->form_model = model('App\Models\E_model');
        $this->form_model->init('na', $config_source);
        $form_def = $this->form_model->get_form_def(array('fields', 'specs', 'load_key'));

        // Get array of field labels associated with field values
        // make sure key field is first in list
        $primary_key = $form_def->load_key;
        $entity_info[$form_def->specs[$primary_key]['label']] = 'key';
        foreach($form_def->specs as $field => $spec) {

            // The form field type may contain several keywords specified by a vertical bar
            $fieldTypes = explode('|', $spec['type']);

            if($field != $primary_key && !in_array('hidden', $fieldTypes) && !in_array('non-edit', $fieldTypes)) {
                $entity_info[$spec['label']] = 'xx';
            }
        }
        $aux_info = array();
        $aux_info_target = $this->get_aux_info_target($config_source);
        if($aux_info_target) {
            // Get aux info definitions
            $this->model = model('App\Models\Q_model');
            $this->model->init('list_report', 'aux_info_def');
            $this->model->add_predicate_item('AND', 'target', 'MatchesText', $aux_info_target);
            $query = $this->model->get_rows('filtered_only');

            $aux_info =  $query->getResultArray();
            foreach($aux_info as &$row) {
                $row['value'] = 'xx';
            }
        }
        helper(['export']);
        export_spreadsheet($config_source, $entity_info, $aux_info, $rowStyle, $ext, $config_source."_template");
    }

    // --------------------------------------------------------------------
    function verify_fields($fname)
    {
        try {
            $this->spreadsheet_loader = new \App\Libraries\Spreadsheet_loader();
            $this->spreadsheet_loader->load($fname);

            $entity_type = $this->spreadsheet_loader->get_entity_type();
            $config_source = $this->get_config_source($entity_type);
            if(!$config_source) return "Not supported";

            echo $this->cross_check_fields_with_entity($entity_type, $config_source);
        } catch (\Exception $e) {
            if($e->getMessage()) echo $e->getMessage();
        }
    }

    // --------------------------------------------------------------------
    private
    function cross_check_fields_with_entity($entity_type, $config_source)
    {
        $tracking_info = $this->spreadsheet_loader->get_tracking_info_fields();
        $aux_info = $this->spreadsheet_loader->get_aux_info_fields();

        $ti_errors = $this->cross_check_tracking_info_fields($config_source, $tracking_info);

        $ai_errors = array();
        $aux_info_target = $this->get_aux_info_target($config_source);
        if($aux_info_target) {
            $ai_errors = $this->cross_check_aux_info_fields($aux_info_target, $aux_info);
        }

        helper('html');
        $str = '';
        $errors = array_merge($ti_errors, $ai_errors);
        if(!empty($errors)) {
            $str .= "Fields from spreadsheet that are not defined for $entity_type: <br>\n";
            $str .= ul($errors);
            $str .= "<br>\n";
        }
        return $str;
    }

    // --------------------------------------------------------------------
    private
    function cross_check_tracking_info_fields($config_source, $tracking_info)
    {
        $this->form_model = model('App\Models\E_model');
        $this->form_model->init('na', $config_source);
        $form_def = $this->form_model->get_form_def(array('fields', 'specs', 'load_key'));

        $errors = array();
        foreach($tracking_info as $field => $row) {
            $good = false;
            foreach($form_def->specs as $f => $spec) {
                if($field == trim($spec['label'])) {
                    $good = true;
                    break;
                }
            }
            if(!$good) {
                $errors[] = "Tracking item '$field' near row $row is not recognized; " .
                            "re-export a new spreadsheet template from the $config_source detail report";
            }
        }
        return $errors;
    }

    // --------------------------------------------------------------------
    private
    function cross_check_aux_info_fields($aux_info_target, $aux_info)
    {
        // Get aux info definitions
        $this->model = model('App\Models\Q_model');
        $this->model->init('list_report', 'aux_info_def');
        $this->model->add_predicate_item('AND', 'target', 'MatchesText', $aux_info_target);
        $query = $this->model->get_rows('filtered_only');
        $result =  $query->getResultArray();

        $errors = array();
        foreach($aux_info as $obj) {
            $good = false;
            foreach($result as $row) {
                if($obj->category == $row['category'] && $obj->subcategory == $row['subcategory'] && $obj->item == $row['item']) {
                    $good = true;
                    break;
                }
            }
            if(!$good) {
                $errors[] = "Aux info item '$obj->category/$obj->subcategory/$obj->item' near row $obj->row is not recognized\n";
            }
        }
        return $errors;
    }

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------


    // --------------------------------------------------------------------
    function directory()
    {
        helper(['url']);
        $this->table = new \CodeIgniter\View\Table();

        $style = "width:40em;padding:5px 0 5px 0;";
        echo "<div style='$style'>This is a list of DMS entity types for which you can upload spreadsheet data</div>";

        foreach(array_keys($this->supported_entities) as $entity) {
            if($entity == 'BOGUS') continue;
            $pf = $this->supported_entities[$entity]['config_source'];
            $lnkXlsx = anchor($this->my_url_tag."/template/".$pf."/true/xlsx", "Blank Excel Template");
            $lnk = anchor($this->my_url_tag."/template/".$pf, "Blank TSV Template");
            $lr = anchor("$pf/report", "List Report");

            if(strtolower($entity) == 'biomaterial')
                $entityDescription = 'Biomaterial (cell culture)';
            else
                $entityDescription = ucwords(strtolower($entity));

            $this->table->addRow($entityDescription, $lnkXlsx, $lnk, $lr);
        }
        echo "<div style='$style'>";
        echo $this->table->generate();
        echo "</div>";

        echo "<div style='$style'>You can get a blank template with all possible fields for the entity by clicking the 'Blank Template' link.</div>";
        echo "<div style='$style'>You can get a template for an existing entity by using the spreadsheet export link on the detail report page for that entity.  As a convenience, the 'List Report' link will take you to the list report page for the entity type, and you can get to the detail report page for a particular entity from there.</div>";
    }

    /**
     * Get definitions for entities that can be uploaded from spreadsheet loader
     * @param type $dbFileName
     * @throws Exception
     * @throws exception
     */
    private
    function get_config_info($dbFileName)
    {
        helper(['config_db']);
        $dbFilePath = get_model_config_db_path($dbFileName)->path;

        $db = new Connection(['database' => $dbFilePath, 'dbdriver' => 'sqlite3']);

        // Get list of tables in database
        $tbl_list = array();
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        if(!in_array('loadable_entities', $tbl_list)) throw new \Exception('Table "loadable_entities" not found in config db');

        $def = array();
        foreach ($db->query("SELECT * FROM loadable_entities")->getResultArray() as $row) {
            $spec = array();
            $spec['config_source'] = $row['config_source'];
            if($row['aux_info_target']) {
                $spec['aux_info_target'] = $row['aux_info_target'];
            }
            $spec['existence_check_sql'] = str_replace('KEY', "'@@'", $row['existence_check_sql']);
            $spec['key'] = $row['key'];
            $def[$row['entity_type']] = $spec;
        }

        $db->close();
        //print_r($def);
        $this->supported_entities = $def;
    }
}
?>
