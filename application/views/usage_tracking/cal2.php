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

</head>
<body id='usage_tracking_calendar'>
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
