
<script type="text/javascript" src="<?= base_url().'javascript/factors.js' ?>"></script>

<script type="text/javascript">
function updateDatabaseFromList(xml, command) {
	if (xml == '') {
		alert('No requests were selected');
		return;
	}
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var p = {};
	p.requestList = xml;
	p.command = command;
	var url =  "<?= $ops_url ?>";
	submitOperation(url, p);
}
function make_xml_list(rlist) {
	var s = '';
	rlist.each(function(item) {
		s += '<r i="' + item + '" />';
	});
	return s;
}
function deleteRequests() {
	var iList = getSelectedItemList();
	var xml = make_xml_list(iList);
	updateDatabaseFromList(xml, 'delete');
}
function setRequestStatus(status) {
	var iList = getSelectedItemList();
	var xml = make_xml_list(iList);
	updateDatabaseFromList(xml, status);
}
</script>

<div class="LRCmds">
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" action="">

<div>
<input class='lst_cmd_btn' type="button" value="Set Requests Active" onClick='setRequestStatus("Active")' title="Test"  /> Set selected requests to "Active" status
</div>

<div>
<input class='lst_cmd_btn' type="button" value="Set Requests Inactive" onClick='setRequestStatus("Inactive")' title="Test"  /> Set selected requests to "Inactive" status
</div>

<hr>
<div>
<input class='lst_cmd_btn' type="button" value="Delete Requests" onClick='deleteRequests()' title="Test"  /> Delete selected requests
</div>

</form>
</div>
