<div id='message_contents' style='display:none'>
<p>You have selected the 'Closed' state and there is biomaterial associated with this prep request.</p>
<p>Would you like to change the state setting to special 'Closed (containers and material)' setting instead?</p>
<p>This will retire the material and containers that are associated only with this prep request, and the request will be left in the 'Closed' state</p>
</div>


<script type="text/javascript">

// set hook to trap standard page submit sequence
epsilon.actions.before = function() {
	var proceed = false;
	// this function will be called by standard submit sequence
	// prior to actually submitting form to server
	return function() {
		// check whether or not we need to have user confirm submit
		proceed = checkMaterial(proceed);
		if(!proceed) {
			// present modal dialog with user choices
			// and return false to cancel original submit
			var text = $('#message_contents').html();
		    $( "<div></div>" ).html(text).dialog({
		        height:300,
		        width: 650,
		        modal: true,
		        buttons: {
		           "Change And Continue Update": function() {
						$('#State').val('Closed (containers and material)');
		                $( this ).dialog( "close" );
		                proceed = true;
		           		$('#primary_cmd').click(); // retrigger the submit 
		            },
		            "Don't Change And Continue Update": function() {
		                $( this ).dialog( "close" );
		                proceed = true;
		            	$('#primary_cmd').click(); // retrigger the submit 
		           },
		            Cancel: function() {
		            	proceed = false;
		                $( this ).dialog( "close" );
		            }
		        }
		    });
		}
		return proceed;	
	}
}();

function checkMaterial(proceed) {
	var state = $('#State').val();
	var biomaterial = $('#CellCultureList').val();
	if((state != 'Closed') || (biomaterial == '(none)' || biomaterial == '') ) {
		proceed = true;
	}
	return proceed;
}

</script>
