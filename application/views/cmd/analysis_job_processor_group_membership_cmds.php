<div class="LRCmds">


<form name="DBG" action="">

<a title="Show or hide the controls to set processor membership enabled" href="javascript:void(0)" onclick="gamma.sectionToggle('setMembershipSection', 0.5)">Set Membership Enabled</a>
<div id="setMembershipSection" style="display:none;padding:5px 0px 0px 0px;">
<div>Set membership enabled state of selected processors</div>
<div>
For this group to be:
<select name="LocalGroupMbshpEnabled" id='LocalGroupMbshpEnabled'><option selected value="">
	<option value="set_membership_enabled_Y">Y</option>
	<option value="set_membership_enabled_N">N</option>
</select>
For other groups to be:
<select name="OtherGroupMbshpEnabled" id='OtherGroupMbshpEnabled'>
	<option selected value=""></option>
	<option  value="" >Don't change</option>
	<option value="Y" >Y</option>
	<option value="N" >N</option>
</select>
</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_membership.op("set_membership_enabled", "LocalGroupMbshpEnabled", "OtherGroupMbshpEnabled")' /> 
</div>

<div></div>

<a title="Show or hide the controls to remove processors from group" href="javascript:void(0)" onclick="gamma.sectionToggle('removeProcessorsSection', 0.5)">Remove Processors</a>
<div id="removeProcessorsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>Remove selected processors from group</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_membership.op("remove_processors", "", "")' />
</div>

<div></div>

<a title="Show or hide the controls to add new processors to group" href="javascript:void(0)" onclick="gamma.sectionToggle('addProcessorsSection', 0.5)">Add Processors</a>
<div id="addProcessorsSection" style="display:none;padding:5px 0px 0px 0px;">
<div>
Processors to be added to this group:
</div>
<div>
<textarea name="addList" id='add_list_fld' onChange='epsilon.convertList("add_list_fld", ",")' rows=6 cols=80 ></textarea>
</div>
<div>
<span>(You can add to the list above by picking processors from :<?= $this->choosers->get_chooser('add_list_fld', 'assignedProcessorPickList', 'append_comma')?>)</span>
</div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='lcmd.analysis_job_processor_group_membership.op("add_processors", "", "")' />
</div>

<div></div>

</form>
</div>