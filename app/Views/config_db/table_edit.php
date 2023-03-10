<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>
<?php echo view('resource_links/cfg') ?>

<?php // Import configdb.js ?>
<?php echo view('resource_links/configdb') ?>

</head>
<body>
<div id="body_container" >

<h2 class='page_title'><?= $heading; ?></h2>

<?= make_config_nav_links($config_db)?>

<div id='edit_container'>
<?php echo view("config_db/sub_table_edit"); ?>
</div>

<div style="height:1em;"></div>

<table class='cfg_tab' style="width:98%;">

<tr><th style="font-weight:bold;text-align:left;">Raw SQL Entry</th></tr>

<tr><td><div id='sql_container' style="padding-right:5px;">
<form id='sql_text' action='post'>
<textarea name='sql_text' id='sql_text_fld' style="height:15em;width:100%;"><?= $sql_text ?></textarea>
</form>
</div></td></tr>

<tr><td>
<a href="javascript:void(0)" onclick="configdb.get_sql('suggest')" title='Get suggested SQL for possible new additions to table'>Suggest Additions</a> &nbsp;  &nbsp;
<a href="javascript:void(0)" onclick="configdb.get_sql('dump')" title='Get SQL for existing contents of table.'>Current Content</a> &nbsp;  &nbsp;
<a href="javascript:void(0)" onclick="configdb.do_sql()" title='Run the SQL against the config db.'>Update</a> &nbsp;  &nbsp;
<a href="javascript:void(0)" onclick="configdb.get_sql_from_range_move('item')" title='Get SQL to move items'> <span id='source_id'></span>-><span id='dest_id'></span> </a> &nbsp;  &nbsp;
<a href="javascript:void(0)" onclick="configdb.get_sql_from_range_move('range')" title='Get SQL to move range of items'> <span id='range_start_id'></span>-<span id='range_stop_id'></span>-><span id='range_dest_id'></span> </a> &nbsp;  &nbsp;
<a href="javascript:void(0)" onclick="configdb.get_sql_for_resequence()" title='Get SQL to resequence id col in table'>Resequence</a> &nbsp;  &nbsp;
<a href="javascript:void(0)" onclick="$('#sql_text_fld').val('')" title='Clear SQL field'>Clear</a> &nbsp;  &nbsp;
</td></tr>

</table>

<div id='end_of_content' style="height:1em;" ></div>

</div>

<script type='text/javascript'>
    // dmsjs is defined in dms.js
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
    dmsjs.pageContext.config_db = '<?= $config_db ?>';
    dmsjs.pageContext.table_name = '<?= $table_name ?>';
</script>

</body>
</html>
