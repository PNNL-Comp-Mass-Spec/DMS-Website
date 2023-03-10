<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>
<?php echo view('resource_links/cfg') ?>

<?php // Import configdb.js ?>
<?php echo view('resource_links/configdb') ?>

<script type="text/javascript">
$(document).ready(function (){ configdb.show_hide_all('none') });
</script>

</head>
<body>
<div id="body_container" >

<h2 class='page_title'><?= $heading; ?></h2>
<div style='min-height:2em;'>
| &nbsp;
<a href="javascript:void(0)" onclick="configdb.show_hide_all('block')" >Show Tables</a> &nbsp; | &nbsp;
<a href="javascript:void(0)" onclick="configdb.show_hide_all('none')" >Hide Tables</a> &nbsp; | &nbsp;
<?= make_config_nav_links($config_db) ?>
</div>

<div id='display_container'>
<?php echo view("config_db/sub_show_db"); ?>
</div>

<table class='cfg_tab'>
<tr>
<td>
<?= $make_main_db_sql_control ?>
</td>
<td>
<?= $make_controller_control ?>
</td>
</tr>
</table>

<div id='end_of_content' style="height:1em;" ></div>

</div>

<script type='text/javascript'>
    // dmsjs is defined in dms.js
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
    dmsjs.pageContext.config_db = '<?= $config_db ?>';
</script>

</body>
</html>
