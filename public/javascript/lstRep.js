var lstRep = {
	// load the filter panel according to the given layout mode
	updateMyFilter: function($mode) {
		lambda.updateContainer('report_filter/' + $mode, 'filter_form', 'search_filter_container', lstRep.filter_observers_action);
		if($mode == 'minimal') {
			$('#show_more_filter').show();$('#show_less_filter').hide();
		} else {
			$('#show_more_filter').hide();$('#show_less_filter').show();
		}
	},
	// bind observers to the filter fields to monitor filter status
	// and initialize filter status display
	filter_observers_action: {
		run:function() {
			lambda.set_filter_field_observers();
			lambda.is_filter_active();
			lambda.adjustFilterVisibilityControls();
		}
	},
	// copy the contents of the upper paging display to the lower one
	paging_cleanup_action: {
		run:function() {
			$('#paging_container_lower').html($('#paging_container_upper').html());
		}
	},
	// update the paging display sections, or hide them if no data rows
	paging_update_action: {
		run:function() {
			if($('#data_message').val() != null) {
				$('#paging_container_upper').hide();
				$('#paging_container_lower').hide();
			} else {
				$('#paging_container_upper').show();
				$('#paging_container_lower').show();
				lambda.updateContainer('report_paging', 'filter_form', 'paging_container_upper', lstRep.paging_cleanup_action);
			}
		}
	},
	// call paging action and also initialize checkbox state if this page is a helper
	data_post_load_action: {
		run:function(){
			lstRep.paging_update_action.run();
			if(!$('#data_message') && gamma.pageContext.is_ms_helper) { lambda.intializeChooserCkbx('ckbx') }
		}
	},
	// go get some data rows
	data_update_action: {
		run:function(){
			lambda.updateContainer('report_data', 'filter_form', 'data_container', lstRep.data_post_load_action);
		}
	},
	updateShowSQL: function(ignoreIfClosed) {
		// Note that string 'SQL' is used in gamma.updateMessageBox to trigger adding line breaks
		gamma.updateMessageBox(gamma.pageContext.my_tag + '/report_info/sql', 'filter_form', 'SQL', ignoreIfClosed);
	},
	updateShowURL: function(ignoreIfClosed) {
		gamma.updateMessageBox(gamma.pageContext.my_tag + '/report_info/url', 'filter_form', 'URL', ignoreIfClosed);
	},
	// start the data update chain for the page
	updateMyData: function(loading) {
		if(loading == 'no_load') {
			$('#data_container').html('Data will be displayed after you click the "Search" button.');
		} else {
			if(loading && loading == 'reset') $('#qf_first_row').val(1);
			lstRep.data_update_action.run();
		}
	}
} // lstRep

// Omicron is used by the run_op_logs page family
var omicron = {
	update: function(url, value, dataRow, fields) {
		var myForm;
		var formSpecs = {
			title: 'Update ' + value,
			id_fld: value,
			url: url,
			fields: sigma.makeFieldSpecsFromList(fields),
			onSave: function() {
				lstRep.updateMyData("retain_paging");
			}
		}
		myForm = $.extend(sigma, formSpecs);
		myForm.setFieldValues(dataRow);
		myForm.showForm();
	}
}

var sigma = {
	// fields title id_fld
	// onSave, onCancel, onClose
	showForm: function() {
		var context = this;
		var tags = this.buildForm(this.fields);
		var height = this.height || (150 + (30 * this.fields.length));
		var width = this.width || 350;
	    $('<div id="Weltanschauung">' + tags + '</div>').dialog({
	      autoOpen: true,
	      height: height,
	      width: width,
	      modal: true,
	      title: this.title,
	      buttons: {
	        "Save": function() {
	        	var dlg = this;
				var p = context.getFieldValues();
				gamma.doOperation(context.url, p, 'Weltanschauung', function(data, container) {
					var response = $.parseJSON(data);
					if(response.result) {
						alert(response.message);
					} else {
			        	$(dlg).dialog( "close" );
			        	context.onSave();
			        }
				});
	        },
	        Cancel: function() {
	          $(this).dialog( "close" );
	          if(context.onCancel) context.onCancel();
	        }
	      },
	      close: function() {
	          if(context.onClose) context.onClose();
	      }
	    });
	},
	makeFieldSpecsFromList: function(fieldList) {
		var id, specs = [];
		$.each(fieldList.split(','), function(i, name) {
			name = $.trim(name);
			id = name.toLowerCase().replace(' ', '_') + '_fld';
			specs.push({label:name, id:id, map:name});
		});
		return specs;
	},
	setFieldValues: function(values) {
		$.each(this.fields, function(idx, field) {
			var x = (field.map) ? field.map : field.label;
			field.value = (values[x]) ? values[x] : '';
		});
	},
	getFieldValues: function() {
		var fv = { id_fld: this.id_fld };
		$.each(this.fields, function(idx, field) {
			fv[field.id] = $('#' + field.id).val();
		});
		return fv;
	},
	buildForm: function(fieldSpecs) {
		var tags = '';
		var tmplt = '<tr><td>@lbl@</td><td><input type="text" name="@id@" id="@id@" class="dlg_form_field" value="@v@" style="width:100%"/></td></tr>';
		tags += '<form><table style="width:100%">';
		$.each(fieldSpecs, function(idx, fieldSpec) {
			tags += tmplt.replace(/@lbl@/g, fieldSpec.label).replace(/@id@/g, fieldSpec.id).replace('@v@', fieldSpec.value);
		});
		tags += '</table></form>';
		return tags;
	}
}


// after the page loads, set things in motion to populate it
$(document).ready(function () {
		lstRep.updateMyFilter('minimal');
		lstRep.updateMyData(gamma.pageContext.initalDataLoad);
	 	lambda.reloadListReportData = function() { lstRep.updateMyData('autoload');}
});