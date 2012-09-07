<script type="text/javascript">
globalAJAX.response_container_name =  "update_message";
globalAJAX.cntrl_container_name =  "clear_message";

function submitOperation(url, p, show_resp) {
	var container_name = globalAJAX.response_container_name;
	$(container_name).update(globalAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			var rt = transport.responseText;
			if(rt.indexOf('Update failed') > -1) {
				$(container_name).update(transport.responseText);
				$(globalAJAX.cntrl_container_name).show();
			} else {
				var msg = 'Operation was successful';
				if(show_resp) msg = rt;
				$(container_name).update(msg);
				$(globalAJAX.cntrl_container_name).hide();
				reloadListReportData();
			}
		}});
}
</script>

<div class="LRCmds">

<span id="update_message" class="RepCmdsResponse" ></span>

<span id="clear_message" style='display:none'>
|<span style='padding:0 4px 0 4px;'><a href='javascript:reloadListReportData()'>Refresh the rows</a></span>
|<span style='padding:0 4px 0 4px;'><a href='javascript:void(0)' onclick='javascript:$("update_message").update("");$("clear_message").hide();'>Clear message</a></span>
|</span>

</div>
