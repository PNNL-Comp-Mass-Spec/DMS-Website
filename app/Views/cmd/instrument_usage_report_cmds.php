<div class="LRCmds">

<form name="DBG" action="">

<?php
    // Buttons for https://dms2.pnl.gov/instrument_usage_report/report
?>

<hr>
<?= general_visibility_control('Reload commands', 'reload_section', '') ?>
<div id="reload_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Refresh" onClick='tracking.instrument_usage_report.refresh_report()' title="Refresh EMSL usage report from DMS usage tracking" /> Refresh EMSL instrument report from DMS
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Reload" onClick='tracking.instrument_usage_report.reload_report()' title="Reload EMSL usage report from DMS usage tracking" /> Reload EMSL instrument report from DMS (wipe current contents)
</div>
</div>

<hr>
<?= general_visibility_control('Upload commands', 'upload_section', '') ?>
<div id="upload_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Update from list" onClick='tracking.instrument_usage_report.load_delimited_text()' title="Test" /> Update database using a tab delimited list
</div>
<div>
<p>Delimited text input:</p>
<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>
<hr>

</form>
</div>

<?php // Import factors.js ?>
<?php echo view('resource_links/factors') ?>

<?php // Import tracking.js ?>
<?php echo view('resource_links/tracking') ?>
