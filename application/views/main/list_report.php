<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php $this->load->view('resource_links/base2css') ?>

</head>

<body>
<div id="body_container" >

<?php $this->load->view('nav_bar') ?>

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

<span class="LRepPagerCartouche"><?= search_btn() ?></span>
<span class="LRepPagerCartouche"><?= clear_filters_btn() ?></span>
<span class="LRepPagerCartouche"><?= collapse_filters_btn() ?><?= expand_filters_btn() ?></span>

<span class="LRepPagerCartouche"><?= primary_filter_vis_control('Primary') ?></span>
<span class="LRepPagerCartouche"><?= secondary_filter_vis_control('Secondary') ?></span>
<span class="LRepPagerCartouche"><?= sorting_filter_vis_control('Sorting') ?></span>
<span class="LRepPagerCartouche"><?= column_filter_vis_control('Column') ?></span>

<span id='filters_active'></span>

</div>
</td>
</tr>
</table>

<table>
<tr>
<td>
<div id='paging_container_upper' class='paging_controls' </div>
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
<div id='paging_container_lower' class='paging_controls'></div>
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
Download in other formats (<a href="http://prismwiki.pnl.gov/wiki/DMS_Data_Export" target="_blank">help</a>):
|<span><a href='javascript:lambda.download_to_doc("excel")'>Excel</a></span>
|<span><a href='javascript:lambda.download_to_doc("tsv")'>Tab-Delimited Text</a></span>
|
</div>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<?php $this->load->view('resource_links/base2js') ?>

<?php if($list_report_cmds != ""): ?>
<script src="<?= base_url().'javascript/lcmd.js?version=103' ?>"></script>
<?php endif; ?>

<?php // Increment version number here to force clients to reload lstRep.js ?>
<script src="<?= base_url().'javascript/lstRep.js?version=100' ?>"></script>

<script type='text/javascript'>
	//
	// gamma is defined in dms2.js
	//
	gamma.pageContext.site_url = '<?= site_url() ?>';
	gamma.pageContext.my_tag = '<?= $this->my_tag ?>';
	gamma.pageContext.is_ms_helper = '<?= $is_ms_helper ?>';
	gamma.pageContext.responseContainerId =  "update_message";
	gamma.pageContext.cntrlContainerId =  "clear_message";
	gamma.pageContext.ops_url = '<?= $ops_url ?>';
	gamma.pageContext.updateShowSQL = lstRep.updateShowSQL;
	gamma.pageContext.updateShowURL = lstRep.updateShowURL;
	gamma.pageContext.initalDataLoad = '<?= $loading ?>';
</script>

</body>
</html>