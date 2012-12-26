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
	var pos = $('#' + item).offset(); 
	var left = pos[0];
	var top = pos[1];
	$('#notification').show();
	new Effect.Move('notification', { x: left, y: top, mode: 'absolute', duration:0 }); // REFACTOR: Fix
}
function doSubmit(change) {
	doCancel();
	if(change) {
		$('#State').setValue('Closed (containers and material)');
	}
	epsilon.submitEntryFormToPage(gSubmission.url, gSubmission.mode);
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
	var container = $('#notification_message');
	var url =  gamma.pageContext.site_url + "sample_prep_biomaterial_location/check_biomaterial";
	var p = {};
	p.ID = $('#ID').getValue();
	p.command = mode;
	container.spin('small');
	$.post(url, p, function (data) {
			container.spin(false);
			showPopup('cmd_buttons');
			container.html(data);
		}
	);
}
</script>
