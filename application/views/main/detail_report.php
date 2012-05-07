<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<script type="text/javascript" src="<?= base_url().'javascript/aux_info.js' ?>"></script>

<script type='text/javascript'>

globalAJAX.progress_message = '<span class="LRepProgress"><img src="<?= base_url() ?>images/throbber.gif" /></span>';
globalAJAX.site_url = '<?= site_url() ?>';
globalAJAX.response_container = 'update_message';

//perform detail report command (via AJAX)
function performCommand(url, id, mode) {
	if( !confirm("Are you sure that you want to update the database?") ) return;
	var p = {};
	p.ID = id;
	p.command = mode;
	var opts = {};
	opts.parameters = p;
	var container_name = globalAJAX.response_container;
	$(container_name).update(globalAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container_name).update(transport.responseText);
			updateMyData();	
		}
	});
}
function updateContainer(url, container) { 
	url = globalAJAX.site_url + url;
	p = {};
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).update(transport.responseText);
		}
	});
}
function updateMyData() {
	updateContainer('<?= $this->my_tag ?>/show_data/<?= $id ?>', 'data_container'); 
}
function updateAuxIntoControls() {
	updateContainer('<?= $this->my_tag ?>/detail_report_aux_info_controls/<?= $id ?>', 'aux_info_controls_container'); 
}
function updateShowSQL() {
	updateAlert('<?= $this->my_tag ?>/detail_sql/<?= $id ?>', 'OFS'); 
}
Event.observe(window, 'load', function() { 
	updateMyData();
<?php if($aux_info_target):?>
	updateAuxIntoControls();
<?php endif; ?>
	}
)
</script>

</head>

<body>
<div style="height:500px;">

<? $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $title; ?></h2>


<div id='data_container'>
(data will be loaded here)
</div>

<?php if(!empty($commands)):?>
<div id='update_message' class="RepCmdsResponse" ></div>
<div class='DrepCmds'>
<?= make_detail_report_commands($commands, $tag, $id) ?>
</div>
<div style="height:1em;" ></div>
<?php endif; ?>

<?php if($detail_report_cmds):?>
<div id='command_box_container'>
<?= $this->load->view("detail_report_cmd/$detail_report_cmds"); ?>
</div>
<?php endif; ?>

<?php if($aux_info_target):?>
<div id= 'aux_info_controls_container' class='DrepAuxInfo'></div>
<div id= 'aux_info_container'></div>
<div style="height:1em;" ></div>
<?php endif; ?>

<?php // export command panel
$this->load->view("main/detail_report_export");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>
<?php // notification
$this->load->view("main/notification");
?>
</body>
</html>