<script type="text/javascript">

// gets JSON back from AJAX call - separate return code and message
function performExec(url, mode) {
	var url =  gamma.global.site_url + url;
	var p = {};
	p.ID = 5;
	p.command = mode;
	$('#' + gamma.global.response_container_name).html(gamma.global.progress_message);
	$.post(url, p, function (data) {
			var response = data.evalJSON();
			$('#' + gamma.global.response_container_name).html(response.message);
			$('#' + gamma.global.cntrl_container_name).show();
		}
	);
}

// gets only text back from the AJAX call
function performCall(url, mode) {
	var url =  gamma.global.site_url + url;
	var p = {};
	p.ID = 5;
	p.command = mode;
	$('#' + gamma.global.response_container_name).html(gamma.global.progress_message);
	$.post(url, p, function (data) {
			$('#' + gamma.global.response_container_name).html(data);
			$('#' + gamma.global.cntrl_container_name).show();
		}
	);
}
function performOperation(url, mode, show_msg) {
	var url =  gamma.global.site_url + url;
	var p = {};
	p.ID = 5;
	p.command = mode;
	delta.submitOperation(url, p, show_msg);
}
</script>


<div class='LRCmds'>


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