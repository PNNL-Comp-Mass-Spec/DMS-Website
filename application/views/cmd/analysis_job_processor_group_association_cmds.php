<div class="LRCmds">

<form name="DBG" action="">
<a title="Show or hide the controls to disassociate jobs from group" href="javascript:void(0)" onclick="gamma.sectionToggle('removeJobsSection', 0.5)");>Remove jobs from association</a>
<div id="removeJobsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>Remove selected jobs from group</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_association.op("remove")' />
</div>

<div></div>

<a title="Show or hide the controls to associate new jobs with group" href="javascript:void(0)" onclick="gamma.sectionToggle('addNewJobsSection', 0.5)">Add New Jobs</a>
<div id="addNewJobsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>
Jobs to be associated with this group:
</div>
<div>
<textarea name="addList" id="add_list_fld" rows=6 cols=80 onChange='epsilon.convertList("add_list_fld", ",")' ></textarea>
</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_association.op("add")' />
</div>

<div></div>

</form>
</div>
