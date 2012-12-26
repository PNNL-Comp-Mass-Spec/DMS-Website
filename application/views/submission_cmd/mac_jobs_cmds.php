<script type="text/javascript">

// get supplemental form fields via an AJAX call
function load_param_form() {
	var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/parameter_form/' + $('#scriptName').val();
	epsilon.load_suplemental_form(url, {}, 'param_container', function() {
		set_param_row_visibility("hide_input", "none");
	});
}
function choose_template(template_name) {
	$('#scriptName').val(template_name);
	load_param_form();
}

function set_param_row_visibility(class_name, visibility) {
	$('.' + class_name).each(function(idx, obj){ 
		obj.style.display = visibility; 
	});
}

//try and set up the supplemental form when the page loads
$(document).ready(function () { 
	epsilon.actions.before = function() {
		epsilon.copy_param_form_to_xml_param_field('param_form', 'jobParam');
	}
	load_param_form();
});	
</script>

<form name="frmParams" id="param_form" action="#">
<div id='param_container'>
<!-- supplemental form fields load here via AJAX -->
</div>
</form>
