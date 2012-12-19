<?php // notification
$this->load->view("main/notification");
?>

<script type="text/javascript">
var gSubmission = {};
function submissionSequence(url, mode) {
	gSubmission.url = url;
	gSubmission.mode = mode;
	checkMaterial();
}
function showPopup(item) {
	var pos = $(item).cumulativeOffset();
	var left = pos[0];
	var top = pos[1];
	$('#notification').show();
	new Effect.Move('notification', { x: left, y: top, mode: 'absolute', duration:0 });
}
function doSubmit(change) {
	doCancel();
	if(change) {
		$('#State').setValue('Closed (containers and material)');
	}
	submitToFamily(gSubmission.url, gSubmission.mode);
}
function doCancel() {
	$('#notification').hide();
}
function checkMaterial() {
	var state = $('#State').getValue();
	var biomaterial = $('#CellCultureList').getValue();
	if((state != 'Closed') || (biomaterial == '(none)' || biomaterial == '') ) {
		doSubmit();
	} else {
		performCall('rowset');
	}
}
function performCall(mode) {
	var url =  "<?= site_url() ?>sample_prep_biomaterial_location/check_biomaterial";
	var p = {};
	p.ID = $('#ID').getValue();
	p.command = mode;
	$('#notification_message').html(globalAJAX.progress_message);
	$.post(url, p, function (data) {
			showPopup('cmd_buttons');
			$('#notification_message').html(data);
		}
	);
}
</script>
