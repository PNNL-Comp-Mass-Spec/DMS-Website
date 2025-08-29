var packages = {
    callChooserSetType: function(item_type, chooserPage, delimiter, xref){
        $('#itemTypeSelector').val(item_type);
        var page = dmsjs.pageContext.site_url + chooserPage;
        dmsChooser.callChooser('entry_item_list', page, delimiter, xref)
    },
    /**
     * Process results
     * @param {type} data
     * @param {type} container
     * @returns {undefined}
     */
    processResults: function(data, container) {
        if(data.indexOf('html failed') > -1) {
            container.html(data);
        } else {
            if (data.length === 0)
                container.html('Operation was successful');
            else
                container.html(data);

            detRep.updateMyData();
        }
    },
    updateDataPackageItems: function(id, form_id, mode) {
        if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
        var url = dmsjs.pageContext.site_url + "data_package/operation";

        var removeParents = 0;
        if (document.getElementById('removeParentsCheckbox').checked)
            removeParents=1;

        $('#entry_cmd_mode').val(mode);
        $('#removeParents').val(removeParents);

        // Call procedure update_data_package_items
        // dmsOps.doOperation is defined in dmsOps.js
        dmsOps.doOperation(url, form_id, 'entry_update_status', function(data, container) {
            packages.processResults(data, container);
        });
    },
    updateOSMPackage: function(id, mode) {
        if ( !confirm("Are you sure that you want to " + mode + " this package?") ) return;
        var url = dmsjs.pageContext.site_url + "osm_package/call/";
        var lUrl = dmsjs.pageContext.site_url + "osm_package/report";
        var p = {osmPackageID: id, mode: mode};
        dmsOps.doOperation(url, p, 'entry_update_status', function(data, container) {
            var x = $.parseJSON(data);
            if(x.result == 0) {
                var s = "OSM Package was deleted. Go to <a href='"+ lUrl + "' >list report</a>";
                $('#osm_cmd_container').hide();
                $('#attachments_control_section').hide();
                $('.LRepExport').hide();
                container.html(s);
                var overlay = packages.makeElementOverlay("data_container", "It's dead, Jim...");
                $('#overlay_label').fadeIn(900);
            } else {
                container.html(x.message);
            }
        });
    },
    makeElementOverlay: function(elementId, message) {
        var target = $("#" + elementId);
        var overlay = $("<div />").css({
            position: "absolute",
            width: "100%",
            height: "100%",
            left: 0,
            top: 0,
            zIndex: 1000,  // to be on the safe side
            background: 'gray',
            opacity: 0.8
        });
        var label = $("<div id='overlay_label' ></div>").css({
            'margin-left' : '2em',
            'margin-top' : '2em',
            'font-size' : '4em',
            'color' : 'black',
            'font-style' : 'italic',
            'display' : 'none'
        });
        if(message) {
            label.text(message);
            overlay.append(label);
        }
        overlay.appendTo(target.css("position", "relative"));
        return overlay;
    },
/* OMCS-977
    revealOsmPackageCreateSection: function() {
        var iframe = document.getElementById('embedded_page');
        var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
        $('#hdrContainer').hide();
        dmsjs.toggleVisibility('package_entry_section',  0.5 );
        return false;
    },
    callSuggestionSetType: function(item_type, mode) {
        $('#entry_item_list').val('');
        var url = dmsjs.pageContext.site_url + "osm_package/suggested_items/" + dmsjs.pageContext.Id + "/" + mode;
        dmsOps.doOperation(url, false, 'item_section', function(data) {
                $('#itemTypeSelector').val(item_type);
                $('#entry_item_list').val(data);
        });
    },
    callOSMChooser: function(){
        var page = dmsjs.pageContext.site_url + "helper_osm_package/report";
        dmsChooser.callChooser('packageID', page,  ',', '')
    },
    goToPage: function() {
        var url = dmsjs.pageContext.site_url + "osm_package_items/report/" + this.codeMap[dmsjs.pageContext.my_tag] + "/" + $('#packageID').val();
        window.location.href = url;
    },
    codeMap:{
       "campaign":"Campaigns",
       "cell_culture":"Biomaterial",
       "dataset":"Datasets",
       "experiment":"Experiments",
       "experiment_group":"Experiment_Groups",
       "prep_lc_run":"HPLC_Runs",
       "material_container":"Material_Containers",
       "requested_run":"Requested_Runs",
       "sample_prep_request":"Sample_Prep_Requests",
       "sample_submission":"Sample_Submissions"
    },
    updateOSMPackageItems_1: function(id, form_id, mode) {
        if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
        var url = dmsjs.pageContext.site_url + "osm_package/operation";
        $('#entry_cmd_mode').val(mode);
        dmsOps.doOperation(url, form_id, 'entry_update_status', function(data, container) {
            packages.processResults(data, container);
        });
    },
    updateOSMPackageItems_2: function(form_id, mode) {
        if ( !confirm("Are you sure that you want to " + mode + " this entity to the OSM package?") ) return;
        var url = dmsjs.pageContext.site_url + "osm_package/operation";
        var id = dmsjs.pageContext.Id;
        $('#entry_cmd_mode').val(mode);
        $('#itemTypeSelector').val(this.codeMap[dmsjs.pageContext.my_tag]);
        $('#entry_item_list').val(id);
        dmsOps.doOperation(url, form_id, 'entry_update_status', function(data, container) {
            packages.processResults(data, container);
        });
    },
    */
    // This is called from "data_package_items/report" when the user clicks "Delete from Package" or "Update comment"
    performOperation: function (mode) {
        var list = '';
        var rows = document.getElementsByName('ckbx');
        for (var i = 0; i < rows.length; i++) {
            if ( rows[i].checked )
                list  += rows[i].value;
        }
        if(list=='') {
            alert('You must select items');
            return;
        }
        if ( !confirm("Are you sure that you want to update the database?") )
            return;

        var removeParents = 0;
        if (document.getElementById('removeParentsCheckbox').checked)
            removeParents=1;

        var url =  dmsjs.pageContext.site_url + 'data_package_items/operation';
        $('#paramListXML').val(list);
        $('#entry_cmd_mode').val(mode);
        $('#removeParents').val(removeParents);
        var p = $('#operation_form').serialize();
        // Call procedure update_data_package_items_xml
        // dmsOps.submitOperation is defined in dmsOps.js
        dmsOps.submitOperation(url, p, true);
    }
}
