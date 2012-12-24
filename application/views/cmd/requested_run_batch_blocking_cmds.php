<hr />
<script type="text/javascript" src="<?= base_url()?>javascript/factors.js"></script>
<script type="text/javascript" src="<?= base_url()?>javascript/run_blocking.js"></script>

<script type="text/javascript" >
function getFactorCols() {
	var cols = theta.getListReportColumnList();
	var factor_cols = cols.without('Sel', 'BatchID', 'Status', 'Name',  'Request', 'Experiment', 'Dataset', 'Dataset_ID', 'Block',  'Run Order');
	return factor_cols;
}
function verifyColName(col_name) {
	var colx = '';
	var cols = getFactorCols();
	cols.each(function(idx, col) {
		if(col.toLowerCase() == col_name.toLowerCase()) {
			colx = col;
		}
	});	
	return colx;
}
function createBlocksFromBlockingFactor(col_name) {
	col = verifyColName(col_name);
	if(!col) {
		alert('"' +  col_name + '" is not a valid name');
		return;
	}
	var rlist = getBlockingFieldsObjList(col);
	var bflist = getUniqueListOfBlockingFactors(col);
	bflist.each(function(idx, bf){
		var tlist = getBlockingFieldObjsInBlockingFactor(rlist, bf);
		assignBlockingFactorToBlocks(tlist);
	});
	randomizeWithinBlocks();
}
function createBlocksViaRandomAssignment() {
	var blkSize = $('#block_size').val();
	if(blkSize < 2 || blkSize > 15) {
		alert('Block size must be within range 1-15');
		return;
	}
	var rlist = getBlockingFieldsObjList();
	if(rlist.length < blkSize) {
		alert('Batch is smaller than block size');
		return
	}
	var slist = rlist.sortBy(function(obj){
		return obj.rnd;
	});
	var numBlocks = Math.ceil(slist.length / blkSize);
	slist.each(function(idx, obj, index) {
		obj.bk.value = (index % numBlocks) + 1;
	});
	randomizeWithinBlocks();
}
</script>

<script type="text/javascript">
function updateDatabaseFromList(flist, blist) {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var factorXML = delta.getFactorXMLFromList(flist);
	var blockingXML = theta.getBlockingXMLFromList(blist);
	
	var url =  gamma.global.ops_url;
	var p = {};
	p.factorList = factorXML;
	p.blockingList = blockingXML;
	delta.submitOperation(url, p);
}
function saveChangesToDababase() {
	var factor_cols = getFactorCols();
	var flist = theta.getFactorFieldList(factor_cols);
	var blocking_cols = ['Block',  'Run Order'];
	var blist = theta.getFactorFieldList(blocking_cols);
	updateDatabaseFromList(flist, blist);
}
function load_delimited_text() {
	var parsed_data = delta.getFactorXMLFromList('delimited_text_input', true);
	if(parsed_data.header[0] != 'Request') {
		alert('Header line does not begin with "Request"');
		// (someday) more extensive validation
		return;
	}
	var col_list = parsed_data.header.without('Request', 'Block', 'Run Order');
	var flist = theta.getFieldListFromParsedData(parsed_data, col_list);
	var blist = theta.getFieldListFromParsedData(parsed_data, ['Block', 'Run Order']);
	updateDatabaseFromList(flist, blist);
}
function setBlockForSelectedItems() {
	var iList = kappa.getSelectedItemList();
	if (iList.size() == 0) {
		alert('No items are selected');
		return;
	}
	var block = $('#block_input_setting').val();
	if(block != parseInt(block)) { 
		alert('Block must be a number'); 
		return; 
	}
	if(block < 1 || block > 50) {
		alert('Block out of range');
		return;
	}
	iList.each(function(idx, req) {
		$('Block_' + req).val(block);
	});
}
</script>

<script type="text/javascript">
function performBatchOperation(mode) {
	var url =  gamma.global.site_url + "requested_run_batch_blocking/exec/batch/";
	var p = {};
	p.command = mode;
	p.batchID = $('#BatchID').val();
	if(p.batchID == '') {
		alert("No batch ID");
		return;
	}
	delta.submitOperation(url, p);
}
</script>

<div class="LRCmds">


<form name="DBG" action="">

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='saveChangesToDababase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Editing and randomizing changes are local and must be explicitly saved to the database.  <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<a href="#" onclick="gamma.sectionToggle('factor_section', 0.5)">Factor commands...</a>
<div id="factor_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Apply Factor" onClick='theta.applyFactorToDatabase()' title=""  /> 
Apply factor <input id='apply_factor_name' value='' size='18'></input>
with value <input id='apply_factor_value' value='' size='18'></input>
to selected items.
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Remove Factor" onClick='theta.removeFactorFromDatabase()' title=""  /> 
Remove factor <input id='remove_factor_name' value='' size='18'></input>
from selected items.
</div>
</div>

<hr>
<a href="#" onclick="gamma.sectionToggle('blocking_section', 0.5)">Blocking commands...</a>
<div id="blocking_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Randomize Run Order" onClick='randomizeWithinBlocks()' id="btn_test" title=""  /> 
Randomize run order within blocks
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Set Block" onClick='setBlockForSelectedItems()' id="btn_test" title="Set block"  /> Set block for selected requests to
<input type='input' size='2' id='block_input_setting' value='1' />
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Assign Blocks by Factor" onClick='createBlocksFromBlockingFactor($('#blocking_factor_name').val())' id="btn_assign_bf" title="Assign requests to blocks"  /> 
Assign requests to blocks according to factor <input id='blocking_factor_name' value='' size='18'></input>
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Assign Blocks (Rnd)" onClick='createBlocksViaRandomAssignment()' id="btn_assign_rnd" title="Assign requests to blocks"  /> 
Assign requests to blocks randomly where block size is <input id='block_size' value='6' size='4'></input> (ignores Blocking Factor)
</div>
</div>

<hr>
<a href="#" onclick="gamma.sectionToggle('batch_section', 0.5)">Batch commands...</a>
<div id="batch_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Actual Run Order" onClick='performBatchOperation("actual_run_order")' title=""  /> Automatically generate 'Actual_Run_Order' factors for all completed requests in the batch.
</div>
</div>

<hr>
<a href="#" onclick="gamma.sectionToggle('upload_section', 0.5)">Upload commands...</a>
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

<!--  -->

</form>
</div>
