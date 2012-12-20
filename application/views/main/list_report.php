<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>
globalAJAX = {};
globalAJAX.progress_message = '<span class="LRepProgress"><img src="<?= base_url() ?>images/throbber.gif" /></span>';
globalAJAX.site_url = '<?= site_url() ?>';
globalAJAX.my_tag = '<?= $this->my_tag ?>';
globalAJAX.is_ms_helper = '<?= $is_ms_helper ?>';

// go get some content from the server and put it into the designated container element
// and initiate the designated follow-on action, if such exists
function updateContainer(url, containerId, follow_on_action) { 
	var container = $('#' + containerId);
	url = globalAJAX.site_url + globalAJAX.my_tag + url;
	var p = $('#filter_form').serialize();
	$.post(url, p, function (data) {
		    container.html(data);
			if(follow_on_action.run) {
				follow_on_action.run();
			}
		}
	);
}
// load the filter panel according to the given layout mode
function updateMyFilter($mode) {
	updateContainer('/report_filter/' + $mode, 'search_filter_container', filter_observers_action); 
	if($mode == 'minimal') { 
		$('#show_more_filter').show();$('#show_less_filter').hide(); 
	} else { 
		$('#show_more_filter').hide();$('#show_less_filter').show(); 
	}
}
// use to terminate a calling chain
var no_action = {
}
// bind observers to the filter fields to monitor filter status
// and initialize filter status display
var filter_observers_action = {
	run:function() {
		set_filter_field_observers();
		is_filter_active();
	}
}
// copy the contents of the upper paging display to the lower one
var paging_cleanup_action = {
	run:function() {
		$('#paging_container_lower').html($('#paging_container_upper').html());
	}
}

// update the paging display sections, or hide them if no data rows
var paging_update_action = {
	run:function() {
		if($('#data_message').val() != null) {
			$('#paging_container_upper').hide();
			$('#paging_container_lower').hide();
		} else {
			$('#paging_container_upper').show();
			$('#paging_container_lower').show();
			$('#paging_container_upper').html(globalAJAX.progress_message);
			$('#paging_container_lower').html(globalAJAX.progress_message);
			updateContainer('/report_paging', 'paging_container_upper', paging_cleanup_action);
		} 	
	}
}
// call paging action and also initialize checkbox state if this page is a helper
var data_post_load_action = {
	run:function(){
		paging_update_action.run();
		if(!$('#data_message') && globalAJAX.is_ms_helper) { intializeChooserCkbx('ckbx') }
	}
}
// go get some data rows
var data_update_action = {
	run:function(){
		$('#paging_container_upper').html(globalAJAX.progress_message);
		$('#paging_container_lower').html(globalAJAX.progress_message);
		updateContainer('/report_data', 'data_container', data_post_load_action); 	
	}
}
function updateShowSQL() {
	updateAlert('<?= $this->my_tag ?>/report_sql', 'filter_form'); 
}
// update the SQL display box if it is visible
var sql_display_action = {
	run:function() {
		if($('#notification').is(':visible')) {
			updateAlert(globalAJAX.my_tag + '/report_sql', 'filter_form');
		}
	}
}
// start the data update chain for the page
function updateMyData(loading) {
	if(loading == 'no_load') {
		$('#data_container').html('Data will be displayed after you click the "Search" button.');
	} else {
		if(loading && loading == 'reset') $('#qf_first_row').val(1);
		data_update_action.run(); 	
		sql_display_action.run();
	}	
}
// after the page loads, set things in motion to populate it
$(document).ready(function () { 
		$('#data_container').html('Data is loading...' + globalAJAX.progress_message);
		updateMyFilter('minimal');
		updateMyData('<?= $loading ?>');
	 	window['reloadListReportData'] = function() { updateMyData('autoload');}
	}
);
</script>

</head>

<body>
<div style="height:500px;">

<? $this->load->view('nav_bar') ?>

<div class='local_title'><?= $title; ?></div>

<table id='filter_table' class='FTab'>
<tr>
<td>
<form name="frmReport" id="filter_form" action="#" method="post">
<div id='search_filter_container'>
(search filter)
</div>
</form>
</td>
</tr>
<tr>
<td >
<div id='search_controls_container'>
<input type="button" onclick="updateMyData('reset')" value="Search" id="search_button" class="search_btn" /> &nbsp; &nbsp; 
<a href='javascript:void(0)' onclick="clearSearchFilters()" >Clear Filters</a> &nbsp; &nbsp;
<span id='show_less_filter'><a href='javascript:void(0)' onclick="updateMyFilter('minimal')" >Minimize Filters</a></span> &nbsp; &nbsp;
<span id='show_more_filter'><a href='javascript:void(0)' onclick="updateMyFilter('maximal')" >Expand Filters</a></span>  &nbsp; &nbsp;

<a href="javascript:void(0)" onclick="sectionToggle('primary_filter_container', 0.1)">Primary</a> &nbsp;
<a href="javascript:void(0)" onclick="sectionToggle('secondary_filter_container', 0.1)">Secondary</a> &nbsp;
<a href="javascript:void(0)" onclick="sectionToggle('sorting_filter_container', 0.1)">Sorting</a> &nbsp;
<a href="javascript:void(0)" onclick="sectionToggle('column_filter_container', 0.1)">Column</a> &nbsp;

<span style="padding:0 20px 0 30px;font-weight:bold;" id='filters_active'></span>
<span style="padding:0 20px 0 30px;font-weight:bold;" id='sorting_active'></span>

</div>
</td>
</tr>
</table>

<table>
<tr>
<td>
<div id='paging_container_upper' class='paging_controls'  style='display:none'></div>
</td>
</tr>
<tr>
<td style="padding:0;" >
<div id='data_container'>
Loading...
</div>
</td>
</tr>
<tr>
<td>
<div id='paging_container_lower' class='paging_controls' style='display:none'></div>
</td>
</tr>
</table>

<?php // any checkbox selectors?
if($has_checkboxes) $this->load->view("main/list_report_checkboxes");
?>

<?php // any list report commands?
if($list_report_cmds != "") $this->load->view("cmd/$list_report_cmds");
?>

<?php // export command panel
$this->load->view("main/list_report_export");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>
<?php // notification
$this->load->view("main/notification");
?>
</body>
</html>