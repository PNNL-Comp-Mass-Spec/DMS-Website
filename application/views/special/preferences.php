<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<script type="text/javascript">
	function setPreference(url) {
	 	var reply = prompt("Please enter a new value", "");
	 	if (reply == null || reply == "") {
			return;
		}
		location = url + reply;
	}	
</script>

</head>
<body>
<div style="height:500px;">
<? $this->load->view('nav_bar') ?>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2>
</div>

<div id='posting_message_container' style='width:70em;'>
<? 
if($result != "") {
	echo "<div class='EPag_error' >";
	echo $result;
	echo "</div>";
}
?>
</div>

<table style='border-spacing: 15px 8px;'>
<tr>
	<th style='text-align:left;'>Name</th>
	<th style='text-align:left;'>Value</th>
	<th></th>
	<th style='text-align:left;'>Description</th>
	<th style='text-align:left;'>Allowed Values</th>
</tr>
<?
foreach($settings as $setting => $def) {
	$str = "";
	$str .= "<tr>";
	$str .= "<td><span style='font-weight:bold;'>".$def['label'] ."</span></td>";
	$str .= "<td>".$def['value']."</td>";
	$url = site_url()."preferences/set/$setting/";
	$str .= "<td><a href='javascript:setPreference(\"$url\")'>Change</a></td>";
	$str .= "<td>".$def['description']."</td>";
	$str .= "<td>".implode($def['validation'], $def['allowed_values'])."</td>";
	$str .= "</tr>";
	echo $str;
}
?>
</table>


<a href='<?= base_url()?>preferences/session'>Session</a>
</div>
</body>
</html>