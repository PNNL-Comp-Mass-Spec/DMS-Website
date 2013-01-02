
<div class="EPagCmds" id='supplemental_material'></div>

<div class="EPagCmds" style="clear:both;display:none;" id="sub_cmd_buttons">
	<button name='create_request_btn' onclick='createRequest()'>Create Job Request</button>
	<button name='create_preview_btn' onclick='previewRequest()'>Preview Job Request</button>
	<a id='move_next_link' href=''>Go to newly created request...</a>
</div>

<div class="EPagCmds" >
	<a href='<?= site_url() ?>analysis_job_request/create'>Skip to generic job request page</a>
</div>

<script type="text/javascript">

$(document).ready(function () { 
	$('#move_next_link').hide();
	epsilon.showHideSections('hide', '3,4,5');
});

function createRequest() {
	submitMainEntryForm('add', showPageLinks);
}
function previewRequest() {
	submitMainEntryForm('preview', function() {
		var mm = $('#main_outcome_msg');
		var sm = $('#supplement_outcome_msg');
		if(mm && sm) { sm.html(mm.html())}
	});	
}

function submitMainEntryForm(mode, followOnAction) {
	$('#requestID').val('0');
	$('#move_next_link').hide();
	var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + "/submit_entry_form";
	epsilon.submitEntryFormToPage(url, mode, followOnAction);
}

function showPageLinks() {
	var id = $('#requestID').val();
	if(id != '0') {
		var url = gamma.pageContext.site_url + "analysis_job_request/show/" + id;
		$('#move_next_link').href = url;
		$('#move_next_link').show();
	}
}

function getJobDefaults() {
	var url = gamma.pageContext.my_tag + '/get_defaults';
	callOperation(url);
}

// 
function callOperation(url) {
	url =  gamma.pageContext.site_url + url;
	var p = { datasets: $('#datasets').val() };
	gamma.loadContainer(url, p, 'supplemental_material', function (data) {
			$('#sub_cmd_buttons').show();
			setFieldValues();
		}
	);
}

function setFieldValues() {
	if($('#return_code').val() != 'success') return;
	
	$('#toolName').val($('#suggested_ToolName').val());
	$('#jobTypeName').val($('#suggested_JobTypeName').val());
	$('#organismName').val($('#suggested_OrganismName').val());
	$('#protCollNameList').val($('#suggested_ProteinCollectionList').val());
	$('#protCollOptionsList').val($('#suggested_ProteinOptionsList').val());
	
	$('#ModificationDynMetOx').checked = ($('#suggested_DynMetOxEnabled').val( '1'));
	$('#ModificationStatCysAlk').checked = ($('#suggested_StatCysAlkEnabled').val( '1'));
	$('#ModificationDynSTYPhos').checked = ( $('#suggested_DynSTYPhosEnabled').val( '1'));

	epsilon.showHideSections('show', '3,4,5');
}

</script>

