function getBlockingXMLFromList(flist) {
	var xml = '';
	if(typeof(flist) != "undefined") {
		flist.each(function(idx, obj){
			xml += '<r i="' + obj.id + '" t="' + obj.factor + '" v="' + obj.value + '" />';
		});
	}
	return xml;
}
function getFactorXMLFromList(flist) {
	var xml = '';
	if (typeof(flist) != "undefined") {
		flist.each(function(idx, obj){
			xml += '<r i="' + obj.id + '" f="' + obj.factor + '" v="' + obj.value + '" />';
		});
	}
	return xml;
}
function getListReportColumnList() {
	var col_list = [];
	$('.col_header').each(function(idx, obj){
		col_list.push(trim(obj.html()));
	});
	return col_list;
}
function getFactorFieldList(factor_cols) {
	var idlist = [];
	$('.lr_ckbx').each(function(idx, obj){
		idlist.push(obj.value);
	});
	var flist = [];
	factor_cols.each(function(idx, col){
		idlist.each(function(idx, id){
			var fldID = col.replace(' ', '_') + '_' + id;
			var val = $(fldID).val();
			var obj = {};
			obj.id = id;
			obj.factor = col;
			obj.value = val;
			flist.push(obj);
		});
	});
	return flist;
}
function makeObjectList(ilist, factor, value) {
	var flist = [];
	ilist.each(function(idx, id){
		var obj = {};
		obj.id = id;
		obj.factor = factor;
		obj.value = value;
		flist.push(obj);
	});
	return flist;
}

function parse_lines(line) {
	flds = [];
	var fields = line.split('\t');
	fields.each(function(idx, fld, fidx){
		var f = fld.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
		flds.push(f);
	});
	return flds;
}
function parseDelimitedText(text_fld) {
	parsed_data = {};
	var lines = $(text_fld).val().split('\n');
	var header = [];
	var data = [];
	lines.each(function(idx, line, lineNumber){
		line = trim(line);
		if(line) {	
			var fields = parse_lines(line)
			if(lineNumber == 0) {
				header = fields;
			} else {
				data.push(fields); // check length of fields?
			}
		}
	});
	// get rid of goofy parsing artifact last row
	if((data[data.length - 1]).length < header.length) {
		data.pop();
	}
	parsed_data.header = header;
	parsed_data.data = data;
	return parsed_data;
}
function getFieldListFromParsedData(parsed_data, col_list) {
	// make array of id/factor/value objects,
	// one for each row of each column
	var flist = [];
	col_list.each(function(idx, factor){
		var idx = parsed_data.header.indexOf(factor);
		if(idx > -1) {
			parsed_data.data.each(function(idx, row){
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
}

function applyFactorToDatabase() {
	var factor = $('#apply_factor_name').val();
	var value = $('#apply_factor_value').val();
	var ilist = getSelectedItemList();
	var flist = makeObjectList(ilist, factor, value);
	if (flist.length == 0) {
		alert('No items selected on which to apply this action');
		return;
	}
	updateDatabaseFromList(flist);
}
function removeFactorFromDatabase(){
	var factor = $('#remove_factor_name').val();
	var value = '';
	var ilist = getSelectedItemList();
	var flist = makeObjectList(ilist, factor, value);
	if (flist.length == 0) {
		alert('No items selected on which to apply this action');
		return;
	}
	updateDatabaseFromList(flist);
}
