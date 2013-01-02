<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>DMS Main Menu</title>

<? $this->load->view('resource_links/base2') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url().'dynatree/skin/ui.dynatree.css' ?>" />
<script src="<?= base_url().'dynatree/jquery.cookie.js' ?>"></script>
<script src="<?= base_url().'dynatree/jquery.dynatree.js' ?>"></script>

</head>

<body class="menu_panel" >
<div  class="searchpnl global_search_panel" >
<?= make_search_form_vertical() ?>
</div>
<div>
    <a target="display_side" href="<?= site_url()?>gen/welcome"><b>HOME</b></a>
</div>

<div id='tree'>
<ul>
<? side_menu_layout($mnu, "", "") ?>
</ul>
</div>

<script type='text/javascript'>
	$(document).ready(function() {
	    $("#tree").dynatree({
	      minExpandLevel: 1,
	      onActivate: function(node) {
	        if( node.data.href ){
	          window.open(node.data.href, node.data.target);
	        }
	      }
	    });
	});
</script>

</body>
</html>

