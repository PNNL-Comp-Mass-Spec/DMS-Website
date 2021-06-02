	var runBlockingGridUtil = {
		runOrderFieldName: 'Run_Order',
		blockNumberFieldName: 'Block',
		grid: null,
		init: function(wrapper) {
			var obj = $.extend({}, this);
			obj.grid = function() {
				return wrapper.grid;
			};
			return obj;
		},
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
				obj.blockNumber = 1;
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
				if (blockNumberList.indexOf(obj.blockNumber) === -1) {
					blockNumberList.push(obj.blockNumber);
				}
			});
			return blockNumberList;
		},
		getUniqueListOfBlockingFactorValues: function(blockingObjList, col_name) {
			ftList = [];
			$.each(blockingObjList, function(idx, obj) {
				var bfv = obj.row[col_name];
				if (ftList.indexOf(bfv) === -1) {
					ftList.push(bfv);
				}
			});
			return ftList;
		},
		getBlockingObjListByBlockNumberValue: function(blockingObjList, blk) {
			var tmplist = [];
			$.each(blockingObjList, function(idx, obj){
				if (obj.blockNumber == blk) {
					tmplist.push(obj);
				}
			});
			return tmplist;
		},
		getBlockingObjListByBlockingFactorValue: function(blockingObjList, col_name, bf) {
			var tmplist = [];
			$.each(blockingObjList, function(idx, obj){
				if (obj.row[col_name] == bf) {
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
		copyBlockingToData: function(blockingObjList) {
			var context = this;
			$.each(blockingObjList, function(idx, obj){
				if (obj.row[context.runOrderFieldName] != obj.runOrder) {
					obj.row[context.runOrderFieldName] = obj.runOrder;
					gridUtil.markChange(obj.row, context.runOrderFieldName);
				}
				if (obj.row[context.blockNumberFieldName] != obj.blockNumber) {
					obj.row[context.blockNumberFieldName] = obj.blockNumber;
					gridUtil.markChange(obj.row, context.blockNumberFieldName);
				}
			});
		},
		//---[blocking commands]----------
		afterBlockingOperation: null,
		titles:{
			globally_randomize:'Place all requests into block 1 and globally randomize run order',
			randomly_block:'Randomly assign requests to blocks of the selected size, and randomize run order within blocks',
			factor_block:'Create blocks based on values for selected factor (attempts to have one request for each factor value in every block)',
			reorder_blocks:'Randomize run order within existing blocks'
		},
		blockingOperation: function(op, param) {
			var blockingObjList;
			if (op == 'global') {
				blockingObjList = this.globallyRandomize(param);
			} else
			if (op == 'block') {
				blockingObjList = this.randomlyBlock(param);
			} else
			if (op == 'factor') {
				blockingObjList = this.blockFromFactor(param);
			} else
			if (op == 'reorder') {
				blockingObjList = this.reorderBlocks(param);
			} else {
				return;
			}
			if (this.afterBlockingOperation) this.afterBlockingOperation(blockingObjList);
		},
		globallyRandomize: function() {
			var blockingObjList = this.getBlockingObjList(this.grid().getData());
			this.randomizeRunOrder(blockingObjList);
			return blockingObjList;
		},
		randomlyBlock: function(param) {
			var blkSize = $('#block_size_ctl').val();
			if (param) {
				var response = prompt('Block Size?', '4');
				if (response) {
					blkSize = response;
				} else {
					return;
				}
			}
			if (blkSize < 2 || blkSize > 15) {
				alert('Block size must be within range 1-15');
				return;
			}
			var blockingObjList = this.getBlockingObjList(this.grid().getData());
			if (blockingObjList.length < blkSize) {
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
			var blockingObjList = this.getBlockingObjList(this.grid().getData());
			this.createBlocksFromFactor(blockingObjList, col_name);
			return blockingObjList;
		},
		reorderBlocks: function() {
			var blockingObjList = this.getBlockingObjList(this.grid().getData());
			this.loadBlockingObjectList(blockingObjList);
			this.randomizeWithinBlocks(blockingObjList);
			return blockingObjList;
		}
	}