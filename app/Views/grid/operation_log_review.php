<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>
<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/slickgrid2css') ?>
</head>

<!-- This page is used by https://dms2.pnl.gov/run_op_logs/grid -->

<body>
<?php echo view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'><?= $title; ?></legend>
<table>
<tr>
    <td><span>Instrument</span></td>
    <td>
    <?= $choosers->get_chooser('instrument_fld', 'usageTrackedInstruments')?>
    </td>
    <td><span>Year</span></td>
    <td>
    <input name="year_fld" id='year_fld' size="6" class="spin_me" />
    </td>
    <td><span>Month</span></td>
    <td>
    <input name="month_fld" id='month_fld' size="6" class="spin_me" />
    </td>
    <td><span>Usage</span></td>
    <td>
        <select id='usage_selector' multiple data-placeholder='Select usage (optional)' >
        <option>CAP_DEV</option>
        <option>MAINTENANCE</option>
        <option>BROKEN</option>
        <option>USER</option>
        <option>USER_UNKNOWN</option>
        </select>
    </td>
    <td><span>Type</span></td>
    <td>
        <select id='type_selector' multiple data-placeholder='Select type (optional)' >
        <option>Dataset</option>
        <option>Long Interval</option>
        <option>Operation</option>
        <option>Configuration</option>
        <option></option>
        </select>
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

<script type="text/javascript">
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
    dmsjs.pageContext.save_changes_url = '<?= $save_url ?>';
    dmsjs.pageContext.data_url = '<?= $data_url ?>';

    var myHotlinks =  {
        interval: 'run_interval/show/',
        request: 'requested_run/show/',
        log: {
            condition_field: 'Type',
            'Operation': 'instrument_operation_history/show/',
            'Configuration': 'instrument_config_history/show/'
        }
    }
    var myFormatterFactory = cellLinkFormatterFactory.init(myHotlinks);
    var myCommonControls;
    var myImportExport;
    var myGrid;
    // meant to be extended with mainGrid object
    var gridConfig = {
        maxColumnChars: 50,
        hiddenColumns: ['Year', 'Month', 'Day'],
        staticColumns: ['entered', 'entered_by', 'instrument', 'type',
        {id:'minutes', ned:true },
        {id:'id', formatter:myFormatterFactory.makeFor('interval'), ned:true },
        {id:'log', formatter:myFormatterFactory.makeFor('log'), ned:true },
        {id:'request', formatter:myFormatterFactory.makeFor('request'), ned:true },
        {id:'usage'},
        {id:'proposal'},
        {id:'emsl_user'},
        {id:'note', editor:Slick.Editors.LongText}],
        getLoadParameters: function() {
            var p = {};
            var instruments = $('#instrument_fld_chooser').val();
            if(instruments) {
                p.instrument = $.map(instruments, function(item) { return "'" + item + "'"; }).join(', ');
            }
            var usage = $('#usage_selector').val();
            if(usage) {
                p.usage = $.map(usage, function(item) { return "'" + item + "'"; }).join(', ');
            }
            var type = $('#type_selector').val();
            if(type) {
                p.type = $.map(type, function(item) { return "'" + item + "'"; }).join(', ');
            }
            p.year = $('#year_fld').val();
            p.month = $('#month_fld').val();
            return p;
        },
        afterLoadAction: function() {
            myCommonControls.enableSave(false);
        },
        getSaveParameters: function() {
            var dataRows = myGrid.grid.getData();
            var invalidUsage = myUtil.findInvalidUsageProposal(dataRows);
            if(invalidUsage) {
                alert(invalidUsage);
                return false;
            }
            var runXml = myUtil.getRequestChangeXml(dataRows);
            var intervalXml = myUtil.getIntervalChangeXml(dataRows);
            var paramXml = runXml + intervalXml
            return { changes: paramXml };
        },
        afterSaveAction: function() {
            myCommonControls.reload();
        },
        handleDataChanged: function(args) {
            myCommonControls.enableSave(true);
            myUtil.adjustCapitalization(args);
        },
        editPermissionFilter: function(e,args) {
            return myUtil.isEditable(args.column.field, args.item.type);
        },
        getContextMenuHandler: function() {
            var ctx = contextMenuManager.init(this).buildBasicMenu(myUtil.cellProtectionChecker);
            return function (e) {
                ctx.menuEvtHandler(e);
            }
        }
    }

    // for the grunt work details
    var myUtil = {
        postImportAction: function() {
        },
        postUpdateAction: function() {
                myCommonControls.enableSave(true);
        },
        initEntryFields: function() {
            var d = new Date();
            //$('#instrument_fld_chooser').val('Exact01');
            $('#month_fld').val(d.getMonth() + 1);
            $('#year_fld').val(d.getFullYear());
        },
        getRequestChangeXml: function(dataRows) {
            var changes = myUtil.getChangedRows(dataRows, myUtil.isDataset);
            var mapP2A = [{p:'request', a:'request'}, {p:'usage', a:'usage'}, {p:'proposal', a:'proposal'}, {p:'emsl_user', a:'user'}];
            return dmsInput.getXmlElementsFromObjectArray(changes, 'run', mapP2A);
        },
        getIntervalChangeXml: function(dataRows) {
            var changes = myUtil.getChangedRows(dataRows, myUtil.isInterval);
            var mapP2A = [{p:'id', a:'id'}, {p:'note', a:'note'}];
            return dmsInput.getXmlElementsFromObjectArray(changes, 'interval', mapP2A);
        },
        /// move to gridUtil
        getChangedRows: function(dataRows, filter) {
            var changes = [];
            $.each(dataRows, function(idx, row) {
                if(row.mod_axe && filter(row)) {
                    changes.push(row);
                }
            });
            return changes;
        },
        isDataset: function(row) {
            return (typeof row.type != 'undefined') && (row.type === 'Dataset');
        },
        isInterval: function(row) {
            return (typeof row.type != 'undefined') && (row.type === 'Long Interval');
        },
        adjustCapitalization: function(args) {
            if(!args) return;
            var field = args.grid.getColumns()[args.cell].field;
            var row = args.grid.getData()[args.row];
            if(myUtil.isDataset(row) && field === 'usage') {
                row.usage = row.usage.toUpperCase();
                args.grid.invalidateRows([args.row]);
                args.grid.render();
            }
        },
        findInvalidUsageProposal: function(dataRows) {
            var message = '';
            var changes = myUtil.getChangedRows(dataRows, myUtil.isDataset);
            $.each(changes, function(idx, row) {
                if((row.usage === 'USER' || row.usage === 'USER_ONSITE' || row.usage === 'USER_OFFSITE') && !(row.proposal) ){
                    message = "No proposal for USER for " + row.note;
                    return false;
                }
            });
            return message;
        },
        isEditable: function(field, type) {
            if((field == 'usage' || field == 'proposal' || field == 'emsl_user') && !(type == 'Dataset' || type == 'Long Interval')) return false;
            if(field == 'note' && type != 'Long Interval') return false;
            if(type == 'Operation' || type == 'Configuration') return false;
            return true;
        },
        cellProtectionChecker: function(field, rowData) {
            return myUtil.isEditable(field, rowData.type);
        }
    }

    $(document).ready(function () {
        myGrid = mainGrid.init(gridConfig);
        myCommonControls = commonGridControls.init(myGrid);
        myImportExport = gridImportExport.init(myGrid, { postImportAction: myUtil.postImportAction });
        myCommonControls.showControls(true);

        myUtil.initEntryFields();
        $('fieldset span').css('font-weight', 'bold');

        $('#instrument_fld_chooser').prop('multiple', 'multiple').css('width', '300px');
        $('#instrument_fld_chooser').attr('data-placeholder', 'Select instruments (optional)');
        $("#instrument_fld_chooser option[value='']").remove();
        $('#instrument_fld_chooser').select2();

        $('#usage_selector').css('width', '300px');
        $('#usage_selector').select2();

        $('#type_selector').css('width', '300px');
        $('#type_selector').select2();
        //$('.spin_me').spinner(); // needs jquery UI 1.9+
    });

</script>

</body>
</html>
