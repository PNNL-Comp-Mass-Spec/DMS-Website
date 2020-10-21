<div class="LRCmds">

<form name="DBG" action="">

<!-- 
  lcmd.requested_run_admin.op will POST to requested_run/operation/ , 
  which in turn will call UpdateRequestedRunAssignments, 
  as defined in https://dms2.pnl.gov/config_db/show_db/requested_run.db
  -->

<div>
<!-- "instrumentGroupIgnoreType" will be passed to the @mode parameter of stored procedure UpdateRequestedRunAssignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update" 
       onClick='lcmd.requested_run_admin.op("instrumentGroupIgnoreType", "instrument_group_chooser")' />
Change instrument group of selected requests to
<span><?= $this->choosers->get_chooser('instrument_group', 'instrumentGroupPickList')?></span>
</div>

<div>
<!-- "datasetType" will be passed to the @mode parameter of stored procedure UpdateRequestedRunAssignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update" 
       onClick='lcmd.requested_run_admin.op("datasetType", "dataset_type_chooser")' />
Change dataset type of selected requests to
<span><?= $this->choosers->get_chooser('dataset_type', 'datasetTypePickList')?></span>
</div>

<div>
<!-- "separationGroup" will be passed to the @mode parameter of stored procedure UpdateRequestedRunAssignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update" 
       onClick='lcmd.requested_run_admin.op("separationGroup", "separation_group_chooser")' />
Change separation group of selected requests to
<span><?= $this->choosers->get_chooser('separation_group', 'separationGroupPickList')?></span>
</div>

<hr>
<div>
<!-- "assignedInstrument" will be passed to the @mode parameter of stored procedure UpdateRequestedRunAssignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update" 
       onClick='lcmd.requested_run_admin.op("assignedInstrument", "instrument_name_chooser")' />
Assign the selected requests to instrument
<span><?= $this->choosers->get_chooser('instrument_name', 'instrumentNamePickList')?></span>
</div>

<div>
<!-- 
  tau.requested_run_admin.setRequestStatus is defined in factors.js
  It calls stored procedure UpdateRequestedRunAdmin, as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
  It does this by examining the POST data then calling the stored procedure defined by operations_sproc
-->
<input class='button lst_cmd_btn' type="button" value="Unassign Requests"
       onClick='tau.requested_run_admin.setRequestStatus("UnassignInstrument")'
       title="Unassign"  /> Unassign selected requests from the queued instrument
</div>

<hr>
<div>
from existing <input id='oldWPN'/> to <input id='newWPN'/>
for all or selected requests
</div>

<hr>
<div>
<!-- 
  tau.requested_run_admin.setRequestStatus is defined in factors.js
  It calls stored procedure UpdateRequestedRunAdmin, as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
-->
<input class='button lst_cmd_btn' type="button" value="Delete Requests" 
       onClick='tau.requested_run_admin.setRequestStatus("Delete")' 
       title="Delete"  /> Delete selected requests
</div>

</form>
</div>

<script src="<?= base_url().'javascript/factors.js?version=100' ?>"></script>
