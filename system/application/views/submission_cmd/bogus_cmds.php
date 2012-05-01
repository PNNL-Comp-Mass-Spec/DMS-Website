
<?php // notification
$this->load->view("main/notification");
?>

<script type="text/javascript">
//example of post-submission action
var post_submission_action = {
	run:function(mode) {
		alert('This simulates a post-submission action for ' + mode);
	}
}

var gSubmission = {};
function submissionSequence(url, mode) {
	gSubmission.url = url;
	gSubmission.mode = mode;
	doFirstThing();
}
function showPopup(item) {
	var pos = $(item).cumulativeOffset();
	var left = pos[0];
	var top = pos[1];
	$('notification').show();
	new Effect.Move('notification', { x: left, y: top, mode: 'absolute', duration:0 });
}
function doSubmit() {
	$('notification').hide();
	submitToFamily(gSubmission.url, gSubmission.mode, post_submission_action);
}
function doCancel() {
//	$('notification_message').update('');
	$('notification').hide();
}
function doFirstThing() {
	showPopup('cmd_buttons');
	$('notification_message').update($('first_thing').innerHTML);
}
function doSecondThing() {
	$('notification_message').update($('second_thing').innerHTML);
	$('notification').show();
}
</script>

<div id='first_thing' style='display:none;'>
There would be cool interactive stuff going on here, if this was for real.
<br/><br/>
<a  href='javascript:void(0)' onclick='doSecondThing();'>Continue</a> &nbsp;
<a  href='javascript:void(0)' onclick='doCancel();'>Cancel</a>
</div>

<div id='second_thing' style='display:none;'>
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam lobortis dapibus varius. Morbi eget lacinia odio. Praesent quis diam vel erat lacinia gravida euismod et felis. Praesent tortor dui, fermentum et venenatis vitae, vestibulum quis massa. Morbi dapibus nisl vel diam pharetra quis rutrum eros tristique. Pellentesque varius consectetur neque vitae iaculis. Sed pulvinar mi at dolor accumsan at ultrices nibh posuere. Nullam at fermentum lectus. Maecenas purus elit, volutpat in sollicitudin ut, bibendum eu ligula. Sed eleifend congue pellentesque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras varius ligula nec lacus dictum interdum tristique est egestas. Nam semper dignissim tristique. Maecenas eget purus sit amet purus porttitor facilisis. Proin vitae ipsum a enim eleifend sodales at non nisl. Vivamus fermentum, purus in feugiat dictum, elit dolor blandit nulla, quis dapibus tortor urna non diam.
</p>
<p>
Proin et urna leo. Mauris at elit et quam hendrerit suscipit faucibus ac nulla. Praesent vitae tellus massa, rhoncus rhoncus augue. Fusce tortor felis, condimentum fermentum vestibulum a, pellentesque eget odio. In hac habitasse platea dictumst. Maecenas aliquet lorem a dui ultrices sit amet commodo risus vehicula. Praesent vestibulum, risus eget bibendum eleifend, velit nisi posuere odio, bibendum egestas velit risus in lectus. Aenean quis enim dui. Duis dictum massa vel velit aliquet ut vehicula metus porta. Praesent et ligula enim, in pharetra nibh. Nulla imperdiet ultricies sapien a rutrum. Duis justo massa, gravida id congue ac, ornare ut libero. Vivamus id blandit turpis.
</p>
<p>
Quisque volutpat neque in velit tempor pharetra. Praesent ante turpis, consequat et tempus nec, faucibus sed arcu. Morbi tempor purus eget arcu sagittis quis placerat velit scelerisque. Nam sit amet lectus in risus posuere rhoncus et et orci. Quisque dignissim blandit varius. Vivamus arcu lacus, luctus et condimentum in, ultricies ut augue. Mauris feugiat dui eu neque varius porttitor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam ut turpis et enim euismod accumsan. Fusce diam arcu, volutpat vitae varius eget, vehicula a magna. Nullam eget nulla quam, posuere gravida magna. Mauris et tincidunt tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus
</p>
And even more cool stuff here, like <a  href='javascript:void(0)' onclick='$("Comment").value += " booga!";'>Add "booga!" to comment</a>
<br/><br/>
<a  href='javascript:void(0)' onclick='doSubmit();'>Submit</a> &nbsp;
<a  href='javascript:void(0)' onclick='doCancel();'>Cancel</a>
</div>