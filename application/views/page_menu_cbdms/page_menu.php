<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/base2js') ?>

<?php $this->load->view('resource_links/page_menu') ?>

<script src="<?= base_url().'javascript/page_menu.js?version=100' ?>"></script>

</head>
<body>
<div id="body_container" >

<?php $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<?php $this->load->view("$page_menu_root/$sub_view_name") ?>

</div>
</body>
</html>
