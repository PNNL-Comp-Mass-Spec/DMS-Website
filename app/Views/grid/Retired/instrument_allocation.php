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
    <legend class='ctl_legend'>Instrument Allocated Usage</legend>
    <div>
    <label for="fiscalYear">Fiscal Year <span class='supplemental_text'>(required)</span></label>
    </div>
    <div>
    <input name="fiscalYear" cols="100" rows="1" id="fiscalYear" onchange="dmsInput.convertList('fiscalYear', ',')" ></input>
    </div>
    <div>
    <label for="itemList">Proposals <span class='supplemental_text'>(leave blank to get all for fiscal year)</span></label>
    </div>
    <div>
    <textarea name="itemList" cols="100" rows="1" id="itemList" onchange="dmsInput.convertList('itemList', ',')" ></textarea>
    </div>
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
    var gridConfig = {
        hiddenColumns: ['#FY_Proposal'],
        staticColumns: ['Fiscal_Year', 'Proposal_ID', 'Title', 'Status', 'Last_Updated'],
        getLoadParameters: function() {
            var itemList = $('#itemList').val();
            var fiscalYear = $('#fiscalYear').val();
            return { itemList:itemList, fiscalYear:fiscalYear };
        },
        afterLoadAction: function() {
            myCommonControls.enableSave(false);
        },
        getSaveParameters: function() {
            var dataRows = this.grid.getData();
            var changes = gridUtil.getChanges(dataRows, 'Proposal_ID');
            $.each(changes, function(idx, obj) {
                if(obj.factor == "General") {
                    obj.comment = obj.value;
                    obj.value = 0;
                }
            });
            var mapP2A = [{p:'id', a:'p'}, {p:'factor', a:'g'}, {p:'value', a:'a'}, {p:'comment', a:'x'}];
            var factorXML = dmsInput.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
            var fy = $('#fiscalYear').val();
            factorXML = '<c fiscal_year="' + fy + '"/>' + factorXML;
            return { parameterList:factorXML };
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
            var fy = $('#fiscalYear').val();
            if(!fy) {
                var d = new Date();
                d.setMonth(d.getMonth() + 3);
                $('#fiscalYear').val(d.getFullYear());
            }
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
