<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

</head>
<body>
<div style="height:500px;">
<? $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<?= make_stats_display($results) ?>

<div style="width:55em;"></div>

</div>
</body>
</html>
