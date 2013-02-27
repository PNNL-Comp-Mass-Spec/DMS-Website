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

	<label for="instrument_fld_chooser">Instrument</label>
	<?= $this->choosers->get_chooser('instrument_fld', 'usageTrackedInstruments')?>
	

	<label for="year_fld">Year</label>
	<input name="year_fld" id='year_fld' size="6" class="spin_me" />

	<label for="month_fld">Month</label>
	<input name="month_fld" id='month_fld' size="6" class="spin_me" />

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
	// meant to be extended with mainGrid object
	var gridConfig = {
		maxColumnChars: 50,
		hiddenColumns: [],
		staticColumns: ['Seq', 'EMSL Inst ID', 'Instrument', 'Type', 'Start', 'Minutes', 'Users', 'ID', {id:"Proposal"}, {id:"Usage"},  {id:"Operator"},  {id:"Comment"}, 'Validation'],
		getLoadParameters: function() {
			var p = {};
			p.instrument = $('#instrument_fld_chooser').val();
			p.year = $('#year_fld').val();
			p.month = $('#month_fld').val();
			if(!p.instrument) {
				alert("You must choose an instrument");
				return false;
			}
			return p;
		},
		afterLoadAction: function() {
			myCommonControls.enableSave(false);
		},
		getSaveParameters: function() {
			var dataRows = myGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'Seq');
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var paramXml = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
			paramXml = '<id type="Seq" />' + paramXml;
			return { 
				factorList: paramXml,
				operation: 'update',
				year: $('#year_fld').val(),
				month: $('#month_fld').val(),
				instrument: $('#instrument_fld_chooser').val()
			}
		},
		afterSaveAction: function() {
			myCommonControls.reload();			
		},
		handleDataChanged: function(args) {
			myCommonControls.enableSave(true);
		},
		editPermissionFilter: function(e,args) {
		}		
	}
	var myUtil = {
		postImportAction: function() {
		},
		postUpdateAction: function() {
				myCommonControls.enableSave(true);			
		},
		initEntryFields: function() {
			var d = new Date();
			$('#instrument_fld_chooser').val('LTQ_Orb_3');
			$('#month_fld').val(d.getMonth() + 1);
			$('#year_fld').val(d.getFullYear());
		},
		isEditable: function(field, type) {
			return true;			
		}
	}

	$(document).ready(function () { 
		myCommonControls = $.extend({}, commonGridControls);
		myImportExport = $.extend({}, gridImportExport, { postImportAction: myUtil.postImportAction });
		myGrid = $.extend({}, mainGrid, gridConfig);
		myImportExport.init(myGrid);
		myCommonControls.init(myGrid);
		myCommonControls.showControls(true);

		myUtil.initEntryFields();
		myCommonControls.showControls(true);
		$('.sel_chooser').chosen({search_contains: true});
	});

</script>
	
</body>
</html>