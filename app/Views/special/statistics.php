<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

</head>
<body>
<div id="body_container" >
<?php echo view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<?= make_stats_display($results) ?>

<div style="width:55em;"></div>

</div>
</body>
</html>
