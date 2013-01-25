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
	</div>
	<div>
	<textarea name="itemList" cols="100" rows="5" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
	Datasets... <a href="javascript:epsilon.callChooser('itemList', '<?= site_url() ?>helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
	</div>
</fieldset>
</form>


<div id='ctl_panel' class='ctl_panel'>
	
<span class='ctls'>
	<a id='reload_btn' class='button' href='javascript:void(0)' >Show</a> Factors For Datasets
</span>

<span class='ctls' id='add_col_ctl_panel'>
<span class='ctls'>
	<a id='add_column_btn' href='javascript:void(0)' >Add</a> New Factor
</span>
<span class='ctls'>
	<input id='add_column_name' type='text' size="20"></input>
</span>
</span>

<span id='save_ctls' class='ctls'>
	<a  id='save_btn' class='button' href='javascript:void(0)' >Save Changes</a>
</span>

</div>

<div id="myTable" ></div>

<? $this->load->view('grid/delimited_text') ?>

<? $this->load->view('resource_links/base2js') ?>
<? $this->load->view('resource_links/slickgrid2js') ?>

<script src="<?= base_url().'javascript/data_grid.js' ?>"></script>

<script>
	gamma.pageContext.save_changes_url = '<?= $save_url ?>';
	gamma.pageContext.data_url = '<?= $data_url ?>';

	var gridConfig = {
		hiddenColumns: ['Sel', 'BatchID', 'Experiment'],
		staticColumns: ['Dataset', 'Name', 'Status', 'Request'],
		getLoadUrl: function() {
			return gamma.pageContext.data_url;
		},
		getLoadParameters: function() {
			var itemList = $('#itemList').val();
			return { itemList:itemList, itemType:'Dataset_Name' };
		},
		afterLoadAction: function() {
			$('#col_ctls').show();
			$('#add_col_ctl_panel').show();
			$('#save_ctls').hide();
			gridImportExport.delimitedTextCtls(true);			
		},
		getSaveUrl: function() {
			return gamma.pageContext.save_changes_url;
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
			$('#reload_btn').click();			
		},
		handleDataChanged: function() {
			$('#save_ctls').show();
		}
	}
	
	var myGrid = $.extend({}, mainGrid, gridConfig);
	var myImportExport = $.extend({}, gridImportExport, {
		postImportAction: function() {
			var x = $.map(myGrid.grid.getData(), function(row) {return row['Request']; });
			$('#itemList').val(x.join(', '));
			$('#add_col_ctl_panel').show();
			$('#save_ctls').show();	
		}
	});

	$(document).ready(function () { 
		$('#col_ctls').hide();
		$('#save_ctls').hide();
		$('#add_col_ctl_panel').hide();
		myImportExport.init();

		$('#reload_btn').click(function() {
		    myGrid.buildGrid();
			myGrid.loadGrid();
		});
		$('#save_btn').click(function() {
			myGrid.saveGrid();
		});
		$('#import_grid_btn').click(function() {
		    myGrid.buildGrid();
			myImportExport.importDelimitedData(myGrid);
		});
		$('#export_grid_btn').click(function() {
			myImportExport.exportDelimitedData(myGrid);
		});
		$('#add_column_btn').click(function() {
			var name = $('#add_column_name').val();
			myGrid.addColumn(name);
		});
	});

</script>
	
</body>
</html>