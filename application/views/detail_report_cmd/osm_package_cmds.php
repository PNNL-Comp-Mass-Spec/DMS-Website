<div style='padding:5px 0px 5px 5px;'>
<a href='<?= site_url() . "osm_package_files/report/".$id ?>''>Included package files...</a>
</div>
<div style="height:1em;"></div>

<? $chimg = base_url()."images/chooser.png"; ?>

<div style='padding:5px 0px 5px 5px;'>
<?= general_visibility_control('Add Items', 'item_section', '') ?>
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
		<td><textarea id='entry_item_list' name='itemList' cols='70' rows='13' onChange='epsilon.convertList("entry_item_list", ",")'></textarea></td>
		<td>
		<div class='chsr'>Sample Submissions... <a href="javascript:packages.callChooserSetType('Sample_Submissions', 'helper_sample_submissions_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Sample Prep Requests.. <a href="javascript:packages.callChooserSetType('Sample_Prep_Requests', 'helper_sample_prep_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Material Containers.... <a href="javascript:packages.callChooserSetType('Material_Containers', 'helper_material_container_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>HPLC Runs... <a href="javascript:packages.callChooserSetType('HPLC_Runs', 'helper_prep_lc_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Experiment Groups... <a href="javascript:packages.callChooserSetType('Experiment_Groups', 'helper_experiment_group_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Experiments... <a href="javascript:packages.callChooserSetType('Experiments', 'helper_experiment_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Requested Runs... <a href="javascript:packages.callChooserSetType('Requested_Runs', 'helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Datasets... <a href="javascript:packages.callChooserSetType('Datasets', 'helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>Biomaterial... <a href="javascript:packages.callChooserSetType('Biomaterial', 'helper_cell_culture/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'><a href="javascript:packages.callSuggestionSetType('Datasets', 'datasets_from_completed_requested_runs')">Datasets</a> (from requested runs)...</div>
		<div class='chsr'><a href="javascript:packages.callSuggestionSetType('Campaigns', 'campaign_from_exp_group_members')">Campaigns</a> (from exp. groups members)...</div>
		</td>
		</tr>
		<tr><td>Comment</td></tr>
		<tr><td><textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea></td></tr>
	</table>
		<div style='margin-top:4px;'>
		<input class='button lst_cmd_btn' type='button' value='Add items to package' onclick='packages.updateOSMPackageItems_1("<?= $id ?>", "entry_form", "add")' />
		<input class='button lst_cmd_btn' type='button' value='Delete items from package' onclick='packages.updateOSMPackageItems_1("<?= $id ?>", "entry_form", "delete")' />
		</div>
	</form>
	
</div>
<div style="height:1em;"></div>

<script src="<?= base_url().'javascript/packages.js' ?>"></script>

