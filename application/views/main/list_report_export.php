<script type='text/javascript'>

// check size of export document in rows
function download_to_doc(format) {
	var row_count = $('#total_rowcount').html();
	if(row_count > 4000) {
		if (!confirm('Are you sure you want to export ' + row_count + ' rows?') ) return;
	}
	var url = globalAJAX.site_url + globalAJAX.my_tag + '/export/' + format
	export_to_doc(url, "filter_form");
}
</script>

<div class="LRepExport">
Download in other formats:
|<span><a href='javascript:download_to_doc("excel")'>Excel</a></span>
|<span><a href='javascript:download_to_doc("tsv")'>Tab-Delimited Text</a></span>|
</div>
