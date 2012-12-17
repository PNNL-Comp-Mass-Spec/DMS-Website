<style type="text/css">

.notification {
	position: absolute;
	top: 180px;
	left: 100px;
	height: auto;
	background: #FAFAF0;
	border: solid 3px gray;
	overflow: hidden;
	zIndex: 900;
}
.notification_header {
	border-bottom:2px solid gray;
}
.notification_header div {
	padding:5px;
}
.notification_message {
	padding:10px 10px 10px 5px;
}
</style>

<script type='text/javascript'>
 $(document).ready(function () { 
		new Draggable("notification", {handle: 'notification_header' })
	 }
 );
</script>

<div id='notification' style='display:none' class='notification' >
<div class='notification_header'>
<div><a href="javascript:void(0)" onclick="$('#notification').hide(); return false;">Close</a></div>
</div>
<div id='notification_message' class='notification_message'>
</div>

</div>
