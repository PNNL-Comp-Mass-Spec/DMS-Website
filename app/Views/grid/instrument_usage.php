<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/slickgrid2css') ?>

</head>

<!-- This page is used by https://dms2.pnl.gov/instrument_usage_report/grid -->

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
        <!--
            SELECT Usage, COUNT(*)
            FROM V_Instrument_Usage_Report_List_Report
            WHERE start >= '2022-10-01'
            GROUP BY usage

            SELECT Name, Description
            FROM T_EMSL_Instrument_Usage_Type
            WHERE Enabled > 0
        -->

        <select id='usage_selector' multiple data-placeholder='Select usage (optional)' >
            <option>CAP_DEV</option>
            <option>MAINTENANCE</option>
            <option>BROKEN</option>
            <option>AVAILABLE</option>
            <option>UNAVAILABLE</option>
            <option>RESOURCE_OWNER</option>
            <option>ONSITE</option>
            <option>REMOTE</option>
        </select>
    </td>
    <td><span>Proposal</span></td>
    <td>
    <input name="proposal_fld" id='proposal_fld' size="8" />
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
    dmsjs.pageContext.save_changes_url = '<?= $save_url ?>';
    dmsjs.pageContext.data_url = '<?= $data_url ?>';

    var myCommonControls;
    var myImportExport;
    var myGrid;

    // Meant to be extended with mainGrid object
    // The column names in the staticColumns array correspond to https://dmsdev.pnl.gov/instrument_usage_report/grid
    // They must match the data returned by view V_Instrument_Usage_Report_List_Report (see also Controllers/Instrument_Usage_Report.php::grid_data())
    var gridConfig = {
        maxColumnChars: 50,
        hiddenColumns: [],
        staticColumns: ['seq', 'emsl_inst_id', 'instrument', 'type', 'start', 'minutes', 'id', {id:"users"}, {id:"proposal"}, {id:"usage"},  {id:"operator"},  {id:"comment"}, 'validation'],
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
            p.proposal = $('#proposal_fld').val();
            p.year = $('#year_fld').val();
            p.month = $('#month_fld').val();
            return p;
        },
        afterLoadAction: function() {
            myCommonControls.enableSave(false);
        },
        getSaveParameters: function() {
            var dataRows = myGrid.grid.getData();
            var changes = gridUtil.getChanges(dataRows, 'seq');
            var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var paramXml = dmsInput.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
            paramXml = '<id type="Seq" />' + paramXml;
            return {
                factorList: paramXml,
                operation: 'update',
                year: $('#year_fld').val(),
                month: $('#month_fld').val(),
                instrument: '' // $('#instrument_fld_chooser').val() is array, sproc expecting a string
            }
        },
        afterSaveAction: function() {
            myCommonControls.reload();
        },
        handleDataChanged: function(args) {
            myCommonControls.enableSave(true);
        },
        editPermissionFilter: function(e,args) {
        },
        getContextMenuHandler: function() {
            var ctx = contextMenuManager.init(this);
            ctx.buildBasicMenu();
            return function (e) {
                ctx.menuEvtHandler(e);
            }
        }
    }
    var myUtil = {
        postImportAction: function() {
        },
        postUpdateAction: function() {
                myCommonControls.enableSave(true);
        },
        initEntryFields: function() {
            var d = new Date();
            $('#instrument_fld_chooser').val('LTQ_Orb_3');
            $('#month_fld').val(d.getMonth() + 1);
            $('#year_fld').val(d.getFullYear());
        },
        isEditable: function(field, type) {
            return true;
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

        $('.sel_chooser').select2();
    });

</script>

</body>
</html>
