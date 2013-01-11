<div class="LRCmds">

<form name="DBG" action="">

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='saveChangesToDababase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Entries are local and must be explicitly saved to the database.  <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<div>
<input class='lst_cmd_btn' type="button" value="Set Cart" onClick='setCartName()' id="btn_test" title="Set cart"  /> Set cart name of selected requests to
<input type='input' size='24' id='cart_name_input' value='' />
<?= $this->choosers->make_chooser('cart_name_input', 'picker.replace', 'lcCartPickList', '', '', '', '') ?>
</div>

<div>
<input class='lst_cmd_btn' type="button" value="Set Col" onClick='setCartCol()' id="btn_test" title="Set col"  /> Set column of selected requests to
<input type='input' size='2' id='col_input_setting' value='1' /> (1-8)
</div>

</form>
</div>

<script type="text/javascript">
function make_xml(rlist) {
	var s = '';
	$.each(rlist, function(idx, obj) {
		s += '<r rq="' + obj.req + '" ct="' + obj.cart + '" co="' + obj.col + '"/>';
	});
	return s;
}
function getEditFieldsObjList() {
	// go through editable fields and build array of objects
	// where each object references the fields for 
	// one block
	var rlist = [];
	$('.Cart').each(function(idx, cartField) {
		var obj = {};
		obj.req = cartField.name;
		obj.cart = cartField.value;
		obj.col = $('#Col_' + obj.req).val();
		rlist.push(obj);
	});
	return rlist;
}
function saveChangesToDababase() {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var rlist = getEditFieldsObjList();
	var xml = make_xml(rlist);
	var url =  gamma.pageContext.ops_url;
	var p = {};
	p.command = 'update';
	p.cartAssignmentList = xml;
	lambda.submitOperation(url, p);
}
</script>
<script type="text/javascript">
function setCartName() {
	var iList = lambda.getSelectedItemList();
	if (iList.length == 0) {
		alert('No items are selected');
		return;
	}
	var cart = $('#cart_name_input').val();
	if(cart == '') {
		alert('Cart name cannot be blank');
		return;
	}
	$.each(iList, function(idx, req) {
		$('Cart_' + req).val(cart);
	});
}
function setCartCol() {
	var iList = lambda.getSelectedItemList();
	if (iList.size() == 0) {
		alert('No items are selected');
		return;
	}
	var col = $('#col_input_setting').val();
	if(col < 1 || col > 8) {
		alert('Column out of range');
		return;
	}
	$.each(iList, function(idx, req) {
		$('Col_' + req).val(col);
	});
}
</script>


