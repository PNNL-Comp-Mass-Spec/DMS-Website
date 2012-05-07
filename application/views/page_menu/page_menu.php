<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<? $this->load->view('resource_links/page_menu') ?>

<script type="text/javascript" src="<?= base_url().'javascript/page_menu.js' ?>"></script>

</head>
<body>
<div style="height:500px;">

<? $this->load->view('nav_bar') ?>

<h2 class='page_title'><?= $heading; ?></h2>

<? $this->load->view("page_menu/$sub_view_name") ?>

</div>
</body>
</html>
