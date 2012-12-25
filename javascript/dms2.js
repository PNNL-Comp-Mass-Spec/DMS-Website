//------------------------------------------
// set up generic way to handle AJAX errors
//------------------------------------------
$(document).ajaxError(function (e, xhr, settings, exception) {
    alert('AJAX error in: ' + settings.url + '; ' + 'error:' + exception);
});	
	
//------------------------------------------
// global and general-purpose functions and objects
//------------------------------------------

var gamma = {
	
	//------------------------------------------
	// context values for current page
	// 
	// many library functions reference this object
	// and depend on proper values being defined 
	// by specific family page before they are called
	//------------------------------------------
	pageContext: {
		'progress_message':'Loading...',
		'site_url':''
	},
	//Returns a copy of a string with leading and trailing whitespace removed.
	trim: function(str) {
		return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	},
	updateAlert: function(url, form) { 
		url = gamma.pageContext.site_url + url;
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
	// document export - repurpose entry form
	// to old fashioned submit instead of AJAX
	export_to_doc: function(url, form) {
		var frm = $('#' + form)[0];
		var oldUrl = frm.action;
		frm.action = url;
	    frm.submit();
		frm.action = oldUrl;
	},
	//------------------------------------------
	// misc functions
	//------------------------------------------
	
	// convert array of objects representing form values
	// where each object has property 'name' and 'value'
	//
	// return single object with each field represented 
	// as a property having value of associated field.
	//
	// fields with shared name have array of values
	reformatFormArray: function(fldObjArray) {
		var obj = {};
		$.each(fldObjArray, function(idx, fldObj) {
			var nm = fldObj.name;
			if(!obj[nm]) {
				obj[nm] = [];
			}
			obj[nm].push(fldObj.value);
		});
		return obj;
	},
	// use to terminate a calling chain
	no_action: {
	}
};
		
//------------------------------------------
//These functions are used by list reports
//------------------------------------------
var kappa = {	
	//this function acts as a hook that other functions call to 
	//reload the row data container for the list report.
	//it needs to be overridden with the actual loading
	//function defined on the page, which will be set up
	//with page-specific features
	reloadListReportData: function() {
		alert('"kappa.reloadListReportData" not overridden');
	},
	// for clearing cached page parameters
	setListReportDefaults: function(url) { 
		p = {};
		$.post(url, p, function (data) {
			    alert(data);
			}
		);
	},
	// go get some content from the server using given form and action
	// and put it into the designated container element
	// and initiate the designated follow-on action, if such exists
	updateContainer: function (action, formId, containerId, follow_on_action) { 
		var container = $('#' + containerId);
		var spin = new Spinner({}).spin(container[0]);
		var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/' + action;
		var p = $('#' + formId).serialize();
		$.post(url, p, function (data) {
				spin.stop();
			    container.html(data);
				if(follow_on_action && follow_on_action.run) {
					follow_on_action.run();
				}
			}
		);
	},
	//------------------------------------------
	//loads a SQL comparison selector (via AJAX)
	loadSqlComparisonSelector: function(container_name, url, col_sel) {
		$('#' + container_name).html(gamma.pageContext.progress_message);
		url += $('#' + col_sel).val();
		$('#' + container_name).load(url);
	},
	//clear the specified list report search filter
	clearSearchFilter: function(filter) {
		$( '.' + filter).each(function(idx, obj) {obj.value = ''} );
		kappa.is_filter_active();
	},
	//clear the list report search filters
	clearSearchFilters: function() {
		$(".filter_input_field").each(function(idx, obj) {obj.value = ''} );
		kappa.is_filter_active();
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
			kappa.reloadListReportData('autoload');
		}
	},
	//------------------------------------------
	// paging
	//set the current starting row for the list report
	setListReportCurRow: function(row) {
		$('#qf_first_row').val(row);
	 	kappa.reloadListReportData();
	},
	setPageSize: function(curPageSize, totalRows, max) {
		var reply = kappa.getPageSizeFromUser(curPageSize, totalRows, max);
		if(reply == null) return;
		kappa.setPageSizeParameter(reply);
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
		    kappa.reloadListReportData();
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
		kappa.set_filter_active_indicator(filterFlag, sortFlag);
	},
	filter_key: function(e) {
		var code;
	//	if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		if(code == 13) {
			$('#qf_first_row').val(1);
		    kappa.reloadListReportData();
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
	    	selections[k] = gamma.trim(selections[k]);
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
			var list = window.opener.epsilon.getFieldValueForChooser();
			kappa.setCkbxFromList(checkBoxName, list);
		}
	}
};

//------------------------------------------
// list report commands
//------------------------------------------
var theta = {
	
	//------------------------------------------
	// submit list report supplemental command
	submitOperation: function(url, p, show_resp) {
		var ctl = $('#' + gamma.pageContext.cntrl_container_name);
		var container = $('#' + gamma.pageContext.response_container_name);
		container.html(gamma.pageContext.progress_message);
		$.post(url, p, function (data) {
				if(data.indexOf('Update failed') > -1) {
					container.html(data);
					ctl.show();
				} else {
					var msg = 'Operation was successful';
					if(show_resp) msg = data;
					container.html(msg);
					ctl.hide();
					kappa.reloadListReportData();
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
				var fields = theta.parse_lines(line)
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

//------------------------------------------
//These functions are used by detail report page 
//------------------------------------------
var delta = {
	//perform detail report command (via AJAX)
	performCommand: function(url, id, mode) {
		if( !confirm("Are you sure that you want to update the database?") ) return;
		var p = {};
		p.ID = id;
		p.command = mode;
		var container = $('#' + gamma.pageContext.response_container);
		var spin = new Spinner({}).spin(container[0]);
		$.post(url, p, function (data) {
				spin.stop();
			    container.html(data);
				delta.updateMyData();	
			}
		);
	},
	updateContainer: function(url, containerId) {
		var container = $('#' + containerId);
		url = gamma.pageContext.site_url + url;
		var p = {};
		var spin = new Spinner({}).spin(container[0]);
		$.post(url, p, function (data) {
				spin.stop();
			    container.html(data);
			}
		);
	},
	updateMyData: function() {
		delta.updateContainer(gamma.pageContext.my_tag + '/show_data/' + gamma.pageContext.Id, 'data_container'); 
	}

};

//------------------------------------------
//These functions are used by entry page 
//------------------------------------------
var epsilon = {
	
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
		var ctl = $('#' + img_element_id).first()[0];
		var cur_src = ctl.src;
		var styleVal = '';
		var index = cur_src.indexOf('hide');
		if(index < 0) {
			$(className).each(function(idx, s){s.style.display=''});
			$('#' + img_element_id)[0].src = url + hide_img;
		} else {
			$(className).each(function(idx, s){s.style.display='none'});
			$('#' + img_element_id)[0].src = url + show_img;		
	    }
	},
	showTableRows: function(block_name, url, hide_img) {
		var className = '.' + block_name;
		var img_element_id = block_name + "_cntl";
		$(className).each(function(idx, s){s.style.display=''});
		$('#' + img_element_id)[0].src = url + hide_img;
	},
	hideTableRows: function(block_name, url, show_img) {
		var className = '.' + block_name;
		var img_element_id = block_name + "_cntl";
		$(className).each(function(idx, s){s.style.display='none'});
		$('#' + img_element_id)[0].src = url + show_img;			
	},
	showSection: function (block_name) {
		var url = gamma.pageContext.base_url + 'images/';
		var hide_img = 'z_hide_col.gif';
		epsilon.showTableRows(block_name, url, hide_img);
	},
	hideSection: function (block_name) {
		var url = gamma.pageContext.base_url + 'images/';
		var show_img = 'z_show_col.gif';
		epsilon.hideTableRows(block_name, url, show_img);
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
		epsilon.closeChooserWindowPage();
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
		epsilon.closeChooserWindowPage();
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
	// used for entry page submission
	//------------------------------------------
	
	// called by the built-in entry page family submission controls
	// submit the entry form to the entry page or alternate submission logic
	submitStandardEntryPage: function(url, mode) {
		if(window.submissionSequence) {
			submissionSequence(url, mode);
		} else {
			epsilon.submitEntryFormToPage(url, mode);
		}
	},
	//POST the entry form to the entry page via AJAX
	submitEntryFormToPage: function(url, mode, follow_on_action) {
		if(!confirm("Are you sure that you want to perform this action?")) return;
		var container = $('#form_container');
		$('#entry_cmd_mode').val(mode);
		p = $('#entry_form').serialize();
		$.post(url, p, function (data) {
			    container.html(data);
				setTimeout("epsilon.adjustEnabledFields()", 350);
				if(follow_on_action && follow_on_action.run) {
					follow_on_action.run(mode);
				}
			}
		);
	},
	// POST the entry form to another page
	submitEntryFormToOtherPage: function(url, mode) {
		$('#entry_cmd_mode').val(mode);
		var f = $('#entry_form');
		f.action = url;
		f.method="post";
		f.submit();
	},	

	//------------------------------------------
	// supplemental parameter entry forms
	//------------------------------------------

	//loop through all the fields in the given parameter form
	//and build properly formatted XML and replace the
	//contents of the given field with it
	copy_param_form_to_xml_param_field :function(formId, fieldId, hasSection) {
		var xml = '';
		var targetForm = $('#' + formId);
		var targetField = $('#' + fieldId)
		var fields = targetForm.serializeArray();
		$.each(fields, function(idx, field) {
			if(field.name.indexOf('_chooser') === -1) {
				var section = '';
				var name = field.name;
				var value = field.value;
				if(hasSection) {
					var nm = field.name.split('.');
					section = nm[0];
					name = nm[1];
				}
				var s = '<Param ';
				s += (section)?'Section="' + section + '" ':'';
				s += 'Name="' + name + '" ';
				s += 'Value="' + value + '" ';
				s += '/>';
				xml += s;
			}		
		});
		targetField.val(xml);
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
	}
	
};
