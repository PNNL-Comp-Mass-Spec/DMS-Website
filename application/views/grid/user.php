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

<div id='ctl_panel' class='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' href='javascript:void(0)' >Show</a> info for requests
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
	mainGrid.hiddenColumns = [];
	mainGrid.staticColumns = ['ID'];
	
	var myGrid = {
		getLoadParameters: function() {
			return { };
		},
		getSaveParameters: function() {
			var dataRows = mainGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'ID');
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
//			factorXML = '<id type="Request" />' + factorXML;
			return { factorList: factorXML };
		}
	}

	$(document).ready(function () { 
		$('#col_ctls').hide();
		$('#save_ctls').hide();

		$('#reload_btn').click(function() {
			mainGrid.refreshGrid(myGrid.getLoadParameters());
			$('#col_ctls').show();
			$('#save_ctls').hide();
		});
		$('#save_btn').click(function() {	
			var url = gamma.pageContext.save_changes_url;
			var p = myGrid.getSaveParameters();
alert('This feature not enabled yet'); return;
			gridUtil.saveChanges(url, p, function(data) {
				$('#reload_btn').click();
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