<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<style type="text/css">
table.GridCell {
	border:2px solid #6495ED;
	background-color:#E1E7EA;
}
table.GridCell tr {
	background-color:#E1E7EA;
}
table.GridCell th {
	background-color:#E1E7EA;
}
table.GridCell td {
	background-color:#EFEFEF;
}
#comp_pos_link_container {
	padding: 5px 5px 5px 5px;
}
.block_spacer {
	width:300px;
}
tr.block_content {
	display:none;
}
tr.block_header {
	min-width:50em;
	text-align:center;
}
</style>

</head>
<body>
<div style="height:500px;">

<? $this->load->view('nav_bar') ?>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2>
</div>

<?php
		// show contents of locations in tables
		$tmpl = array (
			'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="GridCell">', 
			'heading_cell_start' => '<th class="block_header" colspan="4">' 
		);
		$this->table->set_template($tmpl); 
		//
		foreach($storage as $freezer => $f) {
			foreach($f as $shelf => $s) {
				foreach($s as $rack => $rk)	{
					$this->table->set_heading("Freezer:$freezer &nbsp; Shelf:$shelf &nbsp; Rack:$rack");
					//
					foreach($rk as $row => $rw) {
						$tr = array();
						foreach($rw as $col => $location) {
							$x = render_location_contents($location, $contents);
							if($x) $tr[] = $x;
						}
						$this->table->add_row($tr);
					}
					//
					echo $this->table->generate();
					$this->table->clear();
					echo "<br>";
				}	
			}
		}

?>

</div>
</body>
</html>