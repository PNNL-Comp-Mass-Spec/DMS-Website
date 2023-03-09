//------------------------------------------
// global and general-purpose functions and objects
//------------------------------------------

var gamma = {

    dlgPopupInfo: '',

    /**
     * Event handlers for global search panel
     * @param {type} panel
     * @returns {undefined}
     */
    setSearchEventHandlers: function(panel) {
        var sel = panel.find('select');
        var val = panel.find('input');
        var go = panel.find('a');

        val.on("keypress", function(e) {
            if(e.keyCode === 13) {
                gamma.dms_search(sel.val(), val.val());
                return false;
            }
           return true;
        });
        sel.on("change", function(e) {
            gamma.dms_search(sel.val(), val.val());
        });
        go.on("click", function(e) {
            gamma.dms_search(sel.val(), val.val());
        });
    },

    //------------------------------------------
    // context values for current page
    //
    // many library functions reference this object
    // and depend on proper values being defined
    // by specific family page before they are called
    // - Id
    // - base_url
    // - cntrlContainerId
    // - data_url
    // - entityList
    // - entity_type
    // - file_name
    // - hierarchy
    // - is_ms_helper
    // - my_tag
    // - ops_url
    // - processing_params
    // - responseContainerId
    // - site_url
    // - update_in_progress
    // - update_url
    //------------------------------------------
    pageContext: {
    },

    //------------------------------------------
    // parsing stuff
    //------------------------------------------

    /**
     * Return a copy of a string with leading and trailing whitespace removed.
     * @param {string} str String to process
     * @returns {undefined}
     */
    trim: function(str) {
        return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    },
    /**
     * Parse tab-delimited line into array of trimmed values
     * @param {string} line Text to process
     * @returns {array} Array of trimmed values
     */
    parse_lines: function(line) {
        flds = [];
        var fields = line.split('\t');
        $.each(fields, function(idx, fld){
            flds.push(gamma.trim(fld));
        });
        return flds;
    },
    /**
     * Parse multiple rows of tab-delimited text
     * Values in the first row are treated as a header
     * @param {string} text_fld Text to process
     * @param {boolean} removeArtifact If true, remove a parsing artifact by removing the last row of the returned array
     * @returns {object} Object with two arrays: header and data
     */
    parseDelimitedText: function(text_fld, removeArtifact) {
        parsed_data = {};
        var lines = $('#' + text_fld).val().split('\n');
        var header = [];
        var data = [];
        $.each(lines, function(lineNumber, line){
            line = gamma.trim(line);
            if(line) {
                var fields = gamma.parse_lines(line);
                if(lineNumber === 0) {
                    header = fields;
                } else {
                    data.push(fields); // check length of fields?
                }
            }
        });
        // get rid of goofy parsing artifact last row
        if(removeArtifact && (data[data.length - 1]).length < header.length) {
            data.pop();
        }
        parsed_data.header = header;
        parsed_data.data = data;
        return parsed_data;
    },
    /**
     * Get the suggested width, in pixels, for a dialog box
     * based on the number of characters in a string
     * @param {integer} textLength Length of text to be shown in the dialog box
     * @returns {integer} Suggested width (in pixels) for the dialog box
     */
    getDialogWidth: function(textLength) {
        var width = Math.round(textLength * 8.8);

        if (width < 250)
            return 250;

        if (width > 1000)
            return 1000;

        return width;
    },
    /**
     * Parse an array of objects using the mapping array to generate a list of XML elements
     * Example mapping: [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
     * @param {object} objArray Object array of pending changes, for example from function getChanges in data_grid.js
     * @param {string} elementName
     * @param {object} mapping Mapping from items in the object array to xml element names
     * @returns {string} XML as a string
     */
    getXmlElementsFromObjectArray: function(objArray, elementName, mapping) {
        var xml = '';
        if (typeof(objArray) != "undefined") {
            $.each(objArray, function(x, obj){
                xml += '<' + elementName;
                $.each(mapping, function(z, map) {
                    if(typeof obj[map.p] != 'undefined'){
                        xml += ' ' + map.a + '="' + obj[map.p] + '"';
                    }
                });
                xml += ' />';
            });
        }
        return xml;
    },
     /**
     * Convert an array of strings into a list of XML elements using the given element name
     * The XML will have one element for each item in the array, assigning the item in the array as the attribute
     * @param {array} itemArray Array of item names
     * @param {string} elementName
     * @param {string} attributeName
     * @returns {string} XML as a string
     */
    getXmlElementsFromArray: function(itemArray, elementName, attributeName) {
        var xml = '';
        $.each(itemArray, function(x, item){
            xml += '<' + elementName;
            xml += ' ' + attributeName + '="' + item + '"';
            xml += ' />';
        });
        return xml;
    },
    /**
     * Return a new array consisting of items in target that are not in remove
     *
     * @param {array} target Items to process
     * @param {array} remove Items to remove
     * @returns {array} Array of filtered items from target
     */
    removeItems: function(target, remove) {
        var output = [];
        // change all of the items in 'remove' to lowercase in a new array
        var removeLower = remove.map(r => r.toLowerCase());
        $.each(target, function(idx, item){
            // check 'removeLower' for the item (in lowercase)
            if(removeLower.indexOf(item.toLowerCase()) === -1) {
                output.push(item);
            }
        });
        return output;
    },
    /* Display results returned by AJAX query of server
     * in floating modeless dialog (created dynamically)
     * (if ignoreIfClosed is false or undefined, always open
     *  or update dialog, otherwise, only update if already open)
     *
     * Displayed when the user clicks Help->SQL (on a list report or detail report) to view the SQL query used to obtain the data
     * or when they choose Help->URL on a list report to view the URL for deep linking on list reports
     */
    updateMessageBox: function() {
        var dlg;
        return function(url, form, title, ignoreIfClosed) {
            var isClosed = (dlg) ? !dlg.dialog('isOpen') : true;
            if(dlg && !url) {
                dlg.dialog( "close" );
                return;
            }
            if(ignoreIfClosed && isClosed) return;

            if(!dlg) {
                // Make a new dialog box
                dlg = $('<div></div>').dialog({title: title, autoOpen: false, closeOnEscape: true});
            } else {
                // Update the title of an existing dialog box
                dlg.dialog({ title: title });
            }

            // Send a post request to a report_info page, for example:
            // http://dms2.pnl.gov/dataset_qc/report_info/url
            //  or
            // http://dms2.pnl.gov/dataset_qc/report_info/sql

            url = gamma.pageContext.site_url + url;
            var p = $('#' + form).serialize();

            $.post(url, p, function(data) {
                    var maxTextLength = 0;

                    var dataForClipboard = '??';
                    var htmlForClipboard = '??';
                    var buttonName = 'copy-now';

                    // Check for the title being 'SQL'
                    if (title.match(/SQL/i)) {
                        var buttonName = 'copy-sql-now';

                        // Insert some line breaks
                        // Try to match SELECT * FROM table
                        //           or SELECT * FROM table WHERE x=y
                        var selectFromRegEx      = /(SELECT.+)\s+(FROM\s.+)/i;
                        var selectFromWhereRegEx = /(SELECT.+)\s+(FROM\s.+)\s+WHERE\s+(.+)/i;
                        var whereClauseRegEx     = /\s+(AND|OR)\s+/gi;
                        var orderByRegEx         = /\s+(ORDER BY\s.+)/i;

                        var match = selectFromWhereRegEx.exec(data);
                        var unformattedSql = data.replace(/\n/g, ' ').trim();
                        var formattedSql = '?';

                        if (match) {

                            formattedSql = '';
                            formattedSql += match[1] + '<br>';                                               // SELECT ...
                            formattedSql += match[2] + '<br>';                                               // FROM ...

                            // Add a line break after AND or OR
                            formattedSql += 'WHERE ' + match[3].replace(whereClauseRegEx, ' $1<br>      ');  // WHERE ...

                            // Add a line break before ORDER BY
                            formattedSql = formattedSql.replace(orderByRegEx, '<br>$1');

                        } else {
                            // SQL does not have a WHERE clause
                            var match = selectFromRegEx.exec(data);
                            if (match) {

                                formattedSql = '';
                                formattedSql += match[1] + '<br>';   // SELECT ...
                                formattedSql += match[2] + '<br>';   // FROM ...

                                // Add a line break before ORDER BY
                                formattedSql = formattedSql.replace(orderByRegEx, '<br>$1');

                            } else {
                                // No RegEx match
                                maxTextLength = data.length;
                                formattedSql = data.replace(/\n/g, ' ').trim();
                            }
                        }

                        if (maxTextLength == 0) {
                            var dataRows = formattedSql.split('<br>');
                            for(var k = 0; k < dataRows.length; k++) {
                                maxTextLength = Math.max(maxTextLength, dataRows[k].length);
                            }
                        }

                        data = '<pre>' + formattedSql + '</pre>';

                        // Text in dataForClipboard is plain text and will appear when pasting into a text editor or SQL Server Management Studio
                        // Text in htmlForClipboard includes HTML symbols, and will appear when pasted into Microsoft Word
                        // Replace double quotes with &quot; to avoid javascript exceptions
                        dataForClipboard = unformattedSql.replace(/"/g, '&quot;');
                        htmlForClipboard = data.replace(/"/g, '&quot;');

                    } else {
                        var buttonName = 'copy-url-now';

                        data = data.replace(/\n/g, ' ').trim();

                        maxTextLength = data.length;

                        dataForClipboard = data;
                        htmlForClipboard = data;

                        data += '<br>';
                    }

                    var width = gamma.getDialogWidth(maxTextLength);
                    dlg.dialog({ width: width });

                    data = gamma.addCopyDataButton(data, buttonName, dataForClipboard, htmlForClipboard);

                    dlg.html(data);
                    dlg.dialog('open');

                    gamma.dlgPopupInfo = dlg;
                }
            );
        };
    }(),
    /*
     * Display text (data) in a floating modeless dialog (created dynamically)
     *
     * Displayed when the user clicks Help->URL on a detail report to view the URL for deep linking
     */
    updateMessageBoxText: function() {
        var dlg;
        return function(data, title) {

            if(!dlg) {
                // Make a new dialog box
                dlg = $('<div></div>').dialog({title: title, autoOpen: false, closeOnEscape: true});
            } else {
                // Update the title of an existing dialog box
                dlg.dialog({ title: title });
            }

            var width = gamma.getDialogWidth(data.length);
            dlg.dialog({ width: width });

            var dataForClipboard = data;
            var htmlForClipboard = data;
            var buttonName = "copy-data-now";

            data += "<br>";

            data = gamma.addCopyDataButton(data, buttonName, dataForClipboard, htmlForClipboard);

            dlg.html(data);
            dlg.dialog('open');

            gamma.dlgPopupInfo = dlg;
        };
    }(),
    /*
     * Add a button and the required javascript for copying text
     */
    addCopyDataButton: function(data, buttonName, dataForClipboard, htmlForClipboard) {

        // Note: Copy functionality is implemented in clipboard.min.js
        // More info at https://www.npmjs.com/package/clipboard-js
        // and at       https://github.com/lgarron/clipboard.js

        data += "<br><button id='" + buttonName + "' class='copypath_btn'>Copy and close</button>";

        data += "\n";
        data += "<script>\n";    // Or "<p>\n";
//        data += "<p>\n";

        // Attach code to the JQuery dialog's .on("click") method (synonymous with .click())
        data += '$("#' + buttonName + '").on("click",function(e) { \n';
        data += "    clipboard.write([new clipboard.ClipboardItem({\n";
        data += '      "text/html": new Blob(["'  + htmlForClipboard + '"], { type: "text/html" }), \n';
        data += '      "text/plain": new Blob(["' + dataForClipboard + '"], { type: "text/plain" })\n';
        data += "      })]); \n"
        data += "    console.log('success: " + buttonName + "'); \n";
        data += "    if (gamma.dlgPopupInfo) { gamma.dlgPopupInfo.dialog('close'); } \n";
        data += "  });";

        /*
         * Alternative approach, using .getElementById
         * and a Javascript promise
         *
            data += "document.getElementById('" + buttonName + "').addEventListener('click', function() {\n";
            data += "  clipboard.write({\n";
            data += "    'text/plain': \"" + dataForClipboard + "\",\n";
            data += "    'text/html': \""  + htmlForClipboard + "\"\n";
            data += "  }).then(\n";
            data += "    function(){console.log('success: " + buttonName + "');\n";
            data += "  }).then(\n";
            data += "    function(){if (gamma.dlgPopupInfo) { gamma.dlgPopupInfo.dialog('close'); }\n";
            data += "  }),\n";
            data += "    function(err){console.log('failure: " + buttonName + "', err);\n";
            data += "  };\n";
            data += "});\n";
        */


        data += "</script>\n";    // Or "</p>\n";

        return data;
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
    clearSelector: function(name) {
        $('#' + name + ' option').each(function(idx, opt) {
            opt.selected = false;
        });
    },
    toggleVisibility: function(containerId, duration, element) {
        var speed = duration * 1000;
        var container = $('#' + containerId);
        var isVisible = container.is(':visible');
        if(isVisible) {
            container.hide(speed);
        } else {
            container.show(speed);
        }
        if(element) {
            var icon = $(element).find('.expando_section');
            gamma.setToggleIcon(icon, !isVisible);
        }
        return !isVisible;
    },
    setToggleIcon: function(icon, visible) {
        if(visible) {
            icon.toggleClass('ui-icon-circle-plus', false).toggleClass('ui-icon-circle-minus', true);
        } else {
            icon.toggleClass('ui-icon-circle-plus', true).toggleClass('ui-icon-circle-minus', false);
        }
    },
    //------------------------------------------
    //search functions
    //------------------------------------------
    dms_search: function(url, srchVal) {
        if(url == '') return;
        if(srchVal != '') {
            url += srchVal;
            if(typeof top.display_side != 'undefined') {
                top.display_side.location = url;
            } else {
                location = url;
            }
        }
    },
    //------------------------------------------
    // Side menu functions
    // these functions hide and show the side menu
    //------------------------------------------
    kill_frames: function() {
        if(top != self) {
          top.location = location;
        }
    },
    open_frames: function() {
        document.OFS.page.value = location;
        document.OFS.submit();
    },
    toggle_frames: function() {
        if(top != self) {
          top.location = location;
        } else {
          document.OFS.page.value = location;
          document.OFS.submit();
        }
    },
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
     * General AJAX post that fills the given container
     * with returned text and allows
     * pre and post callbacks to be defined
     * @param {string} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    loadContainer: function (url, p, containerId, afterAction, beforeAction) {
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
        });
    },
    /**
     * General AJAX post that gets a data object
     * from JSON returned by server and allows
     * pre and post callbacks to be defined
     * @param {string} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    getObjectFromJSON: function (url, p, containerId, afterAction, beforeAction) {
        var container = (containerId) ? $('#' + containerId) : null;
        var abort = false;
        if(beforeAction) {
            abort = beforeAction();
        }
        if(abort) return;
        if(container) container.spin('small');
        $.post(url, p, function (json) {
                if(container) container.spin(false);
                // Safety - if empty JSON response, return an empty array
                var data = [];
                if(json.length > 0) {
                    data = JSON.parse(json);
                }
                if(afterAction) {
                    afterAction(data);
                }
        });
    },
    /**
     * General AJAX post that calls server operation
     * and returns server response via callback
     * @param {string} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    doOperation: function (url, p, containerId, afterAction, beforeAction) {
        // make calling parameters from p
        // p can be form Id, raw object, or falsey
        var px = {};
        if(p) {
            if (typeof p === "string") {
                px = $('#' + p).serialize();
            } else {
                px = p;
            }
        }
        var container = $('#' + containerId);
        var abort = false;
        if(beforeAction) {
            abort = beforeAction();
        }
        if(abort) return;
        container.spin('small');
        $.post(url, px, function (data) {
                container.spin(false);
                afterAction(data, container);
        });
    },
    //------------------------------------------
    // misc functions and objects
    //------------------------------------------

    /**
     * Convert array of objects representing form values
     * where each object has property 'name' and 'value'
     *
     * Fields with shared name have array of values
     *
     * @param {object} fldObjArray
     * @returns {undefined} Single object with each field represented as a property having value of associated field
     */
    reformatFormArray: function(fldObjArray) {
        var obj = {};
        $.each(fldObjArray, function(idx, fldObj) {
            var nm = fldObj.name;
            if(!obj[nm]) {
                obj[nm] = [];
            }
            obj[nm].push(fldObj.value);
        });
        return obj;
    },
    /**
     * Use to terminate a calling chain
     */
    no_action: {
    },
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
    /**
     * Go to new web page given by url currently selected in the given selection element
     * @param {type} id
     * @returns {undefined}
     */
    goToSelectedPage: function(id) {
        var node = document.getElementById(id);
        window.location.href = node.options[node.selectedIndex].value;
    },
    load_script_diagram_cmd: function() {
        var scriptName = $('#scriptName').val();
        if(scriptName) {
            var url = gamma.pageContext.site_url + 'pipeline_script/dot/' + scriptName
            var p = { datasets: $('#datasets').val() };
            gamma.loadContainer(url, p, 'script_diagram_container');
        }
    },
    load_script_diagram: function () {
        var scriptName = $('#lnk_ID').html();
        if(scriptName) {
            var url = gamma.pageContext.site_url + 'pipeline_script/dot/' + scriptName
            gamma.loadContainer(url, {}, 'script_diagram_container');
        }
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

};  // epsilon
