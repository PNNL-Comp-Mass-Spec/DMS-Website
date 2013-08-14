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
<tbody>
	<tr>
		<td>
		<input class="button" type="button" id="btnCollapseAll1" title="Collapse all expanded locations" value="Collapse"</a" />
		<input class="button" type="button" id="btnClearSelections1" title="Clear selections" value="Clear" />	
	<input class="button" type="button" id="find_location_btn1" title="Find and display location or container" value="Find..." />
		</td>
		<td>
		<input class="button" type="button" id="btnCollapseAll2" title="Collapse all expanded locations" value="Collapse"</a" />
		<input class="button" type="button" id="find_location_btn2" title="Find and display location or container" value="Find..." />
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
	},
	findLocationOrContainer: function(){
		var val = prompt("Enter location path or container ID");
		var identifier = this.Model.getNormalizedIdentifier(val);
		if(identifier.Type == "Container") {
			this.Model.findContainerNode(identifier.NormalizedID);
		} else 
		if(identifier.Type == "Location") {
			this.Model.findLocationNode(identifier.NormalizedID);
		}
	},
	updateTreePostMove: function(tree, locationKey, callback) {
		// find location node by key (if it exists)		
		var locNode = tree.getNodeByKey(locationKey);
		if(!locNode) {
			if(callback) callback();
			return;
		}
		// get parent location node
		var parLocNode = locNode.getParent();
		
		// reload parent location node
		parLocNode.reloadChildren(function() {
			// original location will have new node - find it via key
			var node = tree.getNodeByKey(locationKey);
			if(node) {
				node.reloadChildren(function() {
					node.expand();
					if(callback) callback();
				});
			} else {
				if(callback) callback();				
			}
		});
				
	},
	updatePostMove: function(containerNode, locationNode) {
		var that = this;
		var sourceLocNode = containerNode.getParent();
		var sourceLocKey = sourceLocNode.data.key;
		var destLocKey = locationNode.data.key;
		var treeA = Freezer.Display.Source.Model.getTree();
		var treeB = Freezer.Display.Destination.Model.getTree();
		that.updateTreePostMove(treeA, sourceLocKey, function() {
			that.updateTreePostMove(treeA, destLocKey, function() {
				that.updateTreePostMove(treeB, sourceLocKey, function() {
					that.updateTreePostMove(treeB, destLocKey)
				});
			});
		});
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
	$("#find_location_btn1").click(function(){
		return Freezer.Display.Source.findLocationOrContainer();
	});
	$("#find_location_btn2").click(function(){
		return Freezer.Display.Destination.findLocationOrContainer();
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
		return 'over';
      },
      onDragOver: function(node, sourceNode, hitMode) {
		return node.data.info.Type == 'Col'
      },
      onDrop: function(node, sourceNode, hitMode, ui, draggable) {
        Freezer.Display.Destination.Model.moveContainer(sourceNode, node, function() {
        	Freezer.Display.updatePostMove(sourceNode, node);
        });
      },
      onDragLeave: function(node, sourceNode) {
        // Always called if onDragEnter was called.
      },
	  onDragStart: function(node) {
	    if(node.data.isFolder)
	      return false;
	    return true;
	  },
	  onDragStop: function(node) {
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
	      autoExpandMS: 1000,
	      preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
	      onDragEnter: function(node, sourceNode) {
			return 'over';
	      },
	      onDragOver: function(node, sourceNode, hitMode) {
			return node.data.info.Type == 'Col'
	      },
	      onDrop: function(node, sourceNode, hitMode, ui, draggable) {
	        Freezer.Display.Source.Model.moveContainer(sourceNode, node, function() {
	        	Freezer.Display.updatePostMove(sourceNode, node);
	        });
	      },
	      onDragLeave: function(node, sourceNode) {
	      },
		  onDragStart: function(node) {
		    if(node.data.isFolder)
		      return false;
		    return true;
		  },
		  onDragStop: function(node) {
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

