var theta = {
	getBlockingXMLFromList: function(flist) {
		var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'t'}, {p:'value', a:'v'}];
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
	applyFactorToDatabase: function() {
		var factor = $('#apply_factor_name').val();
		var value = $('#apply_factor_value').val();
		var ilist = lambda.getSelectedItemList();
		var flist = this.makeObjectList(ilist, factor, value);
		if (flist.length == 0) {
			alert('No items selected on which to apply this action');
			return;
		}
		updateDatabaseFromList(flist); // REFACTOR: work into callback
	},
	removeFactorFromDatabase: function(){
		var factor = $('#remove_factor_name').val();
		var value = '';
		var ilist = lambda.getSelectedItemList();
		var flist = this.makeObjectList(ilist, factor, value);
		if (flist.length == 0) {
			alert('No items selected on which to apply this action');
			return;
		}
		updateDatabaseFromList(flist); // REFACTOR: work into callback
	}
};
