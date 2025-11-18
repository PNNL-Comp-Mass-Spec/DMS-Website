// These functions are used by lcmd.js (ready callback for page requested_run_factors)
// and also by several list reports:
//   requested_run_admin            as defined in app/Views/cmd/requested_run_admin_cmds.php
//   requested_run_factors          as defined in app/Views/cmd/requested_run_factors_cmds.php
//   service_center_use_admin       as defined in app/Views/cmd/service_center_use_admin_cmds.php

// These functions are used by the requested_run_factors, requested_run_admin, and service_center_use_admin list reports
var lcmdExtras = lcmdExtras || {
    requested_run_factors: {
        setItemTypeField: function() {
            var $s = '';
            if (dmsChooser.currentChooser.page.indexOf('helper_requested_run_batch') > -1) {
                $s = 'Batch_ID';
            }
            if (dmsChooser.currentChooser.page.indexOf('helper_requested_run_ckbx') > -1) {
                $s = 'Requested_Run_ID';
            }
            if (dmsChooser.currentChooser.page.indexOf('helper_dataset_ckbx') > -1) {
                $s = 'Dataset_Name';
            }
            if (dmsChooser.currentChooser.page.indexOf('helper_experiment_ckbx') > -1) {
                $s = 'Experiment_Name';
            }
            if ($s) {
                $('#itemType').val($s);
            }
        },
        updateDatabaseFromList: function(flist, id_type) {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var factorXML = factorsjs.getFactorXMLFromList(flist);
            if (id_type) {
                factorXML = '<id type="' + id_type + '" />' + factorXML;
            }
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.factorList = factorXML;
            // dmsOps.submitOperation is defined in dmsOps.js
            dmsOps.submitOperation(url, p);
        },
        saveChangesToDatabase: function() {
            var cols = factorsjs.getListReportColumnList();
            var col_list = dmsjs.removeItems(cols, ['Sel', 'BatchID', 'Batch_ID', 'Status', 'Name',  'Request',  'Experiment', 'Dataset']);
            var flist = factorsjs.getFactorFieldList(col_list);
            this.updateDatabaseFromList(flist, 'Request');
        },
        load_delimited_text: function() {
            // Parse tab-delimited text to convert it to XML which is passed to procedure update_requested_run_factors
            // dmsInput.parseDelimitedText is defined in dmsInput.js
            var parsed_data = dmsInput.parseDelimitedText('delimited_text_input');
            var id_type = parsed_data.header[0];
            var col_list = dmsjs.removeItems(parsed_data.header, [id_type, 'Block', 'Run Order', 'Run_Order']);
            var flist = factorsjs.getFieldListFromParsedData(parsed_data, col_list);
            this.updateDatabaseFromList(flist, id_type);
        }
    },
    requested_run_admin: {
        updateDatabaseFromList: function(xml, command) {    // This method is called from method setRequestStatus
            if (xml == '') {
                alert('No requests were selected');
                return;
            }
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var p = {};
            p.requestList = xml;
            p.command = command;
            // dmsjs.pageContext is defined in dms.js
            // POST to requested_run_admin/call/admin_sproc
            var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag +  "/call/admin_sproc";
            // dmsOps.submitCall is defined in dmsOps.js
            dmsOps.submitCall(url, p);
        },
        setRequestStatus: function(command) {
            // POST to requested_run_admin/call/admin_sproc by calling method updateDatabaseFromList
            var iList = dmsChooser.getSelectedItemList();
            var xml = dmsInput.getXmlElementsFromArray(iList, 'r', 'i');
            this.updateDatabaseFromList(xml, command);
        },
        changeWPN: function(oldWpn, newWpn) {
            // POST to requested_run_admin/call/updatewp_sproc
            var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag +  "/call/updatewp_sproc";
            var p = {};
            p.oldWorkPackage = oldWpn;
            p.newWorkPackage = newWpn;
            p.requestIdList = dmsChooser.getSelectedItemList().join();
            if (!p.requestIdList) {
                // This if statement asked the user if they wished to change the work package for all requested runs
                // if ( !confirm("There are no requests selected. Do you wish to apply the change to all requests?") ) return;
                
                // Since this update would be applied to every requested run with the old work package, 
                // not just the ones visible using the specified filters, this functionality was disabled in September 2025
                alert('One or more requested runs must be selected');
                return;
            }
            // dmsOps.submitCall is defined in dmsOps.js
            dmsOps.submitCall(url, p);
        }
    },
    service_center_use_admin: {
        changeWPN: function(oldWpn, newWpn) {
            // POST to service_center_use_admin/call/update_wp_sproc
            // This calls procedure update_service_use_wp
            var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag +  "/call/update_wp_sproc";
            var p = {};
            p.oldWorkPackage = oldWpn;
            p.newWorkPackage = newWpn;
            p.entryIdList = dmsChooser.getSelectedItemList().join();
            if (!p.entryIdList) {
                alert('One or more service use entries must be selected');
                return;
            }
            // dmsOps.submitCall is defined in dmsOps.js
            dmsOps.submitCall(url, p);
        },
        updateComment: function(textToFind, replacementText) {
            // POST to service_center_use_admin/call/update_comment_sproc
            // This calls procedure update_service_use_comment
            var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag +  "/call/update_comment_sproc";
            var p = {};
            p.textTofind = textToFind;
            p.replacementText = replacementText;
            p.entryIdList = dmsChooser.getSelectedItemList().join();
            if (!p.entryIdList) {
                alert('One or more service use entries must be selected');
                return;
            }
            // dmsOps.submitCall is defined in dmsOps.js
            dmsOps.submitCall(url, p);
        },
    }
}
