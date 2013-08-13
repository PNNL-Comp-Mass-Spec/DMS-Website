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

<div class='local_title'>Freezer Management</div>

<div id="messages"></div>

<table>
<thead>
	<tr>
		<th>
		<p style="text-align: left;">Source</p>
		</th>
		<th>
		<p style="text-align: left;">Destination</p>
		</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		<input class="button" type="button" id="btnCollapseAll1" title="Collapse all expanded locations" value="Collapse&nbsp;all"</a" />
		<input class="button" type="button" id="btnClearSelections1" title="Clear selections" value="Clear Selections" />	
		</td>
		<td>
		<input class="button" type="button" id="btnCollapseAll2" title="Collapse all expanded locations" value="Collapse&nbsp;all"</a" />
		</td>
	</tr>
	<tr valign="top">
		<td>
		<div style="width: 30em;"><div id="tree1" > </div></div>
		</td>
		<td>
		<div style="width: 30em;"><div id="tree2"></div></div>
		</td>
	</tr>
</tbody>
</table>

<script type='text/javascript'>
gamma.pageContext.site_url = '<?= site_url() ?>';

Freezer.Display = {
	Model: null,
	create: function(treeElementName) {
		var me = $.extend({}, Freezer.Display);
		me.Model = Freezer.Model.create(treeElementName);
		return me;
	},
	collapseTree: function() {
		this.Model.getTree().reload();
		return false;		
	},
	clearSelection: function() {
		this.Model.getTree().visit(function(node){
			node.select(false);
		});
		return false;		
	}
}

$(document).ready(function() {

	Freezer.Display.Source = Freezer.Display.create("tree1");
	Freezer.Display.Destination = Freezer.Display.create("tree2");
	
	$("#btnCollapseAll1").click(function(){
		return Freezer.Display.Source.collapseTree();
	});
	$("#btnCollapseAll2").click(function(){
		return Freezer.Display.Destination.collapseTree();
	});
	$("#btnClearSelections1").click(function(){
		return Freezer.Display.Source.clearSelection();
	});
	
	
	/*----- set up destination tree -----*/
  Freezer.Display.Destination.Model.myTreeElement.dynatree({
	minExpandLevel: 1,
	selectMode: 2,
	checkbox: false,
	initAjax: {
		url: '<?= site_url() ?>freezer/get_freezers', 
		data: {}
	},
	onLazyRead: function(node){
		if(node.data.info.Type == 'Container') {
			node.setLazyNodeStatus(DTNodeStatus_Ok);
			return;
		}
		if(node.data.info.Status == 'Active') {
			Freezer.Display.Destination.Model.getContainerNodes(node);
		} else {
			if(node.data.info.Type == 'Col') {
				node.setLazyNodeStatus(DTNodeStatus_Ok);
			} else {
				Freezer.Display.Destination.Model.getLocationNodes(node);
			}
		}
    },
    onActivate: function(node) {
      $("#echoActive2").text(node.data.title + "(" + node.data.key + ")");
    },
    onDeactivate: function(node) {
      $("#echoActive2").text("-");
    },
    dnd: {
      autoExpandMS: 1000,
      preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
      onDragEnter: function(node, sourceNode) {
        // Return false to disallow dropping this node.
		return node.data.info.Type == 'Col' && node.data.info.Available > 0
      },
      onDragOver: function(node, sourceNode, hitMode) {
        // Return false to disallow dropping this node.
		return node.data.info.Type == 'Col'
      },
      onDrop: function(node, sourceNode, hitMode, ui, draggable) {
        // This function MUST be defined to enable dropping of items on the tree.
        //sourceNode may be null, if it is a non-Dynatree droppable.
		var container = sourceNode.data.info.Name;
		var location = node.data.info.Tag;
        logMsg("Move %o -> %o", container, location);
      },
      onDragLeave: function(node, sourceNode) {
        // Always called if onDragEnter was called.
      }
    }
  });

	/*----- set up source tree -----*/
	Freezer.Display.Source.Model.myTreeElement.dynatree({
		minExpandLevel: 1,
		selectMode: 2,
		checkbox: true,
		initAjax: {
			url: '<?= site_url() ?>freezer/get_freezers', 
			data: {}
		},
		onPostInit: function(isReloading, isError) {
		},
		onLazyRead: function(node) {
			if(node.data.info.Type == 'Container') {
				node.setLazyNodeStatus(DTNodeStatus_Ok);
				return;
			}
			if(node.data.info.Status == 'Active') {
				Freezer.Display.Source.Model.getContainerNodes(node);
			} else {
				if(node.data.info.Type == 'Col') {
					node.setLazyNodeStatus(DTNodeStatus_Ok);
				} else {
					Freezer.Display.Source.Model.getLocationNodes(node);
				}
			}
		},
		onClick: function(node, event) {
			var et = node.getEventTargetType(event);
			switch(et) {
				case 'expander':
					break;
				case 'title':
					//node.toggleSelect();
					console.log("click->" + node.data.info.Tag);
					return false;
					break;
			}
		},
		onSelect: function(select, node) {
			// Display list of selected nodes
			var selectedNodes = node.tree.getSelectedNodes();
			var selectionPattern = Freezer.Display.getSelectionPattern(selectedNodes);
			if(selectionPattern) {
				var selKeys = $.map(selectedNodes, function(node){
					return node.data.info.Type + "[" + node.data.key + "]";
				});
				$("#messages").text(selectionPattern + selKeys.join(", "));
			} else {
				$("#messages").text("");				
			}
		},
		dnd: {
		  onDragStart: function(node) {
		    /** This function MUST be defined to enable dragging for the tree.
		     *  Return false to cancel dragging of node.
		     */
		    logMsg("tree.onDragStart(%o)", node);
		    if(node.data.isFolder)
		      return false;
		    return true;
		  },
		  onDragStop: function(node) {
		    logMsg("tree.onDragStop(%o)", node);
		  }
		}		
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

