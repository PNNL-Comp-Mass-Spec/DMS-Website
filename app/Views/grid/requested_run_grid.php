<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/slickgrid2css') ?>

<?php $chimg = base_url("images/chooser.png"); ?>

</head>

<body>
<?php echo view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'>Requested Runs</legend>

    <table>
    <tr>
    <td>
    </td>

    <td>
    <div id='req_chsr_panel' class='ctls_grp' data-target='requestItemList'>
    <span class='ctls' data-query='batch_requests' >
    From batch <input type='text' size='10' class='dms_autocomplete_chsr' data-query='requested_run_batch_list' /><a class='button' href='javascript:void(0)' >Get</a>
    </span>
<!-- Deprecated
    <span class='ctls' data-query='osm_package_requests' >
    From OSM package <input type='text' size='10' class='dms_autocomplete_chsr' data-query='osm_package_list' /><a class='button' href='javascript:void(0)' >Get</a>
    </span>
-->
    <span class='ctls'>
    From requested runs or batches... <a href="javascript:epsilon.callChooser('requestItemList', '<?= site_url() ?>/helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
    </span>
    </div>
    </td>

    <td rowspan="2">
        <div class="ctl_panel ctl_pane">
        <div class="ctl_panel">Blocking Commands</div>
        <div class="ctl_panel"><a class='button' id='globally_randomize_btn' href='javascript:void(0)' >Globally Randomize</a>
        </div>
        <div class="ctl_panel"><a class='button' id='randomly_block_btn' href='javascript:void(0)' >Randomly Block</a>
            <select id='block_size_ctl' ></select> (requests per block)
        </div>
        <div class="ctl_panel"><a class='button' id='factor_block_btn' href='javascript:void(0)'>Block by Factor</a>
            <select id='factor_select_ctl'></select>
        </div>
        </div>
    </td>
    </tr>

    <tr>
    <td colspan=2>
    <textarea cols="100" rows="5" name="requestItemList" id="requestItemList" onchange="epsilon.convertList('requestItemList', ',')" ></textarea>
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

<script src="<?= base_url('javascript/run_blocking_grid.js?version=104') ?>"></script>

<script>
    gamma.pageContext.site_url = '<?= site_url() ?>';
    gamma.pageContext.save_changes_url = '<?= $save_url ?>';
    gamma.pageContext.data_url = '<?= $data_url ?>';

    var myCommonControls;
    var myImportExport;
    var myBlockingUtil;
    var myGrid;

    var gridConfig = {

        // Columns are from stored procedure GetRequestedRunParametersAndFactors, and must be exact matches
        hiddenColumns: [],
        staticColumns: ['request', 'name', 'status', 'batch', 'experiment', 'dataset', 'lc_col', {id:"instrument"}, {id:"cart"}, {id:"block"}, {id:"run_order"}],
        getLoadParameters: function() {
            var itemList = $('#requestItemList').val();
            return { itemList:itemList };
        },
        afterLoadAction: function() {
            myCommonControls.enableSave(false);
            myCommonControls.enableAddColumn(true);
            myUtil.setFactorSelection();
            myUtil.setColumnMenuCommands();
        },
        getSaveParameters: function() {
            var mapP2A;
            var runParmColNameList = ['status', 'instrument', 'cart', 'block', 'run_order'];
            var dataRows = myGrid.grid.getData();
            var changes = gridUtil.getChanges(dataRows, 'request');

            var runParamChanges = [];
            var factorChanges = [];
            $.each(changes, function(idx, change) {
                if(runParmColNameList.indexOf(change.factor) === -1) {
                    factorChanges.push(change);
                } else {
                    runParamChanges.push(change);
                }
            });

            mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var factorXML = gamma.getXmlElementsFromObjectArray(factorChanges, 'r', mapP2A);
            factorXML = '<id type="Request" />' + factorXML;

            mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'t'}, {p:'value', a:'v'}];
            var runParamXML = gamma.getXmlElementsFromObjectArray(runParamChanges, 'r', mapP2A);

            return { factorList: factorXML, blockingList: runParamXML };
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
        lastNonFactorColumnName: 'run_order',
        initEntryFields: function() {
            this.initializeBlockingControlPanel();
        },
        initializeBlockingControlPanel: function() {
            // wire up command buttons
            $('#globally_randomize_btn').on("click", function() { myBlockingUtil.blockingOperation('global') }).attr('title', myBlockingUtil.titles.globally_randomize);
            $('#randomly_block_btn').on("click", function() { myBlockingUtil.blockingOperation('block') }).attr('title', myBlockingUtil.titles.randomly_block);
            $('#factor_block_btn').on("click", function() { myBlockingUtil.blockingOperation('factor') }).attr('title', myBlockingUtil.titles.factor_block);
            // set block size selector options
            var el = $('#block_size_ctl');
            for(var i = 2; i < 10; i++) {
                var opt = $('<option></option>').attr('value', i).text(i);
                if(i == 4) opt.attr('selected',true)
                el.append(opt);
            }
        },
        preImportAction: function(inputData) {
            if($.inArray('request', inputData.columns) === -1) {
                alert('Imported data must contain the "request" column');
                return false;
            }
        },
        postImportAction: function() {
            var x = $.map(myGrid.grid.getData(), function(row) {return row['request']; });
            $('#requestItemList').val(x.join(', '));
            myCommonControls.enableSave(true);
            myCommonControls.enableAddColumn(true);
            myUtil.setFactorSelection();
            myUtil.setColumnMenuCommands();
        },
        preUpdateAction: function(inputData) {

        },
        postUpdateAction: function(newColumns) {
            myCommonControls.enableSave(true);
            myUtil.setFactorColumnCommands(newColumns);
            myUtil.setFactorSelection();
        },
        validateNewFactorName: function(newFactorName) {
            var ok = true;
            $.each(myGrid.grid.getColumns(), function(idx, col) {
                if(col.field == newFactorName) {
                    ok = false;
                }
            });
            if(!ok) {
                alert('New factor name is invalid (duplicates existing factor or is reserved word)');
            }
            return ok;
        },
        setFactorSelection: function() {
            var factors = myUtil.getFactorColNameList();
            var el = $('#factor_select_ctl');
            el.empty();
            $.each(factors, function(idx, factor) {
              el.append($('<option></option>').attr('value', factor).text(factor));
            });
        },
        afterBlockingOperation: function(blockingObjList) {
            myBlockingUtil.copyBlockingToData(blockingObjList);
            gridUtil.setChangeHighlighting(myGrid.grid);
            myGrid.grid.invalidateAllRows();
            myGrid.grid.render();
            myCommonControls.enableSave(true);
        },
        setColumnMenuCommands: function() {
            var blockCmds = [
                {command:'randomize-global', title:'Randomize Globally', tooltip:myBlockingUtil.titles.globally_randomize },
                {command:'randomly-block', title:'Randomly Block', tooltip:myBlockingUtil.titles.randomly_block }
            ];
            myGrid.setColumnMenuCmds(myBlockingUtil.blockNumberFieldName, blockCmds, true);

            var runOrderCmds = [
                {command:'randomize-blocks', title:'Randomize Blocks', tooltip:myBlockingUtil.titles.reorder_blocks }
            ];
            myGrid.setColumnMenuCmds(myBlockingUtil.runOrderFieldName, runOrderCmds, true);

            this.setFactorColumnCommands(myUtil.getFactorColNameList());

            var cmdHandlers = {
                'randomize-global': function(column, grid) { myBlockingUtil.blockingOperation('global'); },
                'randomly-block': function(column, grid) { myBlockingUtil.blockingOperation('block', true); },
                'randomize-blocks': function(column, grid) { myBlockingUtil.blockingOperation('reorder'); },
                'factor-blocks': function(column, grid) { myBlockingUtil.blockingOperation('factor', column.field); }
            }
            myGrid.registerColumnMenuCmdHandlers(cmdHandlers);

        },
        setFactorColumnCommands: function(colNames) {
            var cmds = [
                {command:'factor-blocks', title:'Block With This Factor', tooltip:myBlockingUtil.titles.factor_block }
            ];
            $.each(colNames, function(idx, colName) {
                myGrid.setColumnMenuCmds(colName, cmds, true);
            });
        },
        getFactorColNameList: function() {
            var ci = myGrid.grid.getColumnIndex(this.lastNonFactorColumnName);
            var factorCols = [];
            $.each(myGrid.grid.getColumns(), function(idx, colDef) {
                if(idx > ci) factorCols.push(colDef.field);
            });
            return factorCols;
        }
    }

    $(document).ready(function () {
        myGrid = mainGrid.init(gridConfig);
        myCommonControls = commonGridControls.init(myGrid);
        myImportExport = gridImportExport.init(myGrid, {
            preImportAction: myUtil.preImportAction,
            postImportAction: myUtil.postImportAction,
            preUpdateAction: myUtil.preUpdateAction,
            postUpdateAction: myUtil.postUpdateAction,
            acceptNewColumnsOnUpdate: true
        });

        sourceListUtil.setup();
        gamma.autocompleteChooser.setup();

        myBlockingUtil = runBlockingGridUtil.init(myGrid);
        myBlockingUtil.afterBlockingOperation = myUtil.afterBlockingOperation;

        myUtil.initEntryFields();
        myCommonControls.setAddColumnLegend('new factor named:');
        myCommonControls.beforeAddCol = myUtil.validateNewFactorName;
        myCommonControls.afterAddCol = function(colName) {
            myUtil.setFactorSelection();
            myUtil.setFactorColumnCommands([colName]);
        }
        myCommonControls.showControls(true);
    });

</script>

</body>
</html>
