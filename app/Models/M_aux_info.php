<?php
namespace App\Models;

use CodeIgniter\Model;

class M_aux_info extends Model {

    function init_definitions() {
        $this->my_tag = "aux_info";

        // initialize parameters for query for list report
        $this->list_report_data_cols = "Category, Subcategory, Item, Value";
        $this->list_report_data_table = 'V_Aux_Info_Value';
        $this->list_report_data_sort_col = 'SC, SS, SI';

        // initialize primary filter specs
        $this->list_report_primary_filter = array(
            'pf_target' => array(
                'label' => 'Target',
                'size' => '10',
                'value' => '',
                'col' => 'Target',
                'cmp' => 'MatchesText',
            ),
            'pf_id' => array(
                'label' => 'ID',
                'size' => '6',
                'value' => '',
                'col' => 'Target_ID',
                'cmp' => 'Equals',
            ),
            'pf_category' => array(
                'label' => 'Category',
                'size' => '12',
                'value' => '',
                'col' => 'Category',
                'cmp' => 'MatchesText',
            ),
            'pf_subcategory' => array(
                'label' => 'Subcategory',
                'size' => '12',
                'value' => '',
                'col' => 'Subcategory',
                'cmp' => 'MatchesText',
            ),
        );

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
                'rules' => '',
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
                'rules' => '',
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
                'rules' => '',
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
                'rules' => '',
                'maxlength' => '128',
                'rows' => '',
                'cols' => '',
                'default' => '',
            )
            ,
            'FieldNamesEx' => array(
                'label' => 'itemNameList',
                'type' => 'area',
                'size' => '',
                'rules' => '',
                'maxlength' => '',
                'rows' => '4',
                'cols' => '60',
                'default' => '',
            )
            ,
            'FieldValuesEx' => array(
                'label' => 'itemValueList',
                'type' => 'area',
                'size' => '',
                'rules' => '',
                'maxlength' => '',
                'rows' => '4',
                'cols' => '60',
                'default' => '',
            )
        );
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
     * Stub procedure for validating the connection
     * @return boolean
     */
    function check_connection() {
        return true;
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
     * Return current values for given target, and id
     * (only include items that have current values set)
     * @param type $target
     * @param type $id
     * @return type
     * @throws Exception
     */
    function get_aux_info_item_current_values($target, $id) {
        if ($target == '') {
            return array();
        }
        $sql = <<<EOD
        SELECT Target, Target_ID, Category, Subcategory, Item, Value, SC, SS, SI
        FROM V_Aux_Info_Value
        WHERE (Target = '$target') AND (Target_ID = $id)
        ORDER BY SC, SS, SI
EOD;
        $query = $this->db->query($sql);

        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for aux info item values using V_Aux_Info_Value; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    /**
     * Get aux info for the given item
     * @param type $target
     * @param type $category
     * @param type $subcategory
     * @param type $id
     * @return type
     */
    function get_aux_info($target, $category, $subcategory, $id) {
        $ai_items = $this->get_aux_info_item_values($target, $category, $subcategory, $id);
        $ai_choices = $this->get_aux_info_allowed_values($target, $category, $subcategory);
        return array($ai_items, $ai_choices);
    }

    /**
     * Return all defined aux info items for given target, category, subcategory
     * including any currently set values for entity given by id
     * @param type $target
     * @param type $category
     * @param type $subcategory
     * @param type $id
     * @return type
     * @throws Exception
     */
    function get_aux_info_item_values($target, $category, $subcategory, $id) {

    /*
     * When switching to Postgres, update this query to reference v_aux_info_definition and t_aux_info_value
     */

        $sql = <<<EOD
SELECT
       AD.Item,
       AD.Item_ID,
       AD.DataSize,
       AD.HelperAppend,
       AV.Value
FROM V_Aux_Info_Definition AD
     LEFT OUTER JOIN dbo.T_AuxInfo_Value AV
       ON AD.Item_ID = AV.AuxInfo_ID AND AV.Target_ID = $id
WHERE (AD.Target = '$target') AND
       AD.Category = '$category' AND
       AD.Subcategory = '$subcategory'
ORDER BY SI
EOD;
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for aux info item values using V_Aux_Info_Definition; see writable/logs/log-$currentTimestamp.php");
        }
        if ($query->getNumRows() == 0) {
            throw new \Exception("No rows found");
        }
        return $query->getResultArray();
    }

    /**
     * Get allowed values for the given category and subcategory
     * @param type $target
     * @param type $category
     * @param type $subcategory
     * @return type
     * @throws Exception
     */
    function get_aux_info_allowed_values($target, $category, $subcategory) {
        $builder = $this->db->table('V_Aux_Info_Allowed_Values');
        $builder->select('Item, AllowedValue');
        $builder->where('Target', $target);
        $builder->where('Category', $category);
        $builder->where('Subcategory', $subcategory);
        $resultSet = $builder->get();
        if (!$resultSet) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for aux_info_allowed_values; see writable/logs/log-$currentTimestamp.php");
        }
        return $resultSet->getResultArray();
    }

    /**
     * Get list of aux info target definitions (tracking entities that are allowed
     * to have associated aux info)
     * @return type
     * @throws Exception
     */
    function get_aux_info_targets() {
        $builder = $this->db->table('T_AuxInfo_Target');
        $resultSet = $builder->get();
        if (!$resultSet) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for aux_info_targets; see writable/logs/log-$currentTimestamp.php");
        }
        if ($resultSet->getNumRows() == 0) {
            throw new \Exception("No rows found");
        }
        return $resultSet->getResultArray();
    }

    /**
     * Get list of aux info target names only
     * @return type
     */
    function get_aux_info_target_names() {
        $result = $this->get_aux_info_targets();
        $targets = array();
        foreach ($result as $row) {
            $targets[] = $row['Name'];
        }
        return $targets;
    }

    /**
     * Get list of all aux info item definitions for the given target
     * @param type $target
     * @return type
     * @throws Exception
     */
    function get_aux_info_def($target) {
        $builder = $this->db->table('V_Aux_Info_Definition');
        $builder->where('Target', $target);
        $resultSet = $builder->get();
        if (!$resultSet) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for aux_info_def; see writable/logs/log-$currentTimestamp.php");
        }
        if ($resultSet->getNumRows() == 0) {
            throw new \Exception("No rows found");
        }
        $def = array();
        foreach ($resultSet->getResultArray() as $row) {
            $spec = array();
            $spec['Item_ID'] = $row['Item_ID'];
            $spec['DataSize'] = $row['DataSize'];
            $spec['HelperAppend'] = $row['HelperAppend'];
            $spec['SC'] = $row['SC'];
            $spec['SS'] = $row['SS'];
            $spec['SI'] = $row['SI'];
            $def[$row['Category']][$row['Subcategory']][$row['Item']] = $spec;
        }
        return $def;
    }

    /**
     * Get list of all aux info categories for the given target
     * @param type $target
     * @return type
     */
    function get_aux_info_categories($target) {
        $result = $this->get_aux_info_def($target);
        return array_keys($result);
    }

    /**
     * Get list of all aux info subcategories for the given target and category
     * @param type $target
     * @param type $category
     * @return type
     */
    function get_aux_info_subcategories($target, $category) {
        $result = $this->get_aux_info_def($target);
        return array_keys($result[$category]);
    }

    /**
     * Get list of all aux info items for the given target and category and subcategories
     * @param type $target
     * @param type $category
     * @param type $subcategory
     * @return type
     */
    function get_aux_info_items($target, $category, $subcategory) {
        $result = $this->get_aux_info_def($target);
        return $result[$category][$subcategory];
    }

    /**
     * Add or update Aux Info values
     * @param stdClass $parmObj Field values from POST
     * @param type $command Action to perform; will always be 'add'
     * @param type $sa_message Error message to return
     * @return type
     */
    function add_or_update($parmObj, $command, &$sa_message) {
        $my_db = $this->db;

        // Use Sproc_sqlsrv with PHP 7 on Apache 2.4
        // Use Sproc_mssql  with PHP 5 on Apache 2.2
        // Set this based on the current DB driver

        $sprocHandler = "\\App\\Libraries\\Sproc_" . strtolower($my_db->DBDriver);
        $sproc_handler = new $sprocHandler();

        $sprocName = "AddUpdateAuxInfo";

        $sa_message = "";

        $input_params = new \stdClass();

        $args = array();

        $sproc_handler->AddLocalArgument($args, $input_params, "targetName", $parmObj->TargetName, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "targetEntityName", $parmObj->EntityID, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "categoryName", $parmObj->Category, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "subCategoryName", $parmObj->Subcategory, "varchar", "input", 128);
        $sproc_handler->AddLocalArgument($args, $input_params, "itemNameList", $parmObj->FieldNamesEx, "varchar", "input", 4000);
        $sproc_handler->AddLocalArgument($args, $input_params, "itemValueList", $parmObj->FieldValuesEx, "varchar", "input", 3000);
        $sproc_handler->AddLocalArgument($args, $input_params, "mode", $command, "varchar", "input", 12);
        $sproc_handler->AddLocalArgument($args, $input_params, "message", "", "varchar", "output", 512);

        /*
         * Debug dump
          echo "mode ".$command."<br>";
          echo "TargetName:". $parmObj->TargetName . '<br>';
          echo "EntityID:". $parmObj->EntityID . '<br>';
          echo "Category:". $parmObj->Category . '<br>';
          echo "Subcategory:". $parmObj->Subcategory . '<br>';
          echo "FieldNamesEx:". $parmObj->FieldNamesEx . '<br>';
          echo "FieldValuesEx:". $parmObj->FieldValuesEx . '<br>';
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
