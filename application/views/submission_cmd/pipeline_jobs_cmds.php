

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

function load_script_diagram() {
	var scriptName = $('#scriptName').val();
	if(scriptName) {
		var url = gamma.pageContext.site_url + 'pipeline_script/dot/' + scriptName
		var container = 'script_diagram_container';
		p = {};
		$.post(url, p, function (data) {
			    $('#script_diagram_container').html(data);
			}
		);
	}
}

function set_param_row_visibility(class_name, visibility) {
	$('.' + class_name).each(function(idx, obj) { 
		obj.style.display = visibility; 
	});
}

$(document).ready(function () { 
	epsilon.actions.before = function() {
		epsilon.copy_param_form_to_xml_param_field('param_form', 'jobParam');
	}
	load_param_form();
	load_script_diagram();
});	
</script>

<form name="frmParams" id="param_form" action="#">
<div id='param_container'>
<!-- supplemental form fields load here via AJAX -->
</div>
</form>

<div style='padding:4px;'><a href="javascript:load_script_diagram()">Script...</a></div>
<div id="script_diagram_container">
</div>

