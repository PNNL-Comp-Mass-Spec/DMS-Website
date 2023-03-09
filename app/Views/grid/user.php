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
    <legend class='ctl_legend'>DMS User Administration</legend>
    <span>
    <label for="userName">Name contains:</label>
    </span>
    <span>
    <input name="userName" size="40" id="userName" onchange="dmsInput.convertList('userName', ',')" ></input>
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

<?php echo view('grid/grid_control_panel') ?>

<div id="myTable" ></div>

<?php echo view('grid/delimited_text') ?>

<?php echo view('resource_links/base2js') ?>
<?php echo view('resource_links/slickgrid2js') ?>

<script type="text/javascript">
    gamma.pageContext.save_changes_url = '<?= $save_url ?>';
    gamma.pageContext.data_url = '<?= $data_url ?>';

    var myCommonControls;
    var myImportExport;
    var myGrid;
    var gridConfig = {

        // Columns are from Controllers/Grid.php::user_data(), and must be exact matches
        hiddenColumns: [],
        staticColumns: ['id'],

        getLoadParameters: function() {
            return { userName: $('#userName').val(), allUsers:$('#allUsers').is(':checked') };
        },
        afterLoadAction: function() {
            myCommonControls.enableSave(false);
        },
        getSaveParameters: function() {
            var dataRows = myGrid.grid.getData();
            var changes = gridUtil.getChanges(dataRows, 'id');
            var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var factorXML = dmsInput.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
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
