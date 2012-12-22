
<script type="text/javascript">
function transferData(perspective, dslist) {

	var commalist = $('#' + dslist).val();
	var list = gamma.getCkbxList('ckbx' );
	if(list=='' && commalist=='') {
		alert('You must select at least 1 dataset or enter 1 dataset id.'); 
		return;
	}
	//Add or Remove trailing comma
	if(list!='') {
		if (commalist.charAt(commalist.length-1) != ',' && commalist != '')
		{
			commalist = commalist + ',';
		}
	}
	else if (commalist.charAt(commalist.length-1) == ',' )
	{
		commalist = commalist.substring(0, commalist.length-1)
	}

	if ( !confirm("Are you sure that you want to transfer the selected data?") )
		return;

	url =  "<?= site_url() ?>/data_transfer/" + perspective;
	var p = {};
	p.perspective = perspective;
	p.iDList = commalist + list;
	delta.submitOperation(url, p);
}
</script>

<div class='LRCmds'>


<h3>Commands</h3>

<form name="DBG" id="cmds" >
<ul>
<div>
Enter Dataset List:
</div>

<div>
<textarea name="Dataset_list" cols="70" rows="4" id="Dataset_list" maxlength="80" size="20" onChange="gamma.convertList('Dataset_list', ',')" ></textarea>
</div>

<a href='javascript:transferData("dataset", "Dataset_list")'><img src='<?= base_url() ?>images/btn.png' border='0' ></a> Transfer Selected Datasets

</ul>
</form>

</div>
