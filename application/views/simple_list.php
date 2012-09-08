<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

</head>
<body>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2>
</div>

<div id='data_display_container'>
<?php // data display section
if (is_string($result)) {
	echo $result;
} else 
if(is_array($result)){
	echo "<ul>\n";
	foreach($result as $item) {
		echo "<li>$item</<li>\n";
	}
	echo "</ul>\n";
} else {
	echo "No results available";
}
?>
</div>

</body>
</html>
