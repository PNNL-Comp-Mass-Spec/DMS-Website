<div id='message_contents' style='display:none'>
<p>You have selected the 'Closed' state and there is biomaterial associated with this prep request.</p>
<p>Would you like to change the state setting to special 'Closed (containers and material)' setting instead?</p>
<p>This will retire the material and containers that are associated only with this prep request, and the request will be left in the 'Closed' state</p>
</div>


<script type="text/javascript">

epsilon.actions.before = function() {
	var proceed = false;

    $( "#message_contents" ).dialog({
        //resizable: false,
        height:140,
        modal: true,
        buttons: {
           "Change And Continue Update": function() {
                $( this ).dialog( "close" );
            },
            "Don't Change And Continue Update": function() {
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });

	return proceed;	
}

var gSubmission = {};
function submissionSequence(url, mode) {
	gSubmission.url = url;
	gSubmission.mode = mode;
	checkMaterial();
}
function showPopup(item) {
	var pos = $('#' + item).offset(); 
	var left = pos[0];
	var top = pos[1];
	$('#notification').show();
	new Effect.Move('notification', { x: left, y: top, mode: 'absolute', duration:0 }); // REFACTOR: Fix
}
function doSubmit(change) {
	doCancel();
	if(change) {
		$('#State').setValue('Closed (containers and material)');
	}
	epsilon.submitEntryFormToPage(gSubmission.url, gSubmission.mode);
}
function doCancel() {
	$('#notification').hide();
}
function checkMaterial() {
	var state = $('#State').getValue();
	var biomaterial = $('#CellCultureList').getValue();
	if((state != 'Closed') || (biomaterial == '(none)' || biomaterial == '') ) {
		doSubmit();
	} else {
		performCall('rowset');
	}
}
function performCall(mode) {
	var container = $('#notification_message');
	var url =  gamma.pageContext.site_url + "sample_prep_biomaterial_location/check_biomaterial";
	var p = {};
	p.ID = $('#ID').getValue();
	p.command = mode;
	container.spin('small');
	$.post(url, p, function (data) {
			container.spin(false);
			showPopup('cmd_buttons');
			container.html(data);
		}
	);
}
</script>
