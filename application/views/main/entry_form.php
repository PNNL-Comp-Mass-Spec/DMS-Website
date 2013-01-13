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

<form name="frmEntry" id="entry_form" action="#">
<div id='form_container'>
<?= $form ?>
</div>
</form>

<div class='EPagCmds' style='clear:both;' id='cmd_buttons'>
<?= $entry_cmds ?>
</div>

<?php // any submission commands?
if($entry_submission_cmds != "") $this->load->view("submission_cmd/$entry_submission_cmds");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<? $this->load->view('resource_links/base2js') ?>

<script type='text/javascript'>
	gamma.pageContext.site_url = '<?= site_url() ?>';
	gamma.pageContext.base_url = '<?= base_url() ?>';
	gamma.pageContext.my_tag = '<?= $this->my_tag ?>';
	epsilon.pageContext.containerId = 'form_container';
	epsilon.pageContext.modeFieldId = 'entry_cmd_mode';
	epsilon.pageContext.entryFormId = 'entry_form';
	epsilon.adjustEnabledFields();
</script>

<? if($entry_submission_cmds != ""): ?>
<script src="<?= base_url().'javascript/entry.js' ?>"></script>
<script type='text/javascript'>gamma.pageContext.cmdInit = entry.<?= $this->my_tag ?>.cmdInit;</script>
<? endif; ?>

<script type='text/javascript'>
	$(document).ready(function () { 
		$('.sel_chooser').chosen({search_contains: true});
	});
	epsilon.actions.after = function() {
		$('.sel_chooser').chosen({search_contains: true});		
	};
	if(gamma.pageContext.cmdInit) gamma.pageContext.cmdInit();
</script>

</body>
</html>