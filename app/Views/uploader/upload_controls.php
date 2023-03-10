<style type="text/css">
.cx {
    padding:0 0 4px 0;
}
</style>

<div class='LRCmds'>
<h3 style='text-align:center;'>Commands</h3>

<form name="DBG" id="cmds" >

<div style='font-weight:bold;' >What to include:</div>
<div><input type="CHECKBOX" id="incTrackinfo" value="true" CHECKED />Include Tracking Info</div>
<div><input type="CHECKBOX" id="incAuxinfo" value="true" CHECKED />Include Auxiliary Info</div>

<div style='font-weight:bold;' >What action to take:</div>
<div><input type=RADIO NAME="createupdate" VALUE="add" >Create new entities</div>
<div><input type=RADIO NAME="createupdate" VALUE="update">Update existing entities</div>
<div><input type=RADIO NAME="createupdate" VALUE="check_add"  >Verify Create <span style='font-size:80%;'>(database is not changed)</span></div>
<div><input type=RADIO NAME="createupdate" VALUE="check_update" >Verify Update <span style='font-size:80%;'>(database is not changed)</span></div>
<div><input type=RADIO NAME="createupdate" VALUE="check_exists" CHECKED >Check Existence <span style='font-size:80%;'>(database is not changed)</span></div>

<div style='text-align:center;padding-top:15px;' >
<div><input id='start_update_btn' class="button lst_cmd_btn" type="button" value="Start" onClick='dmsUpload.updateSelectedEntities()' title='Start processing selected entities' /></div>
<div><input id='cancel_update_btn' class="button lst_cmd_btn" type="button" value="Cancel" onClick='dmsUpload.cancelUpdate()' title='Stop processing' /></div>
<div id='process_progress' ></div>
</div>

<div>
<div class='cx'><a onclick='dmsChooser.setCkbxState("ckbx", 1)' title="Check all checkboxes" href='javascript:void(0)' >Select All</a> &nbsp;  </div>
<div class='cx'><a onclick='dmsChooser.setCkbxState("ckbx", 0)' title="Clear all checkboxes" href='javascript:void(0)' >Unselect All</a> &nbsp; </div>
<div class='cx'><a onclick='dmsUpload.markUnprocessedEntities()' title="Select entities with blank results" href='javascript:void(0)' >Select Blank Results</a> &nbsp;  </div>
<div class='cx'><a onclick='dmsUpload.clearResults()' title="Clear results column" href='javascript:void(0)' >Clear Results</a>  </div>
</div>

</form>

</div>

