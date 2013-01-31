<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>

</head>

<body>
<div id="body_container" >

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
<input class="button search_btn" type="button" onclick="lstRep.updateMyData('reset')" value="Search" id="search_button" /> &nbsp; &nbsp; 
Clear Filters <a href='javascript:void(0)' onclick="lambda.clearSearchFilters()" ><span class="expando_section ui-icon ui-icon-closethick"></span></a> &nbsp; &nbsp;
<span id='show_less_filter'>Minimize Filters <a href='javascript:void(0)' onclick="lstRep.updateMyFilter('minimal')" ><span class="expando_section ui-icon ui-icon-circle-minus"></span></a></span> &nbsp; &nbsp;
<span id='show_more_filter'>Expand Filters <a href='javascript:void(0)' onclick="lstRep.updateMyFilter('maximal')" ><span class="expando_section ui-icon ui-icon-circle-plus"></span></a></span>  &nbsp; &nbsp;

Primary <a href="javascript:void(0)" onclick="gamma.sectionToggle('primary_filter_container', 0.1, this)"><span class="expando_section ui-icon ui-icon-circle-minus"></span></a> &nbsp;
Secondary <a href="javascript:void(0)" onclick="gamma.sectionToggle('secondary_filter_container', 0.1, this)"><span class="expando_section ui-icon ui-icon-circle-plus"></span></a> &nbsp;
Sorting <a href="javascript:void(0)" onclick="gamma.sectionToggle('sorting_filter_container', 0.1, this)"><span class="expando_section ui-icon ui-icon-circle-plus"></span></a> &nbsp;
Column <a href="javascript:void(0)" onclick="gamma.sectionToggle('column_filter_container', 0.1, this)"><span class="expando_section ui-icon ui-icon-circle-plus"></span></a> &nbsp;

<span style="font-weight:bold;" id='filters_active'></span>

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
if($list_report_cmds != "") {
	$this->load->view("main/list_report_cmd_reporting");
	$this->load->view("cmd/$list_report_cmds");
}
?>

<div class="LRepExport">
Download in other formats:
|<span><a href='javascript:lambda.download_to_doc("excel")'>Excel</a></span>
|<span><a href='javascript:lambda.download_to_doc("tsv")'>Tab-Delimited Text</a></span>|
</div>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<? $this->load->view('resource_links/base2js') ?>

<? if($list_report_cmds != ""): ?>
<script src="<?= base_url().'javascript/lcmd.js' ?>"></script>
<? endif; ?>

<script src="<?= base_url().'javascript/lstRep.js' ?>"></script>

<script type='text/javascript'>
	gamma.pageContext.site_url = '<?= site_url() ?>';
	gamma.pageContext.my_tag = '<?= $this->my_tag ?>';
	gamma.pageContext.is_ms_helper = '<?= $is_ms_helper ?>';
	gamma.pageContext.responseContainerId =  "update_message";
	gamma.pageContext.cntrlContainerId =  "clear_message";
	gamma.pageContext.ops_url = '<?= $ops_url ?>';
	gamma.pageContext.updateShowSQL = lstRep.updateShowSQL;
</script>

</body>
</html>