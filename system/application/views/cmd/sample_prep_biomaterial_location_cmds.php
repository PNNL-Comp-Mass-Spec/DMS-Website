<script type="text/javascript">
function localRowAction(url, value, obj) {
	var v = value.toLowerCase();
	var s = 'U:' + url + ', ';
	s += 'V:' + v;
	if(v == 'retire_biomaterial') {
		s += ' B:' +  obj["Biomaterial"];
	} else 
	if(v == 'retire_container') {
		s += ' C:' +  obj["Container"];
	}	
	alert(s);
}

//perform detail report command (via AJAX)
function performCommand(url, id, mode) {
	if( !confirm("Are you sure that you want to update the database?") ) return;
	var p = {};
	p.ID = id;
	p.command = mode;
	var opts = {};
	opts.parameters = p;
	var container_name = globalAJAX.response_container;
	$(container_name).update(globalAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container_name).update(transport.responseText);
			 updateMyData('autoload');	
		}
	});
}
</script>

<div class="LRCmds">
<div><input type="checkbox" name="xxx" value="Car" />Retire material for selected items</div>
<div><input type="checkbox" name="xxx" value="Car" />Retire container for selected items</div>

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='' title=""  /> 
</div>

</div>