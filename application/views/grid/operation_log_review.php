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
		hiddenColumns: ['Year', 'Month', 'Day'],
		staticColumns: ['Entered', 'EnteredBy', 'Instrument', 'Type', 'ID', 'Log', 'Request', {id:'Usage'}, {id:'Proposal'}, {id:'Note', editor:Slick.Editors.LongText}],
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
			var invalidUsage = myUtil.findInvalidUsageProposal(dataRows);
			if(invalidUsage) {
				alert(invalidUsage);
				return false;
			}
			var runXml = myUtil.getRequestChangeXml(dataRows);
			var intervalXml = myUtil.getIntervalChangeXml(dataRows);
			var paramXml = runXml + intervalXml
			$('#delimited_text').val(paramXml); // temp debug
			return { factorList: paramXml };
		},
		afterSaveAction: function() {
			myCommonControls.reload();			
		},
		handleDataChanged: function(args) {
			myCommonControls.enableSave(true);
			myUtil.adjustCapitalization(args);
		},
		editPermissionFilter: function(e,args) {
			return myUtil.isEditable(args.column.field, args.item.Type);
		}		
	}
	// for the grunt work details
	var myUtil = {
		postImportAction: function() {
		},
		postUpdateAction: function() {
				myCommonControls.enableSave(true);			
		},
		initEntryFields: function() {
			var d = new Date();
			//$('#instrument_fld_chooser').val('Exact01');
			$('#month_fld').val(d.getMonth() + 1);
			$('#year_fld').val(d.getFullYear());
		},
		getRequestChangeXml: function(dataRows) {
			var changes = myUtil.getChangedRows(dataRows, myUtil.isDataset);
			var mapP2A = [{p:'Request', a:'request'}, {p:'Usage', a:'usage'}, {p:'Proposal', a:'proposal'}, {p:'Note', a:'note'}];
			return gamma.getXmlElementsFromObjectArray(changes, 'run', mapP2A);			
		},
		getIntervalChangeXml: function(dataRows) {
			var changes = myUtil.getChangedRows(dataRows, myUtil.isInterval);
			var mapP2A = [{p:'ID', a:'id'}, {p:'Note', a:'note'}];
			return gamma.getXmlElementsFromObjectArray(changes, 'interval', mapP2A);			
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
		},
		isInterval: function(row) {
			return (typeof row.Type != 'undefined') && (row.Type === 'Long Interval');
		},
		adjustCapitalization: function(args) {
			var field = args.grid.getColumns()[args.cell].field;
			var row = args.grid.getData()[args.row];
			if(myUtil.isDataset(row) && field === 'Usage') {
				row.Usage = row.Usage.toUpperCase();
				args.grid.invalidateRows([args.row]);
				args.grid.render();		
			}
		},
		findInvalidUsageProposal: function(dataRows) {
			var message = '';
			var changes = myUtil.getChangedRows(dataRows, myUtil.isDataset);
			$.each(changes, function(idx, row) {
				if(row.Usage === 'USER' && !(row.Proposal) ){
					message = "No proposal for USER for " + row.Note;
					return false;
				}
			});
			return message;	
		},
		isEditable: function(field, type) {
			if((field == 'Usage' || field == 'Proposal') && type != 'Dataset') return false;
			if(field == 'Note' && type != 'Long Interval') return false;
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
		$('.sel_chooser').chosen({search_contains: true});
		//$('.spin_me').spinner(); // needs jquery UI 1.9+
	});

</script>
	
</body>
</html>