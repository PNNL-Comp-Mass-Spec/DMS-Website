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
<input class="lst_cmd_btn" type="button" value="Add" onClick='lcmd.instrument_allowed_dataset_type.op("add")' title='Add'/>
<input class="lst_cmd_btn" type="button" value="Update" onClick='lcmd.instrument_allowed_dataset_type.op("update")' title='Update'/>
<input class="lst_cmd_btn" type="button" value="Delete" onClick='lcmd.instrument_allowed_dataset_type.op("delete")' title='Delete'/>
</div>

</form>
</div>

