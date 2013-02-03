<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Grid Demo Pages</title>
<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/slickgrid2css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url().'css/grid_data.css' ?>" />

<style>

.my_link {
	width:15em;
}
.item {
	margin-bottom:10px;
}
#main {
	margin-top: 30px;
	margin-right: 20px;
}
h3 {
	
}
	
</style>
</head>

<body>

<div id='main' >
<h3>Editing Grid Demonstration Pages</h3>	
<div class='item' ><a  class='button my_link' href='<?= site_url() ?>grid/factors' >factors</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url() ?>grid/instrument_allocation' >instrument_allocation</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url() ?>grid/instrument_usage' >instrument_usage</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url() ?>grid/requested_run' >requested_run</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url() ?>grid/user' >user</a></div>
</div>

</body>
</html>
