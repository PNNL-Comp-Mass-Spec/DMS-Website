<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url().'css/grid_data.css' ?>" />

<?php $chimg = base_url()."images/chooser.png"; ?>

</head>

<body>
<?php $this->load->view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'>DMS User Administration</legend>
    <span>
    <label for="userName">Name contains:</label>
    </span>
    <span>
    <input name="userName" size="40" id="userName" onchange="epsilon.convertList('userName', ',')" ></input>
    </span>
        <span>
    <label for="allUsers">Include inactive users</label>
    </span>
    <span>
    <input type="checkbox" name="allUsers" id="allUsers" />
    </span>
</fieldset>
</form>

<div style='height:1em;'></div>

<?php $this->load->view('grid/grid_control_panel') ?>

<div id="myTable" ></div>

<?php $this->load->view('grid/delimited_text') ?>

<?php $this->load->view('resource_links/base2js') ?>
<?php $this->load->view('resource_links/slickgrid2js') ?>

<script src="<?= base_url().'javascript/data_grid.js?version=100' ?>"></script>

<script>
    gamma.pageContext.save_changes_url = '<?= $save_url ?>';
    gamma.pageContext.data_url = '<?= $data_url ?>';

    var myCommonControls;
    var myImportExport;
    var myGrid;
    var gridConfig = {
        hiddenColumns: [],
        staticColumns: ['ID'],

        getLoadParameters: function() {
            return { userName: $('#userName').val(), allUsers:$('#allUsers').is(':checked') };
        },
        afterLoadAction: function() {
            myCommonControls.enableSave(false);
        },
        getSaveParameters: function() {
            var dataRows = myGrid.grid.getData();
            var changes = gridUtil.getChanges(dataRows, 'ID');
            var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
//          factorXML = '<id type="Request" />' + factorXML;
            return { factorList: factorXML };
        },
        afterSaveAction: function() {
            myCommonControls.reload();
        },
        handleDataChanged: function() {
            myCommonControls.enableSave(true);
        }
    }
    var myUtil = {
        postImportAction: function() {
                myCommonControls.enableSave(true);
        },
        initEntryFields: function() {
        }
    }

    $(document).ready(function () {
        myGrid = mainGrid.init(gridConfig);
        myCommonControls = commonGridControls.init(myGrid);
        myImportExport = gridImportExport.init(myGrid, { postImportAction: myUtil.postImportAction });

        myUtil.initEntryFields();
        myCommonControls.showControls(true);
    });

</script>

</body>
</html>
