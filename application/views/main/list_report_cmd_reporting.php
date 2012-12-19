<script type="text/javascript">
globalAJAX.response_container_name =  "update_message";
globalAJAX.cntrl_container_name =  "clear_message";

function submitOperation(url, p, show_resp) {
	var ctl = $('#' + globalAJAX.cntrl_container_name);
	var container = $('#' + globalAJAX.response_container_name);
	container.html(globalAJAX.progress_message);
	$.post(url, p, function (data) {
			if(data.indexOf('Update failed') > -1) {
				container.html(data);
				ctl.show();
			} else {
				var msg = 'Operation was successful';
				if(show_resp) msg = data;
				container.html(msg);
				ctl.hide();
				reloadListReportData();
			}
		}
	);
}
</script>

<div class="LRCmds">

<span id="update_message" class="RepCmdsResponse" ></span>

<span id="clear_message" style='display:none'>
|<span style='padding:0 4px 0 4px;'><a href='javascript:reloadListReportData()'>Refresh the rows</a></span>
|<span style='padding:0 4px 0 4px;'><a href='javascript:void(0)' onclick='javascript:$('#update_message').html("");$('#clear_message').hide();'>Clear message</a></span>
|</span>

</div>
