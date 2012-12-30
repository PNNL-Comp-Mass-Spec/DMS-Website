<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>
<? $this->load->view('resource_links/cfg') ?>

<script type="text/javascript">
function ops(submit_url) {
	if ( !confirm("Are you sure that you want to modify the config db?") ) return;
	var container_name = "display_container";
	var url =  "<?= site_url()?>" + "config_db/" + submit_url;
	$('#' + container_name).load(url); // gamma.loadContainer(url, {}, container_name);
}

function show_hide_all(mode) {
	$('div.block_content').each(function(idx, s){
			s.style.display=mode;
		});
}
function show_hide_block(name) {
	$('#' + name).toggle();
}
function make_controller() {
 	var reply = prompt("Base title for page family", '');
	var page = "<?= site_url(); ?>" + "config_db/make_controller/<?= $config_db ?>/" + reply;
	window.open(page, "HW", "scrollbars,resizable,height=550,width=1000,menubar");
}

$(document).ready(function (){show_hide_all('none')});
</script>

</head>
<body>
<div style="height:500px;">

<h2 class='page_title'><?= $heading; ?></h2>
<div style='min-height:2em;'> 
| &nbsp;
<a href="javascript:void(0)" onclick="show_hide_all('block')" >Show Tables</a> &nbsp; | &nbsp;
<a href="javascript:void(0)" onclick="show_hide_all('none')" >Hide Tables</a> &nbsp; | &nbsp;
<?= make_config_nav_links($config_db) ?>
</div>

<div id='display_container'>
<? $this->load->view("config_db/sub_show_db"); ?>
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
</body>
</html>
