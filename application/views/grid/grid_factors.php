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
<div>Datasets</div>
<div>
<textarea name="itemList" cols="100" rows="5" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
<span>
Datasets... <a href="javascript:epsilon.callChooser('itemList', '<?= site_url() ?>helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
<span>
</div>

<div id='ctl_panel' class='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' href='javascript:void(0)' >Show</a> Factors For Datasets
</span>
<spanclass='ctls'>
	<a id='add_column_btn' href='javascript:void(0)' >Add</a> New Factor
</span>
<spanclass='ctls'>
	<input id='add_column_name' type='text' size="20"></input>
</span>

<span id='save_ctls' class='ctls'>
	<input id='save_btn' type='button' value='Save Changes' />
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

	$(document).ready(function () { 
		mainGrid.hideColumns = ['Sel', 'BatchID', 'Experiment'];
		mainGrid.staticColumns = ['Dataset', 'Name', 'Status', 'Request'];

		$('#col_ctls').hide();
		$('#save_ctls').hide();

		$('#reload_btn').click(function() {
			var itemList = $('#itemList').val();
			mainGrid.refreshGrid({ itemList:itemList, itemType:'Dataset_Name' });
			$('#col_ctls').show();
			$('#save_ctls').hide();
		});
		$('#add_column_btn').click(function() {
			var name = $('#add_column_name').val();
			mainGrid.addColumn(name);
		});
		$('#save_btn').click(function() {
			var idField = 'Dataset';
			var dataRows = mainGrid.grid.getData();
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			gridUtil.saveChanges(dataRows, idField, mapP2A, 'Dataset_Name', function(data) {
				if(data) {
					alert(data);
				} else {
					$('#reload_btn').click();
				}
			});
		});
		
		$('#delimited_text_panel').hide();
		$('#delimited_text_panel_btn').click(function() {
			$('#delimited_text_panel').toggle();		
		});
		$('#import_grid_btn').click(function() {
			mainGrid.importDelimitedData();
			var x = $.map(mainGrid.grid.getData(), function(row) {return row['Request']; });
			$('#itemList').val(x.join(', '));
			$('#save_ctls').show();
		});
		$('#export_grid_btn').click(function() {
			mainGrid.exportDelimitedData();
		});
		
	    mainGrid.buildGrid();
	});

</script>
	
</body>
</html>