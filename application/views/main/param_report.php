<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>

globalAJAX.progress_message = '<span class="LRepProgress"><img src="<?= base_url() ?>images/throbber.gif" /></span>';
globalAJAX.site_url = '<?= site_url() ?>';
globalAJAX.my_tag = '<?= $this->my_tag ?>';

//go get some content from the server and put it into the designated container element
//and initiate the designaged follow-on action, if such exists
function updateContainer(action, container, follow_on_action) {
	var url = globalAJAX.site_url + globalAJAX.my_tag + '/' + action;
	var p = $('#entry_form').serialize();
	
	$.post(url, p, function (data) {
		    $('#' + container).html(data);
			if(follow_on_action && follow_on_action.run) {
				follow_on_action.run();
			}
		}
	);
}
//use to terminate a calling chain
var no_action = {
}
// update the column and sorting filters
var filter_update_action = {
	run:function(){
		if(!$('#sorting_filter_table')) {
			updateContainer('param_filter', 'search_filter_container', no_action);
			$('#search_controls_container').show();
		}
	}
}
//copy the contents of the upper paging display to the lower one
var paging_cleanup_action = {
	run:function() {
		filter_update_action.run();
		$('#paging_container_lower').html($('#paging_container_upper').html());
	}
}
//update the paging display sections, or hide them if no data rows
var paging_update_action = {
	run:function() {
		if($('#data_message')) {
			$('#paging_container_upper').hide();
			$('#paging_container_lower').hide();
		} else {
			$('#paging_container_upper').show();
			$('#paging_container_lower').show();
			$('#paging_container_upper').html(globalAJAX.progress_message);
			$('#paging_container_lower').html(globalAJAX.progress_message);
			updateContainer('/param_paging', 'paging_container_upper', paging_cleanup_action);
		} 	
	}
}
//go get some data rows
var data_update_action = {
	run:function(){
		updateContainer('param_data', 'data_container', paging_update_action);
	}
}
//start the data update chain for the page
function updateMyData(loading) {
	if(loading && loading == 'reset' && $('#qf_first_row')) $('#qf_first_row').val(1);
	$('#paging_container_upper').html(globalAJAX.progress_message);
	data_update_action.run();
}
//after the page loads, set things in motion to populate it
$(document).ready(function () { 
	 	window['reloadListReportData'] = function() { updateMyData('autoload');}
		$('#data_container').html('Data will be displayed after you click the "Search" button.');
	}
);

</script>

</head>

<body>
<div style="height:500px;">

<? $this->load->view('nav_bar') ?>

<div class='local_title'><?= $title; ?></div>

<form name="frmEntry" id="entry_form" action="#" method="post">
<table>
<tr>
<td style="vertical-align:top;">
<div id='form_container'>
<div style="height: 3px; clear: both;"></div>
<?= $form ?>
</div>
</td>
<td style="vertical-align:top;">
<div id='search_filter_container'>
</div>
</td>
</tr>
</table>
<input type="button" onclick="updateMyData('reset')" value="Search" id="search_button" class="search_btn" /> &nbsp; &nbsp; 

<span id='search_controls_container' style='display:none;'>
<a href="javascript:void(0)" onclick="Effect.toggle('sorting_filter_container', 'appear', { duration: 0.1 }); return false;">Sorting</a> &nbsp;
<a href="javascript:void(0)" onclick="Effect.toggle('column_filter_container', 'appear', { duration: 0.1 }); return false;">Column</a> &nbsp;
</span>
</form>

<table>
<tr>
<td>
<div id='paging_container_upper' class='paging_controls' style='display:none'>
&nbsp; Click "Search" to show data. &nbsp;
</div>
</td>
</tr>
<tr>
<td style="padding:0;" >
<div id='data_container'>
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
$this->load->view("main/param_report_export");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>
</body>
</html>