<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>

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

<?php if(count($detail_report_cmds) > 0):?>
<div id='command_box_container'>
<?php foreach($detail_report_cmds as $cmd): ?>	
<?= $this->load->view("detail_report_cmd/$cmd"); ?>
<?php endforeach ?>
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

<? $this->load->view('resource_links/base2js') ?>

<script type='text/javascript'>
	gamma.pageContext.site_url = '<?= site_url() ?>';
	gamma.pageContext.my_tag = '<?= $this->my_tag ?>';
	gamma.pageContext.responseContainerId = 'update_message';
	gamma.pageContext.Id = '<?= $id ?>';
	gamma.pageContext.aux_info_target = '<?= ($aux_info_target)?$aux_info_target:''; ?>';
	gamma.pageContext.updateShowSQL = delta.updateShowSQL;
</script>

<script src="<?= base_url().'javascript/file_attachment.js' ?>"></script>
<script src="<?= base_url().'javascript/aux_info.js' ?>"></script>

<script type='text/javascript'>
	function updateAuxIntoControls() {
		delta.updateContainer(gamma.pageContext.my_tag + '/detail_report_aux_info_controls/' + gamma.pageContext.Id, 'aux_info_controls_container'); 
	}
	$(document).ready(function () { 
		delta.updateMyData();
		if(gamma.pageContext.aux_info_target) updateAuxIntoControls();
		fileAttachment.init();
	});
</script>

</body>
</html>