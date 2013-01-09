<form name="frmParams" id="param_form" action="#">
<div id='param_container'>
<!-- supplemental form fields load here via AJAX -->
</div>
</form>

<div style='padding:4px;'><a href="javascript:gamma.load_script_diagram_cmd()">Script...</a></div>
<div id="script_diagram_container">
</div>

<script type="text/javascript">

function load_param_form() {
	var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/parameter_form/' + $('#job').val() + '/' + $('#scriptName').val();
	epsilon.load_suplemental_form(url, {}, 'param_container', function() {
		set_param_row_visibility("hide_input", "none");
	});
}
function choose_script(script) {
	$('#scriptName').val(script);
	load_param_form();
}

function set_param_row_visibility(class_name, visibility) {
	$('.' + class_name).each(function(idx, obj) { 
		obj.style.display = visibility; 
	});
}

var cmdInit = function () { 
	epsilon.actions.before = function() {
		epsilon.copy_param_form_to_xml_param_field('param_form', 'jobParam');
	}
	load_param_form();
	gamma.load_script_diagram_cmd();
	return true;
}
</script>

