<div class="LRCmds">

<form name="DBG" action="">

<!--
  lcmd.service_center_use_admin.op will POST to service_center_use_admin/call/admin_sproc ,
  which in turn will call update_service_use_entries,
  as defined in https://dms2.pnl.gov/config_db/show_db/service_center_use_admin.db

  lcmd.service_center_use_admin.op is defined in file public/javascript/lcmd.js
-->

<hr>
<div>
<!-- "datasetRating" will be passed to the @mode parameter of procedure update_service_use_entries -->
<!-- The value selected in the chooser will be sent to the @newValue parameter -->
<input class="button lst_cmd_btn" type="button" value="Update"
       onClick='lcmd.service_center_use_admin.op("datasetRating", "dataset_rating_chooser", $("#datasetRatingComment").val())' />
Update the dataset rating for the selected service use entries
<span><?= $choosers->get_chooser('dataset_rating', 'datasetRatingPickList')?>
Text to append to the dataset comment: <input id='datasetRatingComment' title="Text to append to the comment"/></span>
</div>

<hr>
<div>
<!--
    tau.service_center_use_admin.changeWPN is defined in factors.js
    It calls procedure update_service_use_wp, as defined in https://dms2.pnl.gov/config_db/show_db/service_center_use_admin.db
    It does this via a POST to service_center_use_admin/call/update_wp_sproc
-->
<input class='button lst_cmd_btn' type="button" value="Change WPN"
       onClick='tau.service_center_use_admin.changeWPN($("#oldWPN").val(), $("#newWPN").val())'
       title="Change WPN from old to new for selected service use entries" />
from existing <input id='oldWPN' title="Current work package"/> to <input id='newWPN' title="New work package"/>
for all or selected service use entries
</div>

<div>
<!--
    tau.service_center_use_admin.updateComment is defined in factors.js
    It calls procedure update_service_use_comment, as defined in https://dms2.pnl.gov/config_db/show_db/service_center_use_admin.db
    It does this via a POST to service_center_use_admin/call/update_comment_sproc
-->
<input class='button lst_cmd_btn' type="button" value="Update comments"
       onClick='tau.service_center_use_admin.updateComment($("#textToFind").val(), $("#replacementText").val())'
       title="Update comments for selected service use entries" />
by looking for <input id='textToFind' title="Text to find"/> and replacing with <input id='replacementText' title="Replacement text"/>
for all or selected service use entries. If text to find is not specified, the replacement text will be appended to the existing comments.
</div>

<hr>
<div>
<!-- "serviceCenterRefund" will be passed to the @mode parameter of procedure update_service_use_entries -->
<input class="button lst_cmd_btn" type="button" value="Refund"
       onClick='lcmd.service_center_use_admin.op("serviceCenterRefund", "true")' />
Refund selected datasets to service center (only valid for service use entries with datasets that have report state "Submitted to service center")
</div>

<div>
<!-- "serviceCenterRefund" will be passed to the @mode parameter of procedure update_service_use_entries -->
<input class="button lst_cmd_btn" type="button" value="Cancel Refund"
       onClick='lcmd.service_center_use_admin.op("serviceCenterRefund", "false")' />
Cancel refund of selected datasets (only valid for service use entries with datasets that have report state "Need to refund to service center")
</div>

</form>
</div>

<?php // Import factors.js ?>
<?php echo view('resource_links/factors') ?>
