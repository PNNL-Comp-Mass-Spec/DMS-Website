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
<div><input id='start_update_btn' class="button lst_cmd_btn" type="button" value="Start" onClick='upld.updateSelectedEntities()' title='Start processing selected entities' /></div>
<div><input id='cancel_update_btn' class="button lst_cmd_btn" type="button" value="Cancel" onClick='upld.cancelUpdate()' title='Stop processing' /></div>
<div id='process_progress' ></div>
</div>

<div>
<div class='cx'><a onclick='lambda.setCkbxState("ckbx", 1)' title="Check all checkboxes" href='javascript:void(0)' >Select All</a> &nbsp;  </div>
<div class='cx'><a onclick='lambda.setCkbxState("ckbx", 0)' title="Clear all checkboxes" href='javascript:void(0)' >Unselect All</a> &nbsp; </div>
<div class='cx'><a onclick='upld.markUnprocessedEntities()' title="Select entities with blank results" href='javascript:void(0)' >Select Blank Results</a> &nbsp;  </div>
<div class='cx'><a onclick='upld.clearResults()' title="Clear results column" href='javascript:void(0)' >Clear Results</a>  </div>
</div>

</form>

</div>

<script type="text/javascript">

var upld = {
    //get an object that represents current settings
    //of master controls
    get_master_control_settings: function() {
        var p = {};
        p.mode = $('#cmds :checked').filter(':radio').first().val();
        p.incTrackinfo = $('#incTrackinfo').is(':checked');
        p.incAuxinfo = $('#incAuxinfo').is(':checked');
        return p;
    },
    //update entity in database and call update_next_entity_in_list
    //upon completion (in case a list of entities is being processed)
    update_entity: function(id, containerId) {
        var file_name = gamma.pageContext.file_name;
        var url       = gamma.pageContext.update_url;
        var p         = gamma.pageContext.processing_params;
        p.entity_type = gamma.pageContext.entity_type;
        p.file_name   = file_name;
        p.id          = id;
        if(!p.file_name) {alert('No file name'); return; }
        gamma.loadContainer(url, p, containerId, function(){
                // call update_next_entity_in_list in case we are processing multiple selections
                // making the call via timeout starts new thread allowing the AJAX thread to terminate
                // so that recursion doesn't pork up the thread pool and the call stack
                setTimeout("upld.update_next_entity_in_list()", 200);
        }); 
    },
    //pull the specifications for the next entity to be updated
    //out of the master list and call update_entity for it to update the db
    update_next_entity_in_list: function() {
        var x = gamma.pageContext.entityList.shift();
        if(x && gamma.pageContext.update_in_progress) {
            var obj = $.parseJSON(x);
            if(obj) {
                $('#process_progress').html(gamma.pageContext.entityList.length);
                upld.update_entity(obj.entity, obj.container);
            }
        } else {
            upld.cancelUpdate();
        }
    },
    //start the ball rolling on processing the selected entities
    updateSelectedEntities: function() {
        gamma.pageContext.update_in_progress = true;
    
        var file_name = $('#uploaded_file_name').val();
        if(file_name == '') { alert('File name is blank'); return; }
        gamma.pageContext.file_name = file_name;
    
        var type = $('#entity_type').html();
        if(type == '') { alert('Entity type could not be determined'); return; }
        gamma.pageContext.entity_type = type;
    
        var p = upld.get_master_control_settings();
        gamma.pageContext.processing_params = p;
    
        var action = 'update';
        if(p.mode == 'check_exists') {
            action = 'exists';
        }
        gamma.pageContext.update_url = gamma.pageContext.site_url + "upload/" + action;
    
        gamma.pageContext.entityList = lambda.getSelectedItemList();
        $('#start_update_btn').attr("disabled", true);
        $('#cancel_update_btn').attr("disabled", false);
        upld.update_next_entity_in_list();
    },
    // stop the processing
    cancelUpdate: function() {
        gamma.pageContext.update_in_progress = false;
        $('#process_progress').html('');
        $('#start_update_btn').attr("disabled", false);
        $('#cancel_update_btn').attr("disabled", true);
    },
    markUnprocessedEntities: function() {
        $('.lr_ckbx').each(function(){
            var obj = $.parseJSON(this.value);
            if(obj && !$('#' + obj.container).html()) { 
                this.checked = true;
            } else {
                this.checked = false;   
            }
        });
    },
    clearResults: function() {
        $(".entity_results_container").each(function(){
            $(this).html("");
        });
    }
} // upld
</script>
