
<script type="text/javascript">
function transferData(perspective) {
	var list = gamma.getCkbxList('ckbx' );
	if(list=='') {
		alert('You must select at least 1 analysis job.'); 
		return;
	}
	if ( !confirm("Are you sure that you want to transfer the selected data?") )
		return;

	url =  "<?= site_url() . '/data_transfer/" + perspective + "' ?>";
	var p = {};
	p.perspective = perspective;
	p.iDList = list;
	submitOperation(url, p);
}
</script>

<div class='LRCmds'>
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<h3>Commands</h3>

<form name="DBG" id="cmds" >
<ul>

<a href='javascript:transferData("analysis_job")'><img src='<?= base_url() ?>images/btn.png' border='0' ></a> Transfer Data

</ul>
</form>
</div>
