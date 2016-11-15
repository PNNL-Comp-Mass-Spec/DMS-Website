<div class="LRCmds">

<?php
// This form is used by web pages data_package_items/report and data_package_job_coverage/report
// Button clicks are handled in javascript/lcmd.js
?>
 
<form name="DBG" action="">

<hr>
<?= general_visibility_control('Membership commands', 'membership_section', '') ?>
<div id="membership_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Add Jobs" onClick='lcmd.data_package_job_coverage.op("add")' id="btn_a" title=""  /> 
Add selected jobs to package that are not already in package.
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Remove Jobs" onClick='lcmd.data_package_job_coverage.op("delete")' id="btn_r" title=""  />
Remove selected jobs from package.<br>
<label>
	<?php // This should default to unchecked on this page (thus, we leave off the checked attribute) ?>
	<input type="checkbox" id='removeParentsCheckbox' value='removeParentsCheckbox' title='When deleting jobs or datasets, remove the parent datasets and/or experiments' />
	Also remove parent datasets and experiments
</label> 
</div>
<div style='display:none'>
<input class='button lst_cmd_btn' type="button" value="Test" onClick='lcmd.data_package_job_coverage.op("test")' id="btn_t" title=""  />
Test
</div></div>


<hr>
<?= general_visibility_control('Dataset coverage', 'dump_section', '') ?>
<div id="dump_section" style="display:none;">
<div>
Tool to search for
<input id='tool_name' value='MSXML_Gen' size='24'></input> 
<span><?= $this->choosers->get_chooser('tool_name', 'analysisToolPickList')?></span>
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Datasets Without Jobs" onClick='lcmd.data_package_job_coverage.getDatasetInfo("NoDMSJobs")' title=""  /> 
Get list of data package datasets with <span style="font-weight:bold;">no jobs in DMS</span> for tool 
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Datasets Without Jobs" onClick='lcmd.data_package_job_coverage.getDatasetInfo("NoPackageJobs")' title=""  /> 
Get list of data package datasets with <span style="font-weight:bold;">no jobs in data package</span> for tool 
</div>
<div>
<p>Datasets:</p>
<textarea id='dataset_dump_field' rows='12' cols='90'></textarea>
</div>
<?= detail_report_cmd_link("Create DMS jobs", "", "", "analysis_job_request/create") ?>
<span class='LRcmd_cartouche' ></span>

</div>

<hr>

</form>
</div>
