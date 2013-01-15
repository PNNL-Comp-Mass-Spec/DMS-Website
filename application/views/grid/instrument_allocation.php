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
    <legend>Instrument Allocated Usage</legend>
    <div>
	<label for="itemList">Proposals (leave blank to get all)</label>
	</div>
	<div>
	<textarea name="itemList" cols="100" rows="3" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
	</div>
	<div>
	<label for="fiscalYears">Fiscal Years (leave blank to get all)</label>
	</div>
	<div>
	<textarea name="fiscalYears" cols="100" rows="1" id="fiscalYears" onchange="epsilon.convertList('fiscalYears', ',')" ></textarea>
	</div>  
</fieldset>
</form>

<div id='ctl_panel' class='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' href='javascript:void(0)' >Show</a> Factors For Datasets
</span>

<span id='save_ctls' class='ctls'>
	<input id='save_btn' type='button' value='Save Changes' />
</span>
</div>

<div id="myTable" ></div>

<div class='ctl_panel'>
<a id='delimited_text_panel_btn' href='javascript:void(0)' >Delimited Text</a>
</div>
<div id='delimited_text_panel' class='ctl_panel'>
<div class='ctl_panel'>
<span class='ctls'>
	<a id='import_grid_btn' href='javascript:void(0)' >Import</a> grid contents from delimited text
</span>
<span class='ctls'>
	<a id='export_grid_btn' href='javascript:void(0)' >Export</a> grid contents to delimited text
</span>
</div>

<div>
<textarea id="delimited_text" name="delimited_text" cols="100" rows="5" ></textarea>
</div>
</div>

<? $this->load->view('resource_links/base2js') ?>
<? $this->load->view('resource_links/slickgrid2js') ?>

<script src="<?= base_url().'javascript/data_grid.js' ?>"></script>

<script>
	gamma.pageContext.ops_url = '<?= site_url() ?>xxx/operation';
	gamma.pageContext.data_url = '<?= site_url() .  $this->my_tag ?>/grid_data';

	$(document).ready(function () { 
		mainGrid.hideColumns = ['#FY_Proposal'];
		mainGrid.staticColumns = ['Fiscal_Year', 'Proposal_ID', 'Title', 'Status', 'Last_Updated'];

		$('#col_ctls').hide();
		$('#save_ctls').hide();

		$('#reload_btn').click(function() {
			var itemList = $('#itemList').val();
			var fiscalYears = $('#fiscalYears').val();
			mainGrid.refreshGrid({ itemList:itemList, fiscalYears:fiscalYears });
			$('#col_ctls').show();
			$('#save_ctls').hide();
		});
		$('#add_column_btn').click(function() {
			var name = $('#add_column_name').val();
			mainGrid.addColumn(name);
		});
		$('#save_btn').click(function() {
			alert('This feature not enabled yet'); return;
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