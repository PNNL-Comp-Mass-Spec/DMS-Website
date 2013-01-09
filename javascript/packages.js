var packages = {
	callChooserSetType: function(item_type, chooserPage, delimiter, xref){
		$('#itemTypeSelector').val(item_type);
		var page = gamma.pageContext.site_url + chooserPage;
		epsilon.callChooser('entry_item_list', page, delimiter, xref)
	},
	updateDataPackageItems: function(id, form_id, mode) {
		if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
		var url = gamma.pageContext.site_url + "data_package/operation/";
		var message_container = $('#entry_update_status');
		$('#entry_cmd_mode').val(mode);
		gamma.doOperation(url, form_id, 'message_container', function(data, container) {
			delta.processResults(data, container);
		});
	},
	revealOsmPackageCreateSection: function() {
		var iframe = document.getElementById('embedded_page');
		var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
		$('#hdrContainer').hide();
		gamma.sectionToggle('package_entry_section',  0.5 ); 
		return false;
	},
	callSuggestionSetType: function(item_type, mode) {
		$('#entry_item_list').val('');
		var url = gamma.pageContext.site_url + "osm_package/suggested_items/" + gamma.pageContext.Id + "/" + mode;
		gamma.doOperation(url, false, 'item_section', function(data) {
				$('#itemTypeSelector').val(item_type);
				$('#entry_item_list').val(data);
		});
	},
	callOSMChooser: function(){
		var page = gamma.pageContext.site_url + "helper_osm_package/report";
		epsilon.callChooser('packageID', page,  ',', '')
	},
	goToPage: function() {
		var url = gamma.pageContext.site_url + "osm_package_items/report/" + this.codeMap[gamma.pageContext.my_tag] + "/" + $('#packageID').val();
		window.location.href = url;
	},
	codeMap:{
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
	},
	updateOSMPackageItems_1: function(id, form_id, mode) {
		if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
		var url = gamma.pageContext.site_url + "osm_package/operation/";
		var message_container = $('#entry_update_status');
		$('#entry_cmd_mode').val(mode);
		gamma.doOperation(url, form_id, 'message_container', function(data, container) {
			delta.processResults(data, container);
		});
	},
	updateOSMPackageItems_2: function(form_id, mode) {
		if ( !confirm("Are you sure that you want to " + mode + " this entity to the OSM package?") ) return;
		var url = gamma.pageContext.site_url + "osm_package/operation/";
		var message_container = $('#entry_update_status');
		var id = gamma.pageContext.Id;
		$('#entry_cmd_mode').val(mode);
		$('#itemTypeSelector').val(codeMap[gamma.pageContext.my_tag]);
		$('#entry_item_list').val(id);
		gamma.doOperation(url, form_id, 'message_container', function(data, container) {
			delta.processResults(data, container);
		});
	},
	performOperation: function (mode) {
		var list = '';
		var rows = document.getElementsByName('ckbx');
		for (var i = 0; i < rows.length; i++) {
			if ( rows[i].checked )
				list  += rows[i].value;
		}
		if(list=='') {
			alert('You must select items'); 
			return;
		}
		if ( !confirm("Are you sure that you want to update the database?") )
			return;
	
		var url =  gamma.pageContext.site_url + 'data_package_items/operation/';
		$('#paramListXML').val(list);
		$('#entry_cmd_mode').val(mode);
		var p = $('#operation_form').serialize();
		lambda.submitOperation(url, p);
	}	
}