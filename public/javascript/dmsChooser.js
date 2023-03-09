//------------------------------------------
// global and general-purpose functions and objects
//------------------------------------------

var gamma = {

    /**
     * object that chooser code uses to remember
     * key parameters for off-page chooser
     */
    currentChooser: {
        // callBack
        // delimiter
        // field
        // page
        // window
    },
    autocompleteChooser: {
        // wire up all designated inputs with JQUI autocomplete actions
        // defined by 'data-x' attributes:
        // <input class='dms_autocomplete_chsr' data-query='' >
        setup: function() {
            var context = this;
            $('.dms_autocomplete_chsr').each(function() {
                filterInputFld = $(this);
                var autocompleteQuery = $(this).data('query');
                var append = $(this).data('append');
                if(!autocompleteQuery) return;
                filterInputFld.autocomplete(context.getOptions(autocompleteQuery, append));
            });
        },
        // return JQUI autocomplete options object with source option set to AJAX callback
        getOptions: function(queryName, append) {
            return {
                minLength: 1,
                select: (function(append){
                    var appendVal = append;
                    return function( event, ui ) {
                        if(appendVal) {
                            var curVal = event.target.value;
                            var x = curVal.lastIndexOf(',');
                            curVal = (x != -1) ? curVal = curVal.substring(0, x) + ', ': curVal = '';
                            event.target.value = curVal + ui.item.value;
                            event.preventDefault()
                        }
                    }
                })(append),
                focus: (function(append){
                    var appendVal = append;
                    return function( event, ui ) {
                        if(appendVal) {
                            event.preventDefault()
                        }
                    }
                })(append),
                // use self-invoking anonymous function to set source option to AJAX callback
                // that is bound to given input parameters and will call server data controller with them
                source: (function(queryName, append){
                    var appendVal = append;
                    var url = gamma.pageContext.site_url + 'chooser/json/' + queryName;
                    return function( request, response ) {
                                var f = (appendVal) ? request.term.split(/,\s*/).pop() : request.term ;
                                if(f && (f.length > 1 || f == '*')) {
                                    var p = { filter_values:f };
                                    gamma.getObjectFromJSON(url, p, null, function(obj) {
                                        response( obj );
                                    })
                                } else {
                                    response('');
                                }
                            }
                })(queryName, append)
            }
        }
    }
};  // gamma

//------------------------------------------
//These functions are used by list reports
//------------------------------------------
var lambda = {
    /**
     * This function acts as a hook that other functions call to
     * reload the row data container for the list report.
     * it needs to be overridden with the actual loading
     * function defined on the page, which will be set up
     * with page-specific features
     * @returns {undefined}
     */
    reloadListReportData: function() {
        alert('"lambda.reloadListReportData" not overridden');
    },
    /**
     * For clearing cached page parameters
     * @param {type} pageType
     * @returns {undefined}
     */
    setListReportDefaults: function(pageType) {
        var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/defaults/' + pageType;
        p = {};
        $.post(url, p, function (data) {
                alert(data);
            }
        );
    },
    /**
     * Go get some content from the server using given form and action
     * and put it into the designated container element
     * and initiate the designated follow-on action, if such exists
     * @param {string} action Action (mode)
     * @param {type} formId
     * @param {type} containerId
     * @param {object} follow_on_action
     * @returns {undefined}
     */
    updateContainer: function (action, formId, containerId, follow_on_action) {
        var container = $('#' + containerId);
        container.spin('small');
        var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/' + action;
        var p = $('#' + formId).serialize();
        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
                if(follow_on_action && follow_on_action.run) {
                    follow_on_action.run();
                }
            }
        );
    },
    /**
     * Submit list report supplemental command
     * @param {string} url
     * @param {object} p Object to post
     * @param {boolean} show_resp If true, show the response from the post
     * @returns {undefined}
     */
    submitOperation: function(url, p, show_resp) {
        var ctl = $('#' + gamma.pageContext.cntrlContainerId);
        var container = $('#' + gamma.pageContext.responseContainerId);
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                if(data.indexOf('Update failed') > -1) {
                    container.html(data);
                    ctl.show();
                } else {
                    var msg = 'Operation was successful';
                    if(show_resp) {
                        msg = data;

                        // data should be a JSON-encoded string, for example:
                        //   '{"result":0,"message":"Operation was successful: Deleted 1 analysis job"}'

                        // Look for a message parameter, which many DMS stored procedures have as an output parameter
                        var dataObject = jQuery.parseJSON(data);
                        if (dataObject.message)
                            msg = dataObject.message;
                    }
                    container.html(msg);
                    ctl.hide();
                    lambda.reloadListReportData();
                }
            }
        );
    },
    /**
     * Submit list report supplemental command using "call" semantics
     * @param {string} url
     * @param {object} p Object to post
     * @param {boolean} show_resp Show response (unused)
     * @returns {undefined}
     */
    submitCall: function(url, p, show_resp) {
        var ctl = $('#' + gamma.pageContext.cntrlContainerId);
        var container = $('#' + gamma.pageContext.responseContainerId);
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                var obj = $.parseJSON(data);
                container.html(obj.message);
                if(obj.result) {
                    ctl.show();
                } else {
                    ctl.hide();
                    lambda.reloadListReportData();
                }
            }
        );
    },
    /**
     * Loads a SQL comparison selector (via AJAX)
     * @param {type} containerId
     * @param {type} url
     * @param {type} col_sel
     * @returns {undefined}
     */
    loadSqlComparisonSelector: function(containerId, url, col_sel) {
        url += $('#' + col_sel).val();
        gamma.loadContainer(url, {}, containerId);
    },
    /**
     * Clear the specified list report search filter
     * @param {type} filter
     * @returns {undefined}
     */
    clearSearchFilter: function(filter) {
        $( '.' + filter).each(function(idx, obj) {
            obj.value = ''
        });
        lambda.is_filter_active();
    },
    /**
     * Clear the list report search filters
     * @returns {undefined}
     */
    clearSearchFilters: function() {
        $(".filter_input_field").each(function(idx, obj) {
            obj.value = ''
        });
        lambda.is_filter_active();
    },
    /**
     * Toggle filter visibility
     * @param {type} containerId
     * @param {type} duration
     * @param {type} element
     * @returns {undefined}
     */
    toggleFilterVisibility: function(containerId, duration, element) {
        var visible = gamma.toggleVisibility(containerId, duration, element);
        this.adjustFilterVisibilityControl(containerId, visible);
    },
    /**
     * Adjust filter visibility
     * @param {type} containerId
     * @param {type} visible
     * @returns {undefined}
     */
    adjustFilterVisibilityControl: function(containerId, visible) {
        var vCtls = $('.' + containerId);
        vCtls.each(function() {
            gamma.setToggleIcon($(this), visible);
        });
    },
    /**
     * Adjust filter visibility
     * @returns {undefined}
     */
    adjustFilterVisibilityControls: function() {
        $('.filter_container_box').each(function() {
            var id = this.id;
            var visible = $(this).is(':visible');
            lambda.adjustFilterVisibilityControl(id, visible);
        });
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
            lambda.reloadListReportData('autoload');
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
        lambda.reloadListReportData();
    },
    /**
     * Set the number of items to show on each page
     * @param {type} curPageSize
     * @param {type} totalRows
     * @param {type} max
     * @returns {undefined}
     */
    setPageSize: function(curPageSize, totalRows, max) {
        var reply = lambda.getPageSizeFromUser(curPageSize, totalRows, max);
        if(reply == null) return;
        lambda.setPageSizeParameter(reply);
    },
    /**
     * Prompt the user for hte number of items to show on each page
     * @param {type} curPageSize
     * @param {type} totalRows
     * @param {type} max
     * @returns {lambda.getPageSizeFromUser.reply}
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
            lambda.reloadListReportData();
        }
    },
    //------------------------------------------
    // search filter change monitoring
    //------------------------------------------

    /**
     * Define the observers for a filter field
     * @returns {undefined}
     */
    set_filter_field_observers: function() {
        var that = this;
        var pFields = $('#filter_form').find(".primary_filter_field");
        pFields.each(function(idx, f) {
                $(this).on("keypress", that.filter_key);
                $(this).on("keypress", that.is_filter_active);
            });
        var sFields = $(".secondary_filter_input");
        sFields.each(function(idx, f) {
                $(this).on("keypress", that.filter_key);
                $(this).on("keypress", that.is_filter_active);
            });
    },
    /**
     * Updates the filter active indicator
     * @returns {undefined}
     */
    is_filter_active: function() {
        var filterFlag = 0;
        var sortFlag = 0;
        var ff = $('#filter_form');
        ff.find(".primary_filter_field").each(function(idx, obj) {
                if(obj.value != '') filterFlag++;
            } );
        ff.find(".secondary_filter_input").each(function(idx, obj) {
                if(obj.value != '') filterFlag++;
            } );
        ff.find(".sorting_filter_input").each(function(idx, obj) {
                if(obj.value != '') sortFlag++;
            } );
        lambda.set_filter_active_indicator(filterFlag, sortFlag);
    },
    /**
     * Filter key
     * @param {type} e
     * @returns {Boolean}
     */
    filter_key: function(e) {
        var code;
    //  if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        if(code == 13) {
            $('#qf_first_row').val(1);
            lambda.reloadListReportData();
            return false;
        }
       return true;
    },
    /**
     * Set filter active indicator
     * @param {type} activeSearchFilters
     * @param {type} activeSorts
     * @returns {undefined}
     */
    set_filter_active_indicator: function(activeSearchFilters, activeSorts) {
        if(!activeSearchFilters) {
            $('#filters_active').html('');
        } else
        if(activeSearchFilters ==1 ){
            $('#filters_active').html('There is ' + activeSearchFilters +  ' filter set');
        } else {
            $('#filters_active').html('There are ' + activeSearchFilters +  ' filters set');
        }
    },

    //------------------------------------------
    //These functions are used by multiple-choice
    //chooser list report to manage its checkboxes
    //------------------------------------------

    /**
     * Get selected item list
     * @returns {Array|lambda.getSelectedItemList.checkedIDlist}
     */
    getSelectedItemList: function() {
        var checkedIDlist = [];
        $('.lr_ckbx').each(function(idx, obj){
            if(obj.checked) {
                checkedIDlist.push(obj.value);
            }
        });
        return checkedIDlist;
    },
    //set checked state of all checkboxes with given name
    setCkbxState: function(checkBoxName, state) {
        var rows = document.getElementsByName(checkBoxName);
        for (var i = 0; i < rows.length; i++) {
            rows[i].checked  = state;
        }
    },
    /**
     * Make list of values of checked checkboxes with given name
     * @param {type} checkBoxName
     * @returns {String}
     */
    getCkbxList: function(checkBoxName) {
      var list = '';
      var rows = document.getElementsByName(checkBoxName);
      for (var i = 0; i < rows.length; i++) {
        if ( rows[i].checked ) {
          if (list != '') {
            list  += ', ';
          }
          list  += rows[i].value;
        }
      }
      return list;
    },
    /**
     * Transfer selected list data
     * @param {type} perspective
     * @returns {undefined}
     */
    transferSelectedListData: function(perspective) {
        var list = lambda.getCkbxList('ckbx' );
        if(list=='') {
            alert('You must select at least 1 item.');
            return;
        }
        if ( !confirm("Are you sure that you want to transfer the selected data?") )
            return;

        var url = gamma.pageContext.site_url + "/data_transfer/" + perspective;
        var p = {};
        p.perspective = perspective;
        p.iDList = list;
        lambda.submitOperation(url, p);
    },
    //------------------------------------------
    // used by helper list reports with checkboxes
    //------------------------------------------

    /**
     * Set checked state of all checkboxes with given name from given list
     * @param {type} checkBoxName
     * @param {type} selList
     * @returns {undefined}
     */
    setCkbxFromList: function(checkBoxName, selList) {
        var rows = document.getElementsByName(checkBoxName, selList);
        // split list into separate trimmed elements
        var selections = selList.split(/[,;]/);
        for(var k = 0; k < selections.length; k++) {
            selections[k] = gamma.trim(selections[k]);
        }
        // traverse checkbox elements, setting checkbox
        // if it's value matches an element in list
        for (var i = 0; i < rows.length; i++) {
            for(var k = 0; k < selections.length; k++) {
                if(selections[k] === rows[i].value) {
                   rows[i].checked = true;
                   break;
                }
            }
        }
    },
    /**
     * Set checked state of chooser's checkboxes from
     * the current value of the field for which it is choosing
     * @param {type} checkBoxName
     * @returns {undefined}
     */
    intializeChooserCkbx: function(checkBoxName) {
        if(window.opener) {
            var list = window.opener.epsilon.getFieldValueForChooser();
            lambda.setCkbxFromList(checkBoxName, list);
        }
    },

    //------------------------------------------
    // misc
    //------------------------------------------

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
        var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/export/' + format
        gamma.export_to_doc(url, "filter_form");
    },
    /**
     * Create a dynamic form with JSON data to submit to a URL and load the new page
     * @param {type} url
     * @param {type} jsonObj
     * @returns {undefined}
     */
    submitDynamicForm: function(url, jsonObj) {
        var keys = Object.getOwnPropertyNames(jsonObj);
        var inputs = "";
        keys.forEach(function(item){
            inputs += '<input type="hidden" name="' + item + '" value="' + jsonObj[item] + '" />'
        });
        $("body").append('<form action="' + url + '" method="post" id="dynamicForm">' + inputs + '</form>');
        $("#dynamicForm").submit();
    }

}; // lambda

//------------------------------------------
//These functions are used by the entry page
//------------------------------------------
var epsilon = {

    /**
     * Adjust enabled fields
     * Style associated entry field for each enable checkbox
     * @returns {undefined}
     */
    adjustEnabledFields: function() {
        var that = this;
        $('._ckbx_enable').each(
            function(chkbx) {
                var fieldName = this.name.replace('_ckbx_enable', '');
                that.enableDisableField(this, fieldName);
            }
        );
    },
    /**
     * Enable/disable field
     * Style associated entry field for checkbox
     * according to whether it is enabled or disabled
     * @param {type} chkbx
     * @param {type} fieldName
     * @returns {undefined}
     */
    enableDisableField: function(chkbx, fieldName)
    {
        if(chkbx.checked) {
            $('#' + fieldName).css("color", "Black");
        } else {
            $('#' + fieldName).css("color", "Silver");
        }
    },
    /**
     * Show/hide table rows
     * @param {type} block_name
     * @param {type} url
     * @param {type} show_img
     * @param {type} hide_img
     * @returns {undefined}
     */
    showHideTableRows: function(block_name, url, show_img, hide_img) {
        var className = '.' + block_name;
        var img_element_id = block_name + "_cntl";
        var ctl = $('#' + img_element_id).first()[0];
        var cur_src = ctl.src;
        var styleVal = '';
        var index = cur_src.indexOf('hide');
        if(index < 0) {
            $(className).each(function(idx, s){s.style.display=''});
            $('#' + img_element_id)[0].src = url + hide_img;
        } else {
            $(className).each(function(idx, s){s.style.display='none'});
            $('#' + img_element_id)[0].src = url + show_img;
        }
    },
    /**
     * Show table rows
     * @param {type} block_name
     * @param {type} url
     * @param {type} hide_img
     * @returns {undefined}
     */
    showTableRows: function(block_name, url, hide_img) {
        var className = '.' + block_name;
        var img_element_id = block_name + "_cntl";
        $(className).each(function(idx, s){s.style.display=''});
        $('#' + img_element_id)[0].src = url + hide_img;
    },
    /**
     * Hide table rows
     * @param {type} block_name
     * @param {type} url
     * @param {type} show_img
     * @returns {undefined}
     */
    hideTableRows: function(block_name, url, show_img) {
        var className = '.' + block_name;
        var img_element_id = block_name + "_cntl";
        $(className).each(function(idx, s){s.style.display='none'});
        $('#' + img_element_id)[0].src = url + show_img;
    },
    /**
     * Show a section
     * @param {type} block_name
     * @returns {undefined}
     */
    showSection: function (block_name) {
        var url = gamma.pageContext.base_url + 'images/';
        var hide_img = 'z_hide_col.gif';
        epsilon.showTableRows(block_name, url, hide_img);
    },
    /**
     * Hide a section
     * @param {type} block_name
     * @returns {undefined}
     */
    hideSection: function (block_name) {
        var url = gamma.pageContext.base_url + 'images/';
        var show_img = 'z_show_col.gif';
        epsilon.hideTableRows(block_name, url, show_img);
    },
    /**
     * Show/hide sections
     * @param {type} action
     * @param {type} list
     * @returns {undefined}
     */
    showHideSections: function(action, list) {
        var blks = [];
        if(!list || list === 'all') {
            $('.section_block_header_all').each(function() {
                var hb = this.id;
                var sb = hb.replace('section_block_header_', '');
                blks.push(sb);
            });
        } else {
            blks = list.split(',');
        }
        $.each(blks, function(idx, blk) {
            sb = 'section_block_' + gamma.trim(blk);
            if(action === 'show') {
                epsilon.showSection(sb);
            }
            if(action === 'hide') {
                epsilon.hideSection(sb);
            }
        });
    },
    //------------------------------------------
    // These functions are used by any entry page that invokes a
    // list report chooser
    // Note: a global variable "gamma.currentChooser" that references
    // an empty object must be defined by the entry page
    // that usese these functions
    //------------------------------------------

    /**
     * Close the chooser window page
     * @returns {undefined}
     */
    closeChooserWindowPage: function() {
        if (gamma.currentChooser.window && !gamma.currentChooser.window.closed) {
            gamma.currentChooser.window.close();
        }
    },
    /**
     * Call a chooser
     *
     * This function opens an exernal chooser page and remembers
     * information necessary to update the proper entry field
     * when that page calls back with user's choice
     * @param {type} fieldName Field Name
     * @param {type} chooserPage Chooser URL
     * @param {type} delimiter Delimeter when selecting multiple values
     * @param {type} xref Field name whose contents should be sent to the helper page
     * @returns {undefined}
     */
    callChooser: function(fieldName, chooserPage, delimiter, xref) {
        // resolve cross-reference to other field, if one exists
        var xrefValue = '';
        if (xref != '') {
            // Split xref to see if there is '|required', meaning that a popup should be shown saying the xref value must be populated first
            var xrefSplit = xref.split('|');
            var xrefName = xrefSplit[0];
            var valueRequired = (xrefSplit.length > 1) ? xrefSplit[1] === 'required' : false;
            xrefValue = (xref != '') ? $('#' + xrefName).val() : '';
            if(xrefName != '' && valueRequired && (xrefValue == '' || xrefValue === '(lookup)')){
                // Previously showed an error if the cross referenced field was empty
                // We now allow this for cases where a field starts off as blank but the user needs to choose a value from a list
                alert (xrefName + ' must be selected first.');
                return;
            }
        }
        // check if chooserPage URL needs separator
        var sep = '/';
        if( chooserPage.match(/\/$/) || chooserPage.match(/~$/) ) {
            sep = '';
        }
        // if there is a cross-reference, pass it on end of URL
        if(xrefValue != '') {
            // If xrefValue contains the delimiter, remove the delimiter and any text following it
            var delimPos = xrefValue.indexOf(delimiter);
            if (delimPos > 0) {
                xrefValue = xrefValue.slice(0, delimPos);
            }
            if (xrefValue === '(lookup)') {
                xrefValue = ' ';
            }
            chooserPage += sep + xrefValue;
        }
        // make sure that there are no other chooser pages open
        epsilon.closeChooserWindowPage();
        // remember which field gets the update
        // for when the chooser page calls back (updateFieldValueFromChooser)
        gamma.currentChooser.field = fieldName;
        gamma.currentChooser.delimiter = delimiter;
        gamma.currentChooser.page = chooserPage;
        // open the chooser page in another window
        gamma.currentChooser.window = window.open(chooserPage, "HW", "scrollbars,resizable,height=550,width=1000,menubar");
    },
    /**
     * Update field value from chooser
     *
     * This function is called by an external chooser
     * page to update the value in the field that it is serving
     * @param {type} value
     * @param {type} action
     * @returns {undefined}
     */
    updateFieldValueFromChooser: function(value, action) {
        // todo: make sure gamma.currentChooser.field is defined
        fld = $('#' + gamma.currentChooser.field)[0];
        // lists are always transmitted as comma-delimited
        // and field may need a different delimiter
        if(gamma.currentChooser.delimiter != ',') {
            value = value.replace(/,/g, gamma.currentChooser.delimiter);
        }
        // replace or append new value, as appropriate
        if(action == "append") {
            if (gamma.currentChooser.delimiter != "" && fld.value != "") {
                fld.value += gamma.currentChooser.delimiter + " " + value;
            } else {
                fld.value += value;
            }
        } else { // replace
            fld.value = value;
        }
        // we are done with chooser page - make it go away
        epsilon.closeChooserWindowPage();
        if(gamma.currentChooser.callBack) {
            gamma.currentChooser.callBack();
        }
    },
    /**
     * Get field value for chooser
     * @returns {epsilon.getFieldValueForChooser.value|jQuery}
     */
    getFieldValueForChooser: function() {
        // todo: make sure gamma.currentChooser.field is defined
        var value = $('#' + gamma.currentChooser.field).val();
        if(gamma.currentChooser.delimiter != ',') {
            value = value.replace(/gamma.currentChooser.delimiter/g, ',');
        }
        return value;
    },
    /**
     * Show the date picker
     * @param {type} fieldName
     * @returns {undefined}
     */
    callDatepicker: function(fieldName) {
        var fld = $('#' + fieldName);
        if(!fld.data().datepicker) {
                    fld.datepicker();
        }
        fld.trigger("focus");
    },
    //------------------------------------------
    // used for entry page submission
    //------------------------------------------

    /**
     * Object to contain entry page context values
     * (must be initialized prior to library functions being called)
     * @type type
     */
    pageContext: {
        containerId: null,
        modeFieldId: null,
        entryFormId: null
    },
    /**
     * Contains any actions to be performed prior to and after AJAX submission
     * @type type
     */
    actions: {
        before: null,
        after:null
    },
    /**
     * Called by the built-in entry page family submission controls
     * submit the entry form to the entry page or alternate submission logic
     * @param {type} url
     * @param {type} mode
     * @returns {undefined}
     * @remarks Example usage is 'epsilon.actions.before = entryCmds.sample_prep_request.approveSubmit;' in entry.js
     */
    submitStandardEntryPage: function(url, mode) {
        epsilon.submitEntryFormToPage(url, mode, this.actions.after, this.actions.before);
    },
    /**
     * POST the entry form to the entry page via AJAX
     *
     * @param {type} url
     * @param {type} mode
     * @param {type} afterAction Action (if defined) to be performed prior to submission; abort if it returns true
     * @param {type} beforeAction Action (if defined) to be performed after receiving results of submission
     * @returns {undefined}
     */
    submitEntryFormToPage: function(url, mode, afterAction, beforeAction) {
        var container = $('#' + this.pageContext.containerId);
        var modeField = $('#' + this.pageContext.modeFieldId);
        var entryForm = $('#' + this.pageContext.entryFormId);
        modeField.val(mode);
        var proceed = true;
        if(beforeAction) {
            proceed = beforeAction(mode);
        }
        if(!proceed) return;
        p = entryForm.serialize();
        if(!confirm("Are you sure that you want to perform this action?")) return;
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
                setTimeout("epsilon.adjustEnabledFields()", 350);
                if(afterAction) {
                    afterAction();
                }
            }
        );
    },
    /**
     * POST the entry form to another page
     * @param {type} url
     * @param {type} mode
     * @returns {undefined}
     */
    submitEntryFormToOtherPage: function(url, mode) {
        var modeField = $('#' + epsilon.pageContext.modeFieldId);
        var entryForm = $('#' + epsilon.pageContext.entryFormId);
        modeField.val(mode);
        entryForm.action = url;
        entryForm.method="post";
        entryForm.submit();
    },

    //------------------------------------------
    // supplemental parameter entry forms
    //------------------------------------------

    /**
     * Get supplemental form fields via an AJAX call
     * @param {type} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    load_supplemental_form: function(url, p, containerId, afterAction, beforeAction) {
        var container = $('#' + containerId);
        var abort = false;
        if(beforeAction) {
            abort = beforeAction();
        }
        if(abort) return;
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
                if(afterAction) {
                    afterAction();
                }
            }
        );
    },
    /**
     * Loop through all the fields in the given parameter form
     * and build properly formatted XML and replace the
     * contents of the given field with it
     * @param {type} formId
     * @param {type} fieldId
     * @param {type} hasSection
     * @returns {undefined}
     */
    copy_param_form_to_xml_param_field :function(formId, fieldId, hasSection) {
        var xml = '';
        var targetForm = $('#' + formId);
        var targetField = $('#' + fieldId)
        var fields = targetForm.serializeArray();
        $.each(fields, function(idx, field) {
            if(field.name.indexOf('_chooser') === -1) {
                var section = '';
                var name = field.name;
                var value = field.value;
                if(hasSection) {
                    var nm = field.name.split('.');
                    section = nm[0];
                    name = nm[1];
                }
                var s = '<Param ';
                s += (section) ? 'Section="' + section + '" ' : '';
                s += 'Name="' + name + '" ';
                s += 'Value="' + value + '" ';
                s += '/>';
                xml += s;
            }
        });
        targetField.val(xml);
    },
    /**
     * Set field value from selection
     *
     * Called by a drop-down selection type chooser to update its target field
     * @param {type} fieldName
     * @param {type} chooserName
     * @param {type} mode
     * @returns {undefined}
     */
    setFieldValueFromSelection: function(fieldName, chooserName, mode) {
        var fld = $('#' + fieldName);
        var chooserVal = $('#' + chooserName).val();
        if(fld.val() != null) {
            if(mode == 'replace' || mode == '') {
                fld.val(chooserVal);
                return;
            }

            var delim = ';';

            if (mode == 'prepend_comma' || mode == 'append_comma')
                delim = ', ';
            else if (mode == 'prepend_underscore')
                delim = '_';

            var v = fld.val();

            if (mode == 'prepend' || mode == 'prepend_underscore' || mode == 'prepend_comma') {
                if(v != '')
                    fld.val(chooserVal + delim + v);
                else
                    fld.val(chooserVal);

                return;
            }

            if(v != '')
                v = v + delim;

            fld.val(v + chooserVal);
        }
    },
    /**
     * Set field value
     * @param {type} fieldName
     * @param {type} value
     * @returns {undefined}
     */
    setFieldValue: function(fieldName, value) {
        if($('#' + fieldName)) {
                $('#' + fieldName).val(value);
        }
    },
    /**
     * Set field template value
     * @param {type} fieldName
     * @param {type} value
     * @returns {undefined}
     */
    setFieldTemplateValue: function(fieldName, value) {
        if($('#' + fieldName)) {
            $('#' + fieldName).val(value.replace(/\|/g, '\n'));
        }
    },
    //------------------------------------------
    // entry field formatting
    //------------------------------------------

    /**
     * Convert a list of values spearated by newlines and/or tabs to a list separated by repStr
     * @param {type} fieldName
     * @param {string} repStr List separator
     * @returns {undefined}
     */
    convertList: function(fieldName, repStr) {
        var fld = $('#' + fieldName);
        var findStr = "(\r\n|[\r\n]|\t)";
        var re = new RegExp(new RegExp(findStr, "g"));
        repStr += ' ';
        fld.val(fld.val().replace(re, repStr));
    },
    /**
     * Format XML in a data field
     * @param {type} fieldName
     * @returns {undefined}
     */
    formatXMLText: function(fieldName) {
        var fld = $('#' + fieldName);
        var findStr = "><";
        var repStr = ">\n<";
        var re = new RegExp(new RegExp(findStr, "g"));
        fld.val(fld.val().replace(re, repStr));
    }

};  // epsilon
