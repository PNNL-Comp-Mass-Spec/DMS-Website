<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>
<? $this->load->view('resource_links/scal') ?>

<script type='text/javascript'>

// POST the entry form to the entry page or alternate submission logic
function updateEntryPage(url, mode) {
	if(window.submissionSequence) {
		submissionSequence(url, mode);
	} else {
		submitToFamily(url, mode);
	}
}
//POST the entry form to the entry page via AJAX
function submitToFamily(url, mode, follow_on_action) {
	if(!confirm("Are you sure that you want to perform this action?")) return;
	var container = 'form_container';
	$('#entry_cmd_mode').value = mode;
	p = Form.serialize('entry_form', true);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).update(transport.responseText);
			setTimeout("adjustEnabledFields()", 350);
			if(follow_on_action && follow_on_action.run) {
				follow_on_action.run(mode);
			}			
		}
	});	
}
// POST the entry form to another page
function submitEntryPage(url, mode) {
	$('#entry_cmd_mode').value = mode;
	var f = $('#entry_form');
	f.action = url;
	f.method="post";
	f.submit();
}
Event.observe(window, 'load', function() { 
	adjustEnabledFields();
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