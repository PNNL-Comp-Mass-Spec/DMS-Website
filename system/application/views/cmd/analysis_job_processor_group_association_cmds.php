<script type="text/javascript">
function performOperation(mode) {
	var list = null;
	if (mode == 'add') {
		list = $F('add_list_fld');
		if (list == '') {
			alert('You must supply jobs to add.');
			return;
		}
	} else {
		list = getCkbxList('ckbx');
		if (list == '') {
			alert('You must select items.');
			return;
		}
	}
	if ( !confirm("Are you sure that you want to update the database?") )
		return;

	url =  "<?= $ops_url ?>";
	var p = {};
	p.command = mode;
	p.newValue = '';
	p.processorGroupID = $F('pf_groupid');
	if(p.processorGroupID == '') {alert('No group ID in primary filter'); return;}
	p.JobList = list;
	submitOperation(url, p);
}
</script>


<div class="LRCmds">
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" action="">
<a title="Show or hide the controls to disassociate jobs from group" href="#" onclick="Effect.toggle('removeJobsSection', 'appear', { duration: 0.5 }); return false;");>Remove jobs from association</a>
<div id="removeJobsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>Remove selected jobs from group</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("remove")' />
</div>

<div></div>

<a title="Show or hide the controls to associate new jobs with group" href="#" onclick="Effect.toggle('addNewJobsSection', 'appear', { duration: 0.5 }); return false;">Add New Jobs</a>
<div id="addNewJobsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>
Jobs to be associated with this group:
</div>
<div>
<textarea name="addList" id="add_list_fld" rows=6 cols=80 onChange='convertList("add_list_fld", ",")' ></textarea>
</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("add")' />
</div>

<div></div>

</form>
</div>


