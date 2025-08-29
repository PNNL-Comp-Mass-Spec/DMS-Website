<div class="LRCmds">

<form name="DBG" action="">

<!--
  lcmd.requested_run_admin.op will POST to requested_run_admin/call/admin_sproc ,
  which in turn will call update_requested_run_assignments,
  as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
-->

<hr>
<div>
<!-- "assignedInstrument" will be passed to the @mode parameter of procedure update_requested_run_assignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update"
       onClick='lcmd.requested_run_admin.op("assignedInstrument", "instrument_name_chooser")' />
Assign the selected requests to instrument
<span><?= $choosers->get_chooser('instrument_name', 'instrumentNamePickList')?></span>
</div>

<div>
<!--
  tau.requested_run_admin.setRequestStatus is defined in factors.js
  It calls procedure update_requested_run_admin, as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
  It does this by examining the POST data then calling the procedure defined by operations_sproc
-->
<input class='button lst_cmd_btn' type="button" value="Unassign Requests"
       onClick='tau.requested_run_admin.setRequestStatus("UnassignInstrument")'
       title="Unassign" /> Unassign selected requests from the queued instrument
</div>

<hr>
<div>
<!-- "instrumentGroupIgnoreType" will be passed to the @mode parameter of procedure update_requested_run_assignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update"
       onClick='lcmd.requested_run_admin.op("instrumentGroupIgnoreType", "instrument_group_chooser")' />
Change instrument group of selected requests to
<span><?= $choosers->get_chooser('instrument_group', 'instrumentGroupPickList')?></span>
</div>

<div>
<!-- "datasetType" will be passed to the @mode parameter of procedure update_requested_run_assignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update"
       onClick='lcmd.requested_run_admin.op("datasetType", "dataset_type_chooser")' />
Change dataset type of selected requests to
<span><?= $choosers->get_chooser('dataset_type', 'datasetTypePickList')?></span>
</div>

<div>
<!-- "separationGroup" will be passed to the @mode parameter of procedure update_requested_run_assignments -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update"
       onClick='lcmd.requested_run_admin.op("separationGroup", "separation_group_chooser")' />
Change separation group of selected requests to
<span><?= $choosers->get_chooser('separation_group', 'separationGroupPickList')?></span>
</div>

<hr>
<!--
  tau.requested_run_admin.setRequestStatus is defined in factors.js
  It calls procedure update_requested_run_admin, as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
-->
<div>
<input class='button lst_cmd_btn' type="button" value="Set Requests Active"
       onClick='tau.requested_run_admin.setRequestStatus("Active")'
       title="Set Active"  /> Set selected requests to "Active" status
</div>

<div>
<input class='button lst_cmd_btn' type="button" value="Set Requests Inactive"
       onClick='tau.requested_run_admin.setRequestStatus("Inactive")'
       title="Set Inactive" /> Set selected requests to "Inactive" status
</div>

<div>
<input class='button lst_cmd_btn' type="button" value="Place Requests On Hold"
       onClick='tau.requested_run_admin.setRequestStatus("Holding")'
       title="Set On Hold" /> Set selected requests to "Holding" status
</div>

<hr>
<div>
<!--
    tau.requested_run_admin.changeWPN is defined in factors.js
    It calls procedure update_requested_run_wp, as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
    It does this via a POST to requested_run_admin/call/updatewp_sproc
-->
<input class='button lst_cmd_btn' type="button" value="Change WPN"
       onClick='tau.requested_run_admin.changeWPN($("#oldWPN").val(), $("#newWPN").val())'
       title="Change WPN from old to new for selected requests" />
from existing <input id='oldWPN' title="Current work package"/> to <input id='newWPN' title="New work package"/>
for all or selected requests
</div>

<hr>
<div>
<!--
  tau.requested_run_admin.setRequestStatus is defined in factors.js
  It calls procedure update_requested_run_admin, as defined in https://dms2.pnl.gov/config_db/show_db/requested_run_admin.db
-->
<input class='button lst_cmd_btn' type="button" value="Delete Requests"
       onClick='tau.requested_run_admin.setRequestStatus("Delete")'
       title="Delete" /> Delete selected requests
</div>

</form>
</div>

<?php // Import factors.js ?>
<?php echo view('resource_links/factors') ?>
