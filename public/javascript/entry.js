/****************************************************************
* Javascript methods that are specific to entry pages
****************************************************************/
// After updating this page, increment the version ID defined on the base_url line in file app/Views/main/entry_form.php
// This is required to force browsers to update the cached version of this file

var entry = {

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
        var url = dmsjs.pageContext.base_url + 'images/';
        var hide_img = 'z_hide_col.gif';
        entry.showTableRows(block_name, url, hide_img);
    },
    /**
     * Hide a section
     * @param {type} block_name
     * @returns {undefined}
     */
    hideSection: function (block_name) {
        var url = dmsjs.pageContext.base_url + 'images/';
        var show_img = 'z_show_col.gif';
        entry.hideTableRows(block_name, url, show_img);
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
            sb = 'section_block_' + dmsInput.trim(blk);
            if(action === 'show') {
                entry.showSection(sb);
            }
            if(action === 'hide') {
                entry.hideSection(sb);
            }
        });
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
     * @remarks Example usage is 'entry.actions.before = entryCmds.sample_prep_request.approveSubmit;' in entry.js
     */
    submitStandardEntryPage: function(url, mode) {
        entry.submitEntryFormToPage(url, mode, this.actions.after, this.actions.before);
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
                setTimeout("entry.adjustEnabledFields()", 350);
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
        var modeField = $('#' + entry.pageContext.modeFieldId);
        var entryForm = $('#' + entry.pageContext.entryFormId);
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

    //------------------------------------------
    // entry field formatting
    //------------------------------------------

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
    },
    /**
     * Show the URL of the currently visible page
     * @returns {undefined}
     */
    updateShowURL: function() {
        // POST a request to a report_info/url page
        // For example http://dms2.pnl.gov/dataset_qc/report_info/url
        var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag + '/' + dmsjs.pageContext.page_type + '/' + dmsjs.pageContext.url_segments;
        dmsjs.updateMessageBoxText(url, 'URL');
    }
} // entry
