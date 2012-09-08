<style type="text/css">
#fly_section {
	position:absolute;
	top:90px;
	left:410px;
	width:45em;
	margin-left:15px;
}
.fly_box {
	position:absolute;
	top:0;
	left:0;
	width:45em;
	display:none;
}
.fly_aspect {
 	width:35em;
 }
.fly_aspect_active_area {
	width:34em;
}
.fly_aspect_menu_comment {
	width:34em;
}

#fly_master_list ul {
  padding: 0;
  margin: 4px;
  list-style: none;
}
#fly_master_list li {
	padding-top:.4em;
	padding-bottom:.4em;
}
#fly_master_list li a {
	border-style:solid;
	border-width:2px;
	border-color:gray;
	padding:6px;
	margin:5px;
	background-color:#F0F8FF;
	height:2em;
	width:20em;
	display:block;
	color: black;
	font-weight:bold;
}
#fly_master_list a:hover {
	color:yellow;
	background:#6495ED;
}
#fly_master_list a:link, #fly_master_list a:visited {

	text-decoration: none;
	margin: 0;
}
</style>

<div id='fly_master_list' >
<?= make_fly_master_list($qs_section_defs); ?>
</div> <!-- end 'fly_master_list' -->

<div id='fly_section'>
<?= make_fly_section_layout($qs_section_defs); ?>
<div id='splash_message' class='fly_box'>
<? $this->load->view("page_menu/$splash_view_name") ?>
</div>
</div> <!-- end 'fly_section' -->

