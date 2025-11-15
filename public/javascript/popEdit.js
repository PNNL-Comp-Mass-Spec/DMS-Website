/*******************************************************************
* Javascript functions supporting a pop-up dialog to enter values for editing
*******************************************************************/

// popEdit is used by the run_op_logs page family
var popEdit = popEdit || {
    update: function(url, value, dataRow, fields) {
        var myForm;
        var formSpecs = {
            title: 'Update ' + value,
            id_fld: value,
            url: url,
            fields: popEditForm.makeFieldSpecsFromList(fields),
            onSave: function() {
                lstRep.updateMyData("retain_paging");
            }
        }
        myForm = $.extend(popEditForm, formSpecs);
        myForm.setFieldValues(dataRow);
        myForm.showForm();
    }
}

var popEditForm = popEditForm || {
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
                dmsOps.doOperation(context.url, p, 'Weltanschauung', function(data, container) {
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
