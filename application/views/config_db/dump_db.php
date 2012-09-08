<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>
<? $this->load->view('resource_links/cfg') ?>

<style type="text/css">
div.block_content {
	display:block;
}
</style>

<script type="text/javascript">
	
function search() {
	var file_filter = $('file_filter').value;
	var table_filter = $('table_filter').value;
	var url = '<?= site_url() ?>/config_db/search/'+ file_filter + '/' + table_filter;
	if($('text_only').checked) {
		url += '/text';
	}
	location = url;
}

</script>

</head>
<body>
<div style="height:500px;">


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
<? if($display_format == 'table_dump'): ?>
<?= make_table_dump_display($config_db_table_list); ?>
<? elseif($display_format == 'text'): ?>
Not implmented yet.
<? endif; ?>
</div>

<div id='end_of_content' style="height:1em;" ></div>

</div>
</body>
</html>
