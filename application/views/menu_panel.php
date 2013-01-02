<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>DMS Main Menu</title>

<? $this->load->view('resource_links/base2') ?>
<? $this->load->view('resource_links/menu_panel') ?>

</head>

<body class="menu_panel" >
	
<div  class="searchpnl global_search_panel" >
<?= make_search_form_vertical() ?>
</div>

<div class='ctlPanel'>
    <a target="display_side" href="<?= site_url()?>gen/welcome" title="Go to the home page">Home</a> -
    <a href="javascript:void(0)" id="btnExpandAll" title="Open all menus">Expand all</a> -
    <a href="javascript:void(0)" id="btnCollapseAll" title="Close all menus">Collapse all</a>
</div>

<div id='tree'>
<ul>
<? side_menu_layout($mnu, "", "") ?>
</ul>
</div>

<script type='text/javascript'>

$(document).ready(function() {

	// set up tree menu
    $("#tree").dynatree({
      minExpandLevel: 1,
      onActivate: function(node) {
        if( node.data.href ){
          window.open(node.data.href, node.data.target);
        }
      }
    });

    $("#btnCollapseAll").click(function(){
      $("#tree").dynatree("getRoot").visit(function(node){
        node.expand(false);
      });
      return false;
    });
    $("#btnExpandAll").click(function(){
      $("#tree").dynatree("getRoot").visit(function(node){
        node.expand(true);
      });
      return false;
    });

	// set event handlers for global search panel
	gamma.setSearchEventHandlers($('.global_search_panel'));

});

</script>

</body>
</html>

