<script type="text/javascript">

// reload supplemental form as post-submission action
var post_submission_action = {
	run:function(mode) {
		load_param_form();
	}
}

// override for submission hook to ensure that
// parameters are copied from supplemental form
// as XML to parameters field in main form
var gSubmission = {};
function submissionSequence(url, mode, post_submission_action) {
	gSubmission.url = url;
	gSubmission.mode = mode;
	epsilon.copy_param_form_to_xml_param_field('param_form', 'jobParam');
	// TODO: pre-submission check for blank XML parameters field.
	epsilon.submitEntryFormToPage(gSubmission.url, gSubmission.mode);
}

// get supplemental form fields via an AJAX call
function load_param_form() {
	var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/parameter_form/' + $('#scriptName').val();
	p = {};
	$.post(url, p, function (data) {
		    $('#param_container').html(data);
			set_param_row_visibility("hide_input", "none");
		}
	);
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
	load_param_form();
	}
);	
</script>

<form name="frmParams" id="param_form" action="#">
<div id='param_container'>
<!-- supplemental form fields load here via AJAX -->
</div>
</form>
