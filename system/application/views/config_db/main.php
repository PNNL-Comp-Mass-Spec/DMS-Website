<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<? $this->load->view('resource_links/cfg') ?>

</head>
<body>
<div style="height:500px;">

<h2 class='page_title'><?= $heading; ?></h2>

<?= make_config_nav_links('') ?>

<div>
<?= $contents ?>
</div>

<div id='end_of_content' style="height:1em;" ></div>

</div>
</body>
</html>
