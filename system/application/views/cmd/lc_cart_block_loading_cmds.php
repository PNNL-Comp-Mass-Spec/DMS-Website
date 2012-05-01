
<script type="text/javascript">
function make_xml_list_from_edit_fileds(rlist) {
	var s = '';
	rlist.each(function(obj) {
		s += '<r bt="' + obj.batchID + '" bk="' + obj.block + '" ct="' + obj.cart + '" co="' + obj.col + '"/>';
	});
	return s;
}
function getEditFieldsObjList() {
	// go through editable fields and build array of objects
	// where each object references the fields for 
	// one block
	var rlist = [];
	$$('.Cart').each(function(cartField) {
		var obj = {};
		obj.nm = cartField.name;
		var x = obj.nm.split('.');
		obj.batchID = x[0];
		obj.block = x[1];
		obj.cart = cartField.value;
		obj.col = $('Col_' + obj.nm).value;
		rlist.push(obj);
	});
	return rlist;
}
function validateFields(rlist) {
	var mes = '';
	rlist.each(function(obj){
		if(obj.cart == '(mixed)' || obj.col == 'mixed') {
			mes = '"(mixed)" is not a legal value';
		}
	});
	return mes;
}
function saveChangesToDababase() {
	if ( !confirm("Are you sure that you want to update the database?") ) return;

	var rlist = getEditFieldsObjList();
	var err = validateFields(rlist); 
	if (err != '') {
		alert(err);
		return;
	}
	var xml = make_xml_list_from_edit_fileds(rlist);

	var url =  "<?= $ops_url ?>";
	var p = {};
	p.command = 'update';
	p.cartAssignmentList = xml;
	submitOperation(url, p);
}
</script>
<script type="text/javascript">
function setCartName() {
	var iList = getSelectedItemList();
	if (iList.size() == 0) {
		alert('No items are selected');
		return;
	}
	var cart = $('cart_name_input').value;
	if(cart == '') {
		alert('Cart name cannot be blank');
		return;
	}
	iList.each(function(req) {
		$('Cart_' + req).value = cart;
	});
}
function setCartCol() {
	var iList = getSelectedItemList();
	if (iList.size() == 0) {
		alert('No items are selected');
		return;
	}
	var col = $('col_input_setting').value;
	if(col < 1 || col > 8) {
		alert('Column out of range');
		return;
	}
	iList.each(function(req) {
		$('Col_' + req).value = col;
	});

}
</script>

<div class="LRCmds">
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" action="">

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='saveChangesToDababase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Entries are local and must be explicitly saved to the database.  <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<div>
<input class='lst_cmd_btn' type="button" value="Set Cart" onClick='setCartName()' id="btn_test" title="Set cart"  /> Set cart name of selected blocks to
<input type='input' size='24' id='cart_name_input' value='' />
<?= $this->choosers->make_chooser('cart_name_input', 'picker.replace', 'lcCartPickList', '', '', '', '') ?>
</div>

<div>
<input class='lst_cmd_btn' type="button" value="Set Col" onClick='setCartCol()' id="btn_test" title="Set col"  /> Set column of selected blocks to
<input type='input' size='2' id='col_input_setting' value='1' /> (1-8)
</div>


</form>
</div>
