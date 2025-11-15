var fileAttachment = fileAttachment || {
     report_upload_results: function(msg) {
        $('#result_display').spin(false);
        $('#result_display').html(msg);
        this.showAttachments();
    },
     report_download_results: function(msg) {
        if(msg) alert(msg);
    },
    showAttachments: function() {
        var url =  dmsjs.pageContext.site_url + "file_attachment/show_attachments";
        var p = {};
        p.entity_type = dmsjs.pageContext.my_tag;
        p.entity_id = dmsjs.pageContext.Id;
        dmsOps.doOperation(url, p, 'attachments_list', function(data, container) {
                container.html(data);
                //$('#file_attachments_section').show();
                // Copy the hidden 'count' value to the section title placeholder
                $('#attachments_count').html($('#file_attachments_count').html());
        });
    },
    doOperation: function(faid, mode) {
        if(mode = 'delete') {
            if(!confirm('Are you sure you want to delete this attached file? This operation cannot be undone.')) return;
        }
        var context = this;
        var url =  dmsjs.pageContext.site_url + "file_attachment/perform_operation";
        var p = {};
        p.id = faid;
        p.mode = mode;
        dmsOps.doOperation(url, p, 'result_display', function (data) {
                if(data != '') {
                    alert(data);
                } else {
                    context.showAttachments();
                }
        });
    },
    do_upload: function() {
        $('#result_display').spin('small');
        //$('#upload_form').trigger('submit'); // Skipped: returning true instead to continue into the normal 'submit' method
        return true;
    },
    doDownload: function(url) {
        var cUrl = url.replace('/retrieve/', '/check_retrieve/');
        dmsOps.getObjectFromJSON(cUrl, {}, '', function(response) {
            if(response.ok) {
                var ufrm =  $('#download_form').get(0);
                ufrm.action = url;
                ufrm.submit();
            } else {
                alert("There was a problem accessing the file: " + response.message);
            }
        });
    },
    init: function() {
        var et = $('#entity_type');
        var ei = $('#entity_id');
        if(et.length != 0) et.val(dmsjs.pageContext.my_tag);
        if(ei.length != 0) ei.val(dmsjs.pageContext.Id);
        fileAttachment.showAttachments();
    }
}
