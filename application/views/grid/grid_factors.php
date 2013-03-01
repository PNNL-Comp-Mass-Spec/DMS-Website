<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url().'css/grid_data.css' ?>" />

<? $chimg = base_url()."images/chooser.png"; ?>

<style>
	.ui-widget {
		font-size: 1em;
	}
</style>

</head>

<body>
<? $this->load->view('nav_bar') ?>

<div style='height:1em;'></div>
<form>
<fieldset>
    <legend class='ctl_legend'>Factors</legend>
    
    <table>
    <tr>
    <td>
	<div id="source_selector">
	<input type="radio" id="source_type1" name="source_type" value="Requested_Run_ID" checked="checked" /><label for="source_type1">Requests</label>
	<input type="radio" id="source_type2" name="source_type" value="Dataset_Name"/><label for="source_type2">Datasets</label>
	</div>
    </td>
    
    <td>
    <div id='ds_chsr_panel' style='display:none;'>
	<span class='ctls'>
	from data package... <a href="javascript:epsilon.callChooser('datasetItemList', '<?= site_url() ?>helper_data_package_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
	</span>
	<span class='ctls'>
	from datasets... <a href="javascript:epsilon.callChooser('datasetItemList', '<?= site_url() ?>helper_dataset_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>	
	</span>
	</div>
	
	<div id='req_chsr_panel'>
	<span class='ctls'>
	from requested runs... <a href="javascript:epsilon.callChooser('requestItemList', '<?= site_url() ?>helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
	</span>
	</div>

	</td>
	</tr>
	
	<tr>
	<td colspan=2>
	<textarea cols="100" rows="5" name="requestItemList" id="requestItemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
	<textarea cols="100" rows="5" name="datasetItemList" id="datasetItemList" onchange="epsilon.convertList('itemList', ',')" style="display:none;"></textarea>
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
	var gridConfig = {
		hiddenColumns: ['Sel', 'BatchID', 'Experiment'],
		staticColumns: ['Request', 'Name', 'Dataset', 'Status' ],
		getLoadParameters: function() {
			var sourceType = $("#source_selector input[type='radio']:checked").val();
			var itemList = (sourceType == 'Dataset_Name') ? $('#datasetItemList').val() : $('#requestItemList').val() ;
			return { itemList:itemList, itemType:sourceType };
		},
		afterLoadAction: function() {
			myCommonControls.enableAddColumn(true);
			myCommonControls.enableSave(false);
		},
		getSaveParameters: function() {
			var dataRows = myGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'Request');
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
			factorXML = '<id type="Request" />' + factorXML;
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
				var x = $.map(myGrid.grid.getData(), function(row) {return row['Request']; });
				$('#itemList').val(x.join(', '));
				myCommonControls.enableAddColumn(true);
				myCommonControls.enableSave(true);
		},
		postUpdateAction: function() {
				myCommonControls.enableSave(true);			
		},
		initEntryFields: function() {
		},
		setItemSource: function(source) {
		 	if(source == "Dataset_Name") {
		 		$('#req_chsr_panel').hide();
		 		$('#requestItemList').hide();
		 		$('#ds_chsr_panel').show();
		 		$('#datasetItemList').show();
		 	} else {
		 		$('#req_chsr_panel').show();
		 		$('#requestItemList').show();
		 		$('#ds_chsr_panel').hide();		 		
		 		$('#datasetItemList').hide();
		 	}			
		}
	}

	$(document).ready(function () { 
		 $( "#source_selector" ).buttonset();
		 $('#source_selector input:radio').click(function() {
		 	myUtil.setItemSource(this.value);
		 });

		myCommonControls = $.extend({}, commonGridControls);
		myImportExport = $.extend({}, gridImportExport, { 
			postImportAction: myUtil.postImportAction, 
			postUpdateAction: myUtil.postUpdateAction,
			acceptNewColumnsOnUpdate: true
 		});
		myGrid = $.extend({}, mainGrid, gridConfig);
		myImportExport.init(myGrid);
		myCommonControls.init(myGrid);

		myUtil.initEntryFields();
		myCommonControls.showControls(true);
	});

</script>
	
</body>
</html>