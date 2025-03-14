<hr />
<div class="LRCmds">

<form name="DBG" action="">

<div>
<?php # requested_run_batch_blocking.saveChangesToDatabase is defined in run_blocking.js ?>
<input class='button lst_cmd_btn' type="button" value="Update" onClick='runBlocking.requested_run_batch_blocking.saveChangesToDatabase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Editing and randomizing changes are local and must be explicitly saved to the database.  <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<?= general_visibility_control('Factor commands', 'factor_section', '') ?>
<div id="factor_section" style="display:none;">
<div>
<?php # factorsjs.applyFactorToDatabase is defined in factors.js ?>
<input class='button lst_cmd_btn' type="button" value="Apply Factor" onClick='factorsjs.applyFactorToDatabase(runBlocking.requested_run_batch_blocking.updateDatabaseFromList)' title="" />
Apply factor <input id='apply_factor_name' value='' size='18'></input>
with value <input id='apply_factor_value' value='' size='18'></input>
to selected items.
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Remove Factor" onClick='factorsjs.removeFactorFromDatabase(runBlocking.requested_run_batch_blocking.updateDatabaseFromList)' title="" />
Remove factor <input id='remove_factor_name' value='' size='18'></input>
from selected items.
</div>
</div>

<hr>
<?= general_visibility_control('Blocking commands', 'blocking_section', '') ?>
<div id="blocking_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Randomize Run Order" onClick='runBlocking.randomizeWithinBlocks()' id="btn_randomize_title"  />
Randomize run order within blocks
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Set Block" onClick='runBlocking.requested_run_batch_blocking.setBlockForSelectedItems()' id="btn_set_block" title="Set block"  /> Set block for selected requests to
<input type='input' size='2' id='block_input_setting' value='1' />
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Assign Blocks by Factor" onClick='runBlocking.requested_run_batch_blocking.createBlocksFromBlockingFactor($("#blocking_factor_name").val())' id="btn_assign_bf" title="Assign requests to blocks"  />
Assign requests to blocks according to factor <input id='blocking_factor_name' value='' size='18'></input>
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Assign Blocks (Rnd)" onClick='runBlocking.createBlocksViaRandomAssignment()' id="btn_assign_rnd" title="Assign requests to blocks"  />
Assign requests to blocks randomly where block size is <input id='block_size' value='6' size='4'></input> (ignores Blocking Factor)
</div>
</div>

<hr>
<?= general_visibility_control('Batch commands', 'batch_section', '') ?>
<div id="batch_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Actual Run Order" onClick='runBlocking.requested_run_batch_blocking.performBatchOperation("actual_run_order")' title=""  /> Automatically generate 'Actual_Run_Order' factors for all completed requests in the batch.
</div>
</div>

<hr>
<?= general_visibility_control('Upload commands', 'upload_section', '') ?>
<div id="upload_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Update from list" onClick='runBlocking.requested_run_batch_blocking.load_delimited_text()' title="Test"  /> Update database using a tab delimited list
</div>
<div>
<table>
<tr>
    <td>To update blocking information, the first column must be Request, followed by Block and Run_Order:</td>
</tr>
<tr>
    <td>
    <table border='1' style='border-collapse:collapse'>
      <tr>
        <th style="width: 33%; font-weight: normal; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px;">Request</th>
        <th style="width: 33%; font-weight: normal; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px;">Block</th>
        <th style="width: 34%; font-weight: normal; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px;">Run_Order</th>
      </tr>
    </table>
    </td>
</tr>
</table>
<br>

<table>
<tr>
    <td>To add or update factors, the first column must be Request, then the other columns (besides Block and Run_Order) are the factor names</td>
</tr>
<tr>
    <td>
    <table border='1' style='border-collapse:collapse'>
      <tr>
        <th style="width: 33%; font-weight: normal; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px;">Request</th>
        <th style="width: 33%; font-weight: normal; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px;">Temperature</th>
        <th style="width: 34%; font-weight: normal; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px;">Time_Point</th>
      </tr>
    </table>
    </td>
</tr>
</table>
<br>

<p>Paste a tab-delimited table here:</p>

<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>
<hr>

</form>
</div>

<?php // Import factors.js ?>
<?php echo view('resource_links/factors') ?>

<?php // Import run_blocking.js ?>
<?php echo view('resource_links/run_blocking') ?>
