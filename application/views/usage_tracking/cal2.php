<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<script type="text/javascript">
function goToPage(id) {
	var node = document.getElementById(id);
	window.location.href = node.options[node.selectedIndex].value;
}
</script>

<style type="text/css">
.eventList {
	margin: 1em 0;
	padding: 0;
}
	
.eventList li {
	border: 1px solid #555;
	border-bottom: none;
	color: #FFF;
	margin: 0; 
	padding: 0 0 0 0;
	list-style: none;
}
.repLink {
	padding: 8px;
}
.repBox {
	padding: 4px;
}
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
	width: 160px;
	height: 40px;
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

<div>Select instrument:<?= $instrument_list ?></div>

<?= $rollup ?>
			
<?= $this->calendar->generate($year, $month, $calendarData); ?>

<div class='repBox' >
<span class='repLink' ><a href='<?= $tracking_link ?>'>Tracking Report</a></span>
<span class='repLink' ><a href='<?= $log_link ?>'>Operations Logs</a></span>
<span class='repLink' ><a href='<?= $report_link ?>'>Usage Report</a></span>
<span class='repLink' ><a href='<?= $ers_link ?>'>ERS Report</a></span>
</div>

</div>
</body>
</html>
