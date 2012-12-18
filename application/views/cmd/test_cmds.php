<hr>
<div>
<input class='lst_cmd_btn' type="button" value="Test" onClick='test()'  />
</div>

<div>
<p>Debug output:</p>
<textarea id='zed' rows='12' cols='90'></textarea>
</div>

//--debug---------
<script type="text/javascript">

function notYet() {
	alert('This function is not implemented yet');
}
function logg(s, clear) {
	if(typeof clear != "undefined")$('#zed').val('');
	$('#zed').val() += s + "\n";
}
</script>