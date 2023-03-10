<!DOCTYPE html>
<html>
<head>
<title><?= $title ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

<?php echo view('resource_links/page_menu') ?>

</head>
<body>
<div id="body_container" >

<?php echo view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<?php echo view("$page_menu_root/$sub_view_name") ?>

</div>
</body>
</html>
