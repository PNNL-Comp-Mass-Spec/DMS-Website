<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Grid Demo Pages</title>
<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url().'css/grid_data.css' ?>" />
</head>

<body>

<ul>
<li><a  class='button' href='<?= site_url() ?>grid/factors' >factors</a>
<li><a  class='button' href='<?= site_url() ?>grid/instrument_allocation' >instrument_allocation</a>
<li><a  class='button' href='<?= site_url() ?>grid/instrument_usage' >instrument_usage</a>
<li><a  class='button' href='<?= site_url() ?>grid/requested_run' >requested_run</a>
<li><a  class='button' href='<?= site_url() ?>grid/user' >user</a>
</ul>

</body>
</html>
