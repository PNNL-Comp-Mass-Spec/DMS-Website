<div class="LRCmds">

<form name="DBG" action="">

<div>
<?php # tau.requested_run_factors.saveChangesToDatabase is defined in factors.js ?>
<input class='button lst_cmd_btn' type="button" value="Update" onClick='tau.requested_run_factors.saveChangesToDatabase()' id="btn_save" title="Update"  /> Save changes
</div>

<p>Note: Factors are associated with Requested Run entries. Editing changes are local and must be explicitly saved to the database. <span style='text-decoration:underline;'>Unsaved changes will be lost if you search or sort.</span></p>

<hr>
<?= general_visibility_control('Factor commands', 'factor_section') ?>
<div id="factor_section" style="display:none;">
<div>
<?php # theta.applyFactorToDatabase is defined in factors.js ?>
<input class='button lst_cmd_btn' type="button" value="Apply Factor" onClick='theta.applyFactorToDatabase(tau.requested_run_factors.updateDatabaseFromList)' title=""  />
Apply factor <input id='apply_factor_name' value='' size='18'></input>
with value <input id='apply_factor_value' value='' size='18'></input>
to selected items.
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Remove Factor" onClick='theta.removeFactorFromDatabase(tau.requested_run_factors.updateDatabaseFromList)' title=""  />
Remove factor <input id='remove_factor_name' value='' size='18'></input>
from selected items.
</div>
</div>

<hr>
<?= general_visibility_control('Upload commands', 'upload_section') ?>
<div id="upload_section" style="display:none;">
<div>
<?php # tau.requested_run_factors.load_delimited_text is defined in factors.js ?>
<input class='button lst_cmd_btn' type="button" value="Update from list" onClick='tau.requested_run_factors.load_delimited_text()' title="Test"  /> Update database using a tab delimited list
</div>
<div>
<p>Delimited text input:</p>
<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>
<hr>

</form>
</div>

<script src="<?= base_url('javascript/factors.js?version=100') ?>"></script>
