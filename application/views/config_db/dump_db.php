<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/cfg') ?>

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
<td><input type='button' onclick='search()' value='Search' /></td>
<td><input type='checkbox' id='text_only'  />Text output</td>
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

<?php $this->load->view('resource_links/base2js') ?>

<script type="text/javascript">

function search() {
    var file_filter = $('#file_filter').val();
    var table_filter = $('#table_filter').val();
    var url = '<?= site_url() ?>config_db/search/'+ file_filter + '/' + table_filter;
    if($('#text_only').checked) {
        url += '/text';
    }
    location = url;
}

</script>
</body>
</html>
