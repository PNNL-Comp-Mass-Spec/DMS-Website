<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/slickgrid2css') ?>

<?php $chimg = base_url("images/chooser.png"); ?>

<style>
    .ui-widget {
        font-size: 1em;
    }
</style>

</head>

<body>
<?php echo view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'>Factors</legend>

    <table>
    <tr>
    <td>
    <div id="source_selector">
    <input type="radio" id="source_type_request" name="source_type" value="Requested_Run_ID" checked="checked" /><label for="source_type_request">Requests</label>
    <input type="radio" id="source_type_dataset" name="source_type" value="Dataset_Name"/><label for="source_type_dataset">Datasets</label>
    </div>
    </td>

    <td>
    <div id='ds_chsr_panel' style='display:none;' class='ctls_grp' data-target='datasetItemList'>

<!-- Deprecated
    <span class='ctls' data-query='osm_package_datasets' >
    From OSM package <input type='text' size='10' class='dms_autocomplete_chsr' data-query='osm_package_list' /><a class='button' href='javascript:void(0)' >Get</a>
    </span>
-->
    <span class='ctls' data-query='data_package_datasets' >
    From Data package <input type='text' size='10' class='dms_autocomplete_chsr' data-query='data_package_list' /><a class='button' href='javascript:void(0)' >Get</a>
    </span>
    <span class='ctls'>
    From datasets... <a href="javascript:epsilon.callChooser('datasetItemList', '<?= site_url() ?>/helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
    </span>
    </div>

<!-- Deprecated
    <div id='req_chsr_panel' class='ctls_grp' data-target='requestItemList'>
    <span class='ctls' data-query='osm_package_requests' >
    From OSM package <input type='text' size='10' class='dms_autocomplete_chsr' data-query='osm_package_list' /><a class='button' href='javascript:void(0)' >Get</a>
    </span>
-->
    <span class='ctls'>
    From requested runs or batches... <a href="javascript:epsilon.callChooser('requestItemList', '<?= site_url() ?>/helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
    </span>
    </div>

    </td>
    </tr>

    <tr>
    <td colspan=2>
    <textarea cols="100" rows="5" name="requestItemList" id="requestItemList" onchange="epsilon.convertList('requestItemList', ',')" ></textarea>
    <textarea cols="100" rows="5" name="datasetItemList" id="datasetItemList" onchange="epsilon.convertList('datasetItemList', ',')" style="display:none;"></textarea>
    </td>
    </tr>

    </table>

</fieldset>
</form>

<?php echo view('grid/grid_control_panel') ?>

<div id="myTable" ></div>

<?php echo view('grid/delimited_text') ?>

<?php echo view('resource_links/base2js') ?>
<?php echo view('resource_links/slickgrid2js') ?>

<script>
    // This code is used by https://dms2.pnl.gov/factors/grid

    gamma.pageContext.site_url = '<?= site_url() ?>';
    gamma.pageContext.save_changes_url = '<?= $save_url ?>';
    gamma.pageContext.data_url = '<?= $data_url ?>';

    var myCommonControls;
    var myImportExport;
    var myGrid;
    var gridConfig = {
        hiddenColumns: ['sel', 'batch_id', 'experiment'],
        staticColumns: ['request', 'name', 'dataset', 'status' ],
        getLoadParameters: function() {
            return sourceListSectionsUtil.getSourceList();
        },
        afterLoadAction: function() {
            myCommonControls.enableAddColumn(true);
            myCommonControls.enableSave(false);
        },
        getSaveParameters: function() {
            var dataRows = myGrid.grid.getData();
            var changes = gridUtil.getChanges(dataRows, 'request');
            var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
            factorXML = '<id type="Request" />' + factorXML;
            return { factorList: factorXML };
        },
        afterSaveAction: function() {
            myCommonControls.reload();
        },
        handleDataChanged: function() {
            myCommonControls.enableSave(true);
        },
        getContextMenuHandler: function() {
            var ctx = contextMenuManager.init(this).buildBasicMenu();
            return function (e) {
                ctx.menuEvtHandler(e);
            }
        }
    }
    var myUtil = {
        preImportAction: function(inputData) {
            if($.inArray('request', inputData.columns) === -1) {
                alert('Imported data must contain the "request" column');
                return false;
            }
        },
        postImportAction: function() {
                var requests = $.map(myGrid.grid.getData(), function(row) {return row['request']; });
                sourceListSectionsUtil.setRequestSource(requests);
                myCommonControls.enableAddColumn(true);
                myCommonControls.enableSave(true);
        },
        postUpdateAction: function() {
                myCommonControls.enableSave(true);
        },
        initEntryFields: function() {
        }
    }

    // set up and manage filter section controls
    var sourceListSectionsUtil = {
        setup: function() {
            var context = this;
            $( "#source_selector" ).buttonset();
            $('#source_selector input:radio').on("click", function() {
                context.setItemSource(this.value);
            });
        },
        setItemSource: function(source) {
            if(source == "Dataset_Name") {
                $('#req_chsr_panel').hide();
                $('#requestItemList').hide();
                $('#ds_chsr_panel').show();
                $('#datasetItemList').show();
            } else {
                $('#req_chsr_panel').show();
                $('#requestItemList').show();
                $('#ds_chsr_panel').hide();
                $('#datasetItemList').hide();
            }
        },
        setRequestSource: function(requests) {
            $('#requestItemList').val(requests.join(', '));
            $('#source_type_request').attr("checked","checked").button('refresh');
            var source = $("#source_selector input[type='radio']:checked").val();
            this.setItemSource(source);
        },
        getSourceList: function() {
            var sourceType = $("#source_selector input[type='radio']:checked").val();
            var itemList = (sourceType == 'Dataset_Name') ? $('#datasetItemList').val() : $('#requestItemList').val() ;
            return { item_list:itemList, item_type:sourceType };
        }
    }

    $(document).ready(function () {
        myGrid = mainGrid.init(gridConfig);
        myCommonControls = commonGridControls.init(myGrid);
        myImportExport = gridImportExport.init(myGrid,  {
            preImportAction: myUtil.preImportAction,
            postImportAction: myUtil.postImportAction,
            postUpdateAction: myUtil.postUpdateAction,
            acceptNewColumnsOnUpdate: true
        });

        sourceListUtil.setup();
        sourceListSectionsUtil.setup();
        gamma.autocompleteChooser.setup();

        myUtil.initEntryFields();
        myCommonControls.showControls(true);

    });

</script>

</body>
</html>
