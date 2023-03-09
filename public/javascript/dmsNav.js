//------------------------------------------
// navBar (main drop-down menu)
//------------------------------------------

// set event handlers for global search panel
$(document).ready(function () {
    var panel = $('.global_search_panel');
    gamma.setSearchEventHandlers(panel);
});

// set listener for nav-bar related clicks
$(document).ready(function () {
    $(document.body).on("click", navBar.hide_exposed_menus);
});

var navBar = {
    /**
     * Show a menu
     * @param {type} menu_id Menu ID to expose
     * @returns {undefined}
     */
    expose_menu: function(menu_id) {
        navBar.openMenuId = menu_id;
        var m = $('#' + menu_id);
        m.css('display', 'block');
    },
    /**
     * Hide a menu
     * @param {type} e
     * @returns {undefined}
     */
    hide_exposed_menus: function(e) {
        if(e) {
            var el = e.target;
            var pe = $(el).closest('div')[0];
            var notA = el.tagName.toLowerCase() !== 'a';
            var notM = pe.id !== 'menu';
            if(notA || notM) {
                navBar.openMenuId = '';
            }
        } else {
            navBar.openMenuId = '';
        }
        var menu_list = $('.ddm');
        menu_list.each( function(idx, x) {
                if(x.id !== navBar.openMenuId)
                    x.style.display = 'none';
            });
    },
    /**
     * Invoke an action
     * @param {type} action
     * @param {type} arg
     * @returns {undefined}
     */
    invoke: function(action, arg) {
        if(action) {
            action(arg);
        }
        navBar.hide_exposed_menus();
    }
};

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
