

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
	$('#jobParam').val('');
	$('#param_form').getElements().each(
		function(obj) {
			var nm = obj.name.split('.');
			var s = '<Param ';
			s += 'Section="' + nm[0] + '" ';
			s += 'Name="' + nm[1] + '" ';
			s += 'Value="' + obj.value + '" ';
			if(nm.length > 2) {
				s += 'Step="' + nm[2] + '" ';
			}
			s += '/>';
			$('#jobParam').val() += s;
		}
	);
}

// get supplemental form fields via an AJAX call
function load_param_form() {
	var url = '<?= site_url().$tag ?>/parameter_form/' + $('#job').val() + '/' + $('#scriptName'val();
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
function choose_script(script) {
	$('#scriptName').val(cript);
	load_param_form();
}

function load_script_diagram() {
	var scriptName = $('#scriptName'val();
	if(scriptName) {
		var url = '<?= site_url() ?>pipeline_script/dot/' + scriptName
		var container = 'script_diagram_container';
		p = {};
		new Ajax.Request(url, {
			parameters: p,
			onSuccess: function(transport) {
				$(container).html(transport.responseText);
			}
		});	
	}
}

function set_param_row_visibility(class_name, visibility) {
	var tag = '.' + class_name;
	$(tag).each(function(idx, obj){ obj.style.display = visibility; } );
}

//try and set up the supplemental form when the page loads
$(document).ready(function () { 
	load_param_form();
	load_script_diagram();
	}
);	
</script>

<form name="frmParams" id="param_form" action="#">
<div id='param_container'>
<!-- supplemental form fields load here via AJAX -->
</div>
</form>

<div style='padding:4px;'><a href="javascript:load_script_diagram()">Script...</a></div>
<div id="script_diagram_container">
</div>

