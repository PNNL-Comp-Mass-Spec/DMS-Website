<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>DMS Main Menu</title>

<? $this->load->view('resource_links/base2') ?>

<? $this->load->view('resource_links/menu_panel') ?>

</head>

<body class="menu_panel" onload="initExpandableLists()">
<div  class="searchpnl global_search_panel" >
<?= make_search_form_vertical() ?>
</div>
<div>
    <a target="display_side" href="<?= site_url()?>gen/welcome"><b>HOME</b></a>
</div>
<hr width="65%" align="left">

<ul class='mnupnl expandable'>
<? side_menu_layout($mnu, "", "") ?>
</ul> <!-- end 'mnupnl expandable' -->

</body>
</html>

