function setRandom(rlist) {
	rlist.each(function(idx, obj){
		obj.rnd = Math.random();
	});
}
function getBlockingFieldsObjList(col_name) {
	// go through editable fields and build array of objects
	// where each object references the edit fields for 
	// one requested run
	if(!col_name) {
		col_name = 'Blocking_Factor';
	}
	var rlist = [];
	$('.Block').each(function(idx, bk) {
		var obj = {};
		obj.bk = bk;
		obj.ro = $('Run_Order_' + bk.name);
		obj.bf = $(col_name + '_' + bk.name)
		rlist.push(obj);
	});
	setRandom(rlist);
	return rlist;
}
function randomizeRunOrder(rlist){	
	// get array of request objects that is
	// sorted by random value
	var slist = rlist.sortBy(function(obj){
		return obj.rnd;
	});
	// change value in run order field to match
	// sequence of random sorted array
	slist.each(function(idx, obj, index){
		obj.ro.value = index + 1;
	});
}
function assignBlockingFactorToBlocks(rlist){
	// get array of request objects that is
	// sorted by random value
	var slist = rlist.sortBy(function(obj){
		return obj.rnd;
	});
	// change value in run order field to match
	// sequence of random sorted array
	slist.each(function(idx, obj, index){
		obj.bk.value = index + 1;
	});
}
function getUniqueListOfBlocks() {
	// run through request list and get
	// unique list of block numbers
	bklist = [];
	$('.Block').each(function(idx, bk) {
		var blk = bk.value;
		if(!bklist.include(blk)) {
			bklist.push(blk);
		}
	});
	return bklist;
}
function getUniqueListOfBlockingFactors(col_name) {
	// run through request list and get
	// unique list of blocking factors
	bflist = [];
	$('.' + col_name).each(function(idx, bk) {
		var blk = bk.value;
		if(!bflist.include(blk)) {
			bflist.push(blk);
		}
	});
	return bflist;
}
function getBlockingFieldObjsInBlock(rlist, blk) {
	var tmplist = [];
	rlist.each(function(idx, obj){
		if(obj.bk.value == blk) {
			tmplist.push(obj);
		}
	});
	return tmplist;
}
function getBlockingFieldObjsInBlockingFactor(rlist, bf) {
	var tmplist = [];
	rlist.each(function(idx, obj){
		if(obj.bf.value == bf) {
			tmplist.push(obj);
		}
	});
	return tmplist;
}
//-------------------------
function randomizeWithinBlocks() {
	var rlist = getBlockingFieldsObjList();
	var bklist = getUniqueListOfBlocks();
	bklist.each(function(idx, bkn){
		var tlist = getBlockingFieldObjsInBlock(rlist, bkn);
		randomizeRunOrder(tlist);
	});
}
function randomizeBatch() {
	var rlist = getBlockingFieldsObjList();
	rlist.each(function(idx, obj){
		obj.bk.value = 0;
		obj.bf.value = 'na';
	});
	randomizeRunOrder(rlist);
}
function createBlocksFromBlockingFactor(col_name) {
	var rlist = getBlockingFieldsObjList();
	var bflist = getUniqueListOfBlockingFactors(col_name);
	bflist.each(function(idx, bf){
		var tlist = getBlockingFieldObjsInBlockingFactor(rlist, bf);
		assignBlockingFactorToBlocks(tlist);
	});
	randomizeWithinBlocks();
}
function createBlocksViaRandomAssignment() {
	var blkSize = $('#block_size'val();
	if(blkSize < 2 || blkSize > 15) {
		alert('Block size must be within range 1-15');
		return;
	}
	var rlist = getBlockingFieldsObjList();
	if(rlist.length < blkSize) {
		alert('Batch is smaller than block size');
		return
	}
	var slist = rlist.sortBy(function(obj){
		return obj.rnd;
	});
	var numBlocks = Math.ceil(slist.length / blkSize);
	slist.each(function(idx, obj, index) {
		obj.bk.value = (index % numBlocks) + 1;
		obj.bf.value = 'na';
	});
	randomizeWithinBlocks();
}
