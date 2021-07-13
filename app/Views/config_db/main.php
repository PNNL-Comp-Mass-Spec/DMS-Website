<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/base2js') ?>

<?php $this->load->view('resource_links/cfg') ?>

</head>
<body>
<div id="body_container" >

<h2 class='page_title'><?= $heading; ?></h2>

<?= make_config_nav_links('') ?>

<div>
<?= $contents ?>
</div>

<div id='end_of_content' style="height:1em;" ></div>

</div>
</body>
</html>
