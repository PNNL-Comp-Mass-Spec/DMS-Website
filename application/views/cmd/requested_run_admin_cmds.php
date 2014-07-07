<div class="LRCmds">

<form name="DBG" action="">


<div>
<input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.requested_run_admin.op("instrumentIgnoreType", "instrument_group_chooser")' /> Change instrument group of selected requests to
<span><?= $this->choosers->get_chooser('instrument_group', 'instrumentGroupPickList')?></span>
</div>

<div>
<input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.requested_run_admin.op("datasetType", "dataset_type_chooser")' /> Change dataset type of selected requests to 
<span><?= $this->choosers->get_chooser('dataset_type', 'datasetTypePickList')?></span>
</div>

<div>
<input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.requested_run_admin.op("separationGroup", "separation_group_chooser")' /> Change separation group of selected requests to 
<span><?= $this->choosers->get_chooser('separation_group', 'separationGroupPickList')?></span>
</div>

<hr>
<div>
<input class='button lst_cmd_btn' type="button" value="Set Requests Active" onClick='tau.requested_run_admin.setRequestStatus("Active")' title="Test"  /> Set selected requests to "Active" status
</div>

<div>
<input class='button lst_cmd_btn' type="button" value="Set Requests Inactive" onClick='tau.requested_run_admin.setRequestStatus("Inactive")' title="Test"  /> Set selected requests to "Inactive" status
</div>

<hr>
<div>
<input class='button lst_cmd_btn' type="button" value="Change WPN" onClick='tau.requested_run_admin.changeWPN($("#oldWPN").val(), $("#newWPN").val())' title="Change WPN from old to new for selected requests"  /> 
from existing <input id='oldWPN'/> to <input id='newWPN'/>
for all or selected requests
</div>

<hr>
<div>
<input class='button lst_cmd_btn' type="button" value="Delete Requests" onClick='tau.requested_run_admin.setRequestStatus("delete")' title="Test"  /> Delete selected requests
</div>

</form>
</div>

<script src="<?= base_url().'javascript/factors.js' ?>"></script>
