<div class='LRCmds'>

<form name="DBG" id="operation_form" action="">

<input type="hidden" id="entry_cmd_mode" name="command" value="" />
<input type='hidden' id='paramListXML' name='paramListXML' />

<div>
<div style="font-weight:bold;">Comment</div>
<textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea>
</div>

<div>
<input class="button lst_cmd_btn" type="button" value="Delete From Package" onClick='packages.performOperation("delete")' title='Remove the selected items from their data package'/>
<input class="button lst_cmd_btn" type="button" value="Update Comment" onClick='packages.performOperation("comment")' title='Update the comment for the selected items'/>
</div>

</form>
</div>

<script src="<?= base_url().'javascript/packages.js' ?>"></script>
