<!DOCTYPE html>
<html>
<head>
<title>DMS Main Menu</title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>
<?php echo view('resource_links/menu_panel') ?>

</head>

<body class="menu_panel" >

<div  class="searchpnl global_search_panel" >
<?= make_search_form_vertical() ?>
</div>

<div class='ctlPanel'>
<span class="side_menu_ctl_pnl"><a target="display_side" href="<?= site_url('gen/welcome')?>" title="Go to the home page">Home</a></span>
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnExpandAll" title="Open all menus">Expand&nbsp;all</a></span>
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnCollapseAll" title="Close all menus">Collapse&nbsp;all</a></span>
</div>

<div id='tree'>
<ul>

</ul>
</div>

<div class='ctlPanel'>
<br />
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnToggleAutoCollapse" title="Toggle">Toggle&nbsp;Auto&nbsp;Collapse</a></span>
</div>

<script type='text/javascript'>

$(document).ready(function() {

    // set up tree menu
    $("#tree").fancytree({
        autoActivate: false,
        autoCollapse: true,
        autoScroll: true,
        clickFolderMode: 3, // expand with single click - 'folder' node attribute must be set to true
        //clickFolderMode: 4, // expand when name is double clicked
        //focusOnSelect: true,
        selectMode: 1,
        minExpandLevel: 1,
        toggleEffect: { effect: "slideToggle", duration: 100 },
        source: {
            url: '<?= site_url("gen/side_menu_objects") ?>', data: {}
        },
        icon: false, // Turn off icons
        activate: function(event, data){
            var node = data.node,
                orgEvent = data.originalEvent || {};

            // Open href (force new window if Ctrl is pressed)
            if(node.data.href){
                window.open(node.data.href, (orgEvent.ctrlKey || orgEvent.metaKey) ? "_blank" : "display_side");
            }
        },
        click: function(event, data) {
            var node = data.node,
                orgEvent = data.originalEvent;

            // Open href (force new window if Ctrl is pressed)
            if(node.isActive() && node.data.href) {
                window.open(node.data.href, (orgEvent.ctrlKey || orgEvent.metaKey) ? "_blank" : "display_side");
            }
        }
    });

    $("#btnCollapseAll").click(function(){
      $.ui.fancytree.getTree("#tree").expandAll(false);
      //$.ui.fancytree.getTree("#tree").getRootNode().visit(function(node){
      //  node.setExpanded(false);
      //});
      return false;
    });

    $("#btnExpandAll").click(function(){
      if ($.ui.fancytree.getTree("#tree").getOption("autoCollapse") === true)
          $.ui.fancytree.getTree("#tree").setOption("autoCollapse", false);

      $.ui.fancytree.getTree("#tree").expandAll(true);
      //$.ui.fancytree.getTree("#tree").getRootNode().visit(function(node){
      //  node.setExpanded(true);
      //});
      return false;
    });

    $("#btnToggleAutoCollapse").click(function(){
      if ($.ui.fancytree.getTree("#tree").getOption("autoCollapse") === false)
          $.ui.fancytree.getTree("#tree").setOption("autoCollapse", true);
      else
          $.ui.fancytree.getTree("#tree").setOption("autoCollapse", false);

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

