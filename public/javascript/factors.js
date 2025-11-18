// These functions are used by run_blocking.js (requested_run_batch_blocking), tracking.js (instrument_usage_report), and lcmdExtras.js (requested_run_factors)
// and also by several list reports:
//   instrument_usage_report        as defined in app/Views/cmd/instrument_usage_report_cmds.php
//   requested_run_batch_blocking   as defined in app/Views/cmd/requested_run_batch_blocking_cmds.php
//   requested_run_factors          as defined in app/Views/cmd/requested_run_factors_cmds.php

var factorsjs = factorsjs || {
    getBlockingXMLFromList: function(flist) {
        var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'t'}, {p:'value', a:'v'}];
        // dmsInput.getXmlElementsFromObjectArray is defined in dmsInput.js
        return dmsInput.getXmlElementsFromObjectArray(flist, 'r', mapPropertiesToAttributes);
    },
    getFactorXMLFromList: function(flist) {
        var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
        return dmsInput.getXmlElementsFromObjectArray(flist, 'r', mapPropertiesToAttributes);
    },
    getListReportColumnList: function() {
        var col_list = [];
        $('.col_header').each(function(idx, col){
            col_list.push(dmsInput.trim(col.name));
        });
        return col_list;
    },
    getFactorFieldList: function(factor_cols) {
        var idlist = [];
        $('.lr_ckbx').each(function(idx, obj){
            if (obj.checked) {
                idlist.push(obj.value);
            }
        });
        var flist = [];
        $.each(factor_cols, function(idx, col){
            $.each(idlist, function(idx, id){
                var fldID = col.replace(' ', '_') + '_' + id;
                var val = $('#' + fldID).val();
                var obj = {};
                obj.id = id;
                obj.factor = col;
                obj.value = val;
                flist.push(obj);
            });
        });
        return flist;
    },
    makeObjectList: function(ilist, factor, value) { //private
        var flist = [];
        $.each(ilist, function(idx, id){
            var obj = {};
            obj.id = id;
            obj.factor = factor;
            obj.value = value;
            flist.push(obj);
        });
        return flist;
    },
    getFieldListFromParsedData: function(parsed_data, col_list) {
        // make array of id/factor/value objects,
        // one for each row of each column
        var flist = [];
        $.each(col_list, function(idx, factor){
            var idx = parsed_data.header.indexOf(factor);
            if (idx > -1) {
                $.each(parsed_data.data, function(ignore, row){
                    var id = row[0];
                    var value = row[idx] || '';
                    var obj = {};
                    obj.id = id;
                    obj.factor = factor;
                    obj.value = value;
                    flist.push(obj);
                });
            }
        });
        return flist;
    },
    applyFactorToDatabase: function(update) {
        var factor = $('#apply_factor_name').val();
        var value = $('#apply_factor_value').val();
        // dmsChooser.getSelectedItemList is defined in dms.js
        var ilist = dmsChooser.getSelectedItemList();
        var flist = this.makeObjectList(ilist, factor, value);
        if (flist.length == 0) {
            alert('No items selected on which to apply this action');
            return;
        }
        update(flist);
    },
    removeFactorFromDatabase: function(update){
        var factor = $('#remove_factor_name').val();
        var value = '';
        var ilist = dmsChooser.getSelectedItemList();
        var flist = this.makeObjectList(ilist, factor, value);
        if (flist.length == 0) {
            alert('No items selected on which to apply this action');
            return;
        }
        update(flist);
    }
};
