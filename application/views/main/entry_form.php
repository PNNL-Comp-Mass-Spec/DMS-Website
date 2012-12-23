<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>
$(document).ready(function () { 
	gamma.global.site_url = '<?= site_url() ?>';
	gamma.global.my_tag = '<?= $this->my_tag ?>';
	gamma.adjustEnabledFields();
	}
);
</script>

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
</body>
</html>