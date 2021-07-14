<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

</head>
<body id='freezer_page'>
<div id="body_container" >

<?php echo view('nav_bar') ?>


<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2>
</div>

<div>
    <?= $picker ?>
</div>

<div><?= $tbs ?></div>

</div>
</body>
</html>
