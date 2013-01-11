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
				list = lambda.getCkbxList('ckbx');
				if (list == '') {
					alert('You must select items.');
					return;
				}
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			var url =  gamma.pageContext.ops_url;
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
				list = lambda.getCkbxList('ckbx');
				if (list == '') {
					alert('You must select items.');
					return;
				}
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			if(mode=='set_membership_enabled') mode = $F(p1);
			var url =  gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.newValue = (p2!='')?$F(p2):'';
			p.processorGroupID = $('#pf_groupid').val();
			if(p.processorGroupID == '') {alert('No group ID in primary filter'); return;}
			p.processorNameList = list;
			lambda.submitOperation(url, p);
		}
	},
	data_package_job_coverage: {
		op: function(mode){
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
			var url =  gamma.pageContext.site_url + "data_package_items/exec/";
			var p = {};
			p.command = mode;
			p.paramListXML = list;
			lambda.submitOperation(url, p);
		},
		getDatasetInfo: function (mode) {
			var id = $('#pf_data_package_id').val();
			var tool = $('#tool_name').val();
			if(id == '') {alert('data_package_id filter not set'); return;}
			var url =  gamma.pageContext.site_url + 'data_package/ag/' + id + '/' + tool + '/' + mode;
			$('#dataset_dump_field').html('');
			gamma.loadContainer(url, {}, 'dataset_dump_field');
		}
	},
	dataset_disposition: {
		op: function(mode) {
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select requests.'); 
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			var url =  gamma.pageContext.ops_url;
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
			$('#instrument_group_fld').setValue(obj["Instrument Group"]);
			$('#dataset_type_fld').setValue(obj["Dataset Type"]);
			$('#usage_fld').setValue(obj["Usage for This Group"]);
		},
		op: function(mode) {
			if ( !confirm("Are you sure that you want to update the database?") ) return;
			url = gamma.pageContext.ops_url;
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
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select requests.'); 
				return;
			}
			if (!confirm("Are you sure that you want to update the database?")) return;
			url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.containerList = list;
			p.newValue = (val)?$F(val):'';
			p.comment = $('#comment_fld').val();
			lambda.submitOperation(url, p);
		}
	},
	material_move_items: {
		op: function(mode, itemType, val) {
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select items.'); 
				return;
			}
			if(list.length > 4096) {
				alert('You have selected more items than system can handle at one time.  Please select fewer items and try again.');
				return;
			}
			if (!confirm("Are you sure that you want to update the database?")) return;
			url = gamma.pageContext.ops_url;
			var p = {};
			p.command = mode;
			p.itemType = itemType;
			p.itemList = list;
			p.newValue = (val)?$F(val):'';
			p.comment = $('#comment_fld').val();
			lambda.submitOperation(url, p);
		}
	},
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
			var url =  gamma.pageContext.site_url + 'osm_package_items/operation/';
			$('#paramListXML').val(list);
			$('#entry_cmd_mode').val(mode);
			var p = $('#operation_form').serialize();
			lambda.submitOperation(url, p);
		}
	},
	sample_prep_request_assignment: {
		op: function(mode, value) {
			var list = lambda.getCkbxList('ckbx');
			if(list=='') {
				alert('You must select requests.'); 
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") )
				return;
			url =  gamma.pageContext.site_url + "sample_prep_request_assignment/operation";
			var p = {};
			p.command = mode;
			p.newValue = (value)?$('#' + value).val():'';
			p.reqIDList = list;
			lambda.submitOperation(url, p);
		}
	},
	dataset_ext_cmds: {
		transferData: function (perspective, dslist) {
	
			var commalist = $('#' + dslist).val();
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
	
			var url =  gamma.pageContext.site_url + "/data_transfer/" + perspective;
			var p = {};
			p.perspective = perspective;
			p.iDList = commalist + list;
			lambda.submitOperation(url, p);
		}
	}
} // lcmd
