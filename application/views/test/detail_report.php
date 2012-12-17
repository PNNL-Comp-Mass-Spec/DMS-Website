<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Test - Detail Report</title>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>

function updateContainer(url, container) { 
	p = {};
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).update(transport.responseText);
		}
	});
}


function updateMyData() {
	updateContainer('<?= $q_data_row_ajax ?>', 'data_container'); 	
}
$(document).ready(function () { 
	updateMyData();
	}
)


</script>

</head>

<body>


<div id='data_container'>
(data will be loaded here)
</div>


</body>
</html>