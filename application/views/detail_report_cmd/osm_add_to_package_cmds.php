<div style='padding:5px 0px 5px 5px;'>
<a href="#" onclick="gamma.sectionToggle('item_section', 0.5)">Add to OSM Package...</a>
</div>

<div id='item_section' style='display:none; width:100em;margin:5px 0 0 0;' >
<div style='padding:5px 5px 5px 5px;border:2px solid #AAA;'>
<div id='entry_update_status'></div>

<? $chimg = base_url()."images/chooser.png"; ?>

<form id='entry_form'>
	<input type="hidden" name="command" id="entry_cmd_mode" value=""/>
	<input type='hidden' name='itemList' id='entry_item_list' value='' /> 
	<input type='hidden' name="itemType" id='itemTypeSelector' value='' />
	
	<div style='padding:0px 5px 5px 5px;' >
	Add this item to OSM package <input type='text' name='packageID' id='packageID' size='6'/>
	<span class='chsr'>Choose... <a href="javascript:callOSMChooser()"><img src='<?= $chimg ?>' border='0'></a></span>
	</div>	
	<div style='padding:0px 5px 5px 5px;' >Package Comment</div>
	<div style='padding:0px 5px 5px 5px;' ><textarea name='comment' id='entry_comment' cols='70' rows='2'></textarea></div>
	
	<div style='padding:0px 5px 5px 5px;' >
	<input class='lst_cmd_btn' type='button' value='Add' onclick='updateOSMPackageItems("entry_form", "add")' />
	<a href="javascript:goToPage()">Go to package item list report page...</a>
	</div>
</form>
</div>
</div>
<div style='height: 1em;'></div>

<script type="text/javascript">

function callOSMChooser(){
	var page = "<?= site_url() ?>helper_osm_package/report";
	epsilon.callChooser('packageID', page,  ',', '')
}

function goToPage() {
	var url = gamma.pageContext.site_url + "osm_package_items/report/" + codeMap[gamma.pageContext.my_tag] + "/" + $('#packageID').val();
	window.location.href = url;
}

var codeMap = {
   "campaign":"Campaigns",
   "cell_culture":"Biomaterial",
   "dataset":"Datasets",
   "experiment":"Experiments",
   "experiment_group":"Experiment_Groups",
   "prep_lc_run":"HPLC_Runs",
   "material_container":"Material_Containers",
   "requested_run":"Requested_Runs",
   "sample_prep_request":"Sample_Prep_Requests",
   "sample_submission":"Sample_Submissions"
};	

function updateOSMPackageItems(form_id, mode) {
	if ( !confirm("Are you sure that you want to " + mode + " this entity to the OSM package?") ) return;
	var url = gamma.pageContext.site_url + "osm_package/operation/";
	var message_container = $('#entry_update_status');
	var id = '<?= $id ?>';
	$('#entry_cmd_mode').val(mode);
	$('#itemTypeSelector').val(codeMap['<?= $this->my_tag ?>']);
	$('#entry_item_list').val(id);
	gamma.doOperation(url, form_id, 'message_container', function(data, container) {
		delta.processResults(data, container);
	});
}

</script>
