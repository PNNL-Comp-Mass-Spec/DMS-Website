<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/base2js') ?>

<? $this->load->view('resource_links/page_menu') ?>

<script src="<?= base_url().'javascript/page_menu.js' ?>"></script>

</head>
<body>
<div id="body_container" >

<? $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<? $this->load->view("page_menu/$sub_view_name") ?>

</div>
</body>
</html>
