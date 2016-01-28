<?php
class M_aux_info extends CI_Model {

	function init_definitions()
	{
		$this->my_tag = "aux_info";	
		// initialize parameters for query for list report
		 $this->list_report_data_cols = "Category, Subcategory, Item, Value";
		 $this->list_report_data_table = 'V_AuxInfo_Value';
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

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->init_definitions();
	}

	// --------------------------------------------------------------------
    function check_connection()
    {
		return true;
    }

	// --------------------------------------------------------------------
	// return an array from the form field specifications keyed by
	// field name and containing the field label as the value for the key
	function get_field_validation_fields()
	{
		$x	= array();
		foreach($this->form_fields as $f_name => $f_spec) {
			$x[$f_name]	= $f_spec["label"];
			if(array_key_exists('enable', $f_spec)) {
				$x["${f_name}_ckbx"]= $f_spec["enable"];
			}
		}
		return $x;
	}
    
	// --------------------------------------------------------------------
	// return current values for given target, and id
	// (only include items that have current values set)
	function get_aux_info_item_current_values($target, $id)
	{
		if($target=='') {
			return array();
		}
		$sql = <<<EOD
		SELECT Target, Target_ID, Category, Subcategory, Item, Value, SC, SS, SI
		FROM V_AuxInfo_Value
		WHERE (Target = '$target') AND (Target_ID = $id)
		ORDER BY SC, SS, SI		
EOD;
		$query = $this->db->query($sql);

		if(!$query) {
			throw new Exception("Error querying database");
		}
		return $query->result_array();
	}


	// --------------------------------------------------------------------
	function get_aux_info($target, $category, $subcategory, $id)
	{
		$ai_items = $this->get_aux_info_item_values($target, $category, $subcategory, $id);
		$ai_choices = $this->get_aux_info_allowed_values($target, $category, $subcategory);
		return array($ai_items, $ai_choices);
	}
	
	// --------------------------------------------------------------------
	// return all defined aux info items for given target, category, subcategory
	// including any currently set values for entity given by id
	function get_aux_info_item_values($target, $category, $subcategory, $id)
	{
		$sql = <<<EOD
SELECT
       AD.Item,
       AD.Item_ID,
	   AD.DataSize,
	   AD.HelperAppend,
       AV.Value
FROM V_AuxInfo_Definition AD
     LEFT OUTER JOIN dbo.T_AuxInfo_Value AV
       ON AD.Item_ID = AV.AuxInfo_ID AND AV.Target_ID = $id
WHERE (AD.Target = '$target') AND
       AD.Category = '$category' AND
       AD.Subcategory = '$subcategory'
ORDER BY SI
EOD;
		$query = $this->db->query($sql);
		if(!$query) {
			throw new Exception("Error querying database");
		}
 		if ($query->num_rows() == 0) {
 			throw new Exception("No rows found");
		}
		return $query->result_array();
	}

	// --------------------------------------------------------------------
	function get_aux_info_allowed_values($target, $category, $subcategory)
	{
		$this->db->select('Item, AllowedValue');
		$this->db->from('V_Aux_Info_Allowed_Values');
		$this->db->where('Target', $target);
		$this->db->where('Category', $category);
		$this->db->where('Subcategory', $subcategory);
		$query = $this->db->get();
		if(!$query) {
			throw new Exception("Error querying database");
		}
		return $query->result_array();
	}

	// --------------------------------------------------------------------
	// get list of aux info target definitions (tracking entities that are allowed
	// to have associated aux info)
	function get_aux_info_targets()
	{
		$this->db->from('T_AuxInfo_Target');
		$query = $this->db->get();
		if(!$query) {
			throw new Exception("Error querying database");
		}
 		if ($query->num_rows() == 0) {
 			throw new Exception("No rows found");
		}
		return $query->result_array();
	}
	// --------------------------------------------------------------------
	// get list of aux info target names only
	function get_aux_info_target_names()
	{
		$result = $this->get_aux_info_targets();
		$targets = array();
		foreach($result as $row) {
			$targets[] = $row['Name'];
		}
		return $targets;
	}

	// --------------------------------------------------------------------
	// get list of all aux info item definitions for the given target
	function get_aux_info_def($target)
	{
		$this->db->from('V_AuxInfo_Definition');
		$this->db->where('Target', $target);
		$query = $this->db->get();
		if(!$query) {
			throw new Exception("Error querying database");
		}
 		if ($query->num_rows() == 0) {
 			throw new Exception("No rows found");
		}
		$def = array();
		foreach($query->result_array() as $row) {
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
	// --------------------------------------------------------------------
	// get list of all aux info categories  for the given target
	function get_aux_info_categories($target)
	{
		$result = $this->get_aux_info_def($target);
		return array_keys($result);
	}
	// --------------------------------------------------------------------
	// get list of all aux info subcategories  for the given target and category
	function get_aux_info_subcategories($target, $category)
	{
		$result = $this->get_aux_info_def($target);
		return array_keys($result[$category]);
	}
	// --------------------------------------------------------------------
	// get list of all aux info items for the given target and category and subcategories
	function get_aux_info_items($target, $category, $subcategory)
	{
		$result = $this->get_aux_info_def($target);
		return $result[$category][$subcategory];
	}

	// --------------------------------------------------------------------
	function add_or_update($parmObj, $command, &$sa_message)
	{
		$stmt = mssql_init("AddUpdateAuxInfo", $this->db->conn_id);
		if(!$stmt) {
			$sa_message = "Statement initialization error";
			return 1;		
		}
		$sa_mode = $command;
		$sa_message = "";

		$sa_targetName = $parmObj->TargetName;
		$sa_targetEntityName = $parmObj->EntityID;
		$sa_categoryName = $parmObj->Category;
		$sa_subCategoryName = $parmObj->Subcategory;
		$sa_itemNameList = $parmObj->FieldNamesEx;
		$sa_itemValueList = $parmObj->FieldValuesEx;
/*	
echo "TargetName:". $parmObj->TargetName . '<br>';
echo "EntityID:". $parmObj->EntityID . '<br>';
echo "Category:". $parmObj->Category . '<br>';
echo "Subcategory:". $parmObj->Subcategory . '<br>';
echo "FieldNamesEx:". $parmObj->FieldNamesEx . '<br>';
echo "FieldValuesEx:". $parmObj->FieldValuesEx . '<br>';
return;	
 */
 		mssql_bind($stmt, "@targetName", $sa_targetName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@targetEntityName", $sa_targetEntityName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@categoryName", $sa_categoryName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@subCategoryName", $sa_subCategoryName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@itemNameList", $sa_itemNameList, SQLVARCHAR, false, false, 4000);
 		mssql_bind($stmt, "@itemValueList", $sa_itemValueList, SQLVARCHAR, false, false, 3000);
 		mssql_bind($stmt, "@mode", $sa_mode, SQLVARCHAR, false, false, 12);
 		mssql_bind($stmt, "@message", $sa_message, SQLVARCHAR, true, false, 512);
		mssql_bind($stmt, "RETVAL",$val, SQLINT2);

		$result = mssql_execute($stmt);
		
		if(!$result) {
			$ra_msg = mssql_get_last_message();
			$sa_message = "Database error:" . $ra_msg . ": " . $sa_message;
		} else
		if($val != 0) {
			$ra_msg = mssql_get_last_message();
			$sa_message = "Procedure error:" . $ra_msg . ": " . $sa_message;
		}
		return $val;
	}	

}
?>