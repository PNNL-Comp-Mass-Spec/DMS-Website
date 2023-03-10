//------------------------------------------
// File upload functions
//------------------------------------------

var dmsUpload = {

    // called by javascript that is returned by upload operation
    // into iframe and which is run immediately
    report_upload_results: function(file_name, error) {
        if(error != '') {
            $('#uploaded_file_name').val('');
    //      $('#upload_error').html($error);
        } else {
            $('#uploaded_file_name').val(file_name);
    //      $('#upload_error').html('Upload was successful');
            dmsUpload.extract();
            dmsUpload.clearSpreadsheetDisplay();
        }
    },
    updateContainer: function(action, containerId, id) {
        var url = dmsjs.pageContext.site_url + 'upload/' + action;
        var p = {};
        p.file_name = $('#uploaded_file_name').val();
        p.id = id;
        if(!p.file_name) {alert('No file name'); return; }
        dmsOps.loadContainer(url, p, containerId);
    },
    // extract data from uploaded spreadsheet and display on page
    extract: function() {
        $('#master_control_container').show();
        dmsUpload.updateContainer('extract_data', 'ss_entity_list_container', '');
    //  dmsUpload.showSpreadsheetContents();
    },
    showSpreadsheetContents: function() {
        $('#ss_table_display_area').show();
        dmsUpload.updateContainer('extract_table', 'ss_table_container', '');
    },
    clearSpreadsheetDisplay: function() {
        $('#ss_table_display_area').hide();
        $('#ss_table_container').html("")
    },

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
        var file_name = dmsjs.pageContext.file_name;
        var url       = dmsjs.pageContext.update_url;
        var p         = dmsjs.pageContext.processing_params;
        p.entity_type = dmsjs.pageContext.entity_type;
        p.file_name   = file_name;
        p.id          = id;
        if(!p.file_name) {alert('No file name'); return; }
        dmsOps.loadContainer(url, p, containerId, function(){
                // call update_next_entity_in_list in case we are processing multiple selections
                // making the call via timeout starts new thread allowing the AJAX thread to terminate
                // so that recursion doesn't pork up the thread pool and the call stack
                setTimeout("dmsUpload.update_next_entity_in_list()", 200);
        });
    },
    //pull the specifications for the next entity to be updated
    //out of the master list and call update_entity for it to update the db
    update_next_entity_in_list: function() {
        var x = dmsjs.pageContext.entityList.shift();
        if(x && dmsjs.pageContext.update_in_progress) {
            var obj = JSON.parse(x);
            if(obj) {
                $('#process_progress').html(dmsjs.pageContext.entityList.length);
                dmsUpload.update_entity(obj.entity, obj.container);
            }
        } else {
            dmsUpload.cancelUpdate();
        }
    },
    //start the ball rolling on processing the selected entities
    updateSelectedEntities: function() {
        dmsjs.pageContext.update_in_progress = true;

        var file_name = $('#uploaded_file_name').val();
        if(file_name == '') { alert('File name is blank'); return; }
        dmsjs.pageContext.file_name = file_name;

        var type = $('#entity_type').html();
        if(type == '') { alert('Entity type could not be determined'); return; }
        dmsjs.pageContext.entity_type = type;

        var p = dmsUpload.get_master_control_settings();
        dmsjs.pageContext.processing_params = p;

        var action = 'update';
        if(p.mode == 'check_exists') {
            action = 'exists';
        }
        dmsjs.pageContext.update_url = dmsjs.pageContext.site_url + "upload/" + action;

        dmsjs.pageContext.entityList = dmsChooser.getSelectedItemList();
        $('#start_update_btn').attr("disabled", true);
        $('#cancel_update_btn').attr("disabled", false);
        dmsUpload.update_next_entity_in_list();
    },
    // stop the processing
    cancelUpdate: function() {
        dmsjs.pageContext.update_in_progress = false;
        $('#process_progress').html('');
        $('#start_update_btn').attr("disabled", false);
        $('#cancel_update_btn').attr("disabled", true);
    },
    markUnprocessedEntities: function() {
        $('.lr_ckbx').each(function(){
            var obj = JSON.parse(this.value);
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

}; // dmsUpload
