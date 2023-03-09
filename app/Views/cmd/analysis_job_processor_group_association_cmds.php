<div class="LRCmds">

<form name="DBG" action="">
<?= general_visibility_control('Remove jobs from association', 'removeJobsSection', 'disassociating jobs from group') ?>
<div id="removeJobsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>Remove selected jobs from group</div>
<input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_association.op("remove")' />
</div>

<div></div>

<?= general_visibility_control('Add New Jobs', 'addNewJobsSection', 'associating new jobs with group') ?>
<div id="addNewJobsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>
Jobs to be associated with this group:
</div>
<div>
<textarea name="addList" id="add_list_fld" rows=6 cols=80 onChange='dmsInput.convertList("add_list_fld", ",")' ></textarea>
</div>
<input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_association.op("add")' />
</div>

<div></div>

</form>
</div>
