<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>


<style type="text/css">
.barGraph {
	background: url(images/horizontal_grid_line_50_pixel.png) bottom left;
/*	border-bottom: 3px solid #333; */
	font: 9px Helvetica, Geneva, sans-serif;
	height: 490px;
	margin: 1em 0;
	padding: 0;
	position: relative;
	}
	
.barGraph li {
	background: #666 url(images/bar_50_percent_highlight.png) repeat-y top right;
	border: 1px solid #555;
	border-bottom: none;
/*	bottom: 0; */
	color: #FFF;
	margin: 0; 
	padding: 0 0 0 0;
	position: absolute;
	list-style: none;
	text-align: center;
	width: 120px;
	}
	
.barGraph li.p1{ background-color:blue }
.barGraph li.p2{ background-color:#888888 }
.barGraph li.p3{ background-color:#AAAAAA }
</style>

<style type="text/css">

.runTable {
	margin-top: 10px;
	border-width: 1px 1px 1px 1px;
	border-spacing: 1px 1px;
	border-style: solid solid solid solid;
	border-color: gray gray gray gray;
	border-collapse: separate;
	background-color: #bfbfbf;
}
.runTable th {
	border-width: 1px 1px 1px 1px;
	padding: 4px 4px 4px 4px;
	border-style: hidden hidden hidden hidden;
	border-color: gray gray gray gray;
	background-color: #E1E7EA;
}
.runTable td {
	border-width: 1px 1px 1px 1px;
	padding: 4px 4px 4px 4px;
	border-style: hidden hidden hidden hidden;
	background-color: #EFEFEF;
	width: 130px;
}
.weekDays {
	font-weight: bold;
}

</style>

</head>
<body>
<div style="height:500px;">
<? $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>
			
<?= $this->calendar->generate($year, $month, $calendarData); ?>

</div>
</body>
</html>
