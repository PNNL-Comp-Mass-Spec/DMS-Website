<div class="LRCmds">

<form name="DBG" action="">

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='lcmd.lc_cart_request_loading.saveChangesToDababase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Entries are local and must be explicitly saved to the database.  <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<div>
<input class='lst_cmd_btn' type="button" value="Set Cart" onClick='lcmd.lc_cart_request_loading.setCartName()' id="btn_test" title="Set cart"  /> Set cart name of selected requests to
<input type='input' size='24' id='cart_name_input' value='' />
<?= $this->choosers->make_chooser('cart_name_input', 'picker.replace', 'lcCartPickList', '', '', '', '') ?>
</div>

<div>
<input class='lst_cmd_btn' type="button" value="Set Col" onClick='lcmd.lc_cart_request_loading.setCartCol()' id="btn_test" title="Set col"  /> Set column of selected requests to
<input type='input' size='2' id='col_input_setting' value='1' /> (1-8)
</div>

</form>
</div>



