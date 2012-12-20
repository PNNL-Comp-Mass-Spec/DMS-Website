
<script type="text/javascript" src="<?= base_url().'javascript/factors.js' ?>"></script>

<script type="text/javascript">
$(document).ready(function () { 
	gChooser.callBack = setItemTypeField;
});
function setItemTypeField() {
	var $s = '';
	if(gChooser.page.indexOf('helper_requested_run_batch') > -1) {
		$s = 'Batch_ID';
	}
	if(gChooser.page.indexOf('helper_requested_run_ckbx') > -1) {
		$s = 'Requested_Run_ID';
	}
	if(gChooser.page.indexOf('helper_dataset_ckbx') > -1) {
		$s = 'Dataset_Name';
	}
	if(gChooser.page.indexOf('helper_experiment_ckbx') > -1) {
		$s = 'Experiment_Name';
	}
	if($s) {
		$('#itemType').val($s);
	}
}
function updateDatabaseFromList(flist, id_type) {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var factorXML = getFactorXMLFromList(flist);
	if(id_type) {
		factorXML = '<id type="' + id_type + '" />' + factorXML;
	}
	var url =  "<?= $ops_url ?>";
	var p = {};
	p.factorList = factorXML;
	submitOperation(url, p);
}
function saveChangesToDababase() {
	var cols = getListReportColumnList();
	var col_list = cols.without('Sel', 'BatchID', 'Status', 'Name',  'Request',  'Experiment', 'Dataset');
	var flist = getFactorFieldList(col_list);
	updateDatabaseFromList(flist, 'Request');
}
function load_delimited_text() {
	var parsed_data = parseDelimitedText('delimited_text_input');
	var id_type = parsed_data.header[0];
	var col_list = parsed_data.header.without(id_type, 'Block', 'Run Order');
	var flist = getFieldListFromParsedData(parsed_data, col_list);
	updateDatabaseFromList(flist, id_type);
}
</script>


<div class="LRCmds">
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" action="">

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='saveChangesToDababase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Editing changes are local and must be explicitly saved to the database.  <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<a href="#" onclick="GRONK('factor_section', 0.5)">Factor commands...</a>
<div id="factor_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Apply Factor" onClick='applyFactorToDatabase()' title=""  /> 
Apply factor <input id='apply_factor_name' value='' size='18'></input>
with value <input id='apply_factor_value' value='' size='18'></input>
to selected items.
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Remove Factor" onClick='removeFactorFromDatabase()' title=""  /> 
Remove factor <input id='remove_factor_name' value='' size='18'></input>
from selected items.
</div>
</div>

<hr>
<a href="#" onclick="GRONK('upload_section', 0.5)">Upload commands...</a>
<div id="upload_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Update from list" onClick='load_delimited_text()' title="Test"  /> Update database from delimited list
</div>
<div>
<p>Delimited text input:</p>
<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>
<hr>

</form>
</div>
