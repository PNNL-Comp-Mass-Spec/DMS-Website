<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url().'css/grid_data.css' ?>" />

</head>

<body>
<? $this->load->view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'><?= $title; ?></legend>

	<label for="instrument_fld">Instrument</label>
	<input name="instrument_fld" id='instrument_fld' size="30" />

	<label for="year_fld">Year</label>
	<input name="year_fld" id='year_fld' size="6" />

	<label for="month_fld">Month</label>
	<input name="month_fld" id='month_fld' size="6" />

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
		maxColumnChars: 50,
		hiddenColumns: ['Year', 'Month', 'Day'],
		staticColumns: ['Entered', 'EnteredBy', 'Type', 'ID', 'Log', 'Note', 'Request'],
		getLoadParameters: function() {
			return {};
		},
		afterLoadAction: function() {
			myCommonControls.enableSave(false);
		},
		getSaveParameters: function() {
			var dataRows = myGrid.grid.getData();
			var changes = myUtil.getChangedRows(dataRows, myUtil.isDataset);
			var mapP2A = [{p:'Request', a:'i'}, {p:'Usage', a:'u'}, {p:'Proposal', a:'p'}];
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
		},
		postUpdateAction: function() {
				myCommonControls.enableSave(true);			
		},
		initEntryFields: function() {
		},
		/// move to gridUtil
		getChangedRows: function(dataRows, filter) {
			var changes = [];
			$.each(dataRows, function(idx, row) {
				if(row.mod_axe && filter(row)) {
					changes.push(row);
				}
			});
			return changes;
		},
		isDataset: function(row) {
			return (typeof row.Type != 'undefined') && (row.Type === 'Dataset');
		}	
	}

	$(document).ready(function () { 
		myCommonControls = $.extend({}, commonGridControls);
		myImportExport = $.extend({}, gridImportExport, { postImportAction: myUtil.postImportAction });
		myGrid = $.extend({}, mainGrid, gridConfig);
		myImportExport.init(myGrid);
		myCommonControls.init(myGrid);

		myUtil.initEntryFields();
		myCommonControls.showControls(true);
		
		// temp
		$('#instrument_fld').val('QExact01');
		$('#month_fld').val('2');
		$('#year_fld').val('2013');
	});

</script>
	
</body>
</html>