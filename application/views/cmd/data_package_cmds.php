<script type="text/javascript">
function performOperation(mode) {
	var list = '';
	var rows = document.getElementsByName('ckbx');
	for (var i = 0; i < rows.length; i++) {
		if ( rows[i].checked )
			list  += rows[i].value;
	}
	if(list=='') {
		alert('You must select items'); 
		return;
	}
	if ( !confirm("Are you sure that you want to update the database?") )
		return;

	var url =  gamma.pageContext.site_url + 'data_package_items/operation/';
	$('#paramListXML').val(list);
	$('#entry_cmd_mode').val(mode);
	var p = $('#operation_form').serialize();
	lambda.submitOperation(url, p);
}

</script>


<div class='LRCmds'>


<form name="DBG" id="operation_form" action="">

<input type="hidden" id="entry_cmd_mode" name="command" value="" />
<input type='hidden' id='paramListXML' name='paramListXML' />

<div>
<div style="font-weight:bold;">Comment</div>
<textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea>
</div>

<div>
<input class="lst_cmd_btn" type="button" value="Delete From Package" onClick='performOperation("delete")' title='Remove the selected items from their data package'/>
<input class="lst_cmd_btn" type="button" value="Update Comment" onClick='performOperation("comment")' title='Update the comment for the selected items'/>
</div>

</form>
</div>