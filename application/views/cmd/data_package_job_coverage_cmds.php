<div class="LRCmds">

<form name="DBG" action="">

<hr>
Membership commands <a href="javascript:void(0)" onclick="gamma.sectionToggle('membership_section', 0.5, this)"><span class="expando_section ui-icon ui-icon-circle-plus"></span></a>
<div id="membership_section" style="display:none;">
<div>
<input class='button lst_cmd_btn' type="button" value="Add Jobs" onClick='lcmd.data_package_job_coverage.op("add")' id="btn_a" title=""  /> 
Add selected jobs to package that are not already in package.
</div>
<div>
<input class='button lst_cmd_btn' type="button" value="Remove Jobs" onClick='lcmd.data_package_job_coverage.op("delete")' id="btn_r" title=""  />
Remove selected jobs from package.
</div>
<div style='display:none'>
<input class='button lst_cmd_btn' type="button" value="Test" onClick='lcmd.data_package_job_coverage.op("test")' id="btn_t" title=""  />
Test
</div></div>


<hr>
Dataset coverage <a href="javascript:void(0)" onclick="gamma.sectionToggle('dump_section', 0.5, this)"><span class="expando_section ui-icon ui-icon-circle-plus"></span></a>
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
Create DMS jobs <a href="<?= site_url() ?>analysis_job_request/create" ><span class="expando_section ui-icon ui-icon-circle-arrow-e"></a>
<span class='LRcmd_cartouche' ></span>

</div>

<hr>

</form>
</div>
