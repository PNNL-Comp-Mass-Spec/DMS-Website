var packages = {
	callChooserSetType: function(item_type, chooserPage, delimiter, xref){
		$('#itemTypeSelector').val(item_type);
		var page = gamma.pageContext.site_url + chooserPage;
		epsilon.callChooser('entry_item_list', page, delimiter, xref)
	},
	updateDataPackageItems: function(id, form_id, mode) {
		if ( !confirm("Are you sure that you want to " + mode + " the items in the list?") ) return;
		var url = gamma.pageContext.site_url + "data_package/operation/";

		var removeParents = 0;
		if (document.getElementById('removeParentsCheckbox').checked)
			removeParents=1;

		$('#entry_cmd_mode').val(mode);
		$('#removeParents').val(removeParents);
		
		// Call stored procedure UpdateDataPackageItems
		// gamma.doOperation is defined in dms2.js
		gamma.doOperation(url, form_id, 'entry_update_status', function(data, container) {
			delta.processResults(data, container);
		});
	},
	updateOSMPackage: function(id, mode) {
		if ( !confirm("Are you sure that you want to " + mode + " this package?") ) return;
		var url = gamma.pageContext.site_url + "osm_package/call/";
		var lUrl = gamma.pageContext.site_url + "osm_package/report";
		var p = {osmPackageID: id, mode: mode};
		gamma.doOperation(url, p, 'entry_update_status', function(data, container) {
			var x = $.parseJSON(data);
			if(x.result == 0) {
				var s = "OSM Package was deleted. Go to <a href='"+ lUrl + "' >list report</a>";
				$('#osm_cmd_container').hide();
				$('#attachments_control_section').hide();
				$('.LRepExport').hide();
				container.html(s);			
				var overlay = gamma.makeElementOverlay("data_container", "It's dead, Jim...");	
				$('#overlay_label').fadeIn(900);
			} else {
				container.html(x.message);
			}
		});
	},
/* OMCS-977	
	revealOsmPackageCreateSection: function() {
		var iframe = document.getElementById('embedded_page');
		var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
		$('#hdrContainer').hide();
		gamma.toggleVisibility('package_entry_section',  0.5 ); 
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
		$('#entry_cmd_mode').val(mode);
		gamma.doOperation(url, form_id, 'entry_update_status', function(data, container) {
			delta.processResults(data, container);
		});
	},
	updateOSMPackageItems_2: function(form_id, mode) {
		if ( !confirm("Are you sure that you want to " + mode + " this entity to the OSM package?") ) return;
		var url = gamma.pageContext.site_url + "osm_package/operation/";
		var id = gamma.pageContext.Id;
		$('#entry_cmd_mode').val(mode);
		$('#itemTypeSelector').val(this.codeMap[gamma.pageContext.my_tag]);
		$('#entry_item_list').val(id);
		gamma.doOperation(url, form_id, 'entry_update_status', function(data, container) {
			delta.processResults(data, container);
		});
	},
	*/	
	// This is called from "data_package_items/report" when the user clicks "Delete from Package" or "Update comment"
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
	
		var removeParents = 0;
		if (document.getElementById('removeParentsCheckbox').checked)
			removeParents=1;

		var url =  gamma.pageContext.site_url + 'data_package_items/operation/';
		$('#paramListXML').val(list);
		$('#entry_cmd_mode').val(mode);
		$('#removeParents').val(removeParents);
		var p = $('#operation_form').serialize();
		// Call stored procedure UpdateDataPackageItemsXML
		// lambda.submitOperation is defined in dms2.js
		lambda.submitOperation(url, p, true);
	}
}