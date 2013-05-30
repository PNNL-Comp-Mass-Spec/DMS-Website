<!DOCTYPE html>
<html>
<head>
<title>DMS Freezers</title>

<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/base2js') ?>
<? $this->load->view('resource_links/freezer_tree') ?>

</head>

<body class="freezer_tree" >

<div id="body_container" >
<? $this->load->view('nav_bar') ?>


<div class='ctlPanel'>
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnExpandAll" title="Open all menus">Expand&nbsp;all</a></span>
<span class="side_menu_ctl_pnl"><a href="javascript:void(0)" id="btnCollapseAll" title="Close all menus">Collapse&nbsp;all</a></span>
</div>
<a href='javascript:testme(0)' >Test</a>
<div id=metest></div>

<div id='tree'>
<ul>

</ul>
</div>

<script type='text/javascript'>

function testme() {
}

$(document).ready(function() {
/*	*/
    $.ui.dynatree.nodedatadefaults["icon"] = false; // Turn off icons by default

	// set up tree menu
	$("#tree").dynatree({
		minExpandLevel: 1,
		initAjax: {
			url: '<?= site_url() ?>freezer/get_freezers', 
			data: {}
		},
		onLazyRead: function(node) {
			node.appendAjax({
				url: '<?= site_url() ?>freezer/get_locations',
				type: "POST",			
				data: {
					"Type": node.data.Type,
					"Freezer":node.data.Freezer,
					"Shelf":node.data.Shelf,
					"Rack":node.data.Rack,
					"Row":node.data.Row,
					"Col":node.data.Col
				}
			});
		},
		onClick: function(node, event) {
			var et = node.getEventTargetType(event);
		}		
	});

	$("#tree a").hover(function() {
		var node = $.ui.dynatree.getNode(this);
		logMsg("Hover in %s", node);
	}, function() {
		var node = $.ui.dynatree.getNode(this);
		logMsg("Hover out %s", node);
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

</div>
</body>
</html>

