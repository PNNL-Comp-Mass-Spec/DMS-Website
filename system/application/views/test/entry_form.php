<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Test - Entry Form</title>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>

function updateContainer(url, container) { 
	p = Form.serialize('entry_form', true);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).update(transport.responseText);
		}
	});
}

</script>

</head>

<body>

<form name="frmEntry" id="entry_form" action="#">
<div id='form_container'>
<?= $form ?>
</div>
</form>

<input type="button" onclick="updateContainer('<?= $entry_form_submit_url ?>', 'form_container')" value="Submit" id="submision_button" class="submision_btn" />

</body>
</html>