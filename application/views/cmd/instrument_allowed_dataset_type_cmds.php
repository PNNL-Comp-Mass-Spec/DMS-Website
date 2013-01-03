<script type="text/javascript">
function localRowAction(url, value, obj) {
	$('#instrument_group_fld').setValue(obj["Instrument Group"]);
	$('#dataset_type_fld').setValue(obj["Dataset Type"]);
	$('#usage_fld').setValue(obj["Usage for This Group"]);
}

function performOperation(mode) {
	if ( !confirm("Are you sure that you want to update the database?") ) return;

	url = gamma.pageContext.ops_url;
	var p = {};
	p.command = mode;
	p.InstrumentGroup = $('#instrument_group_fld').val();
	p.DatasetType = $('#dataset_type_fld').val();
	p.Comment = $('#usage_fld').val();
	lambda.submitOperation(url, p);
}
</script>

<div class='LRCmds'>


<form name="DBG" action="">

<div>
<span style="font-weight:bold">Instrument Group:</span>
<div><input type="text" name='Instrument Group' id='instrument_group_fld' ></input></div>
</div>

<div>
<span style="font-weight:bold">Dataset Type:</span>
<div>
	<input type="text" name='DatasetType' id='dataset_type_fld' ></input>
	<span><?= $this->choosers->get_chooser('dataset_type_fld', 'datasetTypePickList')?></span>
</div>
</div>

<div>
<span style="font-weight:bold">Usage for this instrument group</span>
<div><textarea name='Comment' id='usage_fld' rows='4' cols='60' ></textarea></div>
</div>

<div>
<input class="lst_cmd_btn" type="button" value="Add" onClick='performOperation("add")' title='Add'/>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("update")' title='Update'/>
<input class="lst_cmd_btn" type="button" value="Delete" onClick='performOperation("delete")' title='Delete'/>
</div>

</form>
</div>