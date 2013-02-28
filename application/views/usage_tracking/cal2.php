<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>

</head>
<body id='usage_tracking_calendar'>
<div id="body_container" >
<? $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<div>Select instrument:<?= $instrument_list ?></div>

<?= $rollup ?>
			
<?= $this->calendar->generate($year, $month, $calendarData); ?>

<div class='repBox' >
<table>
<tr style='vertical-align: top;'>
<td><span class='repLink' ><a href='<?= $tracking_link ?>'>Tracking Report</a></span></td>
<td>
	<div><span class='repLink' ><a href='<?= $log_link ?>'>Operations Logs</a></span></div>
	<div><span class='repLink' ><a href='<?= site_url() . "run_op_logs/grid"?>'>Operations Logs (grid)</a></span></div>
</td>
<td><span class='repLink' ><a href='<?= $report_link ?>'>Usage Report</a></span></td>
<td><span class='repLink' ><a href='<?= $ers_link ?>'>ERS Report</a></span></td>
</tr>
</table>
</div>

</div>

<? $this->load->view('resource_links/base2js') ?>

</body>
</html>
