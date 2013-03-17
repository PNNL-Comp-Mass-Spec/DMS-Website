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
    <legend class='ctl_legend'>Requested Runs</legend>
    
    <table>
    <tr>
    <td>
    </td>
    
    <td>	
	<div id='req_chsr_panel' class='ctls_grp' data-target='requestItemList'>
	<span class='ctls' data-query='batch_requests' data-chsr='requested_run_batch_list'>
	From batch <input type='text' size='10' /><a class='button' href='javascript:void(0)' >Get</a>
	</span>
	<span class='ctls' data-query='osm_package_requests' data-chsr='osm_package_list'>
	From OSM package <input type='text' size='10' /><a class='button' href='javascript:void(0)' >Get</a>
	</span>
	<span class='ctls'>
	From requested runs... <a href="javascript:epsilon.callChooser('requestItemList', '<?= site_url() ?>helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
	</span>
	</div>

	</td>
	</tr>
	
	<tr>
	<td colspan=2>
	<textarea cols="100" rows="5" name="requestItemList" id="requestItemList" onchange="epsilon.convertList('requestItemList', ',')" ></textarea>
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
	gamma.pageContext.site_url = '<?= site_url() ?>';
	gamma.pageContext.save_changes_url = '<?= $save_url ?>';
	gamma.pageContext.data_url = '<?= $data_url ?>';
	
	var myCommonControls;
	var myImportExport;
	var myGrid;
	var gridConfig = {
		hiddenColumns: [],
		staticColumns: ['Request', 'Name', 'Status', 'BatchID', 'Experiment', 'Instrument', 'Separation_Type'],
		getLoadParameters: function() {
			var itemList = $('#requestItemList').val();
			return { itemList:itemList };
		},
		afterLoadAction: function() {
			myCommonControls.enableSave(false);
		},
		getSaveParameters: function() {
			var dataRows = myGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'Request');
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
//			factorXML = '<id type="Request" />' + factorXML;
//			return { factorList: factorXML };
			return false; // temp to suppress save action
		},
		afterSaveAction: function() {
			myCommonControls.reload();			
		},
		handleDataChanged: function() {
			myCommonControls.enableSave(true);
		},
		getContextMenuHandler: function() {
			var ctx = contextMenuManager.init(this).buildBasicMenu();
			return function (e) {
				ctx.menuEvtHandler(e);
		    }
		}	
	}
	var myUtil = {
			postImportAction: function() {
				var x = $.map(myGrid.grid.getData(), function(row) {return row['Request']; });
				$('#itemList').val(x.join(', '));
				myCommonControls.enableSave(true);
		},
		initEntryFields: function() {
		}
	}

	$(document).ready(function () { 
		myGrid = mainGrid.init(gridConfig);
		myCommonControls = commonGridControls.init(myGrid);
		myImportExport = gridImportExport.init(myGrid, { postImportAction: myUtil.postImportAction });

 		sourceListUtil.setup();

		myUtil.initEntryFields();
		myCommonControls.showControls(true);
	});

</script>
	
</body>
</html>