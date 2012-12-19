//------------------------------------------
// These functions are generic
//------------------------------------------

// these are global values whose defaults 
// can be overridden by individual pages
var globalAJAX = {
	'progress_message':'Loading...',
	'site_url':''
}

//Returns a copy of a string with leading and trailing whitespace removed.
function trim(str) {
	return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}

function updateAlert(url, form) { 
	url = globalAJAX.site_url + url;
	p = $('#' + form).serialize();
	$.post(url, p, function(data) {
//			alert(transport.responseText);
			$('#notification_message').html(data);
			$('#notification').show();
		}
	);
}
function clearSelector(name) {
	selObj = $('#' + name);
	for (i=0; i<selObj.options.length; i++) {
		selObj.options[i].selected = false;
	}
}
//------------------------------------------
//These functions are used by list reports
//------------------------------------------

//this function acts as a hook that other functions call to 
//reload the row data container for the list report.
//it needs to be overridden with the actual loading
//function defined on the page, which will be set up
//with page-specific features
function reloadListReportData() {
	alert('"reloadListReportData" not overridden');
}
// for clearing cached page parameters
function setListReportDefaults(url) { 
	p = {};
	$.post(url, p, function (data) {
		    alert(data);
		}
	);
}
//------------------------------------------
//loads a SQL comparison selector (via AJAX)
function loadSqlComparisonSelector(container_name, url, col_sel) {
	$('#' + container_name).html(globalAJAX.progress_message);
	var url += $('#' + col_sel).val();
	$('#' + container_name.load(url);
}
//clear the specified list report search filter
function clearSearchFilter(filter) {
	$( '.' + filter).each(function(idx, obj) {obj.value = ''} );
	is_filter_active();
}
//clear the list report search filters
function clearSearchFilters() {
	$(".filter_input_field").each(function(idx, obj) {obj.value = ''} );
//	$(".primary_filter_field").each(function(idx, obj) {obj.value = ''} );
//	$(".secondary_filter_input").each(function(idx, obj) {obj.value = ''} );
//	$(".sorting_filter_input").each(function(idx, obj) {obj.value = ''} );
	is_filter_active();
}
//------------------------------------------
function setColSort(colName) {
	var curCol = $('#qf_sort_col_0').val();
	var curDir = $('#qf_sort_dir_0').val();
	$(".sorting_filter_input").each(function(idx, obj) {obj.value = ''} );
	var dir = 'ASC';
	if(colName == curCol) {dir = (curDir == 'ASC')?'DESC':'ASC'; };
	$('#qf_sort_col_0').val(colName);
	$('#qf_sort_dir_0').val(dir);
	reloadListReportData('autoload');
}
//------------------------------------------
// paging
//set the current starting row for the list report
function setListReportCurRow(row) {
	$('#qf_first_row').val(row);
 reloadListReportData();
}
function setPageSize(curPageSize, totalRows, max) {
	var reply = getPageSizeFromUser(curPageSize, totalRows, max);
	if(reply == null) return;
	setPageSizeParameter(reply);
}
function getPageSizeFromUser(curPageSize, totalRows, max) {
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
}
function setPageSizeParameter(newPageSize) {
	if(isNaN(newPageSize)) {
		alert("Sorry, '" + newPageSize + "' is not a number");
	} else {
		var n = Number(newPageSize);
		$('#qf_rows_per_page').val(newPageSize);
		$('#qf_first_row').val(1);
	    reloadListReportData();
	}
}
//------------------------------------------
// search filter change monitoring
function set_filter_field_observers() {
	var pFields = $('#filter_form').find(".primary_filter_field");
	pFields.each(function(idx, f) { 
			$(this).keyup(filter_key); 
			$(this).keyup(is_filter_active); 
		});
	var sFields = $(".secondary_filter_input");
	sFields.each(function(idx, f) { 
			$(this).keyup(filter_key); 
			$(this).keyup(is_filter_active); 
		});
}
function is_filter_active() {
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
	set_filter_active_indicator(filterFlag, sortFlag);
}
function filter_key(e) {
	var code;
//	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	if(code == 13) {
		$('#qf_first_row').val(1);
	    reloadListReportData();
		return false;
	}
   return true;
}
function set_filter_active_indicator(activeSearchFilters, activeSorts) {
	if(!activeSearchFilters) {
		$('#filters_active').html('');
	} else 
	if(activeSearchFilters ==1 ){
		$('#filters_active').html('There is ' + activeSearchFilters +  ' filter set');
	} else {
		$('#filters_active').html('There are ' + activeSearchFilters +  ' filters set');
	}
}

//------------------------------------------
//These functions are used by entry page 
//------------------------------------------

// style associated entry field for each enable checkbox
function adjustEnabledFields() {
	$('._ckbx_enable').each(
		function(chkbx) {
			var fieldName = chkbx.name.replace('_ckbx_enable', '');
			enableDisableField(chkbx, fieldName);
		}
	);
}

// style associated entry field for checkbox
// according to whether it is enabled or disabled
function enableDisableField(chkbx, fieldName)
{
	if(chkbx.checked) {
		$('#' + fieldName).style.color="Black";
	} else {
		$('#' + fieldName).style.color="Silver";
	}
}
function showHideTableRows(block_name, url, show_img, hide_img) {
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
}
function showTableRows(block_name, url, hide_img) {
	var className = '.' + block_name;
	var img_element_id = block_name + "_cntl";
	$(className).each(function(idx, s){s.style.display=''});
	$('#' + img_element_id).src = url + hide_img;
}
function hideTableRows(block_name, url, show_img) {
	var className = '.' + block_name;
	var img_element_id = block_name + "_cntl";
	$(className).each(function(idx, s){s.style.display='none'});
	$('#' + img_element_id).src = url + show_img;			
}

//------------------------------------------
//These functions are used by entry page that invokes 
//list report chooser
//Note: a global variable "gChooser" that references an
//empty object must be defined by the entry page 
//that usese these functions
//------------------------------------------

function closeChooserWindowPage() {
	if (gChooser.window && !gChooser.window.closed) {
		gChooser.window.close();
	}
}
//this function opens an eternal chooser page and remembers
//information necessary to update the proper entry field
//when that page calls back with user's choice
function callChooser(fieldName, chooserPage, delimiter, xref) {
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
	closeChooserWindowPage();
	// remember which field gets the update 
	// for when the chooser page calls back (updateFieldValueFromChooser)
	gChooser.field = fieldName;
	gChooser.delimiter = delimiter;
	gChooser.page = chooserPage;
	// open the chooser page in another window
	gChooser.window = window.open(chooserPage, "HW", "scrollbars,resizable,height=550,width=1000,menubar");
}
//this function is called by an external chooser
//page to update the value in the field that it is serving
function updateFieldValueFromChooser(value, action) {
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
	closeChooserWindowPage();
	if(gChooser.callBack) {
		gChooser.callBack();
	}
}
function getFieldValueForChooser() {
	// todo: make sure gChooser.field is defined
	var value = $('#' + gChooser.field).val();
	if(gChooser.delimiter != ',') {
		value = value.replace(/gChooser.delimiter/g, ',');
	}
	return value;
}
//for scal datepicker
function callDatepicker(fieldName) {
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
}

//------------------------------------------
//These functions are used by multiple-choice 
//chooser list report to manage its checkboxes
//------------------------------------------

function getSelectedItemList() {
	var checkedIDlist = [];
	$('.lr_ckbx').each(function(idx, obj){
		if(obj.checked) {
			checkedIDlist.push(obj.value);
		}
	});
	return checkedIDlist;
}
//set checked state of all checkboxes with given name
function setCkbxState(checkBoxName, state) {
	var rows = document.getElementsByName(checkBoxName);
	for (var i = 0; i < rows.length; i++) {
		rows[i].checked  = state;
	}
}
//make list of values of checked checkboxes with given name
function getCkbxList(checkBoxName) {
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
}

//------------------------------------------
// used by helper list reports with checkboxes
//------------------------------------------

// set checked state of all checkboxes with given name from given list
function setCkbxFromList(checkBoxName, selList) {
	var rows = document.getElementsByName(checkBoxName, selList);
	// split list into separate trimmed elements
	var selections = selList.split(/[,;]/);
    for(var k = 0; k < selections.length; k++) {
    	selections[k] = trim(selections[k]);
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
}
// set checked state of chooser's checkboxes from
// the current value of the field it is choosing for
function intializeChooserCkbx(checkBoxName) {
	if(window.opener) {
		var list = window.opener.getFieldValueForChooser();
		setCkbxFromList(checkBoxName, list);
	}
}

//------------------------------------------
//search functions
//------------------------------------------
function dms_search(selFldName, valFldName) {
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
}
//------------------------------------------
// nav_bar functions
//------------------------------------------
// these functions hide and show the side menu
function kill_frames() {
	if(top != self) {
	  top.location = location;
	}
}
function open_frames() {
	document.OFS.page.value = location;
	document.OFS.submit();
}
function toggle_frames() {
if(top != self) {
  top.location = location;
} else {
  document.OFS.page.value = location;
  document.OFS.submit();
}
}

//------------------------------------------
// called by a drop-down selection type chooser
// to update its target field
//------------------------------------------

function setFieldValueFromSelection(fieldName, chooserName, mode) {
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
}

function setFieldValue(fieldName, value) {
	if($('#' + fieldName)) {
			$('#' + fieldName).val(value);
	}
}
//------------------------------------------
// entry field formatting
//------------------------------------------

function convertList(fieldName, repStr)
{
	var fld = $('#' + fieldName);
    var findStr = "(\r\n|[\r\n]|\t)";
    var re = new RegExp(new RegExp(findStr, "g")); 
	repStr += ' ';
    fld.value = fld.value.replace(re, repStr);
}
function formatXMLText(fieldName)
{
	var fld = $('#' + fieldName);
    var findStr = "><";
    var repStr = ">\n<";
    var re = new RegExp(new RegExp(findStr, "g")); 
    fld.value = fld.value.replace(re, repStr);
}

//------------------------------------------
//------------------------------------------

//------------------------------------------
// document export - repurpose entry form
// to old fashioned submit instead of AJAX
function export_to_doc(url, form) {
	var frm = $('#' + form)[0];
	var oldUrl = frm.action;
	frm.action = url;
    frm.submit();
	frm.action = oldUrl;
}
//------------------------------------------
