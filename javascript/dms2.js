var gamma = {
	
	//------------------------------------------
	// These functions are generic
	//------------------------------------------
	
	// these are global values whose defaults 
	// can be overridden by individual pages
	global: {
		'progress_message':'Loading...',
		'site_url':''
	},
	//Returns a copy of a string with leading and trailing whitespace removed.
	trim: function(str) {
		return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	},
	updateAlert: function(url, form) { 
		url = gamma.global.site_url + url;
		p = $('#' + form).serialize();
		$.post(url, p, function(data) {
				$('#notification_message').html(data);
				$('#notification').show();
			}
		);
	},
	clearSelector: function(name) {
		$('#' + name + ' option').each(function(idx, opt) {
			opt.selected = false;
		});
	},
	sectionToggle: function(containerId, duration) {
		var speed = duration * 1000;
		$('#' + containerId).toggle(speed);
		return false;
	},
	//------------------------------------------
	//These functions are used by list reports
	//------------------------------------------
	
	//this function acts as a hook that other functions call to 
	//reload the row data container for the list report.
	//it needs to be overridden with the actual loading
	//function defined on the page, which will be set up
	//with page-specific features
	reloadListReportData: function() {
		alert('"gamma.reloadListReportData" not overridden');
	},
	// for clearing cached page parameters
	setListReportDefaults: function(url) { 
		p = {};
		$.post(url, p, function (data) {
			    alert(data);
			}
		);
	},
	//------------------------------------------
	//loads a SQL comparison selector (via AJAX)
	loadSqlComparisonSelector: function(container_name, url, col_sel) {
		$('#' + container_name).html(gamma.global.progress_message);
		url += $('#' + col_sel).val();
		$('#' + container_name).load(url);
	},
	//clear the specified list report search filter
	clearSearchFilter: function(filter) {
		$( '.' + filter).each(function(idx, obj) {obj.value = ''} );
		this.is_filter_active();
	},
	//clear the list report search filters
	clearSearchFilters: function() {
		$(".filter_input_field").each(function(idx, obj) {obj.value = ''} );
		this.is_filter_active();
	},
	//------------------------------------------
	setColSort: function(colName, noUpdate) {
		var curCol = $('#qf_sort_col_0').val();
		var curDir = $('#qf_sort_dir_0').val();
		$(".sorting_filter_input").each(function(idx, obj) {obj.value = ''} );
		var dir = 'ASC';
		if(colName == curCol) {dir = (curDir == 'ASC')?'DESC':'ASC'; };
		$('#qf_sort_col_0').val(colName);
		$('#qf_sort_dir_0').val(dir);
		if(!noUpdate) {
			this.reloadListReportData('autoload');
		}
	},
	//------------------------------------------
	// paging
	//set the current starting row for the list report
	setListReportCurRow: function(row) {
		$('#qf_first_row').val(row);
	 this.reloadListReportData();
	},
	setPageSize: function(curPageSize, totalRows, max) {
		var reply = this.getPageSizeFromUser(curPageSize, totalRows, max);
		if(reply == null) return;
		this.setPageSizeParameter(reply);
	},
	getPageSizeFromUser: function(curPageSize, totalRows, max) {
		var reply = null;
		if (curPageSize == 'all') {
			return (totalRows > max)?max:totalRows;
		} 
		var reply = prompt("Please enter a value for number \n of rows to display on each page \n (1 to " + max + ")", curPageSize);
		if (reply == null || reply == "") {
			return null;
		}
		if (reply == 'all') {
			return (totalRows > max)?max:totalRows;
		} 
		if(isNaN(reply)) {
			alert("Sorry, '" + reply + "' is not a number");
			return null;
		}
		if (reply > totalRows) {
			reply = totalRows;
		}
		return (reply > max)?max:reply;
	},
	setPageSizeParameter: function(newPageSize) {
		if(isNaN(newPageSize)) {
			alert("Sorry, '" + newPageSize + "' is not a number");
		} else {
			var n = Number(newPageSize);
			$('#qf_rows_per_page').val(newPageSize);
			$('#qf_first_row').val(1);
		    this.reloadListReportData();
		}
	},
	//------------------------------------------
	// search filter change monitoring
	set_filter_field_observers: function() {
		var that = this;
		var pFields = $('#filter_form').find(".primary_filter_field");
		pFields.each(function(idx, f) { 
				$(this).keyup(that.filter_key); 
				$(this).keyup(that.is_filter_active); 
			});
		var sFields = $(".secondary_filter_input");
		sFields.each(function(idx, f) { 
				$(this).keyup(that.filter_key); 
				$(this).keyup(that.is_filter_active); 
			});
	},
	is_filter_active: function() {
		var filterFlag = 0;
		var sortFlag = 0;
		var ff = $('#filter_form');
		ff.find(".primary_filter_field").each(function(idx, obj) {
				if(obj.value != '') filterFlag++;
			} );
		ff.find(".secondary_filter_input").each(function(idx, obj) {
				if(obj.value != '') filterFlag++;
			} );
		ff.find(".sorting_filter_input").each(function(idx, obj) {
				if(obj.value != '') sortFlag++;
			} );	
		gamma.set_filter_active_indicator(filterFlag, sortFlag);
	},
	filter_key: function(e) {
		var code;
	//	if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		if(code == 13) {
			$('#qf_first_row').val(1);
		    gamma.reloadListReportData();
			return false;
		}
	   return true;
	},
	set_filter_active_indicator: function(activeSearchFilters, activeSorts) {
		if(!activeSearchFilters) {
			$('#filters_active').html('');
		} else 
		if(activeSearchFilters ==1 ){
			$('#filters_active').html('There is ' + activeSearchFilters +  ' filter set');
		} else {
			$('#filters_active').html('There are ' + activeSearchFilters +  ' filters set');
		}
	},
	
	//------------------------------------------
	//These functions are used by entry page 
	//------------------------------------------
	
	// style associated entry field for each enable checkbox
	adjustEnabledFields: function() {
		var that = this;
		$('._ckbx_enable').each(
			function(chkbx) {
				var fieldName = chkbx.name.replace('_ckbx_enable', '');
				that.enableDisableField(chkbx, fieldName);
			}
		);
	},
	// style associated entry field for checkbox
	// according to whether it is enabled or disabled
	enableDisableField: function(chkbx, fieldName)
	{
		if(chkbx.checked) {
			$('#' + fieldName).style.color="Black";
		} else {
			$('#' + fieldName).style.color="Silver";
		}
	},
	showHideTableRows: function(block_name, url, show_img, hide_img) {
		var className = '.' + block_name;
		var img_element_id = block_name + "_cntl";
		var cur_src = $('#' + img_element_id).src;
		var styleVal = '';
		var index = cur_src.indexOf('hide');
		if(index < 0) {
			$(className).each(function(idx, s){s.style.display=''});
			$('#' + img_element_id).src = url + hide_img;
		} else {
			$(className).each(function(idx, s){s.style.display='none'});
			$('#' + img_element_id).src = url + show_img;		
	    }
	},
	showTableRows: function(block_name, url, hide_img) {
		var className = '.' + block_name;
		var img_element_id = block_name + "_cntl";
		$(className).each(function(idx, s){s.style.display=''});
		$('#' + img_element_id).src = url + hide_img;
	},
	hideTableRows: function(block_name, url, show_img) {
		var className = '.' + block_name;
		var img_element_id = block_name + "_cntl";
		$(className).each(function(idx, s){s.style.display='none'});
		$('#' + img_element_id).src = url + show_img;			
	},
	//------------------------------------------
	//These functions are used by entry page that invokes 
	//list report chooser
	//Note: a global variable "gChooser" that references an
	//empty object must be defined by the entry page 
	//that usese these functions
	//------------------------------------------
	
	closeChooserWindowPage: function() {
		if (gChooser.window && !gChooser.window.closed) {
			gChooser.window.close();
		}
	},
	//this function opens an exernal chooser page and remembers
	//information necessary to update the proper entry field
	//when that page calls back with user's choice
	callChooser: function(fieldName, chooserPage, delimiter, xref) {
		// resolve cross-reference to other field, if one exists
		var xv = (xref != '')?$('#' + xref).val():'';
		if(xref != '' && xv == ''){ 
			alert (xref + ' must be selected first.');
			return;
		}
		// check if chooserPage URL needs separator
		var sep = '/';
	//	REFACTOR - make sure this works
		if( chooserPage.match(/\/$/) || chooserPage.match(/~$/) ) {
			sep = '';
		}
		// if there is a cross-reference, pass it on end of URL
		if(xv != '') chooserPage += sep + xv;
		// make sure that there are no other chooser pages open
		this.closeChooserWindowPage();
		// remember which field gets the update 
		// for when the chooser page calls back (updateFieldValueFromChooser)
		gChooser.field = fieldName;
		gChooser.delimiter = delimiter;
		gChooser.page = chooserPage;
		// open the chooser page in another window
		gChooser.window = window.open(chooserPage, "HW", "scrollbars,resizable,height=550,width=1000,menubar");
	},
	//this function is called by an external chooser
	//page to update the value in the field that it is serving
	updateFieldValueFromChooser: function(value, action) {
		// todo: make sure gChooser.field is defined
		fld = $('#' + gChooser.field)[0];
		// lists are always transmitted as comma-delimited
		// and field may need a different delimiter
		if(gChooser.delimiter != ',') {
			value = value.replace(/,/g, gChooser.delimiter);
		}
		// replace or append new value, as appropriate
		if(action == "append") {
			if (gChooser.delimiter != "" && fld.value != "") {
				fld.value += gChooser.delimiter + " " + value;		
			} else {
				fld.value += value;		
			}
		} else { // replace
			fld.value = value;
		}
		// we are done with chooser page - make it go away
		this.closeChooserWindowPage();
		if(gChooser.callBack) {
			gChooser.callBack();
		}
	},
	getFieldValueForChooser: function() {
		// todo: make sure gChooser.field is defined
		var value = $('#' + gChooser.field).val();
		if(gChooser.delimiter != ',') {
			value = value.replace(/gChooser.delimiter/g, ',');
		}
		return value;
	},
	//for scal datepicker
	callDatepicker: function(fieldName) {
		var chName = fieldName + '_chooser';
		var ch = $('#' + chName);
		if(ch) {
			if(ch.is(':visible')) {
				ch.hide();
			} else {
				ch.show();
			}
		} else {
			var dv = "<div id='"+ chName + "'></div>";
			$('#' + fieldName).ancestors().first().insert(dv);
			var ch = $('#' + chName);
			ch.addClass('googleblue');
		    ch.absolutize();
			new scal(chName, 
					 function(dt){
						$('#' + fieldName).val(dt.format('mm/dd/yyyy'));
					 	ch.hide();
					 }, 
					 {updateformat: 'mm/dd/yyyy'}
				);
		}
	},
	//------------------------------------------
	//These functions are used by multiple-choice 
	//chooser list report to manage its checkboxes
	//------------------------------------------
	
	getSelectedItemList: function() {
		var checkedIDlist = [];
		$('.lr_ckbx').each(function(idx, obj){
			if(obj.checked) {
				checkedIDlist.push(obj.value);
			}
		});
		return checkedIDlist;
	},
	//set checked state of all checkboxes with given name
	setCkbxState: function(checkBoxName, state) {
		var rows = document.getElementsByName(checkBoxName);
		for (var i = 0; i < rows.length; i++) {
			rows[i].checked  = state;
		}
	},
	//make list of values of checked checkboxes with given name
	getCkbxList: function(checkBoxName) {
	  var list = '';
	  var rows = document.getElementsByName(checkBoxName);
	  for (var i = 0; i < rows.length; i++) {
	    if ( rows[i].checked ) {
	      if (list != '') {
	        list  += ', ';
	      }
	      list  += rows[i].value;
	    }
	  }
	  return list;
	},
	//------------------------------------------
	// used by helper list reports with checkboxes
	//------------------------------------------
	
	// set checked state of all checkboxes with given name from given list
	setCkbxFromList: function(checkBoxName, selList) {
		var rows = document.getElementsByName(checkBoxName, selList);
		// split list into separate trimmed elements
		var selections = selList.split(/[,;]/);
	    for(var k = 0; k < selections.length; k++) {
	    	selections[k] = this.trim(selections[k]);
	    }
	    // traverse checkbox elements, setting checkbox 
	    // if it's value matches an element in list
		for (var i = 0; i < rows.length; i++) {
	        for(var k = 0; k < selections.length; k++) {
	            if(selections[k] === rows[i].value) {
	 			   rows[i].checked = true;
	 			   break;
	            }
	        }
		}
	},
	// set checked state of chooser's checkboxes from
	// the current value of the field it is choosing for
	intializeChooserCkbx: function(checkBoxName) {
		if(window.opener) {
			var list = window.opener.gamma.getFieldValueForChooser();
			this.setCkbxFromList(checkBoxName, list);
		}
	},
	//------------------------------------------
	//search functions
	//------------------------------------------
	dms_search: function(selFldName, valFldName) {
		var srchVal = $('#' + valFldName).val();
		var url = $('#' + selFldName).val();
		if(url == '') return;
		if(srchVal != '') {
			url += srchVal;
			if(typeof top.display_side != 'undefined') {
				top.display_side.location = url;
			} else {
				location = url;
			}
		}
	},
	//------------------------------------------
	// nav_bar functions
	//------------------------------------------
	// these functions hide and show the side menu
	kill_frames: function() {
		if(top != self) {
		  top.location = location;
		}
	},
	open_frames: function() {
		document.OFS.page.value = location;
		document.OFS.submit();
	},
	toggle_frames: function() {
		if(top != self) {
		  top.location = location;
		} else {
		  document.OFS.page.value = location;
		  document.OFS.submit();
		}
	},
	//------------------------------------------
	// called by a drop-down selection type chooser
	// to update its target field
	//------------------------------------------
	
	setFieldValueFromSelection: function(fieldName, chooserName, mode) {
		var fld = $('#' + fieldName);
		var chv = $('#' + chooserName).val();
		if(fld.val() != null) {
			if(mode == 'replace' || mode == '') {
				fld.val(chv);
				return;
			}
			var delim = ';';
			if(mode == 'append_comma') delim = ',';		
			var v = fld.val();
			if(v != '') v = v + delim;
			fld.val(v + chv);			
		}
	},
	setFieldValue: function(fieldName, value) {
		if($('#' + fieldName)) {
				$('#' + fieldName).val(value);
		}
	},
	//------------------------------------------
	// entry field formatting
	//------------------------------------------
	
	convertList: function(fieldName, repStr) {
		var fld = $('#' + fieldName);
	    var findStr = "(\r\n|[\r\n]|\t)";
	    var re = new RegExp(new RegExp(findStr, "g")); 
		repStr += ' ';
	    fld.value = fld.value.replace(re, repStr);
	},
	formatXMLText: function(fieldName) {
		var fld = $('#' + fieldName);
	    var findStr = "><";
	    var repStr = ">\n<";
	    var re = new RegExp(new RegExp(findStr, "g")); 
	    fld.value = fld.value.replace(re, repStr);
	},
	//------------------------------------------
	//------------------------------------------
	
	//------------------------------------------
	// document export - repurpose entry form
	// to old fashioned submit instead of AJAX
	export_to_doc: function(url, form) {
		var frm = $('#' + form)[0];
		var oldUrl = frm.action;
		frm.action = url;
	    frm.submit();
		frm.action = oldUrl;
	}
};

//------------------------------------------
// list report commands
//------------------------------------------
var delta = {
	
	//------------------------------------------
	// submit list report supplemental command
	submitOperation: function(url, p, show_resp) {
		var ctl = $('#' + gamma.global.cntrl_container_name);
		var container = $('#' + gamma.global.response_container_name);
		container.html(gamma.global.progress_message);
		$.post(url, p, function (data) {
				if(data.indexOf('Update failed') > -1) {
					container.html(data);
					ctl.show();
				} else {
					var msg = 'Operation was successful';
					if(show_resp) msg = data;
					container.html(msg);
					ctl.hide();
					gamma.reloadListReportData();
				}
			}
		);
	},
	parse_lines: function(line) { //gamma
		flds = [];
		var fields = line.split('\t');
		fields.each(function(idx, fld, fidx){
			var f = fld.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
			flds.push(f);
		});
		return flds;
	},
	parseDelimitedText: function(text_fld, removeArtifact) { //gamma
		parsed_data = {};
		var lines = $('#' + text_fld).val().split('\n');
		var header = [];
		var data = [];
		lines.each(function(idx, line, lineNumber){
			line = gamma.trim(line);
			if(line) {	
				var fields = delta.parse_lines(line)
				if(lineNumber == 0) {
					header = fields;
				} else {
					data.push(fields); // check length of fields?
				}
			}
		});
		// get rid of goofy parsing artifact last row
		if(removeArtifact && (data[data.length - 1]).length < header.length) {
			data.pop();
		}
		parsed_data.header = header;
		parsed_data.data = data;
		return parsed_data;
	},
};
