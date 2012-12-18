<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Test - List Report</title>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>

function updateContainer(url, container) { 
	p = Form.serialize('filter_form', true);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).html(transport.responseText);
		}
	});
}

function updateMyFilter() {
	updateContainer('<?= $search_filter_ajax ?>', 'search_filter_container'); 
}

function updateMyData() {
	updateContainer('<?= $q_data_rows_ajax ?>', 'data_container'); 	
	updateContainer('<?= $q_data_rows_ajax_pages ?>', 'paging_container'); 	
}
$(document).ready(function () { 
	updateMyFilter();
	updateMyData();
	}
)

function clearFilters() {
	$(".primary_filter_field").each(function(idx, obj) {obj.value = ''} );
	$(".secondary_filter_input").each(function(idx, obj) {obj.value = ''} );
	$(".sorting_filter_input").each(function(idx, obj) {obj.value = ''} );	
}

function setColSort(colName) {
	var curCol = $('#qf_sort_col_0'val();
	var curDir = $('#qf_sort_dir_0'val();
	$(".sorting_filter_input").each(function(idx, obj) {obj.value = ''} );
	var dir = 'ASC';
	if(colName == curCol) {dir = (curDir == 'ASC')?'DESC':'ASC'; };
	$('#qf_sort_col_0').val(colName);
	$('#qf_sort_dir_0').val(dir);
	// call updateMyData();
}
</script>

</head>

<body>

<form name="frmReport" id="filter_form" action="#">
<div id='search_filter_container'>
(search filter will be loaded here)
</div>
</form>
<input type="button" onclick="updateMyData()" value="Search" id="search_button" class="search_btn">
<a href='javascript:void(0)' onclick="clearFilters()" >clear filters</a>

<div id='paging_container'>
(paging data will be loaded here)
</div>

<div id='data_container'>
(data will be loaded here)
</div>


</body>
</html>