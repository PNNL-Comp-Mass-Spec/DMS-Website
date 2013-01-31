
<? $chimg = base_url()."images/chooser.png"; ?>

<div class='LRcmd_panel'>
<span class='LRcmd_cartouche' >Add Items to Data Package <a title="Show or hide the item management section" href="javascript:void(0)" onclick="gamma.sectionToggle('add_items_section', 0.25, this )"><span class="expando_section ui-icon ui-icon-circle-plus"></span></a></span>
<span class='LRcmd_cartouche' >Refresh <a href='javascript:void(0)' onclick='delta.updateMyData()'><span class="expando_section ui-icon ui-icon-refresh"></span></a></span>
<span class='LRcmd_cartouche' >Go to job coverage page <a href="<?= site_url() ?>data_package_job_coverage/report/<?= $id ?>"><span class="expando_section ui-icon ui-icon-circle-arrow-e"></span></a></span>
<span class='LRcmd_cartouche' >Go to dataset coverage page <a href="<?= site_url() ?>data_package_dataset_job_coverage/param/<?= $id ?>"><span class="expando_section ui-icon ui-icon-circle-arrow-e"></span></a></span>
<span id='detail_reload_status'></span>
</div>



<div id='add_items_section' class='LRcmd_section'>
<hr>
<form id='entry_form'>
	<input type="hidden" name="command" value="" id="entry_cmd_mode"/>
	<input type='hidden' name='packageID' value='<?= $id ?>' /> 
	<table>
		<tr>
		<td>Select Item type:
			<select name="itemType" id='itemTypeSelector'>
			<option value="analysis_jobs">Analysis Jobs</option>
			<option value="datasets">Datasets</option>
			<option value="experiments">Experiments</option>
			<option value="biomaterial">Biomaterial</option>
			</select>
		</td>
		</tr>
		<tr><td>Item List</td></tr>
		<tr>
		<td><textarea id='entry_item_list' name='itemList' cols='70' rows='6' onChange='epsilon.convertList("entry_item_list", ",")'></textarea></td>
		<td>
		<div class='chsr'>choose biomaterial... <a href="javascript:packages.callChooserSetType('biomaterial', 'helper_cell_culture/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>choose experiments... <a href="javascript:packages.callChooserSetType('experiments', 'helper_experiment_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>choose datasets... <a href="javascript:packages.callChooserSetType('datasets', 'helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>choose analysis jobs... <a href="javascript:packages.callChooserSetType('analysis_jobs', 'helper_analysis_job_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		</td>
		</tr>
		<tr><td>Comment</td></tr>
		<tr><td><textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea></td></tr>
	</table>

	<div style='margin-top:4px;'>
	<input class='button lst_cmd_btn' type='button' value='Add items to package' onclick='packages.updateDataPackageItems("<?= $id ?>", "entry_form", "add")' />
	<input class='button lst_cmd_btn' type='button' value='Delete items from package' onclick='packages.updateDataPackageItems("<?= $id ?>", "entry_form", "delete")' />
	</div>

</form>

<div id='entry_update_status'></div>
</div>

<script src="<?= base_url().'javascript/packages.js' ?>"></script>

