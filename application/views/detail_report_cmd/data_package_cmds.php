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

<div class="LRepChooser" >
|<span><a href='javascript:void(0)' onclick='updateMyData()'>Reload details</a></span>
|<span><a href="<?= site_url() ?>data_package_job_coverage/report/<?= $id ?>">Job Coverage</a></span>
|<span><a href="<?= site_url() ?>data_package_dataset_job_coverage/param/<?= $id ?>">Dataset Coverage</a></span>|
<span id='detail_reload_status'></span>
</div>

<div class='block_header'>
<span class='block_title' >Add Items to Data Package</span>
</div>
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
		<td><textarea id='entry_item_list' name='itemList' cols='70' rows='6' onChange='convertList("entry_item_list", ",")'></textarea></td>
		<td>
		<div class='chsr'>choose biomaterial... <a href="javascript:callChooserSetType('biomaterial', 'helper_cell_culture/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>choose experiments... <a href="javascript:callChooserSetType('experiments', 'helper_experiment_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>choose datasets... <a href="javascript:callChooserSetType('datasets', 'helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		<div class='chsr'>choose analysis jobs... <a href="javascript:callChooserSetType('analysis_jobs', 'helper_analysis_job_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a></div>
		</td>
		</tr>
		<tr><td>Comment</td></tr>
		<tr><td><textarea id='entry_comment' name='comment' cols='70' rows='2'></textarea></td></tr>
	</table>
		<div style='margin-top:4px;'>
		<input class='lst_cmd_btn' type='button' value='Add items to package' onclick='updateDataPackageItems("<?= $id ?>", "entry_form", "add")' />
		<input class='lst_cmd_btn' type='button' value='Delete items from package' onclick='updateDataPackageItems("<?= $id ?>", "entry_form", "delete")' />
		</div>
	</form>
	<div id='entry_update_status'></div>

<script type="text/javascript">
	
function callChooserSetType(item_type, chooserPage, delimiter, xref){
	$('itemTypeSelector').value = item_type;
	var page = "<?= site_url() ?>" + chooserPage;
	callChooser('entry_item_list', page, delimiter, xref)
}

function updateDataPackageItems(id, form_id, mode) {
	if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
	var url = globalAJAX.site_url + "data_package/operation/";
	var message_container = $('entry_update_status');
	$('entry_cmd_mode').value = mode;
	var p = $(form_id).serialize(true);
	message_container.update(globalAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			var rt = transport.responseText;
			if(rt.indexOf('Update failed') > -1) {
				message_container.update(transport.responseText);
			} else {
				message_container.update('Operation was successful');
				updateMyData();
			}
		}});
}

</script>
