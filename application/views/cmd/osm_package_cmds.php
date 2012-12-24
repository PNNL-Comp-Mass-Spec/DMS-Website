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

	var url =  gamma.pageContext.site_url + 'osm_package_items/operation/';
	$('#paramListXML').val(list);
	$('#entry_cmd_mode').val(mode);
	var p = $('#operation_form').serialize();
	theta.submitOperation(url, p);
}

</script>


<div class='LRCmds'>


<form name="DBG" id="operation_form" action="">

<input type="hidden" id="entry_cmd_mode" name="command" value="" />
<input type='hidden' id='paramListXML' name='paramListXML' />
<input type="hidden" id="comment" name="comment" value="" />

<div>
<input class="lst_cmd_btn" type="button" value="Delete Selected Items From Package" onClick='performOperation("delete")' title='Remove the selected items from their OSM package'/>
</div>

</form>
</div>