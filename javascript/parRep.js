var parRep = {	
	// update the column and sorting filters
	filter_update_action: {
		run:function(){
			if(!$('#sorting_filter_table')) {
				lambda.updateContainer('param_filter', 'entry_form', 'search_filter_container', gamma.no_action);
				$('#search_controls_container').show();
			}
		}
	},
	//copy the contents of the upper paging display to the lower one
	paging_cleanup_action: {
		run:function() {
			parRep.filter_update_action.run();
			$('#paging_container_lower').html($('#paging_container_upper').html());
		}
	},
	//update the paging display sections, or hide them if no data rows
	paging_update_action: {
		run:function() {
			if($('#data_message')) {
				$('#paging_container_upper').hide();
				$('#paging_container_lower').hide();
			} else {
				$('#paging_container_upper').show();
				$('#paging_container_lower').show();
				lambda.updateContainer('param_paging', 'entry_form', 'paging_container_upper', parRep.paging_cleanup_action);
			} 	
		}
	},
	//go get some data rows
	data_update_action: {
		run:function(){
			lambda.updateContainer('param_data', 'entry_form', 'data_container', parRep.paging_update_action);
		}
	},
	//start the data update chain for the page
	updateMyData: function(loading) {
		if(loading && loading == 'reset' && $('#qf_first_row')) $('#qf_first_row').val(1);
		parRep.data_update_action.run();
	}
} // parRep	
	
//after the page loads, set things in motion to populate it
$(document).ready(function () { 
	 	lambda.reloadListReportData = function() { parRep.updateMyData('autoload');}
		$('#data_container').html('Data will be displayed after you click the "Search" button.');
});
