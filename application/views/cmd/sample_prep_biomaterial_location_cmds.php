<div class="LRCmds">
<div><input type="checkbox" name="xxx" value="Car" />Retire material for selected items</div>
<div><input type="checkbox" name="xxx" value="Car" />Retire container for selected items</div>

<div>
<input class='lst_cmd_btn' type="button" value="Update" onClick='' title=""  /> 
</div>

</div>

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

</script>