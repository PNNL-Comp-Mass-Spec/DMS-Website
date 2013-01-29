<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url().'css/grid_data.css' ?>" />

<? $chimg = base_url()."images/chooser.png"; ?>

</head>

<body>
<? $this->load->view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'>Dataset Factors</legend>
    <div>
	<label for="itemList">Datasets</label>
	<span class='ctls'> &nbsp; </span>
	<span class='ctls'>
	from data package... <a href="javascript:epsilon.callChooser('itemList', '<?= site_url() ?>helper_data_package_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
	</span>
	<span class='ctls'>
	from datasets... <a href="javascript:epsilon.callChooser('itemList', '<?= site_url() ?>helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>	
	</span>
	</div>
	<div>
	<textarea name="itemList" cols="100" rows="5" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
	</div>
</fieldset>
</form>

<? $this->load->view('grid/grid_control_panel') ?>

<div id="myTable" ></div>

<? $this->load->view('grid/delimited_text') ?>

<? $this->load->view('resource_links/base2js') ?>
<? $this->load->view('resource_links/slickgrid2js') ?>

<script src="<?= base_url().'javascript/data_grid.js' ?>"></script>

<script>
	gamma.pageContext.save_changes_url = '<?= $save_url ?>';
	gamma.pageContext.data_url = '<?= $data_url ?>';

	var myCommonControls;
	var myImportExport;
	var myGrid;
	var gridConfig = {
		hiddenColumns: ['Sel', 'BatchID', 'Experiment'],
		staticColumns: ['Dataset', 'Name', 'Status', 'Request'],
		getLoadParameters: function() {
			var itemList = $('#itemList').val();
			return { itemList:itemList, itemType:'Dataset_Name' };
		},
		afterLoadAction: function() {
			myCommonControls.enableAddColumn(true);
			myCommonControls.enableSave(false);
		},
		getSaveParameters: function() {
			var dataRows = myGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'Dataset');
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
			factorXML = '<id type="Dataset_Name" />' + factorXML;
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
				var x = $.map(myGrid.grid.getData(), function(row) {return row['Request']; });
				$('#itemList').val(x.join(', '));
				myCommonControls.enableAddColumn(true);
				myCommonControls.enableSave(true);
		},
		postUpdateAction: function() {
				myCommonControls.enableSave(true);			
		},
		initEntryFields: function() {
		}
	}

	$(document).ready(function () { 
		myCommonControls = $.extend({}, commonGridControls);
		myImportExport = $.extend({}, gridImportExport, { 
			postImportAction: myUtil.postImportAction, 
			postUpdateAction: myUtil.postUpdateAction,
			acceptNewColumnsOnUpdate: true
 		});
		myGrid = $.extend({}, mainGrid, gridConfig);
		myImportExport.init(myGrid);
		myCommonControls.init(myGrid);

		myUtil.initEntryFields();
		myCommonControls.showControls(true);
	});

</script>
	
</body>
</html>