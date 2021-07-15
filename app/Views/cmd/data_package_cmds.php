<div class='LRCmds'>

<?php
// This form is used by web page data_package_items/report
// Button clicks are handled in javascript/packages.js
?>

<form name="DBG" id="operation_form" action="">

<input type="hidden" id="entry_cmd_mode" name="command" value="" />
<input type='hidden' id='paramListXML' name='paramListXML' />

<!-- This is set to 0 or 1 by packages.js -->
<input type='hidden' id='removeParents' name='removeParents' />

<div>
<div style="font-weight:bold;">Comment</div>
<textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea>
</div>

<div>
<input class="button lst_cmd_btn" type="button" value="Delete From Package" onClick='packages.performOperation("delete")' title='Remove the selected items from their data package'/>
<label>
    <?php // This should default to checked on this page ?>
    <input type="checkbox" id='removeParentsCheckbox' value='removeParentsCheckbox' checked='true' title='When deleting jobs or datasets, remove the parent datasets and/or experiments' />
    Also remove parent datasets and experiments
</label>
</div>
<div>
<input class="button lst_cmd_btn" type="button" value="Update Comment" onClick='packages.performOperation("comment")' title='Update the comment for the selected items'/>
</div>

</form>
</div>

<script src="<?= base_url('javascript/packages.js?version=101') ?>"></script>
