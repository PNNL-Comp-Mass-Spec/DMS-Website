
<script type="text/javascript">
function performOperation(mode){
	var iList = kappa.getSelectedItemList();
	if (iList.size() == 0) {
		alert('No items are selected');
		return;
	}
	var list = '';
	iList.each(function(idx, obj) {
		list += obj;
	});
	if ( !confirm("Are you sure that you want to update the database?") )
		return;

	var url =  gamma.pageContext.site_url + "data_package_items/exec/";
	var p = {};
	p.command = mode;
	p.paramListXML = list;
	theta.submitOperation(url, p);
}
function getDatasetInfo(mode) {
	var id = $('#pf_data_package_id').val();
	var tool = $('#tool_name').val();
	if(id == '') {alert('data_package_id filter not set'); return;}
	var url =  gamma.pageContext.site_url + 'data_package/ag/' + id + '/' + tool + '/' + mode;
	$('#dataset_dump_field').html('');
	gamma.loadContainer(url, {}, 'dataset_dump_field');
}
</script>


<div class="LRCmds">


<form name="DBG" action="">

<hr>
<a href="#" onclick="gamma.sectionToggle('membership_section', 0.5)">Membership commands...</a>
<div id="membership_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Add Jobs" onClick='performOperation("add")' id="btn_a" title=""  /> 
Add selected jobs to package that are not already in package.
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Remove Jobs" onClick='performOperation("delete")' id="btn_r" title=""  />
Remove selected jobs from package.
</div>
<div style='display:none'>
<input class='lst_cmd_btn' type="button" value="Test" onClick='performOperation("test")' id="btn_t" title=""  />
Test
</div></div>


<hr>
<a href="#" onclick="gamma.sectionToggle('dump_section', 0.5)">Dataset coverage...</a>
<div id="dump_section" style="display:none;">
<div>
Tool to search for
<input id='tool_name' value='MSXML_Gen' size='24'></input> 
<span><?= $this->choosers->get_chooser('tool_name', 'analysisToolPickList')?></span>
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Datasets Without Jobs" onClick='getDatasetInfo("NoDMSJobs")' title=""  /> 
Get list of data package datasets with <span style="font-weight:bold;">no jobs in DMS</span> for tool 
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Datasets Without Jobs" onClick='getDatasetInfo("NoPackageJobs")' title=""  /> 
Get list of data package datasets with <span style="font-weight:bold;">no jobs in data package</span> for tool 
</div>
<div>
<p>Datasets:</p>
<textarea id='dataset_dump_field' rows='12' cols='90'></textarea>
</div>
<a href="<?= site_url() ?>analysis_job_request/create" >Create DMS jobs...</a>
</div>

<hr>
<!-- begin debug 

<a href="#" onclick="gamma.sectionToggle('debug_section', 0.5)">Debug...</a>
<div id="debug_section" style="display:none;">
<input class='lst_cmd_btn' type="button" value="Test" onClick='test()' title=""  />
<p>Debug output:</p>
<textarea id='zed' rows='12' cols='90'></textarea>
</div>

<script type="text/javascript">
function notYet() {
	alert('This function is not implemented yet');
}
function logg(s, clear) {
	if(typeof clear != "undefined")$('#zed').val('');
	$('#zed').val() += s + "\n";
}
function test() {
	var iList = kappa.getSelectedItemList();
	logg(iList);
}
</script>
-->
<!-- end debug -->

</form>
</div>
