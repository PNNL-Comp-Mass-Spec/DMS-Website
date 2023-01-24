<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Grid Demo Pages</title>
<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/slickgrid2css') ?>

<style>

.my_link {
    width:25em;
    height:1.5em;
}
.item {
    margin-bottom:10px;
}
#main {
    margin-top: 30px;
    margin-right: 20px;
    padding-left:30px;
}
h3 {

}

</style>
</head>

<body>

<div id='main' >
<h3>Editing Grid Demonstration Pages</h3>
<div class='item' ><a  class='button my_link' href='<?= site_url("factors/grid") ?>' >Factors</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url("instrument_usage_report/grid") ?>' >Instrument Usage EMSL Updates</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url("run_op_logs/grid") ?>' >Instrument Operation Log Review</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url("requested_run_batch_blocking/grid") ?>' >Requested Runs</a></div>
<div class='item' ><a  class='button my_link' href='<?= site_url("grid/user") ?>' >User</a></div>
<!--<div class='item' ><a  class='button my_link' href='<?= site_url("grid/instrument_allocation") ?>' >Instrument Allocation</a></div>__.
</div>

</body>
</html>
