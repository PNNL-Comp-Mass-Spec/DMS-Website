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
    <legend class='ctl_legend'>DMS Users</legend>
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

<div id='ctl_panel' class='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' title='Load data into editing grid'class='button' href='javascript:void(0)' >Show</a> info for users
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
		hiddenColumns: [],
		staticColumns: ['ID'],

		getLoadUrl: function() {
			return gamma.pageContext.data_url;
		},
		getLoadParameters: function() {
			return { userName: $('#userName').val(), allUsers:$('#allUsers').is(':checked') };
		},
		afterLoadAction: function() {
			$('#col_ctls').show();
			$('#save_ctls').hide();
		},
		getSaveUrl: function() {
			return gamma.pageContext.save_changes_url;
		},
		getSaveParameters: function() {
			var dataRows = myGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'ID');
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
//			factorXML = '<id type="Request" />' + factorXML;
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
	var myImportExport = $.extend({}, gridImportExport);

	$(document).ready(function () { 
		myImportExport.init(myGrid);

		$('#reload_btn').click(function() {
		    myGrid.buildGrid();
			myGrid.loadGrid();
		});
		$('#save_btn').click(function() {	
			myGrid.saveGrid();
		});
		
		$('#ctl_panel').show();
		$('#delimited_text_ctl_panel').show();
	});

</script>
	
</body>
</html>