//------------------------------------------
// Functions used by list and param reports, for displaying tables of data and data paging
//------------------------------------------

var tableRep = {

    /**
     * Document export: repurpose entry form
     * to old fashioned submit instead of AJAX
     * @param {string} url
     * @param {type} form
     * @returns {undefined}
     */
    export_to_doc: function(url, form) {
        var frm = $('#' + form)[0];
        var oldUrl = frm.action;
        frm.action = url;
        frm.submit();
        frm.action = oldUrl;
    },
    /**
     * Set the sort direction of a column
     * @param {type} colName
     * @param {type} noUpdate
     * @returns {undefined}
     */
    setColSort: function(colName, noUpdate) {
        var curCol = $('#qf_sort_col_0').val();
        var curDir = $('#qf_sort_dir_0').val();
        $(".sorting_filter_input").each(function(idx, obj) {obj.value = ''} );
        var dir = 'ASC';
        if(colName == curCol) {dir = (curDir == 'ASC') ? 'DESC' : 'ASC'; };
        $('#qf_sort_col_0').val(colName);
        $('#qf_sort_dir_0').val(dir);
        if(!noUpdate) {
            dmsOps.reloadListReportData('autoload');
        }
    },
    //------------------------------------------
    // paging
    //------------------------------------------

    /**
     * Set the current starting row for the list report
     * @param {type} row
     * @returns {undefined}
     */
    setListReportCurRow: function(row) {
        $('#qf_first_row').val(row);
        dmsOps.reloadListReportData();
    },
    /**
     * Set the number of items to show on each page
     * @param {type} curPageSize
     * @param {type} totalRows
     * @param {type} max
     * @returns {undefined}
     */
    setPageSize: function(curPageSize, totalRows, max) {
        var reply = tableRep.getPageSizeFromUser(curPageSize, totalRows, max);
        if(reply == null) return;
        tableRep.setPageSizeParameter(reply);
    },
    /**
     * Prompt the user for hte number of items to show on each page
     * @param {type} curPageSize
     * @param {type} totalRows
     * @param {type} max
     * @returns {tableRep.getPageSizeFromUser.reply}
     */
    getPageSizeFromUser: function(curPageSize, totalRows, max) {
        var reply = null;
        if (curPageSize == 'all') {
            return (totalRows > max) ? max : totalRows;
        }
        var reply = prompt("Please enter a value for number \n of rows to display on each page \n (1 to " + max + ")", curPageSize);
        if (reply == null || reply == "") {
            return null;
        }
        if (reply == 'all') {
            return (totalRows > max) ? max : totalRows;
        }
        if(isNaN(reply)) {
            alert("Sorry, '" + reply + "' is not a number");
            return null;
        }
        if (reply > totalRows) {
            reply = totalRows;
        }
        return (reply > max) ? max : reply;
    },
    /**
     * Validate and store the user-specified page size
     * @param {type} newPageSize
     * @returns {undefined}
     */
    setPageSizeParameter: function(newPageSize) {
        if(isNaN(newPageSize)) {
            alert("Sorry, '" + newPageSize + "' is not a number");
        } else {
            var n = Number(newPageSize);
            $('#qf_rows_per_page').val(newPageSize);
            $('#qf_first_row').val(1);
            dmsOps.reloadListReportData();
        }
    },
    /**
     * Export data
     * @param {type} format Export format: 'excel' or 'tsv'
     * @returns {undefined}
     */
    download_to_doc: function(format) {
        var row_count = $('#total_rowcount').html();
        if(row_count > 4000) {
            if (!confirm('Are you sure you want to export ' + row_count + ' rows?') ) return;
        }
        var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag + '/export/' + format
        tableRep.export_to_doc(url, "filter_form");
    }

}; // tableRep
