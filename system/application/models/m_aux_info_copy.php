<?php
class M_aux_info_copy extends Model {

	function init_definitions()
	{
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

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::Model();

		$this->init_definitions();
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
	function add_or_update($parmObj, $command, &$sa_message)
	{
		$stmt = mssql_init("CopyAuxInfo", $this->db->conn_id);
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
		$sa_sourceEntityName = $parmObj->CopySource;
/*
 * Debug dump
echo "mode ".$command."<br>";
echo "targetName ". $parmObj->TargetName."<br>";
echo "targetEntityName ". $parmObj->EntityID."<br>";
echo "categoryName ". $parmObj->Category."<br>";
echo "subCategoryName ". $parmObj->Subcategory."<br>";
echo "sourceEntityName ". $parmObj->CopySource."<br>";
return;
*/


 		mssql_bind($stmt, "@targetName", $sa_targetName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@targetEntityName", $sa_targetEntityName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@categoryName", $sa_categoryName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@subCategoryName", $sa_subCategoryName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@sourceEntityName", $sa_sourceEntityName, SQLVARCHAR, false, false, 128);
 		mssql_bind($stmt, "@mode", $sa_mode, SQLVARCHAR, false, false, 24);
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