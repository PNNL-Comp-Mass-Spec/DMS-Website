<div class="LRCmds">

<form name="DBG" action="">

<hr>
<?= general_visibility_control('Upload allocations', 'upload_section', '') ?>
<div id="upload_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Update from list" onClick='tracking.instrument_allocation.load_delimited_text()' title="Test"  /> Update database using a tab delimited list
</div>
<div>
<div>
Fiscal Year:<input id='fiscal_year' size='8' value='' ></input>
</div>
<p>Delimited text input:</p>
<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>

<hr>
<?= general_visibility_control('Move allocation', 'move_cmd_section', '') ?>
<div id="move_cmd_section" style="display:none;">
<div>
Move <input id='move_hours' size='5' /> hours for instrument group <input id='move_group' size='5' /> 
from proposal <input id='move_from' size='5' /> to proposal <input id='move_to' size='5' /> 
for fiscal year <input id='move_fy' size='5' /> 
<input class='button lst_cmd_btn' type="button" value="Update" onClick='tracking.instrument_allocation.move_allocated_hours()' />
</div>
<div>Comment</div>
<div><textarea id='move_comment' rows='2' cols='80' ></textarea></div>
</div>

<hr>
<?= general_visibility_control('Set allocation', 'set_cmd_section', '') ?>
<div id="set_cmd_section" style="display:none;">
<div>
Set <input id='set_hours' size='5' /> hours for instrument group <input id='set_group' size='5' /> 
to proposal <input id='set_to' size='5' /> 
for fiscal year <input id='set_fy' size='5' /> 
<input class='button lst_cmd_btn' type="button" value="Update" onClick='tracking.instrument_allocation.set_allocated_hours()' />
</div>
<div>Comment</div>
<div><textarea id='set_comment' rows='2' cols='80' ></textarea></div>
</div>
<hr>
</form>
</div>

<script src="<?= base_url().'javascript/tracking.js' ?>"></script>

