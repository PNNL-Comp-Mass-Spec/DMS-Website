// Specialized code for specific list report cmd pages
// To force the reload of this file, update the version in app\Views\resource_links\lcmd.php
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
                // dmsChooser.getCkbxList is in dmsChooser.js
                list = dmsChooser.getCkbxList('ckbx');
                if (list == '') {
                    alert('You must select items.');
                    return;
                }
            }
            if ( !confirm("Are you sure that you want to update the database?") )
                return;
            // URL will point to the operations_sproc value defined in analysis_job_processor_group_association.db, typically update_analysis_job_processor_group_associations
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.newValue = '';
            p.processorGroupID = $('#pf_groupid').val();
            if(p.processorGroupID == '') {alert('No group ID in primary filter'); return;}
            p.JobList = list;
            dmsOps.submitOperation(url, p);
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
                // dmsChooser.getCkbxList is in dmsChooser.js
                list = dmsChooser.getCkbxList('ckbx');
                if (list == '') {
                    alert('You must select items.');
                    return;
                }
            }
            if ( !confirm("Are you sure that you want to update the database?") )
                return;
            if(mode=='set_membership_enabled') mode = $('#' + p1).val();
            // URL will point to the operations_sproc value defined in analysis_job_processor_group_association.db, typically update_analysis_job_processor_group_membership
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.newValue = (p2)?$('#' + p2).val():'';
            p.processorGroupID = $('#pf_groupid').val();
            if(p.processorGroupID == '') {alert('No group ID in primary filter'); return;}
            p.processorNameList = list;
            dmsOps.submitOperation(url, p);
        }
    },
    data_package_job_coverage: {
        op: function(mode){
            // dmsChooser.getSelectedItemList is in dmsChooser.js
            // The item names come from the value field of the selected checkboxes on the web page, for example:
            // <input type="checkbox" value="<item pkg="2900" type="Job" id="1511459"></item>" name="ckbx" class="lr_ckbx">
            var iList = dmsChooser.getSelectedItemList();
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

            var removeParents = 0;
            if (document.getElementById('removeParentsCheckbox').checked)
                removeParents=1;

            var url = dmsjs.pageContext.site_url + "data_package_items/exec";

            console.log("Contacting " + url);
            console.log("with p.paramListXML = " + list);
            console.log("and p.removeParents = " + removeParents);

            var p = {};
            p.command = mode;
            p.paramListXML = list;
            p.removeParents = removeParents;

            // Call stored procedure update_data_package_items_xml
            // dmsOps.submitOperation is defined in dmsOps.js
            dmsOps.submitOperation(url, p, true);
        },
        getDatasetInfo: function (mode) {
            var id = $('#pf_data_package_id').val();
            var tool = $('#tool_name').val();
            if(id == '') {alert('data_package_id filter not set'); return;}
            var url = dmsjs.pageContext.site_url + 'data_package/ag/' + id + '/' + tool + '/' + mode;
            $('#dataset_dump_field').html('');
            dmsOps.loadContainer(url, {}, 'dataset_dump_field');
        }
    },
    dataset_disposition: {
        op: function(mode) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select datasets.');
                return;
            }
            if ( !confirm("Are you sure that you want to update the database?") )
                return;
            // URL will point to the operations_sproc value defined in dataset_disposition.db, typically update_dataset_dispositions
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.datasetIDList = list;
            p.rating = $('#rating_fld').val();
            p.comment = $('#comment_fld').val();
            p.recycleRequest = $('#recycle_fld').val();
            dmsOps.submitOperation(url, p);
        }
    },
    instrument_allowed_dataset_type: {
        localRowAction: function (url, value, obj) {
            $('#instrument_group_fld').val(obj["instrument_group"]);
            $('#dataset_type_fld').val(obj["dataset_type"]);
            $('#usage_fld').val(obj["usage_for_this_group"]);
        },
        op: function(mode) {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.InstrumentGroup = $('#instrument_group_fld').val();
            p.DatasetType = $('#dataset_type_fld').val();
            p.Comment = $('#usage_fld').val();
            dmsOps.submitOperation(url, p);
        }
    },
    material_move_container: {
        op: function(mode, val) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            // Valid options for mode:
            //   retire_container
            //   retire_container_and_contents
            //   unretire_container
            //   move_container
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select containers.');
                return;
            }
            if (!confirm("Are you sure that you want to update the database?")) return;
            // URL will point to the operations_sproc value defined in material_container.db, typically do_material_container_operation
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.containerList = list;
            p.newValue = (val)?$('#' + val).val():'';
            p.comment = $('#comment_fld').val();
            dmsOps.submitOperation(url, p);
        }
    },
    material_move_items: {
        op: function(mode, itemType, val) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            // Contents of list will be of the form E:8432,E:8435,R:170
            // where the item names come from the value text associated with each checkbox
            // Checkbox names come from column #I_ID in view V_Material_Items_List_Report
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select items.');
                return;
            }
            if(list.length > 4096) {
                // Stored procedure update_material_items has argument @itemList varchar(4096)
                alert('You have selected more items than the system can handle at one time.  Please select fewer items and try again.');
                return;
            }
            if (!confirm("Are you sure that you want to update the database?")) return;
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.itemType = itemType;
            p.itemList = list;
            p.newValue = (val)?$('#' + val).val():'';
            p.comment = $('#comment_fld').val();
            dmsOps.submitOperation(url, p);
        }
    },
/*  OMCS-977
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
            // URL will point to the operations_sproc value defined in osm_package_items.db, typically UpdateOSMPackageItemsXML
            var url = dmsjs.pageContext.site_url + 'osm_package_items/operation';
            $('#paramListXML').val(list);
            $('#entry_cmd_mode').val(mode);
            var p = $('#operation_form').serialize();
            dmsOps.submitOperation(url, p);
        }
    },
*/
    requested_run_admin: {
        // mode is the update mode, to be passed to the operation stored procedure
        // value is the new value
        op: function(mode, value) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select requested runs.');
                return;
            }
            if(list.length > 64000) {
                // Stored procedure update_requested_run_assignments has argument @reqRunIDList varchar(max)
                // We can thus push in more than 8000 characters; the 64000 limit is an arbitrary limit
                alert('You have selected more items than the system can handle at one time.  Please select fewer items and try again.');
                return;
            }
            if (!confirm("Are you sure that you want to update the database?")) return;
            // URL will point to the operations_sproc value defined in requested_run.db: update_requested_run_assignments
            // See: http://dmsdev.pnl.gov/config_db/edit_table/requested_run.db/general_params
            var url = dmsjs.pageContext.ops_url;
            var p = {};

            // This is auto-mapped to the @mode parameter of the stored procedure
            p.command = mode;

            // The following two form fields are defined in the sproc_args table for page family https://dmsdev.pnl.gov/config_db/edit_table/requested_run.db/sproc_arg
            //   param maps to the @newValue parameter
            //   id    maps to the @reqRunIDList parameter
            // Since "id" is lowercase in "p.id", the field name must also be lowercase

            p.param = (value)?$('#' + value).val():'';
            p.id = list;

            dmsOps.submitOperation(url, p);
        }
    },
    sample_prep_request_assignment: {
        op: function(mode, value) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select prep requests.');
                return;
            }
            if ( !confirm("Are you sure that you want to update the database?") )
                return;
            // URL will point to the operations_sproc value defined in sample_prep_request_assignment.db, typically update_sample_request_assignments
            var url = dmsjs.pageContext.site_url + "sample_prep_request_assignment/operation";
            var p = {};
            p.command = mode;
            p.newValue = (value)?$('#' + value).val():'';
            p.reqIDList = list;
            dmsOps.submitOperation(url, p);
        }
    },
    mc_enable_control_by_manager: {
        op: function(mode, newValFld) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select at least one manager.');
                return;
            }
            if ( !confirm("Are you sure that you want to update the database?") )
                return;
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.newValue =  $('#' + newValFld).val();
            p.paramName = mode;
            p.managerIDList = list;
            dmsOps.submitOperation(url, p);
        }
    },
    mc_enable_control_by_manager_type: {
        op: function(mode, newValFld) {
            // dmsChooser.getCkbxList is in dmsChooser.js
            var list = dmsChooser.getCkbxList('ckbx');
            if(list=='') {
                alert('You must select at least one manager type.');
                return;
            }
            if ( !confirm("Are you sure that you want to update the database?") )
                return;
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = mode;
            p.newValue =  $('#' + newValFld).val();
            p.paramName = mode;
            p.managerTypeIDList = list;
            dmsOps.submitOperation(url, p);
        }
    },
    dataset_ext_cmds: {
        transferData: function (perspective, dslist) {

            var commalist = $('#' + dslist).val();

            // dmsChooser.getCkbxList is in dmsChooser.js
            var list = dmsChooser.getCkbxList('ckbx' );
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

            var url = dmsjs.pageContext.site_url + "/data_transfer/" + perspective;
            var p = {};
            p.perspective = perspective;
            p.iDList = commalist + list;
            dmsOps.submitOperation(url, p);
        }
    },
    lc_cart_request_loading: {
        getEditFieldsObjList: function () {
            // go through editable fields and build array of objects
            // where each object references the fields for
            // one block
            var rlist = [];
            $('.cart').each(function(idx, cartField) {
                var obj = {};
                obj.req = cartField.name;
                obj.cart = cartField.value;
                obj.col = $('#col_' + obj.req).val();
                obj.cartConfig = $('#cart_config_' + obj.req).val();
                rlist.push(obj);
            });
            return rlist;
        },
        saveChangesToDatabase: function () {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var rlist = this.getEditFieldsObjList();
            var mapP2A = [{p:'req', a:'rq'}, {p:'cart', a:'ct'}, {p:'col', a:'co'}, {p:'cart_config', a:'cg'}];
            var xml = dmsInput.getXmlElementsFromObjectArray(rlist, 'r', mapP2A);
            var url = dmsjs.pageContext.ops_url;
            var p = {};
            p.command = 'update';
            p.cartAssignmentList = xml;
            dmsOps.submitOperation(url, p);
        },
        setCartName: function () {
            var iList = dmsChooser.getSelectedItemList();
            if (iList.length == 0) {
                alert('No items are selected');
                return;
            }
            var cart = $('#cart_name_input').val();
            if(cart == '') {
                alert('Cart name cannot be blank');
                return;
            }
            $.each(iList, function(idx, req) {
                $('#cart_' + req).val(cart);
            });
        },
        setCartConfigName: function () {
            var iList = dmsChooser.getSelectedItemList();
            if (iList.length == 0) {
                alert('No items are selected');
                return;
            }
            var cartConfig = $('#cart_config_input').val();
            if(cartConfig == '') {
                alert('Cart config name cannot be blank');
                return;
            }
            $.each(iList, function(idx, req) {
                $('#cart_config_' + req).val(cartConfig);
            });
        },
        setCartCol: function () {
            var iList = dmsChooser.getSelectedItemList();
            if (iList.length == 0) {
                alert('No items are selected');
                return;
            }
            var col = $('#col_input_setting').val();
            if(col < 1 || col > 8) {
                alert('Column out of range');
                return;
            }
            $.each(iList, function(idx, req) {
                $('#col_' + req).val(col);
            });
        }
    },
    dataset_instrument_runtime: {
        // get data rows via an AJAX call for list report
        // using all the current search filters, and build graph from it
        download_to_graph: function() {
            var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag + '/export_param/json'
            var p = $('#entry_form').serialize();
            dmsOps.getObjectFromJSON(url, p, 'graph_container', function(rows) {
                    lcmd.dataset_instrument_runtime.draw_graph(rows);
            });
        },
        draw_graph: function(rows) {
            var caption = "Dataset Acquisition/Interval Time For " + $('#instrument_name').val() + " From " + $('#start_date').val() + " To " + $('#end_date').val()
            $('#caption_container').html(caption);
            // Create a bar chart using Apache ECharts...
            var dataSeriesSet = lcmd.dataset_instrument_runtime.make_data_series_from_column(rows, "duration") ;
            var options = lcmd.dataset_instrument_runtime.set_graph_options(dataSeriesSet);
            $('#graph_container').show();
            var chartDom = document.getElementById('graph_container');
            var plot = echarts.init(chartDom);
            options && plot.setOption(options);
        },
        make_data_series_from_column: function(rows, colName) {
            var intervalSeries = [];
            var acquistionSeries = [];
            var index = 0;
            var yMax = 0;
            rows.forEach(function(obj) {
                    var val = obj[colName];
                    yMax = Math.max(yMax, val);
                    if(obj["seq"] > 0) {
                        if(obj["dataset"] == "Interval") {
                            var item = [];
                            item.push(index++);
                            item.push(val);
                            item.push(obj);
                            intervalSeries.push(item);
                        } else {
                            var item = [];
                            item.push(index++);
                            item.push(val);
                            item.push(obj);
                            acquistionSeries.push(item);
                        }
                    }
                }
            );
            // Note: this is actually unused, because the tooltip type is set to 'axis'; this will be used if the type is changed to 'item'
            var toolTipFormat = { formatter: function(d) { return '' + d.value[2]['dataset'] + '<br />Duration (minutes): ' + d.value[1] + '<br />ID: ' + d.value[2]['id'] + '<br />Time: ' + d.value[2]['time_start'] + ' - ' + d.value[2]['time_end']; } };
            // barGap is set to -100% to make ECharts treat the series as an overlapping series, because it wants to always group bars into categories...
            return {
                dataYmax : yMax,
                dataXmax : index - 1,
                series: [
                    { name: "Acquisition Time", color: '#0000ff', type: 'bar', barGap: '-100%', barCategoryGap: 0, tooltip: toolTipFormat, data: acquistionSeries },
                    { name: "Interval Time"   , color: '#ff0000', type: 'bar', barGap: '-100%', barCategoryGap: 0, tooltip: toolTipFormat, data: intervalSeries }
                ]
            };
        },
        set_graph_options: function(seriesData) {
            var yMax = Math.round(seriesData.dataYmax * 1.10);
            return {
                yAxis: { type: 'value', min: 0, max: yMax },
                legend: { orient: 'vertical', right: '20', top: '20', align: 'left', backgroundColor: '#ffffff', borderColor: '#000000', borderWidth: 2 },
                xAxis: { type: 'value', max: seriesData.dataXmax + 1, boundaryGap: false },
                grid: { top: '10', bottom: '0', left: '25', right: '0', show: true},
                animation: false,
                tooltip: { trigger: 'axis', axisPointer: { type: 'line', axis: 'x' }, formatter: function(d) { return '' + d[0].data[2]['dataset'] + '<br />Duration (minutes): ' + d[0].data[1] + '<br />ID: ' + d[0].data[2]['id'] + '<br />Time: ' + d[0].data[2]['time_start'] + ' - ' + d[0].data[2]['time_end']; } },
                toolbox: { show: true, itemSize: 20, feature: { dataZoom: { show: true }, restore: {} }, right: 200 },
                dataZoom: [ { type: 'inside' } ],
                series: seriesData.series
            };
        }
    }
} // lcmd

$(document).ready(function () {
    $('.sel_chooser').select2();
    if(dmsjs.pageContext.my_tag == 'requested_run_factors') {
        dmsChooser.currentChooser.callBack = tau.requested_run_factors.setItemTypeField;
    }
});

