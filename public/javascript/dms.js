//------------------------------------------
// For browsers that don't support console.log
//------------------------------------------
if (typeof console === "undefined" || typeof console.log === "undefined") {
    console = {};
    console.log = function () { };
}

//------------------------------------------
// Set up generic hook to catch $.post AJAX errors
//------------------------------------------
$(document).ajaxError(function (e, xhr, settings, exception) {
    console.log('AJAX error in: ' + settings.url + '; ' + 'error:' + exception);
});

//------------------------------------------
// Stupid IE
//------------------------------------------

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(obj, start) {
         for (var i = (start || 0), j = this.length; i < j; i++) {
         if (this[i] === obj) { return i; }
         }
         return -1;
    };
}

//------------------------------------------
// JQuery plug-in for spinner
//------------------------------------------
/*
$("#el").spin(); // Produces default Spinner using the text color of #el.
$("#el").spin("small"); // Produces a 'small' Spinner using the text color of #el.
$("#el").spin("large", "white"); // Produces a 'large' Spinner in white (or any valid CSS color).
$("#el").spin({ ... }); // Produces a Spinner using your custom settings.
$("#el").spin(false); // Kills the spinner.
*/
(function($) {
    $.fn.spin = function(opts, color) {
        var presets = {
            "tiny": {  lines: 8,  length: 2, width: 2, radius: 3, opacity: 0.1, speed: 0.5, trail: 70, top: '15%', left: '10%' },
            "small": { lines: 8,  length: 4, width: 3, radius: 5, opacity: 0.1, speed: 0.5, trail: 70, top: '25%', left: '15%' },
            "large": { lines: 10, length: 8, width: 4, radius: 8, opacity: 0.1, speed: 0.5, trail: 70, top: '30%', left: '20%' }
        };
        if (Spinner) {
            return this.each(function() {
                var $this = $(this),
                    data = $this.data();

                if (data.spinner) {
                    data.spinner.stop();
                    delete data.spinner;
                }
                if (opts !== false) {
                    if (typeof opts === "string") {
                        if (opts in presets) {
                            opts = presets[opts];
                            opts.color = 'blue';
                        } else {
                            opts = {};
                        }
                        if (color) {
                            opts.color = color;
                        }
                    }
                    data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
                }
            });
        } else {
            throw "Spinner class not available.";
        }
    };
})(jQuery);

//------------------------------------------
// Global and general-purpose functions and objects
//------------------------------------------

var dmsjs = {

    dlgPopupInfo: '',

    //------------------------------------------
    // Context values for current page
    //
    // Many library functions reference this object
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
     * Return a new array consisting of items in target that are not in remove
     *
     * @param {array} target Items to process, e.g. ['Request', 'Temperature', Time_Point, 'Block', 'Run Order']
     * @param {array} remove Items to remove, e.g. ['Request', 'Block', 'Run Order', 'Run_Order']
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

            url = dmsjs.pageContext.site_url + url;
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
                        // Replace '\' with '\\' since PostgreSQL queries with SIMILAR TO will have underscores and parentheses escaped using a backslash

                        dataForClipboard = unformattedSql.replace(/"/g, '&quot;').replace(/\\/g, '\\\\');
                        htmlForClipboard = data.replace(/"/g, '&quot;').replace(/\\/g, '\\\\');

                    } else {
                        var buttonName = 'copy-url-now';

                        data = data.replace(/\n/g, ' ').trim();

                        maxTextLength = data.length;

                        dataForClipboard = data;
                        htmlForClipboard = data;

                        data += '<br>';
                    }

                    var width = dmsjs.getDialogWidth(maxTextLength);
                    dlg.dialog({ width: width });

                    data = dmsjs.addCopyDataButton(data, buttonName, dataForClipboard, htmlForClipboard);

                    dlg.html(data);
                    dlg.dialog('open');

                    dmsjs.dlgPopupInfo = dlg;
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

            var width = dmsjs.getDialogWidth(data.length);
            dlg.dialog({ width: width });

            var dataForClipboard = data;
            var htmlForClipboard = data;
            var buttonName = "copy-data-now";

            data += "<br>";

            data = dmsjs.addCopyDataButton(data, buttonName, dataForClipboard, htmlForClipboard);

            dlg.html(data);
            dlg.dialog('open');

            dmsjs.dlgPopupInfo = dlg;
        };
    }(),
    /*
     * Add a button and the required javascript for copying text
     */
    addCopyDataButton: function(data, buttonName, dataForClipboard, htmlForClipboard) {

        // Note: Copy functionality is build-in on all supported browsers from 2020+
        // We previously used clipboard-polyfill clipboard.min.js
        // More info at https://github.com/lgarron/clipboard-polyfill

        data += "<br><button id='" + buttonName + "' class='copypath_btn'>Copy and close</button>";

        data += "\n";
        data += "<script>\n";    // Or "<p>\n";
//        data += "<p>\n";

        // Attach code to the JQuery dialog's .on("click") method (synonymous with .click())
        data += '$("#' + buttonName + '").on("click",function(e) { \n';
        data += "    navigator.clipboard.write([new ClipboardItem({\n";
        data += '      "text/html": new Blob(["'  + htmlForClipboard + '"], { type: "text/html" }), \n';
        data += '      "text/plain": new Blob(["' + dataForClipboard + '"], { type: "text/plain" })\n';
        data += "      })]); \n"
        data += "    console.log('success: " + buttonName + "'); \n";
        data += "    if (dmsjs.dlgPopupInfo) { dmsjs.dlgPopupInfo.dialog('close'); } \n";
        data += "  });";

        /*
         * Alternative approach, using .getElementById
         * and a Javascript promise
         *
            data += "document.getElementById('" + buttonName + "').addEventListener('click', function() {\n";
            data += "  navigator.clipboard.write([new ClipboardItem({\n";
            data += '      "text/html": new Blob(["'  + htmlForClipboard + '"], { type: "text/html" }), \n';
            data += '      "text/plain": new Blob(["' + dataForClipboard + '"], { type: "text/plain" })\n';
            data += "  })]).then(\n";
            data += "    function(){console.log('success: " + buttonName + "');\n";
            data += "  }).then(\n";
            data += "    function(){if (dmsjs.dlgPopupInfo) { dmsjs.dlgPopupInfo.dialog('close'); }\n";
            data += "  }),\n";
            data += "    function(err){console.log('failure: " + buttonName + "', err);\n";
            data += "  };\n";
            data += "});\n";
        */


        data += "</script>\n";    // Or "</p>\n";

        return data;
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
            dmsjs.setToggleIcon(icon, !isVisible);
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
    // Misc functions and objects
    //------------------------------------------

    /**
     * Go to new web page given by url currently selected in the given selection element
     * @param {type} id
     * @returns {undefined}
     */
    goToSelectedPage: function(id) {
        var node = document.getElementById(id);
        window.location.href = node.options[node.selectedIndex].value;
    }
};  // dmsjs
