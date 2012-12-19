<div style='padding:5px 0px 5px 5px;'>
<a href='<?= site_url() . "osm_package_files/report/".$id ?>''>Included package files...</a>
</div>
<div style="height:1em;"></div>

<style type="text/css">
.block_header {
	min-width:50em;
	padding-top:10px;
	padding-bottom:10px;
}
.block_title {
	font-size:125%;
	font-weight:bold;
	padding-right:10px;
	width:18em;
}
.chsr {
	padding-bottom:5px;
}
</style>

<? $chimg = base_url()."images/chooser.png"; ?>

<div style='padding:5px 0px 5px 5px;'>
<a href="#" onclick="Effect.toggle('item_section', 'appear', { duration: 0.5 }); return false;">Add Items...</a>
</div>

<div id='item_section' style='display:none; width:75em;margin:5px 0 0 0;padding:0px 5px 5px 5px;border:2px solid #AAA;' >

<div class='block_header'>
<h2 style="text-align:center;">Add Items to OSM Package</h2>
</div>
	<div id='entry_update_status'></div>
	<form id='entry_form'>
		<input type="hidden" name="command" value="" id="entry_cmd_mode"/>
		<input type='hidden' name='packageID' value='<?= $id ?>' /> 
	<table>
		<tr>
		<td>Select Item type:
			<select name="itemType" id='itemTypeSelector'>
			<option value="Sample_Submissions">Sample Submissions</option>
			<option value="Sample_Prep_Requests">Sample Prep Requests</option>
			<option value="Material_Containers">Material Containers</option>
			<option value="HPLC_Runs">HPLC Runs</option>
			<option value="Experiment_Groups">Experiment Groups</option>
			<option value="Experiments">Experiments</option>
			<option value="Requested_Runs">Requested Runs</option>
			<option value="Datasets">Datasets</option>
			<option value="Campaigns">Campaigns</option>
			<option value="Biomaterial">Biomaterial</option>
			</select>
		</td>
		<td>Choose Items:</td>
		</tr>
		<tr>
		<td><textarea id='entry_item_list' name='itemList' cols='70' rows='13' onChange='convertList("entry_item_list", ",")'></textarea></td>
		<td>
		<div class='chsr'>Sample Submissions... <a href="javascript:callChooserSetType('Sample_Submissions', 'helper_sample_submissions_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Sample Prep Requests.. <a href="javascript:callChooserSetType('Sample_Prep_Requests', 'helper_sample_prep_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Material Containers.... <a href="javascript:callChooserSetType('Material_Containers', 'helper_material_container_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>HPLC Runs... <a href="javascript:callChooserSetType('HPLC_Runs', 'helper_prep_lc_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Experiment Groups... <a href="javascript:callChooserSetType('Experiment_Groups', 'helper_experiment_group_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Experiments... <a href="javascript:callChooserSetType('Experiments', 'helper_experiment_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Requested Runs... <a href="javascript:callChooserSetType('Requested_Runs', 'helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Datasets... <a href="javascript:callChooserSetType('Datasets', 'helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Biomaterial... <a href="javascript:callChooserSetType('Biomaterial', 'helper_cell_culture/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'><a href="javascript:callSuggestionSetType('Datasets', 'datasets_from_completed_requested_runs')">Datasets</a> (from requested runs)...</div>
		<div class='chsr'><a href="javascript:callSuggestionSetType('Campaigns', 'campaign_from_exp_group_members')">Campaigns</a> (from exp. groups members)...</div>
		</td>
		</tr>
		<tr><td>Comment</td></tr>
		<tr><td><textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea></td></tr>
	</table>
		<div style='margin-top:4px;'>
		<input class='lst_cmd_btn' type='button' value='Add items to package' onclick='updateOSMPackageItems("<?= $id ?>", "entry_form", "add")' />
		<input class='lst_cmd_btn' type='button' value='Delete items from package' onclick='updateOSMPackageItems("<?= $id ?>", "entry_form", "delete")' />
		</div>
	</form>
	
</div>
<div style="height:1em;"></div>

<script type="text/javascript">
	
function callChooserSetType(item_type, chooserPage, delimiter, xref){
	$('#itemTypeSelector').val(item_type);
	var page = "<?= site_url() ?>" + chooserPage;
	callChooser('entry_item_list', page, delimiter, xref)
}

function callSuggestionSetType(item_type, mode) {
	$('#entry_item_list').val('');
	var url = globalAJAX.site_url + "osm_package/suggested_items/<?= $id ?>/" + mode;
	var p = {};
	$.post(url, p, function (data) {
			$('#itemTypeSelector').val(item_type);
			$('#entry_item_list').val(data);
		}
	);
}

function updateOSMPackageItems(id, form_id, mode) {
	if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
	var url = globalAJAX.site_url + "osm_package/operation/";
	var message_container = $('#entry_update_status');
	$('#entry_cmd_mode').val(mode);
	var p = $('#' + form_id).serialize();
	message_container.html(globalAJAX.progress_message);
	$.post(url, p, function (data) {
			if(data.indexOf('html failed') > -1) {
				message_container.html(data);
			} else {
				message_container.html('Operation was successful');
				updateMyData();
			}
		}
	);
}

</script>
