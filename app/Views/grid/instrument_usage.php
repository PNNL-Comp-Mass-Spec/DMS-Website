<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/grid_data.css') ?>" />

</head>

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
        <option>ONSITE</option>
        <option>REMOTE</option>
        </select>
    </td>
    <td><span>Proposal</span></td>
    <td>
    <input name="proposal_fld" id='proposal_fld' size="8"  />
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

<?php // When updating the version for data_grid.js, search for other .php files that also define the version ?>
<script src="<?= base_url('javascript/data_grid.js?version=101') ?>"></script>

<script>
    gamma.pageContext.save_changes_url = '<?= $save_url ?>';
    gamma.pageContext.data_url = '<?= $data_url ?>';

    var myCommonControls;
    var myImportExport;
    var myGrid;
    // meant to be extended with mainGrid object
    var gridConfig = {
        maxColumnChars: 50,
        hiddenColumns: [],
        staticColumns: ['Seq', 'EMSL Inst ID', 'Instrument', 'Type', 'Start', 'Minutes', {id:"Users"}, 'ID', {id:"Proposal"}, {id:"Usage"},  {id:"Operator"},  {id:"Comment"}, 'Validation'],
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
            var changes = gridUtil.getChanges(dataRows, 'Seq');
            var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var paramXml = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
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
