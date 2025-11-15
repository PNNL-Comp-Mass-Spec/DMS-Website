var lstRep = lstRep || {
    // load the filter panel according to the given layout mode
    updateMyFilter: function($mode) {
        dmsOps.updateContainer('report_filter/' + $mode, 'filter_form', 'search_filter_container', lstRep.filter_observers_action);
        if($mode == 'minimal') {
            $('#show_more_filter').show();$('#show_less_filter').hide();
        } else {
            $('#show_more_filter').hide();$('#show_less_filter').show();
        }
    },
    // bind observers to the filter fields to monitor filter status
    // and initialize filter status display
    filter_observers_action: {
        run:function() {
            dmsFilter.set_filter_field_observers();
            dmsFilter.is_filter_active();
            dmsFilter.adjustFilterVisibilityControls();
        }
    },
    // copy the contents of the upper paging display to the lower one
    paging_cleanup_action: {
        run:function() {
            $('#paging_container_lower').html($('#paging_container_upper').html());
        }
    },
    // update the paging display sections, or hide them if no data rows
    paging_update_action: {
        run:function() {
            if($('#data_message').val() != null) {
                $('#paging_container_upper').hide();
                $('#paging_container_lower').hide();
            } else {
                $('#paging_container_upper').show();
                $('#paging_container_lower').show();
                dmsOps.updateContainer('report_paging', 'filter_form', 'paging_container_upper', lstRep.paging_cleanup_action);
            }
        }
    },
    // call paging action and also initialize checkbox state if this page is a helper
    data_post_load_action: {
        run:function(){
            lstRep.paging_update_action.run();
            if(!$('#data_message') && dmsjs.pageContext.is_ms_helper) { dmsChooser.initializeChooserCkbx('ckbx') }
        }
    },
    // go get some data rows
    data_update_action: {
        run:function(){
            dmsOps.updateContainer('report_data', 'filter_form', 'data_container', lstRep.data_post_load_action);
        }
    },
    updateShowSQL: function(ignoreIfClosed) {
        // Note that string 'SQL' is used in dmsjs.updateMessageBox to trigger adding line breaks
        dmsjs.updateMessageBox(dmsjs.pageContext.my_tag + '/report_info/sql', 'filter_form', 'SQL', ignoreIfClosed);
    },
    updateShowURL: function(ignoreIfClosed) {
        dmsjs.updateMessageBox(dmsjs.pageContext.my_tag + '/report_info/url', 'filter_form', 'URL', ignoreIfClosed);
    },
    // start the data update chain for the page
    updateMyData: function(loading) {
        if(loading == 'no_load') {
            $('#data_container').html('Data will be displayed after you click the "Search" button.');
        } else {
            if(loading && loading == 'reset') $('#qf_first_row').val(1);
            lstRep.data_update_action.run();
        }
    }
} // lstRep

// after the page loads, set things in motion to populate it
$(document).ready(function () {
        lstRep.updateMyFilter('minimal');
        lstRep.updateMyData(dmsjs.pageContext.initalDataLoad);
        dmsOps.reloadListReportData = function() { lstRep.updateMyData('autoload');}
});
