<?php
namespace App\Models;

use CodeIgniter\Model;

class M_aux_info_copy extends Model {

    function init_definitions() {
        $this->my_tag = "aux_info_copy";

        // query to get data for existing record in database for editing
        $this->entry_page_data_cols = "*";
        $this->entry_page_data_table = '';
        $this->entry_page_data_id_col = '';

        // fields for entry form
        $this->form_fields = array(
            'TargetName' => array(
                'label' => 'targetName',
                'type' => 'text',
                'size' => '60',
                'rules' => 'trim',
                'maxlength' => '128',
                'rows' => '',
                'cols' => '',
                'default' => '',
            )
            ,
            'EntityID' => array(
                'label' => 'targetEntityName',
                'type' => 'text',
                'size' => '60',
                'rules' => 'trim',
                'maxlength' => '128',
                'rows' => '',
                'cols' => '',
                'default' => '',
            )
            ,
            'Category' => array(
                'label' => 'categoryName',
                'type' => 'text',
                'size' => '60',
                'rules' => 'trim',
                'maxlength' => '128',
                'rows' => '',
                'cols' => '',
                'default' => '',
            )
            ,
            'Subcategory' => array(
                'label' => 'subCategoryName',
                'type' => 'text',
                'size' => '60',
                'rules' => 'trim',
                'maxlength' => '128',
                'rows' => '',
                'cols' => '',
                'default' => '',
            )
            ,
            'CopySource' => array(
                'label' => 'sourceEntityName',
                'type' => 'text',
                'size' => '60',
                'rules' => 'trim',
                'maxlength' => '128',
                'rows' => '',
                'cols' => '',
                'default' => '',
            )
        );

        $this->entry_sproc = "CopyAuxInfo";
    }

    /**
     * Constructor
     */
    function __construct() {
        // Call the Model constructor
        parent::__construct();

        $this->init_definitions();
    }

    /**
     * Return an array from the form field specifications keyed by
     * field name and containing the field label as the value for the key
     * @return type
     */
    function get_field_validation_fields() {
        $x = array();
        foreach ($this->form_fields as $f_name => $f_spec) {
            $x[$f_name] = $f_spec["label"];
            if (array_key_exists('enable', $f_spec)) {
                $x["${f_name}_ckbx"] = $f_spec["enable"];
            }
        }
        return $x;
    }

    /**
     * Copy Aux Info values from one item to another
     * @param type $parmObj
     * @param type $command Action to perform; will always be 'CopyMode'
     * @param type $sa_message
     * @return type
     */
    function add_or_update($parmObj, $command, &$sa_message) {
        $my_db = $this->db;

        // Use Sproc_sqlsrv with PHP 7 on Apache 2.4
        // Use Sproc_mssql  with PHP 5 on Apache 2.2
        // Set this based on the current DB driver

        $sprocHandler = "\\App\\Libraries\\Sproc_" . strtolower($my_db->DBDriver);
        $sproc_handler = new $sprocHandler();

        $sprocName = "CopyAuxInfo";

        $sa_message = "";

        $input_params = new \stdClass();

        $args = array();

        $sproc_handler->AddLocalArgument($args, $input_params, "targetName", $parmObj->TargetName, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "targetEntityName", $parmObj->EntityID, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "categoryName", $parmObj->Category, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "subCategoryName", $parmObj->Subcategory, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "sourceEntityName", $parmObj->CopySource, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "mode", $command, "varchar", "input", 24);
        $sproc_handler->AddLocalArgument($args, $input_params, "message", "", "varchar", "output", 512);

        /*
         * Debug dump
          echo "mode ".$command."<br>";
          echo "TargetName ". $parmObj->TargetName."<br>";
          echo "TargetEntityName ". $parmObj->EntityID."<br>";
          echo "CategoryName ". $parmObj->Category."<br>";
          echo "SubCategoryName ". $parmObj->Subcategory."<br>";
          echo "SourceEntityName ". $parmObj->CopySource."<br>";
          return;
         */

        $sproc_handler->execute($sprocName, $my_db->connID, $args, $input_params);

        // Examine the result code
        $result = $input_params->exec_result;
        $val = $input_params->retval;

        if (!$result) {
            $sa_message = "Execution failed for $sprocName";
            return -1;
        }

        if ($val != 0) {
            $sa_message = "Procedure error: " . $input_params->message . " ($val for $sprocName)";
        }

        return $val;
    }
}
?>
