<script type="text/javascript">

//get an object that represents current settings
//of master controls
function get_master_control_settings() {
	var p = {};
	p.mode = $('#cmds').getInputs('radio', 'createupdate').find(function(radio){return radio.checked;}).value;
	p.incTrackinfo = $('#incTrackinfo').checked;
	p.incAuxinfo = $('#incAuxinfo').checked;
	return p;
}
//update entity in database and call update_next_entity_in_list
//upon completion (in case a list of entities is being processed)
function update_entity(id, container) {
	var file_name = globalAJAX.file_name;
	var url       = globalAJAX.update_url;
	var p         = globalAJAX.processing_params;
	p.entity_type = globalAJAX.entity_type;
	p.file_name   = file_name;
	p.id          = id;
	if(!p.file_name) {alert('No file name'); return; }
	$(container).update(globalAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).update(transport.responseText);
			// call update_next_entity_in_list in case we are processing multiple selections
			// making the call via timeout starts new thread allowing the AJAX thread to terminate
			// so that recursion doesn't pork up the thread pool and the call stack
			setTimeout("update_next_entity_in_list()", 200);
		}
	});
}
//pull the specifications for the next entity to be updated
//out of the master list and call update_entity for it to update the db
function update_next_entity_in_list() {
	var x = globalAJAX.entityList.shift();
	if(x && globalAJAX.update_in_progress) {
		var obj = x.evalJSON();
		if(obj) {
			$('#process_progress').update(globalAJAX.entityList.length);
			update_entity(obj.entity, obj.container);
		}
	} else {
		cancelUpdate();
	}
}
//start the ball rolling on processing the selected entities
function updateSelectedEntities() {
	globalAJAX.update_in_progress = true;

	var file_name = $('#uploaded_file_name').value;
	if(file_name == '') { alert('File name is blank'); return; }
	globalAJAX.file_name = file_name;

	var type = $('#entity_type').innerHTML;
	if(type == '') { alert('Entity type could not be determined'); return; }
	globalAJAX.entity_type = type;

	var p = get_master_control_settings();
	globalAJAX.processing_params = p;

	var action = 'update';
	if(p.mode == 'check_exists') {
		action = 'exists';
	}
	globalAJAX.update_url = globalAJAX.site_url + "upload/" + action;

	globalAJAX.entityList = getSelectedItemList();
	$('#start_update_btn').disable();
	$('#cancel_update_btn').enable();
	update_next_entity_in_list();
}
// stop the processing
function cancelUpdate() {
	globalAJAX.update_in_progress = false;
	$('#process_progress').update('');
	$('#start_update_btn').enable();
	$('#cancel_update_btn').disable();
}
function markUnprocessedEntities() {
	$('.lr_ckbx').each(function(idx, sel){
		var obj = sel.value.evalJSON();
		if(obj && $(obj.container).empty()) {
			sel.checked = true;
		} else {
			sel.checked = false;	
		}
	});
}
</script>

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
<div><input type=RADIO NAME="createupdate" VALUE="add" >Create new entites</div>
<div><input type=RADIO NAME="createupdate" VALUE="update">Update existing entities</div>
<div><input type=RADIO NAME="createupdate" VALUE="check_add"  >Verify Create <span style='font-size:80%;'>(database is not changed)</span></div>
<div><input type=RADIO NAME="createupdate" VALUE="check_update" >Verify Update <span style='font-size:80%;'>(database is not changed)</span></div>
<div><input type=RADIO NAME="createupdate" VALUE="check_exists" CHECKED >Check Existence <span style='font-size:80%;'>(database is not changed)</span></div>

<div style='text-align:center;padding-top:15px;' >
<div><input id='start_update_btn' class="lst_cmd_btn" type="button" value="Start" onClick='updateSelectedEntities()' title='Start processing selected entities' /></div>
<div><input id='cancel_update_btn' class="lst_cmd_btn" type="button" value="Cancel" onClick='cancelUpdate()' title='Stop processing' /></div>
<div id='process_progress' ></div>
</div>

<div>
<div class='cx'><a onclick='setCkbxState("ckbx", 1)' title="Check all checkboxes" href='javascript:void(0)' >Select All</a> &nbsp;  </div>
<div class='cx'><a onclick='setCkbxState("ckbx", 0)' title="Clear all checkboxes" href='javascript:void(0)' >Unselect All</a> &nbsp; </div>
<div class='cx'><a onclick='markUnprocessedEntities()' title="Select entities with blank results" href='javascript:void(0)' >Select Blank Results</a> &nbsp;  </div>
<div class='cx'><a onclick='$(".entity_results_container").each(function(idx, obj){obj.update("")});' title="Clear results column" href='javascript:void(0)' >Clear Results</a>  </div>
</div>

</form>

</div>
