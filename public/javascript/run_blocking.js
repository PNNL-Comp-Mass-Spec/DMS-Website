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
        $('.block').each(function(idx, bk) {
            var obj = {};
            obj.bk = bk;
            obj.ro = $('#run_order_' + bk.name).get(0);
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
        $('.block').each(function(idx, bk) {
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
            var cols = factorsjs.getListReportColumnList();
            var factor_cols = dmsjs.removeItems(cols, ['Sel', 'BatchID', 'Batch_ID', 'Status', 'Name',  'Request', 'Experiment', 'Dataset', 'Dataset_ID', 'Block',  'Run Order',  'Run_Order']);

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

        updateDatabaseFromList: function(factor_list, blocking_list) {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var factorXML = factorsjs.getFactorXMLFromList(factor_list);
            var blockingXML = factorsjs.getBlockingXMLFromList(blocking_list);

            var url =  dmsjs.pageContext.ops_url;
            var p = {};
            p.factorList = factorXML;
            p.blockingList = blockingXML;
            // dmsOps.submitOperation is defined in dmsOps.js
            dmsOps.submitOperation(url, p);
        },

        saveChangesToDatabase: function() {
            var factor_cols = this.getFactorCols();
            var factor_list = factorsjs.getFactorFieldList(factor_cols);
            var blocking_cols = ['block',  'run_order'];
            var blocking_list = factorsjs.getFactorFieldList(blocking_cols);
            this.updateDatabaseFromList(factor_list, blocking_list);
        },

        load_delimited_text: function() {
            var parsed_data = dmsInput.parseDelimitedText('delimited_text_input');
            var header_updates = '';
            
            // Change header names to lowercase and change column 'run order' to 'run_order'   
                     
            parsed_data.header.forEach((headerName, index, array) => {
                var lowercaseName = headerName.toLowerCase().trim();
                
                if (lowercaseName == 'run order') {
                    lowercaseName = 'run_order'; 
                }
                
                if (lowercaseName != headerName) {
                   array[index] = lowercaseName;
                   
                   if (header_updates == '') {
                       header_updates = 'Header updates: ' + headerName + '=>' + lowercaseName;
                   } else {
                       header_updates += ', ' + headerName + '=>' + lowercaseName;
                   }
                }
            });
            
            // Uncomment to show the list of updated column names

            // if (header_updates != '') {
            //     alert(header_updates);
            // }                
            
            if(parsed_data.header[0] != 'request') {
                alert('The first column in the header line must be "Request"');
                // (someday) more extensive validation
                return;
            }

            var factor_names = dmsjs.removeItems(parsed_data.header, ['Request', 'Block', 'Run Order', 'Run_Order']);
            var factor_list = factorsjs.getFieldListFromParsedData(parsed_data, factor_names);
            var blocking_list = factorsjs.getFieldListFromParsedData(parsed_data, ['block', 'run_order']);

            this.updateDatabaseFromList(factor_list, blocking_list);
        },

        setBlockForSelectedItems: function() {
            // dmsChooser.getSelectedItemList is defined in dmsChooser.js
            var iList = dmsChooser.getSelectedItemList();
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
                $('#block_' + req).val(block);
            });
        },

        performBatchOperation: function(mode) {
            var url =  dmsjs.pageContext.site_url + "requested_run_batch_blocking/exec/batch";
            var p = {};
            p.command = mode;
            p.batchID = $('#batch_id').val();
            if(p.batchID == '') {
                alert("No batch ID");
                return;
            }
            // dmsOps.submitOperation is defined in dmsOps.js
            dmsOps.submitOperation(url, p);
        }
    }
} // runBlocking
