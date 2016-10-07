var theta = {
	getBlockingXMLFromList: function(flist) {
		var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'t'}, {p:'value', a:'v'}];
		// gamma.getXmlElementsFromObjectArray is defined in dms2.js
		return gamma.getXmlElementsFromObjectArray(flist, 'r', mapPropertiesToAttributes);
	},
	getFactorXMLFromList: function(flist) {
		var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
		return gamma.getXmlElementsFromObjectArray(flist, 'r', mapPropertiesToAttributes);
	},
	getListReportColumnList: function() {
		var col_list = [];
		$('.col_header').each(function(){
			col_list.push(gamma.trim($(this).html()));
		});
		return col_list;
	},
	getFactorFieldList: function(factor_cols) {
		var idlist = [];
		$('.lr_ckbx').each(function(idx, obj){
			idlist.push(obj.value);
		});
		var flist = [];
		$.each(factor_cols, function(idx, col){
			$.each(idlist, function(idx, id){
				var fldID = col.replace(' ', '_') + '_' + id;
				var val = $('#' + fldID).val();
				var obj = {};
				obj.id = id;
				obj.factor = col;
				obj.value = val;
				flist.push(obj);
			});
		});
		return flist;
	},
	makeObjectList: function(ilist, factor, value) { //private
		var flist = [];
		$.each(ilist, function(idx, id){
			var obj = {};
			obj.id = id;
			obj.factor = factor;
			obj.value = value;
			flist.push(obj);
		});
		return flist;
	},
	getFieldListFromParsedData: function(parsed_data, col_list) {
		// make array of id/factor/value objects,
		// one for each row of each column
		var flist = [];
		$.each(col_list, function(idx, factor){
			var idx = parsed_data.header.indexOf(factor);
			if(idx > -1) {
				$.each(parsed_data.data, function(ignore, row){
					var id = row[0];
					var value = row[idx] || '';
					var obj = {};
					obj.id = id;
					obj.factor = factor;
					obj.value = value;
					flist.push(obj);
				});				
			}
		});
		return flist;
	},
	applyFactorToDatabase: function(update) {
		var factor = $('#apply_factor_name').val();
		var value = $('#apply_factor_value').val();
		// lambda.getSelectedItemList is defined in dms2.js
		var ilist = lambda.getSelectedItemList();
		var flist = this.makeObjectList(ilist, factor, value);
		if (flist.length == 0) {
			alert('No items selected on which to apply this action');
			return;
		}
		update(flist);
	},
	removeFactorFromDatabase: function(update){
		var factor = $('#remove_factor_name').val();
		var value = '';
		var ilist = lambda.getSelectedItemList();
		var flist = this.makeObjectList(ilist, factor, value);
		if (flist.length == 0) {
			alert('No items selected on which to apply this action');
			return;
		}
		update(flist);
	}
};

var tau = {
	requested_run_factors: {
		setItemTypeField: function() {
			var $s = '';
			if(gamma.currentChooser.page.indexOf('helper_requested_run_batch') > -1) {
				$s = 'Batch_ID';
			}
			if(gamma.currentChooser.page.indexOf('helper_requested_run_ckbx') > -1) {
				$s = 'Requested_Run_ID';
			}
			if(gamma.currentChooser.page.indexOf('helper_dataset_ckbx') > -1) {
				$s = 'Dataset_Name';
			}
			if(gamma.currentChooser.page.indexOf('helper_experiment_ckbx') > -1) {
				$s = 'Experiment_Name';
			}
			if($s) {
				$('#itemType').val($s);
			}
		},
		updateDatabaseFromList: function(flist, id_type) {
			if ( !confirm("Are you sure that you want to update the database?") ) return;
			var factorXML = theta.getFactorXMLFromList(flist);
			if(id_type) {
				factorXML = '<id type="' + id_type + '" />' + factorXML;
			}
			var url =  gamma.pageContext.ops_url;
			var p = {};
			p.factorList = factorXML;
			// lambda.submitOperation is defined in dms2.js
			lambda.submitOperation(url, p);
		},
		saveChangesToDatabase: function() {
			var cols = theta.getListReportColumnList();
			var col_list = gamma.removeItems(cols, ['Sel', 'BatchID', 'Status', 'Name',  'Request',  'Experiment', 'Dataset']);
			var flist = theta.getFactorFieldList(col_list);
			this.updateDatabaseFromList(flist, 'Request');
		},
		load_delimited_text: function() {
			// Parse tab-delimited text to convert it to XML which is passed to stored procedure UpdateRequestedRunFactors
			// gamma.parseDelimitedText is defined in dms2.js
			var parsed_data = gamma.parseDelimitedText('delimited_text_input');
			var id_type = parsed_data.header[0];
			var col_list = gamma.removeItems(parsed_data.header, [id_type, 'Block', 'Run Order']);
			var flist = theta.getFieldListFromParsedData(parsed_data, col_list);
			this.updateDatabaseFromList(flist, id_type);
		}		
	},
	requested_run_admin: {
		updateDatabaseFromList: function(xml, command) {
			if (xml == '') {
				alert('No requests were selected');
				return;
			}
			if ( !confirm("Are you sure that you want to update the database?") ) return;
			var p = {};
			p.requestList = xml;
			p.command = command;
			// gamma.pageContext and lambda.submitOperation are defined in dms2.js
			var url =  gamma.pageContext.ops_url;			
			lambda.submitOperation(url, p);
		},
		setRequestStatus: function(status) {
			var iList = lambda.getSelectedItemList();
			var xml = gamma.getXmlElementsFromArray(iList, 'r', 'i');
			this.updateDatabaseFromList(xml, status);
		},
		changeWPN: function(oldWpn, newWpn) {
			var url = gamma.pageContext.site_url + gamma.pageContext.my_tag +  "/call/updatewp_sproc";
			var p = {};
			p.OldWorkPackage = oldWpn;
			p.NewWorkPackage = newWpn;
			p.RequestedIdList = lambda.getSelectedItemList().join();
			if(!p.RequestedIdList) {
				if ( !confirm("There are no requests selected. Do you wish to apply the change to all requests?") ) return;
			}
			// lambda.submitCall is defined in dms2.js
			lambda.submitCall(url, p);
		},
	} // requested_run_admin
}
