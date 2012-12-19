<script type="text/javascript">

// gets JSON back from AJAX call - separate return code and message
function performExec(url, mode) {
	var url =  "<?= site_url() ?>" + url;
	var p = {};
	p.ID = 5;
	p.command = mode;
	$(globalAJAX.response_container_name).html(globalAJAX.progress_message);
	$.post(url, p, function (data) {
			var response = data.evalJSON();
			$('#' + globalAJAX.response_container_name).html(response.message);
			$('#' + globalAJAX.cntrl_container_name).show();
		}
	);
}

// gets only text back from the AJAX call
function performCall(url, mode) {
	var url =  "<?= site_url() ?>" + url;
	var p = {};
	p.ID = 5;
	p.command = mode;
	$('#' + globalAJAX.response_container_name).html(globalAJAX.progress_message);
	$.post(url, p, function (data) {
			$('#' + globalAJAX.response_container_name).html(data);
			$('#' + globalAJAX.cntrl_container_name).show();
		}
	);
}
function performOperation(url, mode, show_msg) {
	var url =  "<?= site_url() ?>" + url;
	var p = {};
	p.ID = 5;
	p.command = mode;
	submitOperation(url, p, show_msg);
}
</script>


<div class='LRCmds'>
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" id="operation_form" action="">

<div>
<textarea id='local_debug' name='comment' cols='70' rows='4'></textarea>
</div>

<div>
Mode: 
<select name="modes" id="modes">
<option value="succeed">succeed</option>
<option value="echo">succeed with message</option>
<option value="fail">fail</option>
</select>
</div>

<div>
<input class="lst_cmd_btn" type="button" value="Test exec" onClick='performExec("bogus/exec/test", $('#modes').val())'/>
</div>
<div>
<input class="lst_cmd_btn" type="button" value="Test command" onClick='performCall("bogus/command", $('#modes').val())'/>
</div>
<div>
<input class="lst_cmd_btn" type="button" value="Test operation" onClick='performOperation("bogus/operation", $('#modes').val())'/>
</div>

</form>
</div>