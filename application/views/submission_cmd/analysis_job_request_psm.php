
<div class="EPagCmds" >
<button name='get_defaults_btn' onclick='getJobDefaults()'>Get Job Defaults</button>
	<a href='<?= site_url() ?>analysis_job_request/create'>Skip To Job Request Page</a>
</div>

<div class="EPagCmds" id='supplemental_material'></div>

<div class="EPagCmds" style="clear:both;display:none;" id="sub_cmd_buttons">
	<button name='create_request_btn' onclick='submitMainEntryForm()'>Create Job Request</button>
	<a id='move_next_link' href=''>Go to newly created request...</a>
</div>



<script type="text/javascript">

Event.observe(window, 'load', function() { 
	$('cmd_buttons').hide();
	$('move_next_link').hide();
});

function submitMainEntryForm() {
	$('requestID').value = '0';
	$('move_next_link').hide();
	var url = "<?= site_url() . $tag ?>/submit_entry_form";
	submitToFamily(url, 'add', { run:function() {showPageLinks();} } );
}

function showPageLinks() {
	var id = $('requestID').value;
	if(id != '0') {
		var url = "<?= site_url() ?>analysis_job_request/show/" + id;
		$('move_next_link').href = url;
		$('move_next_link').show();
	}
}

function getJobDefaults() {
	var url = '<?= $tag ?>/get_defaults';
	callOperation(url);
}

// 
function callOperation(url) {
	var url =  "<?= site_url() ?>" + url;
	var p = {};
	p.datasets = $('datasets').value;

//	$(globalAJAX.response_container_name).update(globalAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			var result = transport.responseText;
			$('supplemental_material').update(result);

			$('sub_cmd_buttons').show();
			$('toolName').value = $('suggested_ToolName').value;
			$('jobTypeName').value = $('suggested_JobTypeName').value;
			
			if( $('suggested_DynMetOxEnabled').value == '1') {
				$('ModificationDynMetOx').checked = 'checked';
			}
			if( $('suggested_StatCysAlkEnabled').value == '1') {
				$('ModificationStatCysAlk').checked = 'checked';
			}
			if( $('suggested_DynSTYPhosEnabled').value == '1') {
				$('ModificationDynSTYPhos').checked = 'checked';
			}
		}});
}

</script>