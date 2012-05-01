<script type="text/javascript">
function performOperation(mode) {
	var list = getCkbxList('ckbx');
	if(list=='') {
		alert('You must select requests.'); 
		return;
	}
	if ( !confirm("Are you sure that you want to update the database?") )
		return;

	url =  "<?= $ops_url ?>";
	var p = {};
	p.command = mode;
	p.datasetIDList = list;
	p.rating = $F('rating_fld');
	p.comment = $F('comment_fld');
	p.recycleRequest = $F('recycle_fld');
	submitOperation(url, p);
}
</script>

<div class='LRCmds'>
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" action="">

<div>
<span>Dataset Rating:</span>
<select name='rating' id='rating_fld'>
	<option>Released</option>
	<option>Not Released</option>
	<option>Rerun (Good Data)</option>
	<option>Rerun (Superseded)</option>
</select>
</div>

<div>
<span>Recycle Request:</span>
<select name='recycle' id='recycle_fld' >
	<option>No</option>
	<option>Yes</option>
</select>
</div>

<div>
<span>Append To Comment:</span>
<div><textarea name='comment' id='comment_fld' rows='4' cols='45' ></textarea></div>

<span style='position:relative;left:10px;''>
<select name='cannedComments' id='commend_selector' onChange='setFieldValueFromSelection("comment_fld", "commend_selector", "append")'>
  <option></option>
  <Option>Air bubble in vial</Option>
  <Option>Calibration bad</Option>
  <Option>Chromatography poor</Option>
  <Option>Column background high</Option>
  <Option>Column filter plugged</Option>
  <Option>Column maintenance needed</Option>
  <Option>Column plugged</Option>
  <Option>Column position bad</Option>
  <Option>Excalibur crashed</Option>
  <Option>Gradient too slow</Option>
  <Option>Incomplete run</Option>
  <Option>Inconsistent sensitivity</Option>
  <Option>Instrument scan not completed</Option>
  <Option>Instrument performance questionable</Option>
  <Option>Ion funnel off</Option>
  <Option>LC cart leak</Option>
  <Option>LC cart method entry incorrect</Option>
  <Option>LC cart problems</Option>
  <Option>LC cart system/column mismatch</Option>
  <Option>LC set to wrong column</Option>
  <Option>LC valve problem</Option>
  <Option>Magnetic stirrer off</Option>
  <Option>Mixer off</Option>
  <Option>No calibrant peak</Option>
  <Option>No ms/ms collected</Option>
  <Option>Power outage</Option>
  <Option>Raw file bad</Option>
  <option>Sample exhausted.</option>
  <Option>Sample injection bad</Option>
  <Option>Sample plugged the column</Option>
  <Option>Sample response low</Option>
  <Option>Sensitivity drop</Option>
  <Option>Sensitivity issue</Option>
  <Option>Sensitivity low</Option>
  <Option>Signal lost</Option>
  <Option>Signal low</Option>
  <Option>Signal too high</Option>
  <Option>Signal too low</Option>
  <Option>Slow gradient</Option>
  <Option>Solvent mixing problem</Option>
  <Option>Solvent pump was empty</Option>
  <Option>Split flow too low</Option>
  <Option>Split plugged</Option>
  <Option>Spray problem</Option>
  <Option>Stirrer not on</Option>
  <Option>Stirrer slow</Option>
  <Option>Syringe error</Option>
  <Option>Syringe plugged</Option>
  <Option>Syringe wash malfunction</Option>
  <Option>Tip alignment bad</Option>
  <Option>Tip plugged</Option>
  <Option>Transfer line plugged</Option>
  <Option>Vacuum pump problem</Option>
  <Option>Valve leaking</Option>
  <Option>Valve problem</Option>
  <Option>Vial empty</Option>
  <Option>Vial out of sample</Option>
  <Option>Voltage loss</Option>
  <Option>Voltage lost</Option>
  <Option>Voltage unstable</Option>
  <Option>Wrong column method</Option>
  <Option>Wrong gradient time on LC cart</Option>
  <Option>Wrong sample</Option>
  <Option>Wrong system selected on LC cart</Option>
</select>
<span>(select canned phrases)</span>
</span>

</div>

<div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("update")' title='Disposition the selected datasets.'/>
</div>

</form>
</div>