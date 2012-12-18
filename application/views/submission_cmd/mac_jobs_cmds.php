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
	copy_param_form_to_xml_param_field();
	// TODO: pre-submission check for blank XML parameters field.
	submitToFamily(gSubmission.url, gSubmission.mode);
}

//loop through all the fields in the parameter form
//and build properly formatted XML and replace the
//contents of the jobParams field on main form with it
function copy_param_form_to_xml_param_field() {
	$('#jobParam').value = '';
	$('#param_form').getElements().each(
		function(obj) {
			if(obj.name.indexOf('_chooser') === -1) {
				var s = '<Param ';
				s += 'Name="' + obj.name + '" ';
				s += 'Value="' + obj.value + '" ';
				s += '/>';
				$('#jobParam').value += s;
			}
		}
	);
}

// get supplemental form fields via an AJAX call
function load_param_form() {
	var url = '<?= site_url().$tag ?>/parameter_form/' + $('#scriptName'val();
	var container = 'param_container';
	p = {};
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).html(transport.responseText);
			set_param_row_visibility("hide_input", "none");
		}
	});	
}
function choose_template(template_name) {
	$('#scriptName').value = template_name;
	load_param_form();
}

function set_param_row_visibility(class_name, visibility) {
	var tag = '.' + class_name;
	$(tag).each(function(idx, obj){ obj.style.display = visibility; } );
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
