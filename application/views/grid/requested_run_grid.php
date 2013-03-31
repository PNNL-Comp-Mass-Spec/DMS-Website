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
	<span class='ctls' data-query='batch_requests' >
	From batch <input type='text' size='10' class='dms_autocomplete_chsr' data-query='requested_run_batch_list' /><a class='button' href='javascript:void(0)' >Get</a>
	</span>
	<span class='ctls' data-query='osm_package_requests' >
	From OSM package <input type='text' size='10' class='dms_autocomplete_chsr' data-query='osm_package_list' /><a class='button' href='javascript:void(0)' >Get</a>
	</span>
	<span class='ctls'>
	From requested runs... <a href="javascript:epsilon.callChooser('requestItemList', '<?= site_url() ?>helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
	</span>
	</div>
	</td>

	<td rowspan="2">
		<div class="ctl_panel ctl_pane">
		<div class="ctl_panel">Blocking Commands</div>
		<div class="ctl_panel"><a class='button' id='globally_randomize_btn' href='javascript:void(0)' >Globally Randomize</a>
		</div>
		<div class="ctl_panel"><a class='button' id='randomly_block_btn' href='javascript:void(0)' >Randomly Block</a>
			<select id='block_size_ctl' ></select> (requests per block)
		</div>
		<div class="ctl_panel"><a class='button' id='factor_block_btn' href='javascript:void(0)'>Block by Factor</a>
			<select id='factor_select_ctl'></select>
		</div>
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
		staticColumns: ['Request', 'Name', 'Status', 'Batch', 'Experiment', 'Dataset', 'LC_Col', {id:"Instrument"}, {id:"Cart"}, {id:"Block"}, {id:""}, {id:"Run_Order"}],
		getLoadParameters: function() {
			var itemList = $('#requestItemList').val();
			return { itemList:itemList };
		},
		afterLoadAction: function() {
			myCommonControls.enableSave(false);
			myCommonControls.enableAddColumn(true);
			myUtil.setFactorSelection();
			myUtil.setColumnMenuCommands();			
		},
		getSaveParameters: function() {
			var mapP2A;
			var runParmColNameList = ['Status', 'Instrument', 'Cart', 'Block', 'Run_Order'];
			var dataRows = myGrid.grid.getData();
			var changes = gridUtil.getChanges(dataRows, 'Request');
			
			var runParamChanges = [];
			var factorChanges = [];
			$.each(changes, function(idx, change) {
				if(runParmColNameList.indexOf(change.factor) === -1) {
					factorChanges.push(change);
				} else {
					runParamChanges.push(change);
				}
			});

			mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(factorChanges, 'r', mapP2A);
			factorXML = '<id type="Request" />' + factorXML;

			mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'t'}, {p:'value', a:'v'}];
			var runParamXML = gamma.getXmlElementsFromObjectArray(runParamChanges, 'r', mapP2A);

			return { factorList: factorXML, blockingList: runParamXML };
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

	var myBlockingUtil = {
		runOrderFieldName: 'Run_Order',
		blockNumberFieldName: 'Block',
		lastNonFactorColumnName: 'Run_Order',
		// set property "rnd" in each object in input list to have random value
		setRandom: function(rlist) {
			$.each(rlist, function(idx, obj){
				obj.rnd = Math.random();
			});
		},
		getBlockingObjList: function(data) {
			var blockingObjList = [];
			$.each(data, function(idx) {
				var obj = {};
				obj.row = data[idx];
				obj.blockNumber = 0;
				obj.runOrder = 0;	
				blockingObjList.push(obj);
			});
			this.setRandom(blockingObjList);
			return blockingObjList;
		},
		loadBlockingObjectList: function(blockingObjList) {
			$.each(blockingObjList, function(idx, obj) {
				obj.blockNumber = obj.row['Block'];
				obj.runOrder = obj.row['Run_Order'];
			});			
		},
		getUniqueListOfBlockNumbers: function(blockingObjList) {
			blockNumberList = [];
			$.each(blockingObjList, function(idx, obj) {
				if(blockNumberList.indexOf(obj.blockNumber) === -1) {
					blockNumberList.push(obj.blockNumber);
				}
			});
			return blockNumberList;
		},
		getUniqueListOfBlockingFactorValues: function(blockingObjList, col_name) {
			ftList = [];
			$.each(blockingObjList, function(idx, obj) {
				var bfv = obj.row[col_name];
				if(ftList.indexOf(bfv) === -1) {
					ftList.push(bfv);
				}
			});
			return ftList;			
		},
		getBlockingObjListByBlockNumberValue: function(blockingObjList, blk) {
			var tmplist = [];
			$.each(blockingObjList, function(idx, obj){
				if(obj.blockNumber == blk) {
					tmplist.push(obj);
				}
			});
			return tmplist;
		},
		getBlockingObjListByBlockingFactorValue: function(blockingObjList, col_name, bf) {
			var tmplist = [];
			$.each(blockingObjList, function(idx, obj){
				if(obj.row[col_name] == bf) {
					tmplist.push(obj);
				}
			});
			return tmplist;
		},
		sortByRandomized: function(blockingObjList) {
			return blockingObjList.sort(function(a,b){return a.rnd > b.rnd ? 1 : a.rnd < b.rnd ? -1 : 0 });
		},
		randomizeRunOrder: function(blockingObjList){	
			var slist = this.sortByRandomized(blockingObjList);
			$.each(slist, function(idx, obj){
				obj.runOrder = idx + 1;
			});
		},
		randomizeWithinBlocks: function(blockingObjList) {
			var context = this;
			var blockNumberList = this.getUniqueListOfBlockNumbers(blockingObjList);
			$.each(blockNumberList, function(idx, blockNumber){
				var tlist = context.getBlockingObjListByBlockNumberValue(blockingObjList, blockNumber);
				context.randomizeRunOrder(tlist);
			});
		},		
		createRandomBlocksToSize: function(blockingObjList, blkSize) {
			var numBlocks = Math.ceil(blockingObjList.length / blkSize);
			this.setRandom(blockingObjList);
			var slist = this.sortByRandomized(blockingObjList);
			$.each(slist, function(idx, obj) {
				obj.blockNumber = (idx % numBlocks) + 1;
			});
			this.randomizeWithinBlocks(blockingObjList);
		},
		createBlocksFromFactor: function(blockingObjList, col_name) {
			var context = this;
			var bflist = this.getUniqueListOfBlockingFactorValues(blockingObjList, col_name);
			$.each(bflist, function(idx, bf){
				var tlist = context.getBlockingObjListByBlockingFactorValue(blockingObjList, col_name, bf);
				var slist = context.sortByRandomized(tlist);
				$.each(slist, function(seq, obj) {
					obj.blockNumber = seq + 1;
				});
			});
			this.randomizeWithinBlocks(blockingObjList);
		},
		//-------------
		globallyRandomize: function() {
			var blockingObjList = this.getBlockingObjList(myGrid.grid.getData());
			this.randomizeRunOrder(blockingObjList);
			return blockingObjList;
		},
		randomlyBlock: function(param) {
			var blkSize = $('#block_size_ctl').val();
			if(param) {
				var response = prompt('Block Size?', '4');
				if(response) { 
					blkSize = response;
				} else {
					return;
				}
			}
			if(blkSize < 2 || blkSize > 15) {
				alert('Block size must be within range 1-15');
				return;
			}
			var blockingObjList = this.getBlockingObjList(myGrid.grid.getData());
			if(blockingObjList.length < blkSize) {
				alert('Batch is smaller than block size');
				return
			}
			this.createRandomBlocksToSize(blockingObjList, blkSize);
			return blockingObjList;	
		},
		blockFromFactor: function(col_name) {
			col_name = col_name || $('#factor_select_ctl').val();
			if(!col_name) {
				alert('"' +  col_name + '" is not a valid name');
				return;
			}
			var blockingObjList = this.getBlockingObjList(myGrid.grid.getData());
			this.createBlocksFromFactor(blockingObjList, col_name);
			return blockingObjList;
		},
		reorderBlocks: function() {
			var blockingObjList = this.getBlockingObjList(myGrid.grid.getData());
			this.loadBlockingObjectList(blockingObjList);
			this.randomizeWithinBlocks(blockingObjList);
			return blockingObjList;
		},
		//-------------
		copyBlockingToData: function(blockingObjList) {
			var context = this;
			$.each(blockingObjList, function(idx, obj){
				if(obj.row[context.runOrderFieldName] != obj.runOrder) {
					obj.row[context.runOrderFieldName] = obj.runOrder;
					gridUtil.markChange(obj.row, context.runOrderFieldName);
				}
				if(obj.row[context.blockNumberFieldName] != obj.blockNumber) {
					obj.row[context.blockNumberFieldName] = obj.blockNumber;
					gridUtil.markChange(obj.row, context.blockNumberFieldName);			
				}
			});	
		},
		getFactorColNameList: function() {
			var ci = myGrid.grid.getColumnIndex(this.lastNonFactorColumnName);
			var factorCols = [];
			$.each(myGrid.grid.getColumns(), function(idx, colDef) {
				if(idx > ci) factorCols.push(colDef.field);
			});
			return factorCols;			
		},
		titles:{
			globally_randomize:'Place all requests into block 0 and globally randomize run order',
			randomly_block:'Randomly assign requests to blocks of the selected size, and randomize run order within blocks',
			factor_block:'Create blocks based on values for selected factor (attempts to have one request for each factor value in every block)',
			reorder_blocks:'Randomize run order within existing blocks'
		},		
		//-------------
		blockOp: function(op, param) {
			var blockingObjList;
			if(op == 'global') {
				blockingObjList = this.globallyRandomize(param);
			}
			if(op == 'block') {
				blockingObjList = this.randomlyBlock(param);
			}
			if(op == 'factor') {
				blockingObjList = this.blockFromFactor(param);
			}
			if(op == 'reorder') {
				blockingObjList = this.reorderBlocks(param);
			}
			this.copyBlockingToData(blockingObjList);
			gridUtil.setChangeHighlighting(myGrid.grid);
			myGrid.grid.invalidateAllRows();
			myGrid.grid.render();
			myCommonControls.enableSave(true);
		}		
	}
	
	var myUtil = {
		initEntryFields: function() {
			$('#globally_randomize_btn').click(function() { myBlockingUtil.blockOp('global') }).attr('title', myBlockingUtil.titles.globally_randomize); 
			$('#randomly_block_btn').click(function() { myBlockingUtil.blockOp('block') }).attr('title', myBlockingUtil.titles.randomly_block);  
			$('#factor_block_btn').click(function() { myBlockingUtil.blockOp('factor') }).attr('title', myBlockingUtil.titles.factor_block);  

			var el = $('#block_size_ctl');
			for(var i = 2; i < 10; i++) {
				var opt = $('<option></option>').attr('value', i).text(i);
				if(i == 4) opt.attr('selected',true)		
				el.append(opt);
			}
		},
		preImportAction: function(inputData) {
			if($.inArray('Request', inputData.columns) === -1) {
				alert('Imported data must contain the Request column');
				return false;
			}
		},
		postImportAction: function() {
			var x = $.map(myGrid.grid.getData(), function(row) {return row['Request']; });
			$('#requestItemList').val(x.join(', '));
			myCommonControls.enableSave(true);
			myCommonControls.enableAddColumn(true);
			myUtil.setFactorSelection();
			myUtil.setColumnMenuCommands();
		},
		postUpdateAction: function(newColumns) {
			myCommonControls.enableSave(true);	
			myUtil.setFactorColumnCommands(newColumns);		
		},
		validateNewFactorName: function(newFactorName) {
			var ok = true;
			$.each(myGrid.grid.getColumns(), function(idx, col) {
				if(col.field == newFactorName) {
					ok = false;
				}
			});
			if(!ok) {
				alert('New factor name is invalid (duplicates existing factor or is reserved word)');
			}
			return ok;
		},
		setFactorSelection: function() {
			var factors = myBlockingUtil.getFactorColNameList();
			var el = $('#factor_select_ctl');
			el.empty(); 
			$.each(factors, function(idx, factor) {
			  el.append($('<option></option>').attr('value', factor).text(factor));
			});
		},
		setColumnMenuCommands: function() {
			var context = this;	
			var col, cols;
			col = myGrid.grid.getColumns()[myGrid.grid.getColumnIndex(myBlockingUtil.blockNumberFieldName)];
			if(col) {				
				col.header.menu.items.push( { command:'', title:'-----' });
				col.header.menu.items.push({command:'randomize-global', title:'Randomize Globally', tooltip:myBlockingUtil.titles.globally_randomize })
				col.header.menu.items.push({command:'randomly-block', title:'Randomly Block', tooltip:myBlockingUtil.titles.randomly_block })
			}
			col = myGrid.grid.getColumns()[myGrid.grid.getColumnIndex(myBlockingUtil.runOrderFieldName)];
			if(col) {				
				col.header.menu.items.push( { command:'', title:'-----' });
				col.header.menu.items.push({command:'randomize-blocks', title:'Randomize Blocks', tooltip:myBlockingUtil.titles.reorder_blocks })
			}
			cols = myBlockingUtil.getFactorColNameList();
			context.setFactorColumnCommands(cols);

			myGrid.headerUtil.commands['randomize-global'] = function(column, grid) { myBlockingUtil.blockOp('global') };
			myGrid.headerUtil.commands['randomly-block'] = function(column, grid) { myBlockingUtil.blockOp('block', true) };
			myGrid.headerUtil.commands['randomize-blocks'] = function(column, grid) { myBlockingUtil.blockOp('reorder') };
			myGrid.headerUtil.commands['factor-blocks'] = function(column, grid) { myBlockingUtil.blockOp('factor', column.field) };
		},
		setFactorColumnCommands: function(colNames) {
			var col;
			$.each(colNames, function(idx, colName) {
				col = myGrid.grid.getColumns()[myGrid.grid.getColumnIndex(colName)];
				if(!col) return;
				col.header.menu.items.push( { command:'', title:'-----' } );
				col.header.menu.items.push({command:'factor-blocks', title:'Block With This Factor', tooltip:myBlockingUtil.titles.factor_block });
			});
		}
	}

	$(document).ready(function () { 
		myGrid = mainGrid.init(gridConfig);
		myCommonControls = commonGridControls.init(myGrid);
		myImportExport = gridImportExport.init(myGrid, { 
			preImportAction: myUtil.preImportAction,
			postImportAction: myUtil.postImportAction,
			preUpateAction: myUtil.preImportAction,
			postUpdateAction: myUtil.postUpdateAction,
			acceptNewColumnsOnUpdate: true
		});

 		sourceListUtil.setup();
		gamma.autocompleteChooser.setup();

		myUtil.initEntryFields();
		myCommonControls.setAddColumnLegend('new factor named:');
		myCommonControls.beforeAddCol = myUtil.validateNewFactorName;
		myCommonControls.afterAddCol = function(colName) {
			myUtil.setFactorSelection;
			myUtil.setFactorColumnCommands([colName]);
		}
		myCommonControls.showControls(true);
	});

</script>
	
</body>
</html>