<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="<?= base_url() ?>qunit/qunit-1.11.0.css">
	<?php $this->load->view('resource_links/base2css') ?>
</head>
<body>
	<div id="qunit"></div>
	<div id="qunit-fixture"></div>

<?php $this->load->view('resource_links/base2js') ?>

	<script src="<?= base_url() ?>qunit/qunit-1.11.0.js"></script>
	<script src="<?= base_url() ?>javascript/unit_tests/<?= $testFile ?>.js"></script>
</body>
</html>
