// specialized code for specific list report cmd pages
var lcmd = {
	analysis_job_processor_group_association: {
		op: function(mode) {
			var list = null;
			if (mode == 'add') {
				list = $('#add_list_fld').val();
				if (list == '') {
					alert('You must supply jobs to add.');
					return;
				}
			} else {
				// lambda.getCkbxList is in dms2.js
				list = lambda.getCkbxList('ckbx');
				if (list == '') {
					alert('You must select items.');
					return;
				}
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			// URL will point to the operations_sproc value defined in analysis_job_processor_group_association.db, typically UpdateAnalysisJobProcessorGroupAssociations
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.newValue = '';
			p.processorGroupID = $('#pf_groupid').val();
			if(p.processorGroupID == '') {alert('No group ID in primary filter'); return;}
			p.JobList = list;
			lambda.submitOperation(url, p);
		}
	},
	analysis_job_processor_group_membership: {
		op: function(mode, p1, p2) {
			var list = null;
			if (mode == 'add_processors') {
				list = $('#add_list_fld').val();
				if (list == '') {
					alert('You must supply processors to add.');
					return;
				}
			} else {
				// lambda.getCkbxList is in dms2.js
				list = lambda.getCkbxList('ckbx');
				if (list == '') {
					alert('You must select items.');
					return;
				}
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			if(mode=='set_membership_enabled') mode = $('#' + p1).val();
			// URL will point to the operations_sproc value defined in analysis_job_processor_group_association.db, typically UpdateAnalysisJobProcessorGroupMembership
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.newValue = (p2)?$('#' + p2).val():'';
			p.processorGroupID = $('#pf_groupid').val();
			if(p.processorGroupID == '') {alert('No group ID in primary filter'); return;}
			p.processorNameList = list;
			lambda.submitOperation(url, p);
		}
	},
	data_package_job_coverage: {
		op: function(mode){
			// lambda.getSelectedItemList is in dms2.js
			// The item names come from the value field of the selected checkboxes on the web page, for example:
			// <input type="checkbox" value="<item pkg="2900" type="Job" id="1511459"></item>" name="ckbx" class="lr_ckbx">
			var iList = lambda.getSelectedItemList();
			if (iList.length == 0) {
				alert('No items are selected');
				return;
			}
			var list = '';
			$.each(iList, function(idx, obj) {
				list += obj;
			});
			if ( !confirm("Are you sure that you want to update the database?") )
				return;

			var removeParents = 0;
			if (document.getElementById('removeParentsCheckbox').checked)
				removeParents=1;

			var url = gamma.pageContext.site_url + "data_package_items/exec/";

			console.log("Contacting " + url);
			console.log("with p.paramListXML = " + list);
			console.log("and p.removeParents = " + removeParents);

			var p = {};
			p.command = mode;
			p.paramListXML = list;
			p.removeParents = removeParents;

			// Call stored procedure UpdateDataPackageItemsXML
			// lambda.submitOperation is defined in dms2.js
			lambda.submitOperation(url, p, true);
		},
		getDatasetInfo: function (mode) {
			var id = $('#pf_data_package_id').val();
			var tool = $('#tool_name').val();
			if(id == '') {alert('data_package_id filter not set'); return;}
			var url = gamma.pageContext.site_url + 'data_package/ag/' + id + '/' + tool + '/' + mode;
			$('#dataset_dump_field').html('');
			gamma.loadContainer(url, {}, 'dataset_dump_field');
		}
	},
	dataset_disposition: {
		op: function(mode) {
			// lambda.getCkbxList is in dms2.js
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select datasets.');
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			// URL will point to the operations_sproc value defined in dataset_disposition.db, typically UpdateDatasetDispositions
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.datasetIDList = list;
			p.rating = $('#rating_fld').val();
			p.comment = $('#comment_fld').val();
			p.recycleRequest = $('#recycle_fld').val();
			lambda.submitOperation(url, p);
		}
	},
	instrument_allowed_dataset_type: {
		localRowAction: function (url, value, obj) {
			$('#instrument_group_fld').val(obj["Instrument Group"]);
			$('#dataset_type_fld').val(obj["Dataset Type"]);
			$('#usage_fld').val(obj["Usage for This Group"]);
		},
		op: function(mode) {
			if ( !confirm("Are you sure that you want to update the database?") ) return;
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.InstrumentGroup = $('#instrument_group_fld').val();
			p.DatasetType = $('#dataset_type_fld').val();
			p.Comment = $('#usage_fld').val();
			lambda.submitOperation(url, p);
		}
	},
	material_move_container: {
		op: function(mode, val) {
			// lambda.getCkbxList is in dms2.js
			// Valid options for mode:
			//   retire_container
			//   retire_container_and_contents
			//   unretire_container
			//   move_container
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select containers.');
				return;
			}
			if (!confirm("Are you sure that you want to update the database?")) return;
			// URL will point to the operations_sproc value defined in material_container.db, typically DoMaterialContainerOperation
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.containerList = list;
			p.newValue = (val)?$('#' + val).val():'';
			p.comment = $('#comment_fld').val();
			lambda.submitOperation(url, p);
		}
	},
	material_move_items: {
		op: function(mode, itemType, val) {
			// lambda.getCkbxList is in dms2.js
			// Contents of list will be of the form E:8432,E:8435,R:170
			// where the item names come from the value text associated with each checkbox
			// Checkbox names come from column #I_ID in view V_Material_Items_List_Report
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select items.');
				return;
			}
			if(list.length > 4096) {
				// Stored procedure UpdateMaterialItems has argument @itemList varchar(4096)
				alert('You have selected more items than the system can handle at one time.  Please select fewer items and try again.');
				return;
			}
			if (!confirm("Are you sure that you want to update the database?")) return;
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.itemType = itemType;
			p.itemList = list;
			p.newValue = (val)?$('#' + val).val():'';
			p.comment = $('#comment_fld').val();
			lambda.submitOperation(url, p);
		}
	},
/*	OMCS-977
	osm_package: {
		op: function(mode) {
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
			// URL will point to the operations_sproc value defined in osm_package_items.db, typically UpdateOSMPackageItemsXML
			var url = gamma.pageContext.site_url + 'osm_package_items/operation/';
			$('#paramListXML').val(list);
			$('#entry_cmd_mode').val(mode);
			var p = $('#operation_form').serialize();
			lambda.submitOperation(url, p);
		}
	},
*/
	requested_run_admin: {
		// mode is the update mode, to be passed to the operation stored procedure
		// value is the new value
		op: function(mode, value) {
			// lambda.getCkbxList is in dms2.js
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select requested runs.');
				return;
			}
			if(list.length > 64000) {
				// Stored procedure UpdateRequestedRunAssignments has argument @reqRunIDList varchar(max)
				// We can thus push in more than 8000 characters; the 64000 limit is an arbitrary limit
				alert('You have selected more items than the system can handle at one time.  Please select fewer items and try again.');
				return;
			}
			if (!confirm("Are you sure that you want to update the database?")) return;
			// URL will point to the operations_sproc value defined in requested_run.db: UpdateRequestedRunAssignments
			// See: http://dmsdev.pnl.gov/config_db/edit_table/requested_run.db/general_params
			var url = gamma.pageContext.ops_url;
			var p = {};
			
			// This is auto-mapped to the @mode parameter of the stored procedure
			p.command = mode;
			
			// The following two form fields are defined at https://dmsdev.pnl.gov/config_db/edit_table/requested_run.db/sproc_args
			// Param maps to the @newValue parameter
			// ID maps to the @reqRunIDList parameter
			p.Param = (value)?$('#' + value).val():'';
			p.ID = list;
			lambda.submitOperation(url, p);
		}
	},
	sample_prep_request_assignment: {
		op: function(mode, value) {
			// lambda.getCkbxList is in dms2.js
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select prep requests.');
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			// URL will point to the operations_sproc value defined in sample_prep_request_assignment.db, typically UpdateSampleRequestAssignments
			var url = gamma.pageContext.site_url + "sample_prep_request_assignment/operation";
			var p = {};
			p.command = mode;
			p.newValue = (value)?$('#' + value).val():'';
			p.reqIDList = list;
			lambda.submitOperation(url, p);
		}
	},
	mc_enable_control_by_manager: {
		op: function(mode, newValFld) {
			// lambda.getCkbxList is in dms2.js
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select at least one manager.');
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.newValue =  $('#' + newValFld).val();
			p.paramName = mode;
			p.managerIDList = list;
			lambda.submitOperation(url, p);
		}
	},
	mc_enable_control_by_manager_type: {
		op: function(mode, newValFld) {
			// lambda.getCkbxList is in dms2.js
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select at least one manager type.');
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.newValue =  $('#' + newValFld).val();
			p.paramName = mode;
			p.managerTypeIDList = list;
			lambda.submitOperation(url, p);
		}
	},
	dataset_ext_cmds: {
		transferData: function (perspective, dslist) {

			var commalist = $('#' + dslist).val();

			// lambda.getCkbxList is in dms2.js
			var list = lambda.getCkbxList('ckbx' );
			if(list=='' && commalist=='') {
				alert('You must select at least 1 dataset or enter 1 dataset id.');
				return;
			}
			//Add or Remove trailing comma
			if(list!='') {
				if (commalist.charAt(commalist.length-1) != ',' && commalist != '')
				{
					commalist = commalist + ',';
				}
			}
			else if (commalist.charAt(commalist.length-1) == ',' )
			{
				commalist = commalist.substring(0, commalist.length-1)
			}

			if ( !confirm("Are you sure that you want to transfer the selected data?") )
				return;

			var url = gamma.pageContext.site_url + "/data_transfer/" + perspective;
			var p = {};
			p.perspective = perspective;
			p.iDList = commalist + list;
			lambda.submitOperation(url, p);
		}
	},
	lc_cart_request_loading: {
		getEditFieldsObjList: function () {
			// go through editable fields and build array of objects
			// where each object references the fields for
			// one block
			var rlist = [];
			$('.Cart').each(function(idx, cartField) {
				var obj = {};
				obj.req = cartField.name;
				obj.cart = cartField.value;
				obj.col = $('#Col_' + obj.req).val();
				obj.cartConfig = $('#Cart_Config_' + obj.req).val();
				rlist.push(obj);
			});
			return rlist;
		},
		saveChangesToDatabase: function () {
			if ( !confirm("Are you sure that you want to update the database?") ) return;
			var rlist = this.getEditFieldsObjList();
			var mapP2A = [{p:'req', a:'rq'}, {p:'cart', a:'ct'}, {p:'col', a:'co'}, {p:'cartConfig', a:'cg'}];
			var xml = gamma.getXmlElementsFromObjectArray(rlist, 'r', mapP2A);
			var url = gamma.pageContext.ops_url;
			var p = {};
			p.command = 'update';
			p.cartAssignmentList = xml;
			lambda.submitOperation(url, p);
		},
		setCartName: function () {
			var iList = lambda.getSelectedItemList();
			if (iList.length == 0) {
				alert('No items are selected');
				return;
			}
			var cart = $('#cart_name_input').val();
			if(cart == '') {
				alert('Cart name cannot be blank');
				return;
			}
			$.each(iList, function(idx, req) {
				$('#Cart_' + req).val(cart);
			});
		},
		setCartConfigName: function () {
			var iList = lambda.getSelectedItemList();
			if (iList.length == 0) {
				alert('No items are selected');
				return;
			}
			var cartConfig = $('#cart_config_input').val();
			if(cartConfig == '') {
				alert('Cart config name cannot be blank');
				return;
			}
			$.each(iList, function(idx, req) {
				$('#Cart_Config_' + req).val(cartConfig);
			});
		},
		setCartCol: function () {
			var iList = lambda.getSelectedItemList();
			if (iList.length == 0) {
				alert('No items are selected');
				return;
			}
			var col = $('#col_input_setting').val();
			if(col < 1 || col > 8) {
				alert('Column out of range');
				return;
			}
			$.each(iList, function(idx, req) {
				$('#Col_' + req).val(col);
			});
		}
	},
	dataset_instrument_runtime: {
		// get data rows via an AJAX call for list report
		// using all the current search filters, and build graph from it
		download_to_graph: function() {
			var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/export_param/json'
			var p = $('#entry_form').serialize();
			gamma.getObjectFromJSON(url, p, 'graph_container', function(rows) {
					lcmd.dataset_instrument_runtime.draw_graph(rows);
			});
		},
		draw_graph: function(rows) {
			var caption = "Dataset Acquisition/Interval Time For " + $('#instrumentName').val() + " From " + $('#startDate').val() + " To " + $('#endDate').val()
			$('#caption_container').html(caption);
			var dataSeriesSet = lcmd.dataset_instrument_runtime.make_data_series_from_column(rows, "Duration") ;
			var graphFormatting = lcmd.dataset_instrument_runtime.set_graph_format();
			$('#graph_container').show();
			var f = $.plot($('#graph_container'), dataSeriesSet, graphFormatting);
		},
		make_data_series_from_column: function(rows, colName) {
			var intervalSeries = [];
			var acquistionSeries = [];
			var index = 0;
			$.each(rows, function(idx, obj) {
					var val = obj[colName];
					if(obj["Seq"] > 0) {
						if(obj["Dataset"] == "Interval") {
							var item = [];
							item.push(index++);
							item.push(val);
							intervalSeries.push(item);
						} else {
							var item = [];
							item.push(index++);
							item.push(val);
							acquistionSeries.push(item);
						}
					}
				}
			);
			return [
				{ label: "Acquisition Time", color: '#0000ff', bars: { show: true, barWidth: 0.5 }, data: acquistionSeries },
				{ label: "Interval Time", color: '#ff0000', bars: { show: true, barWidth: 0.5 }, data: intervalSeries }
			];
		},
		set_graph_format: function() {
			return {
				yaxis: { min: 0 },
        // See https://github.com/flot/flot/issues/1708 for current issue with legend background
				legend: { show: true, backgroundColor: '#ffffff' }
			};
		}
	}
} // lcmd

$(document).ready(function () {
	$('.sel_chooser').chosen({search_contains: true});
	if(gamma.pageContext.my_tag == 'requested_run_factors') {
		gamma.currentChooser.callBack = tau.requested_run_factors.setItemTypeField;
	}
});

