<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/cfg') ?>

</head>
<body>
<div id="body_container" >


<h2 class='page_title'><?= $heading; ?></h2>

<?= make_config_nav_links('')?>
<hr>

<form name='search_filter' action='post'>
<table class='cfg_tab'>
<tr>
    <th><span>Search Filter</span></th>
</tr>
<tr>
    <td>Find all tables whose names contain "Table Filter" <br> within all config db file whose names contain "File Filter".</td>
</tr>
<tr><td>
<table class='cfg_tab'>
<tr>
<td><span style='font-weight:bold;'>File filter:</span></td>
<td><input id='file_filter' value='<?= $raw_filter?>' size='30'/></td><td>'db' used by itself matches all config db</td>
</tr>
<tr>
<td><span style='font-weight:bold;'>Table filter:</span></td>
<td><input id='table_filter' value='<?= $table_filter?>' size='30' /></td><td>'_' used by itself matches all tables</td>
</tr>
<tr>
<td><input type='button' onclick='configdb.search()' value='Search' /></td>
<td><input type='checkbox' id='text_only' />Text output</td>
<td>&nbsp;</td>
</tr>
</table>
</td></tr>
</table>
</form>

<div id='display_container'>
<?php if($display_format == 'table_dump'): ?>
<?= make_table_dump_display($config_db_table_list); ?>
<?php elseif($display_format == 'text'): ?>
Not implmented yet.
<?php endif; ?>
</div>

<div id='end_of_content' style="height:1em;" ></div>

</div>

<?php echo view('resource_links/base2js') ?>

<?php // Import configdb.js ?>
<?php echo view('resource_links/configdb') ?>

<script type='text/javascript'>
    // dmsjs is defined in dms.js
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
</script>

</body>
</html>
