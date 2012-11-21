
<div class="EPagCmds" id='supplemental_material'></div>

<div class="EPagCmds" style="clear:both;display:none;" id="sub_cmd_buttons">
	<button name='create_request_btn' onclick='submitMainEntryForm()'>Create Job Request</button>
	<a id='move_next_link' href=''>Go to newly created request...</a>
</div>

<div class="EPagCmds" >
	<a href='<?= site_url() ?>analysis_job_request/create'>Skip to generic job request page</a>
</div>

<script type="text/javascript">

Event.observe(window, 'load', function() { 
	$('cmd_buttons').hide();
	$('move_next_link').hide();
	hideSection('section_block_3');
	hideSection('section_block_4');
	hideSection('section_block_5');
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
	//	FUTURE:progress indicator
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			var result = transport.responseText;
			$('supplemental_material').update(result);
			$('sub_cmd_buttons').show();
			setFieldValues();
			showSection('section_block_3');
			showSection('section_block_4');
			showSection('section_block_5');
		}});
}

function setFieldValues() {
	$('toolName').value = $('suggested_ToolName').value;
	$('jobTypeName').value = $('suggested_JobTypeName').value;
	$('organismName').value = $('suggested_OrganismName').value;
	$('protCollNameList').value = $('suggested_ProteinCollectionList').value;
	$('protCollOptionsList').value = $('suggested_ProteinOptionsList').value;
	
	$('ModificationDynMetOx').checked = ($('suggested_DynMetOxEnabled').value == '1');
	$('ModificationStatCysAlk').checked = ($('suggested_StatCysAlkEnabled').value == '1');
	$('ModificationDynSTYPhos').checked = ( $('suggested_DynSTYPhosEnabled').value == '1');
}

function showSection(block_name) {
	var url = '<?= base_url() ?>images/';
	var hide_img = 'z_hide_col.gif';
	showTableRows(block_name, url, hide_img);
}
function hideSection(block_name) {
	var url = '<?= base_url() ?>images/';
	var show_img = 'z_show_col.gif';
	hideTableRows(block_name, url, show_img);
}


</script>