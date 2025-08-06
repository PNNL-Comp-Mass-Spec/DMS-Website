<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>

</head>

<body>
<div id="body_container" >

<?php echo view('nav_bar') ?>

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
<input class="button search_btn" type="button" onclick="parRep.updateMyData('reset')" value="Search" id="search_button" /> &nbsp; &nbsp;

<span id='search_controls_container' style='display:none;'>
Sorting <a href="javascript:void(0)" onclick="dmsFilter.toggleFilterVisibility('sorting_filter_container', 0.1, this)"><?= expansion_link_icon() ?></a> &nbsp;
Column <a href="javascript:void(0)" onclick="dmsFilter.toggleFilterVisibility('column_filter_container', 0.1, this)"><?= expansion_link_icon() ?></a> &nbsp;
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
if($has_checkboxes) echo view("main/list_report_checkboxes");
?>

<?php // any list report commands?
if($list_report_cmds != "") {
    echo view("main/list_report_cmd_reporting");
    echo view("cmd/$list_report_cmds");
}
?>

<?php // export command panel
echo view("main/param_report_export");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<?php echo view('resource_links/base2js') ?>

<?php if($list_report_cmds != ""): ?>
    <?php // Import echarts.min.js ?>
    <?php echo view('resource_links/echarts') ?>

    <?php // Import lcmd.js ?>
    <?php echo view('resource_links/lcmd') ?>

<?php else: ?>
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.sel_chooser').select2();
        });
    </script>
<?php endif; ?>

<?php // Import parRep.js ?>
<?php echo view('resource_links/parRep') ?>

<script type='text/javascript'>
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
    dmsjs.pageContext.my_tag = '<?= $my_tag ?>';
    dmsjs.pageContext.responseContainerId =  'update_message';
    dmsjs.pageContext.cntrlContainerId =  'clear_message';
    dmsjs.pageContext.ops_url = '<?= $ops_url ?>';
    dmsjs.pageContext.updateShowURL = parRep.updateShowURL;
</script>

</body>
</html>
