//------------------------------------------
// Chooser and list report selection objects and functions
//------------------------------------------

var dmsChooser = {

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
    },

    //------------------------------------------
    //These functions are used by list reports
    //------------------------------------------

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
    //These functions are used by the entry page
    //------------------------------------------

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
    // supplemental parameter entry forms
    //------------------------------------------

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
    }

};  // dmsChooser
