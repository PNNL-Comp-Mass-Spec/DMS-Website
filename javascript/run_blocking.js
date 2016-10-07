var runBlocking = {
	setRandom: function(rlist) {
		$.each(rlist, function(idx, obj){
			obj.rnd = Math.random();
		});
	},
	getBlockingFieldsObjList: function(col_name) {
		// go through editable fields and build array of objects
		// where each object references the edit fields for 
		// one requested run
		var rlist = [];
		$('.Block').each(function(idx, bk) {
			var obj = {};
			obj.bk = bk;
			obj.ro = $('#Run_Order_' + bk.name).get(0);
			obj.bf = $('#' + col_name + '_' + bk.name).get(0);
			rlist.push(obj);
		});
		this.setRandom(rlist);
		return rlist;
	},
	randomizeRunOrder: function(rlist){	
		// get array of request objects that is
		// sorted by random value
		var slist = rlist.sort(function(a,b){return a.rnd > b.rnd ? 1 : a.rnd < b.rnd ? -1 : 0 });
		// change value in run order field to match
		// sequence of random sorted array
		$.each(slist, function(idx, obj){
			obj.ro.value = idx + 1;
		});
	},
	assignBlockingFactorToBlocks: function(rlist){
		// get array of request objects that is
		// sorted by random value
		var slist = rlist.sort(function(a,b){return a.rnd > b.rnd ? 1 : a.rnd < b.rnd ? -1 : 0 });
		// change value in run order field to match
		// sequence of random sorted array
		$.each(slist, function(idx, obj){
			obj.bk.value = idx + 1;
		});
	},
	getUniqueListOfBlocks: function() {
		// run through request list and get
		// unique list of block numbers
		bklist = [];
		$('.Block').each(function(idx, bk) {
			var blk = bk.value;
			if(bklist.indexOf(blk) === -1) {
				bklist.push(blk);
			}
		});
		return bklist;
	},
	getUniqueListOfBlockingFactors: function(col_name) {
		// run through request list and get
		// unique list of blocking factors
		bflist = [];
		$('.' + col_name).each(function(idx, bk) {
			var blk = bk.value;
			if(bflist.indexOf(blk) === -1) {
				bflist.push(blk);
			}
		});
		return bflist;
	},
	getBlockingFieldObjsInBlock: function(rlist, blk) {
		var tmplist = [];
		$.each(rlist, function(idx, obj){
			if(obj.bk.value == blk) {
				tmplist.push(obj);
			}
		});
		return tmplist;
	},
	getBlockingFieldObjsInBlockingFactor: function(rlist, bf) {
		var tmplist = [];
		$.each(rlist, function(idx, obj){
			if(obj.bf.value == bf) {
				tmplist.push(obj);
			}
		});
		return tmplist;
	},
	//-------------------------
	randomizeWithinBlocks: function() {
		var rlist = this.getBlockingFieldsObjList('Blocking_Factor');
		var bklist = this.getUniqueListOfBlocks();
		$.each(bklist, function(idx, bkn){
			var tlist = runBlocking.getBlockingFieldObjsInBlock(rlist, bkn);
			runBlocking.randomizeRunOrder(tlist);
		});
	},
	randomizeBatch: function() {
		var rlist = this.getBlockingFieldsObjList('Blocking_Factor');
		$.each(rlist, function(idx, obj){
			obj.bk.value = 0;
			obj.bf.value = 'na';
		});
		this.randomizeRunOrder(rlist);
	},
	createBlocksFromBlockingFactor: function(col_name) {
		var rlist = this.getBlockingFieldsObjList(col_name);
		var bflist = this.getUniqueListOfBlockingFactors(col_name);
		$.each(bflist, function(idx, bf){
			var tlist = runBlocking.getBlockingFieldObjsInBlockingFactor(rlist, bf);
			runBlocking.assignBlockingFactorToBlocks(tlist);
		});
		this.randomizeWithinBlocks();
	},
	createBlocksViaRandomAssignment: function() {
		var blkSize = $('#block_size').val();
		if(blkSize < 2 || blkSize > 15) {
			alert('Block size must be within range 1-15');
			return;
		}
		var rlist = this.getBlockingFieldsObjList('Blocking_Factor');
		if(rlist.length < blkSize) {
			alert('Batch is smaller than block size');
			return
		}
		var slist = rlist.sort(function(a,b){return a.rnd > b.rnd ? 1 : a.rnd < b.rnd ? -1 : 0 });
		var numBlocks = Math.ceil(slist.length / blkSize);
		$.each(slist, function(idx, obj) {
			obj.bk.value = (idx % numBlocks) + 1;
			if(obj.bf) obj.bf.value = 'na';
		});
		this.randomizeWithinBlocks();
	},
	requested_run_batch_blocking: {
		getFactorCols: function() {
			var cols = theta.getListReportColumnList();
			var factor_cols = gamma.removeItems(cols, ['Sel', 'BatchID', 'Status', 'Name',  'Request', 'Experiment', 'Dataset', 'Dataset_ID', 'Block',  'Run Order']);

			return factor_cols;
		},
		verifyColName: function(col_name) {
			var colx = '';
			var cols = this.getFactorCols();
			$.each(cols, function(idx, col) {
				if(col.toLowerCase() == col_name.toLowerCase()) {
					colx = col;
				}
			});	
			return colx;
		},
		createBlocksFromBlockingFactor: function(col_name) {
			col = this.verifyColName(col_name);
			if(!col) {
				alert('"' +  col_name + '" is not a valid name');
				return;
			}
			runBlocking.createBlocksFromBlockingFactor(col_name);
		},
		updateDatabaseFromList: function(flist, blist) {
			if ( !confirm("Are you sure that you want to update the database?") ) return;
			var factorXML = theta.getFactorXMLFromList(flist);
			var blockingXML = theta.getBlockingXMLFromList(blist);
			
			var url =  gamma.pageContext.ops_url;
			var p = {};
			p.factorList = factorXML;
			p.blockingList = blockingXML;
			// lambda.submitOperation is defined in dms2.js
			lambda.submitOperation(url, p);
		},
		saveChangesToDatabase: function() {
			var factor_cols = this.getFactorCols();
			var flist = theta.getFactorFieldList(factor_cols);
			var blocking_cols = ['Block',  'Run Order'];
			var blist = theta.getFactorFieldList(blocking_cols);
			this.updateDatabaseFromList(flist, blist);
		},
		load_delimited_text: function() {
			var parsed_data = gamma.parseDelimitedText('delimited_text_input');
			if(parsed_data.header[0] != 'Request') {
				alert('Header line does not begin with "Request"');
				// (someday) more extensive validation
				return;
			}
			var col_list = gamma.removeItems(parsed_data.header, ['Request', 'Block', 'Run Order']);
			var flist = theta.getFieldListFromParsedData(parsed_data, col_list);
			var blist = theta.getFieldListFromParsedData(parsed_data, ['Block', 'Run Order']);
			this.updateDatabaseFromList(flist, blist);
		},
		setBlockForSelectedItems: function() {
			// lambda.getSelectedItemList is defined in dms2.js
			var iList = lambda.getSelectedItemList();
			if (iList.length == 0) {
				alert('No items are selected');
				return;
			}
			var block = $('#block_input_setting').val();
			if(block != parseInt(block)) { 
				alert('Block must be a number'); 
				return; 
			}
			if(block < 1 || block > 50) {
				alert('Block out of range');
				return;
			}
			$.each(iList, function(idx, req) {
				$('#Block_' + req).val(block);
			});
		},
		performBatchOperation: function(mode) {
			var url =  gamma.pageContext.site_url + "requested_run_batch_blocking/exec/batch/";
			var p = {};
			p.command = mode;
			p.batchID = $('#BatchID').val();
			if(p.batchID == '') {
				alert("No batch ID");
				return;
			}
			// lambda.submitOperation is defined in dms2.js
			lambda.submitOperation(url, p);
		}
	}
} // runBlocking
