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
    <legend class='ctl_legend'>Instrument Allocated Usage</legend>
	<div>
	<label for="fiscalYear">Fiscal Year <span class='supplemental_text'>(required)</span></label>
	</div>
	<div>
	<input name="fiscalYear" cols="100" rows="1" id="fiscalYear" onchange="epsilon.convertList('fiscalYear', ',')" ></input>
	</div>  
    <div>
	<label for="itemList">Proposals <span class='supplemental_text'>(leave blank to get all for fiscal year)</span></label>
	</div>
	<div>
	<textarea name="itemList" cols="100" rows="1" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
	</div>
</fieldset>
</form>

<div id='ctl_panel' class='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' title='Load data into editing grid'class='button' href='javascript:void(0)' >Show</a> allocations
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
		hiddenColumns: ['#FY_Proposal'],
		staticColumns: ['Fiscal_Year', 'Proposal_ID', 'Title', 'Status', 'Last_Updated'],
		getLoadUrl: function() {
			return gamma.pageContext.data_url;
		},
		getLoadParameters: function() {
			var itemList = $('#itemList').val();
			var fiscalYear = $('#fiscalYear').val();
			return { itemList:itemList, fiscalYear:fiscalYear };
		},
		afterLoadAction: function() {
			$('#col_ctls').show();
			$('#save_ctls').hide();
		},
		getSaveUrl: function() {
			return gamma.pageContext.save_changes_url;
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
			var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
			var fy = $('#fiscalYear').val();
			factorXML = '<c fiscal_year="' + fy + '"/>' + factorXML;
			return { parameterList:factorXML };
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
		var fy = $('#fiscalYear').val();
		if(!fy) {
			var d = new Date();
			d.setMonth(d.getMonth() + 3);
			$('#fiscalYear').val(d.getFullYear());
		}
		$('#ctl_panel').show();
		$('#delimited_text_ctl_panel').show();
	});

</script>
	
</body>
</html>