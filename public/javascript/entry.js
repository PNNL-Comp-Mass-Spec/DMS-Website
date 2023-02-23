// After updating this page, increment the version ID defined on the base_url line in file app/Views/main/entry_form.php
// This is required to force browsers to update the cached version of this file

var entry = {

    /**
     * Show the URL of the currently visible page
     * @returns {undefined}
     */
    updateShowURL: function() {
        // POST a request to a report_info/url page
        // For example http://dms2.pnl.gov/dataset_qc/report_info/url
        var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/' + gamma.pageContext.page_type + '/' + gamma.pageContext.url_segments;
        gamma.updateMessageBoxText(url, 'URL');
    }
} // entry
