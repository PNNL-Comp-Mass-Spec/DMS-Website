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
<table>
<tr>
	<td><span>Instrument</span></td>
	<td>
	<?= $this->choosers->get_chooser('instrument_fld', 'usageTrackedInstruments')?>
	</td>
	<td><span>Year</span></td>
	<td>
	<input name="year_fld" id='year_fld' size="6" class="spin_me" />
	</td>
	<td><span>Month</span></td>
	<td>
	<input name="month_fld" id='month_fld' size="6" class="spin_me" />
	</td>
	<td><span>Usage</span></td>
	<td>
		<select id='usage_selector' multiple data-placeholder='Select usage (optional)' >
		<option>CAP_DEV</option>
		<option>MAINTENANCE</option>
		<option>BROKEN</option>
		<option>USER</option>
		<option>USER_UNKNOWN</option>
		</select>		
	</td>
	<td><span>Type</span></td>
	<td>
		<select id='type_selector' multiple data-placeholder='Select type (optional)' >
		<option>Dataset</option>
		<option>Long Interval</option>
		<option>Operation</option>
		<option>Configuration</option>
		<option></option>
		</select>		
	</td>
</tr>
</table>

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
		staticColumns: ['Entered', 'EnteredBy', 'Instrument', 'Type', 'ID', 'Log', 'Request', {id:'Usage'}, {id:'Proposal'}, {id:'EMSL_User'}, {id:'Note', editor:Slick.Editors.LongText}],
		getLoadParameters: function() {
			var p = {};
			var instruments = $('#instrument_fld_chooser').val();
			if(instruments) {
				p.instrument = $.map(instruments, function(item) { return "'" + item + "'"; }).join(', ');
			}
			var usage = $('#usage_selector').val();
			if(usage) {
				p.usage = $.map(usage, function(item) { return "'" + item + "'"; }).join(', ');
			}
			var type = $('#type_selector').val();
			if(type) {
				p.type = $.map(type, function(item) { return "'" + item + "'"; }).join(', ');
			}
			p.year = $('#year_fld').val();
			p.month = $('#month_fld').val();
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
			return { changes: paramXml };
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
			var mapP2A = [{p:'Request', a:'request'}, {p:'Usage', a:'usage'}, {p:'Proposal', a:'proposal'}, {p:'EMSL_User', a:'user'}];
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
			if(!args) return;
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
			if(type == 'Operation' || type == 'Configuration') return false;
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
		$('fieldset span').css('font-weight', 'bold');
		
		$('#instrument_fld_chooser').prop('multiple', 'multiple').css('width', '300px');
		$('#instrument_fld_chooser').attr('data-placeholder', 'Select instruments (optional)');
		$("#instrument_fld_chooser option[value='']").remove();
		$('#instrument_fld_chooser').chosen({search_contains: true});
		
		$('#usage_selector').css('width', '300px');
		$('#usage_selector').chosen({search_contains: true});
		
		$('#type_selector').css('width', '300px');
		$('#type_selector').chosen({search_contains: true});
		//$('.spin_me').spinner(); // needs jquery UI 1.9+
	});

</script>
	
</body>
</html>