<!DOCTYPE html>
<html>
<head>
<title>DMS Main Menu</title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/base2js') ?>
<?php $this->load->view('resource_links/menu_panel') ?>

</head>

<body class="menu_panel" >
    
<div  class="searchpnl global_search_panel" >
<?= make_search_form_vertical() ?>
</div>

<div class='ctlPanel'>
<span class="side_menu_ctl_pnl"><a target="display_side" href="<?= site_url()?>gen/welcome" title="Go to the home page">Home</a></span>
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnExpandAll" title="Open all menus">Expand&nbsp;all</a></span>
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnCollapseAll" title="Close all menus">Collapse&nbsp;all</a></span>
</div>

<div id='tree'>
<ul>

</ul>
</div>

<script type='text/javascript'>

$(document).ready(function() {
    
    $.ui.dynatree.nodedatadefaults["icon"] = false; // Turn off icons by default

    // set up tree menu
    $("#tree").dynatree({
      minExpandLevel: 1,
      initAjax: {
        url: '<?= site_url() ?>gen/side_menu_objects', data: {}
      },      
      onClick: function(node, event) {
        if( node.data.href ){
          window.open(node.data.href, 'display_side');
          return false;
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

<!-- Add two blank lines to prevent the final item in the tree from appearing at the bottom of the frame -->
<br>
<br>

</body>
</html>

