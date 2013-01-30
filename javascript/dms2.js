//------------------------------------------
// set up generic hook to catch $.post AJAX errors
//------------------------------------------
$(document).ajaxError(function (e, xhr, settings, exception) {
    alert('AJAX error in: ' + settings.url + '; ' + 'error:' + exception);
});	

//------------------------------------------
// stupid IE
//------------------------------------------

if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(obj, start) {
	     for (var i = (start || 0), j = this.length; i < j; i++) {
		 if (this[i] === obj) { return i; }
	     }
	     return -1;
	}
}

//------------------------------------------
// navBar (main drop-down menu)
//------------------------------------------

// set event handlers for global search panel
$(document).ready(function () {
	var panel = $('.global_search_panel');
	gamma.setSearchEventHandlers(panel);
});

// set listener for nav-bar related clicks
$(document).ready(function () {
	$(document.body).click(navBar.hide_exposed_menus);
});

var navBar = {
	// id of menu that is exposed
	openMenuId: '',
	expose_menu: function(menu_id) {
		navBar.openMenuId = menu_id;
		var m = $('#' + menu_id);
		m.css('display', 'block');
	},
	hide_exposed_menus: function(e) {
		if(e) {
			var el = e.target;
			var pe = $(el).closest('div')[0];
			var notA = el.tagName.toLowerCase() != 'a';
			var notM = pe.id != 'menu';
			if(notA || notM) {
				navBar.openMenuId = '';
			}
		} else {
			navBar.openMenuId = '';
		}
		var menu_list = $('.ddm');
		menu_list.each( function(idx, x) {
				if(x.id != navBar.openMenuId) x.style.display = 'none';
			});	
	},
	invoke: function(action, arg) {
		if(action) {
			action(arg);
		}
		navBar.hide_exposed_menus();
	}
};

//------------------------------------------
// JQuery plug-in for spinner
//------------------------------------------
/*
$("#el").spin(); // Produces default Spinner using the text color of #el.
$("#el").spin("small"); // Produces a 'small' Spinner using the text color of #el.
$("#el").spin("large", "white"); // Produces a 'large' Spinner in white (or any valid CSS color).
$("#el").spin({ ... }); // Produces a Spinner using your custom settings.
$("#el").spin(false); // Kills the spinner.
*/
(function($) {
	$.fn.spin = function(opts, color) {
		var presets = {
			"tiny": { lines: 8, length: 2, width: 2, radius: 3 },
			"small": { lines: 8, length: 4, width: 3, radius: 5 },
			"large": { lines: 10, length: 8, width: 4, radius: 8 }
		};
		if (Spinner) {
			return this.each(function() {
				var $this = $(this),
					data = $this.data();
				
				if (data.spinner) {
					data.spinner.stop();
					delete data.spinner;
				}
				if (opts !== false) {
					if (typeof opts === "string") {
						if (opts in presets) {
							opts = presets[opts];
							opts.color = 'blue';
						} else {
							opts = {};
						}
						if (color) {
							opts.color = color;
						}
					}
					data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
				}
			});
		} else {
			throw "Spinner class not available.";
		}
	};
})(jQuery);
	
//------------------------------------------
// global and general-purpose functions and objects
//------------------------------------------

var gamma = {
	
	//------------------------------------------
	// event handlers for global search panel
	//------------------------------------------
	setSearchEventHandlers: function(panel) {
		var sel = panel.find('select');
		var val = panel.find('input');
		var go = panel.find('a');
		
		val.keypress(function(e) {
			if(e.keyCode == 13) {
				gamma.dms_search(sel.val(), val.val()); 
				return false;
			}
		   return true;
		});
		sel.change(function(e) {
			gamma.dms_search(sel.val(), val.val()); 
		});
		go.click(function(e) {
			gamma.dms_search(sel.val(), val.val()); 
		});
	},
	
	//------------------------------------------
	// context values for current page
	// 
	// many library functions reference this object
	// and depend on proper values being defined 
	// by specific family page before they are called
	// - Id 
	// - base_url 
	// - cntrlContainerId 
	// - data_url 
	// - entityList 
	// - entity_type 
	// - file_name 
	// - hierarchy 
	// - is_ms_helper 
	// - my_tag 
	// - ops_url 
	// - processing_params 
	// - responseContainerId 
	// - site_url 
	// - update_in_progress 
	// - update_url 
	//------------------------------------------
	pageContext: {
	},
	//------------------------------------------
	// parsing stuff
	//------------------------------------------
	//Returns a copy of a string with leading and trailing whitespace removed.
	trim: function(str) {
		return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	},
	// parse tab-delimited line into array of trimmed values
	parse_lines: function(line) { 
		flds = [];
		var fields = line.split('\t');
		$.each(fields, function(idx, fld){
			flds.push(gamma.trim(fld));
		});
		return flds;
	},
	parseDelimitedText: function(text_fld, removeArtifact) {
		parsed_data = {};
		var lines = $('#' + text_fld).val().split('\n');
		var header = [];
		var data = [];
		$.each(lines, function(lineNumber, line){
			line = gamma.trim(line);
			if(line) {	
				var fields = gamma.parse_lines(line)
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
	// return text containing list of XML elements
	// with given element name and attributes extracted from objects
	// according to mapping array 
	getXmlElementsFromObjectArray: function(objArray, elementName, mapping) {
		var xml = '';
		if (typeof(objArray) != "undefined") {
			$.each(objArray, function(x, obj){
				xml += '<' + elementName;
				$.each(mapping, function(z, map) {
					if(typeof obj[map.p] != 'undefined'){
						xml += ' ' + map.a + '="' + obj[map.p] + '"';
					}
				});
				xml += ' />';
			});
		}
		return xml;
	},
	// return text containing list of XML elements
	// with given element name, one element for each item in the array
	// as attribute with given name
	getXmlElementsFromArray: function(itemArray, elementName, attributeName) {
		var xml = '';
		$.each(itemArray, function(x, item){
			xml += '<' + elementName;
			xml += ' ' + attributeName + '="' + item + '"';
			xml += ' />';
		});
		return xml;
	},
	// return new array consisting of items in target that are not in remove
	removeItems: function(target, remove) {
		var output = [];
		$.each(target, function(idx, item){
			if(remove.indexOf(item) === -1) {
				output.push(item);
			}
		});
		return output;
	},
	// display results returned by AJAX query of server
	// in floating modeless dialog (created dynamically)
	// (if ignoreIfClosed is false or undefined, always open 
	//  or update dialog, otherwise, only update if already open)
	updateMessageBox: function() {
		var dlg;
		return function(url, form, ignoreIfClosed) {
			var isClosed = (dlg) ? !dlg.dialog('isOpen') : true;
			if(ignoreIfClosed && isClosed) return;
			if(!dlg) {
				dlg = $('<div></div>').dialog({title: 'SQL', autoOpen: false});	
			}
			url = gamma.pageContext.site_url + url;
			var p = $('#' + form).serialize();
			$.post(url, p, function(data) {
					dlg.html(data);
					dlg.dialog('open');
				}
			);
		};
	}(),
	clearSelector: function(name) {
		$('#' + name + ' option').each(function(idx, opt) {
			opt.selected = false;
		});
	},
	sectionToggle: function(containerId, duration, element) {
		var speed = duration * 1000;
		$('#' + containerId).toggle(speed);
		if(element) {
			$(element).find('.expando_section').toggleClass('ui-icon-circle-plus ui-icon-circle-minus');
		}
		return false;
	},
	//------------------------------------------
	//search functions
	//------------------------------------------
	dms_search: function(url, srchVal) {
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
	// side menu functions
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
	// general AJAX post the fills given container
	// with returned text and allows pre and post
	// callbacks to be defined
	//------------------------------------------
	loadContainer: function (url, p, containerId, afterAction, beforeAction) { 
		var container = $('#' + containerId);
		var abort = false;
		if(beforeAction) { 
			abort = beforeAction();
		}
		if(abort) return;
		container.spin('small');
		$.post(url, p, function (data) {
				container.spin(false);
				container.html(data);
				if(afterAction) {
					afterAction();
				}
		});
	},	
	//------------------------------------------
	// general AJAX post that gets a data object 
	// from JSON returned by server and allows 
	// pre and post callbacks to be defined
	//------------------------------------------
	getObjectFromJSON: function (url, p, containerId, afterAction, beforeAction) { 
		var container = (containerId)?$('#' + containerId):null;
		var abort = false;
		if(beforeAction) { 
			abort = beforeAction();
		}
		if(abort) return;
		if(container) container.spin('small');
		$.post(url, p, function (data) {
				if(container) container.spin(false);
//				var data = $.parseJSON(json);
				if(afterAction) {
					afterAction(data);
				}
		});
	},
	//------------------------------------------
	// general AJAX post that calls server operation
	// and returns server response via callback
	//------------------------------------------
	doOperation: function (url, p, containerId, afterAction, beforeAction) {
		// make calling parameters from p
		// p can be form Id, raw object, or falsey
		var px = {};
		if(p) {
			if (typeof p === "string") {
				px = $('#' + p).serialize();
			} else {
				px = p;
			}
		}
		var container = $('#' + containerId);
		var abort = false;
		if(beforeAction) { 
			abort = beforeAction();
		}
		if(abort) return;
		container.spin('small');
		$.post(url, px, function (data) {
				container.spin(false);
				afterAction(data, container);
		});
	},
	//------------------------------------------
	// misc functions and objects
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
	},
	// object that chooser code uses to 
	// remember key parameters for off-page chooser
	currentChooser: {
		// callBack
		// delimiter
		// field
		// page
		// window
	},
	// go to new web page given by url currently selected in the given selection element
	goToSelectedPage: function(id) {
		var node = document.getElementById(id);
		window.location.href = node.options[node.selectedIndex].value;
	},
	load_script_diagram_cmd: function() {
		var scriptName = $('#scriptName').val();
		if(scriptName) {
			var url = gamma.pageContext.site_url + 'pipeline_script/dot/' + scriptName
			var p = { datasets: $('#datasets').val() };
			gamma.loadContainer(url, p, 'script_diagram_container');
		}
	},
	load_script_diagram: function () {
		var scriptName = $('#lnk_ID').html();
		if(scriptName) {
			var url = gamma.pageContext.site_url + 'pipeline_script/dot/' + scriptName
			gamma.loadContainer(url, {}, 'script_diagram_container'); 
		}
	}	
};

//------------------------------------------
//These functions are used by list reports
//------------------------------------------
var lambda = {	
	//this function acts as a hook that other functions call to 
	//reload the row data container for the list report.
	//it needs to be overridden with the actual loading
	//function defined on the page, which will be set up
	//with page-specific features
	reloadListReportData: function() {
		alert('"lambda.reloadListReportData" not overridden');
	},
	// for clearing cached page parameters
	setListReportDefaults: function(pageType) { 
		var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/defaults/' + pageType;
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
		container.spin('small');
		var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/' + action;
		var p = $('#' + formId).serialize();
		$.post(url, p, function (data) {
				container.spin(false);
			    container.html(data);
				if(follow_on_action && follow_on_action.run) {
					follow_on_action.run();
				}
			}
		);
	},
	//------------------------------------------
	// submit list report supplemental command
	submitOperation: function(url, p, show_resp) {
		var ctl = $('#' + gamma.pageContext.cntrlContainerId);
		var container = $('#' + gamma.pageContext.responseContainerId);
		container.spin('small');
		$.post(url, p, function (data) {
				container.spin(false);
				if(data.indexOf('Update failed') > -1) {
					container.html(data);
					ctl.show();
				} else {
					var msg = 'Operation was successful';
					if(show_resp) msg = data;
					container.html(msg);
					ctl.hide();
					lambda.reloadListReportData();
				}
			}
		);
	},
	//------------------------------------------
	//loads a SQL comparison selector (via AJAX)
	loadSqlComparisonSelector: function(containerId, url, col_sel) {
		url += $('#' + col_sel).val();
		gamma.loadContainer(url, {}, containerId);
	},
	//clear the specified list report search filter
	clearSearchFilter: function(filter) {
		$( '.' + filter).each(function(idx, obj) {obj.value = ''} );
		lambda.is_filter_active();
	},
	//clear the list report search filters
	clearSearchFilters: function() {
		$(".filter_input_field").each(function(idx, obj) {obj.value = ''} );
		lambda.is_filter_active();
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
			lambda.reloadListReportData('autoload');
		}
	},
	//------------------------------------------
	// paging
	//set the current starting row for the list report
	setListReportCurRow: function(row) {
		$('#qf_first_row').val(row);
	 	lambda.reloadListReportData();
	},
	setPageSize: function(curPageSize, totalRows, max) {
		var reply = lambda.getPageSizeFromUser(curPageSize, totalRows, max);
		if(reply == null) return;
		lambda.setPageSizeParameter(reply);
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
		    lambda.reloadListReportData();
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
		lambda.set_filter_active_indicator(filterFlag, sortFlag);
	},
	filter_key: function(e) {
		var code;
	//	if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		if(code == 13) {
			$('#qf_first_row').val(1);
		    lambda.reloadListReportData();
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
	transferSelectedListData: function(perspective) {
		var list = lambda.getCkbxList('ckbx' );
		if(list=='') {
			alert('You must select at least 1 item.'); 
			return;
		}
		if ( !confirm("Are you sure that you want to transfer the selected data?") )
			return;
	
		var url =  gamma.pageContext.site_url + "/data_transfer/" + perspective;
		var p = {};
		p.perspective = perspective;
		p.iDList = list;
		lambda.submitOperation(url, p);
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
			lambda.setCkbxFromList(checkBoxName, list);
		}
	},
	//------------------------------------------
	// misc
	//------------------------------------------
	download_to_doc: function(format) {
		var row_count = $('#total_rowcount').html();
		if(row_count > 4000) {
			if (!confirm('Are you sure you want to export ' + row_count + ' rows?') ) return;
		}
		var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/export/' + format
		gamma.export_to_doc(url, "filter_form");
	}	
	
}; // lambda

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
		var container = $('#' + gamma.pageContext.responseContainerId);
		container.spin('small');
		$.post(url, p, function (data) {
				container.spin(false);
			    container.html(data);
				delta.updateMyData();	
			}
		);
	},
	updateContainer: function(url, containerId) {
		var container = $('#' + containerId);
		url = gamma.pageContext.site_url + url;
		var p = {};
		container.spin('small');
		$.post(url, p, function (data) {
				container.spin(false);
			    container.html(data);
			}
		);
	},
	updateMyData: function() {
		delta.updateContainer(gamma.pageContext.my_tag + '/show_data/' + gamma.pageContext.Id, 'data_container'); 
	},
	processResults: function(data, container) {
		if(data.indexOf('html failed') > -1) {
			container.html(data);
		} else {
			container.html('Operation was successful');
			delta.updateMyData();
		}
	},
	updateShowSQL: function () {
		gamma.updateMessageBox(gamma.pageContext.my_tag + '/detail_sql/' + gamma.pageContext.Id, 'OFS'); 
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
				var fieldName = this.name.replace('_ckbx_enable', '');
				that.enableDisableField(this, fieldName);
			}
		);
	},
	// style associated entry field for checkbox
	// according to whether it is enabled or disabled
	enableDisableField: function(chkbx, fieldName)
	{
		if(chkbx.checked) {
			$('#' + fieldName).css("color", "Black");
		} else {
			$('#' + fieldName).css("color", "Silver");
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
	showHideSections: function(action, list) {
		var blks = [];
		if(!list || list === 'all') {
			$('.section_block_header_all').each(function() {
				var hb = this.id;
				var sb = hb.replace('section_block_header_', '');
				blks.push(sb);
			});
		} else {
			blks = list.split(',');
		}
		$.each(blks, function(idx, blk) {
			sb = 'section_block_' + gamma.trim(blk);
			if(action === 'show') {
				epsilon.showSection(sb);
			}
			if(action === 'hide') {
				epsilon.hideSection(sb);
			}
		});
	},
	//------------------------------------------
	//These functions are used by entry page that invokes 
	//list report chooser
	//Note: a global variable "gamma.currentChooser" that references an
	//empty object must be defined by the entry page 
	//that usese these functions
	//------------------------------------------
	
	closeChooserWindowPage: function() {
		if (gamma.currentChooser.window && !gamma.currentChooser.window.closed) {
			gamma.currentChooser.window.close();
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
		if( chooserPage.match(/\/$/) || chooserPage.match(/~$/) ) {
			sep = '';
		}
		// if there is a cross-reference, pass it on end of URL
		if(xv != '') chooserPage += sep + xv;
		// make sure that there are no other chooser pages open
		epsilon.closeChooserWindowPage();
		// remember which field gets the update 
		// for when the chooser page calls back (updateFieldValueFromChooser)
		gamma.currentChooser.field = fieldName;
		gamma.currentChooser.delimiter = delimiter;
		gamma.currentChooser.page = chooserPage;
		// open the chooser page in another window
		gamma.currentChooser.window = window.open(chooserPage, "HW", "scrollbars,resizable,height=550,width=1000,menubar");
	},
	//this function is called by an external chooser
	//page to update the value in the field that it is serving
	updateFieldValueFromChooser: function(value, action) {
		// todo: make sure gamma.currentChooser.field is defined
		fld = $('#' + gamma.currentChooser.field)[0];
		// lists are always transmitted as comma-delimited
		// and field may need a different delimiter
		if(gamma.currentChooser.delimiter != ',') {
			value = value.replace(/,/g, gamma.currentChooser.delimiter);
		}
		// replace or append new value, as appropriate
		if(action == "append") {
			if (gamma.currentChooser.delimiter != "" && fld.value != "") {
				fld.value += gamma.currentChooser.delimiter + " " + value;		
			} else {
				fld.value += value;		
			}
		} else { // replace
			fld.value = value;
		}
		// we are done with chooser page - make it go away
		epsilon.closeChooserWindowPage();
		if(gamma.currentChooser.callBack) {
			gamma.currentChooser.callBack();
		}
	},
	getFieldValueForChooser: function() {
		// todo: make sure gamma.currentChooser.field is defined
		var value = $('#' + gamma.currentChooser.field).val();
		if(gamma.currentChooser.delimiter != ',') {
			value = value.replace(/gamma.currentChooser.delimiter/g, ',');
		}
		return value;
	},
	//for datepicker
	callDatepicker: function(fieldName) {
		var fld = $('#' + fieldName);
		if(!fld.data().datepicker) {
					fld.datepicker();
		}
		fld.focus();
	},
	//------------------------------------------
	// used for entry page submission
	//------------------------------------------
	
	// object to contain entry page context values
	// (must be initialized prior to library functions being called)
	pageContext: {
		containerId: null,
		modeFieldId: null,
		entryFormId: null		
	},
	// contains any actions to be performed prior to and after 
	// AJAX submission 
	actions: {
		before: null,
		after:null
	},
	// called by the built-in entry page family submission controls
	// submit the entry form to the entry page or alternate submission logic
	submitStandardEntryPage: function(url, mode) {
		epsilon.submitEntryFormToPage(url, mode, this.actions.after, this.actions.before);
	},
	//POST the entry form to the entry page via AJAX
	// perform beforeAction (if defined) prior to submission - abort if it returns true
	// perform afterAction (if defined) after receiving results of submission
	submitEntryFormToPage: function(url, mode, afterAction, beforeAction) {
		var container = $('#' + this.pageContext.containerId);
		var modeField = $('#' + this.pageContext.modeFieldId);
		var entryForm = $('#' + this.pageContext.entryFormId);
		modeField.val(mode);
		p = entryForm.serialize();
		var proceed = true;
		if(beforeAction) { 
			proceed = beforeAction();
		}
		if(!proceed) return;
		if(!confirm("Are you sure that you want to perform this action?")) return;
		container.spin('small');
		$.post(url, p, function (data) {
				container.spin(false);
			    container.html(data);
				setTimeout("epsilon.adjustEnabledFields()", 350);
				if(afterAction) {
					afterAction();
				}
			}
		);
	},
	// POST the entry form to another page
	submitEntryFormToOtherPage: function(url, mode) {
		var modeField = $('#' + epsilon.pageContext.modeFieldId);
		var entryForm = $('#' + epsilon.pageContext.entryFormId);
		modeField.val(mode);
		entryForm.action = url;
		entryForm.method="post";
		entryForm.submit();
	},	

	//------------------------------------------
	// supplemental parameter entry forms
	//------------------------------------------
	
	// get supplemental form fields via an AJAX call
	load_suplemental_form: function(url, p, containerId, afterAction, beforeAction) {
		var container = $('#' + containerId);
		var abort = false;
		if(beforeAction) { 
			abort = beforeAction();
		}
		if(abort) return;
		container.spin('small');
		$.post(url, p, function (data) {
				container.spin(false);
			    container.html(data);
				if(afterAction) {
					afterAction();
				}
			}
		);
	},
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
	    fld.val(fld.val().replace(re, repStr));
	},
	formatXMLText: function(fieldName) {
		var fld = $('#' + fieldName);
	    var findStr = "><";
	    var repStr = ">\n<";
	    var re = new RegExp(new RegExp(findStr, "g")); 
	    fld.val(fld.val().replace(re, repStr));
	}
	
};
