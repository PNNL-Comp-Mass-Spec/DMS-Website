//------------------------------------------
//These functions are used by detail report page
//------------------------------------------
var detRep = {
    /**
     * Perform detail report command (via AJAX)
     *
     * This function is reached when the user clicks a button on the detail report
     * Button definitions are in table detail_report_commands in the model config DB
     * Function make_detail_report_commands in detail_report_helper.php creates the hyperlink via the cmd_op option
     * For example:
     *   javascript:detRep.performCommand("http://dms2.pnl.gov/dataset/command", "QC_Shew_15_02_2_29Oct15_Lynx_15-08-27", "reset", "Are you sure that you want to reset this dataset to New?")
     *
     * The performCommand function in turn will post to http://dms2.pnl.gov/dataset/command/QC_Shew_15_02_2_29Oct15_Lynx_15-08-27/reset
     * That URL is processed by the base controller for the given page family, specifically function command in DmsBase.php
     * The command function calls function internal_operation in Operation.php
     * The internal_operation function looks up the name of the stored procedure specified by operations_sproc in the general_params table of the model config db
     * It then calls the stored procedure, passing on the given command to the @mode parameter
     * In the above example, DoDatasetOperation is called with @mode='reset'
     *
     * @param {type} url URL to post to
     * @param {type} id ID of the entity to update (e.g. dataset ID)
     * @param {type} mode Mode to send to the operations_sproc stored procedure
     * @param {type} promptMsg Message to show the user to ask them to confirm the operation
     * @returns {undefined}
     */
    performCommand: function(url, id, mode, promptMsg) {
        if (!promptMsg) {
            promptMsg = "Are you sure that you want to update the database?";
        }
        if( !confirm(promptMsg) ) return;

        // p.id corresponds to the field name defined in the sproc_args table for a page family
        // For example, https://dmsdev.pnl.gov/config_db/edit_table/requested_run_batch.db/sproc_args
        // Since "id" is lowercase in "p.id", the field name must also be lowercase
        var p = {};
        p.id = id;
        p.command = mode;

        var container = $('#' + dmsjs.pageContext.responseContainerId);
        container.spin('small');

        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
                detRep.updateMyData();
            }
        );
    },
    /**
     * Update the container
     * @param {type} url
     * @param {type} containerId
     * @returns {undefined}
     */
    updateContainer: function(url, containerId) {
        var container = $('#' + containerId);
        url = dmsjs.pageContext.site_url + url;
        var p = {};
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
            }
        );
    },
    /**
     * Use a page like http://dms2.pnl.gov/analysis_job/show_data/1386092
     * to populate the data_container div defined in detail_report.php
     * @returns {undefined}
     */
    updateMyData: function() {
        detRep.updateContainer(dmsjs.pageContext.my_tag + '/show_data/' + dmsjs.pageContext.Id, 'data_container');
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
    /**
     * Show the SQL behind the given page of data
     * Example data retrieved: http://dms2.pnl.gov/analysis_job/detail_sql/1386092
     * Note that string 'SQL' is used in dmsjs.updateMessageBox to trigger adding line breaks
     * @returns {undefined}
     */
    updateShowSQL: function () {
        // POST a request to a report_info/sql page
        // For example http://dms2.pnl.gov/dataset_qc/report_info/sql
        dmsjs.updateMessageBox(dmsjs.pageContext.my_tag + '/detail_sql/' + dmsjs.pageContext.Id, 'OFS', 'SQL');
    },
    /**
     * Show the URL of the currently visible page
     * @returns {undefined}
     */
    updateShowURL: function() {
        // POST a request to a report_info/url page
        // For example http://dms2.pnl.gov/dataset_qc/report_info/url
        var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag + '/show/' + dmsjs.pageContext.Id;
        dmsjs.updateMessageBoxText(url, 'URL');
    }

};  //detRep
