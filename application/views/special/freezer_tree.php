<!DOCTYPE html>
<html>
<head>
<title>DMS Freezers</title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/base2js') ?>
<?php $this->load->view('resource_links/freezer_tree') ?>

</head>

<body class="freezer_tree" >

<div id="body_container" >
<?php $this->load->view('nav_bar') ?>

<div class='local_title'>Freezer Management</div>

<div id="messages"></div>

<table>
<tbody>
	<tr>
		<td>
		<input class="button" type="button" id="btnCollapseAll1" title="Collapse all expanded locations" value="Collapse"</a" />
		<input class="button" type="button" id="find_location_btn1" title="Find and display location or container" value="Find..." />
		<input class="button" type="button" id="find_available_location_btn1" title="Find available location" value="Available..." />
		<input class="button" type="button" id="show_newest_container_btn1" title="Show newest container" value="Newest" />
		</td>
		<td>
		<input class="button" type="button" id="btnCollapseAll2" title="Collapse all expanded locations" value="Collapse"</a" />
		<input class="button" type="button" id="find_location_btn2" title="Find and display location or container" value="Find..." />
		<input class="button" type="button" id="find_available_location_btn2" title="Find available location" value="Available..." />
		<input class="button" type="button" id="show_newest_container_btn2" title="Show newest container" value="Newest" />
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
	<tr>
		<td>
		<input class="button" type="button" id="btnClearSelections1" title="Clear selections" value="Clear Selection" />	
		<input class="button" type="button"  id="set_active_btn1" title="Set selected locations to active" value="Set Active" /> 
		<input class="button" type="button"  id="set_inactive_btn1" title="Set selected locations to inactive" value="Set Inactive" /> 
		</td>
		<td>
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
	updateLocations: function(){ 
		var changeList = this.Model.getStatusChangeList();
		if(changeList.length == 0) {
			alert("No locations are currently selected");			
		} else {
			this.Model.updateDatabase(changeList);
		}
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
	findAvailableLocation: function() {
		var val = prompt("Enter partial location path");
		this.Model.findAvailableLocationNode(val);
	},
	showNewestContainer: function() {
		var context = this;
		this.Model.getTree().reload(function() {
			context.Model.findNewestContainerNode(function(objArray) {
				if(objArray.length) {
					context.Model.exposeLocation(objArray[0].info.Tag);
				}
			});
		});
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
		var treeLeft = Freezer.Display.Left.Model.getTree();
		var treeRight = Freezer.Display.Right.Model.getTree();
		that.updateTreePostMove(treeLeft, sourceLocKey, function() {
			that.updateTreePostMove(treeLeft, destLocKey, function() {
				that.updateTreePostMove(treeRight, sourceLocKey, function() {
					that.updateTreePostMove(treeRight, destLocKey)
				});
			});
		});
	},
	setControls: function(enabled) {
		if(enabled) {
			$('#set_active_btn1').prop("disabled", false).removeClass('ui-state-disabled')
			$('#set_inactive_btn1').prop("disabled", false).removeClass('ui-state-disabled')
			$('#btnClearSelections1').prop("disabled", false).removeClass('ui-state-disabled')
		
		} else {
			$('#set_active_btn1').prop("disabled", true).addClass('ui-state-disabled')
			$('#set_inactive_btn1').prop("disabled", true).addClass('ui-state-disabled')
			$('#btnClearSelections1').prop("disabled", true).addClass('ui-state-disabled')	
		}
	},
	clearSelection: function() {
		this.Model.getTree().visit(function(node){
			node.select(false);
		});
		return false;		
	},
	getClickHandler: function() {
		return function(node, event) {
			var et = node.getEventTargetType(event);
			switch(et) {
				case 'expander':
					break;
				case 'title':
					return false;
					break;
			}
		}
	},
	getDblClickHandler: function() {
		var context = this;
		return function(node, event) {
			if(node.data.info.Type == 'Container') {
				var link = gamma.pageContext.site_url + "material_container/show/" + node.data.info.Name;
				window.open(link);
			}			
			if(node.data.info.Type == 'Col' && node.data.info.Available > 0) {
				context.pendingLocaton = node.data.info.Tag;
				var link = gamma.pageContext.site_url + "material_container/create/init/-/-/" + node.data.info.Tag;
				window.open(link);
			}
		}					
	},
	getSelectionHandler: function() {
		var context = this;
		return 	function(select, node) {
			var selectedNodes = node.tree.getSelectedNodes();
			if(selectedNodes.length > 0) {
				context.setControls(true);
			} else {
				context.setControls(false);				
			}
		}
	},
	getLazyReadHandler: function() {
		var context = this;
		return function(node) {
			if(node.data.info.Type == 'Container') {
				node.setLazyNodeStatus(DTNodeStatus_Ok);
				return;
			}
			if(node.data.info.Status == 'Active') {
				context.Model.getContainerNodes(node);
			} else {
				if(node.data.info.Type == 'Col') {
					node.setLazyNodeStatus(DTNodeStatus_Ok);
				} else {
					context.Model.getLocationNodes(node);
				}
			}
		}
	},
	getDndObj: function() {
		var context = this;
		return {
			autoExpandMS: 1000,
			preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			onDragEnter: function(node, sourceNode) {
				return 'over';
			},
			onDragOver: function(node, sourceNode, hitMode) {
				return node.data.info.Type == 'Col'
			},
			onDrop: function(node, sourceNode, hitMode, ui, draggable) {
				context.Model.moveContainer(sourceNode, node, function() {
					context.updatePostMove(sourceNode, node);
				});
			},
			onDragLeave: function(node, sourceNode) {
				// Always called if onDragEnter was called.
			},
			onDragStart: function(node) {
				if(node.data.isFolder) return false;
				return true;
			},
			onDragStop: function(node) {
			}
	    }
	}
}

$(document).ready(function() {

	Freezer.Display.Left = Freezer.Display.create("tree1");
	Freezer.Display.Right = Freezer.Display.create("tree2");
	
	/*----- bind controls -----*/
	$("#btnCollapseAll1").click(function(){
		return Freezer.Display.Left.collapseTree();
	});
	$("#btnCollapseAll2").click(function(){
		return Freezer.Display.Right.collapseTree();
	});
	$("#btnClearSelections1").click(function(){
		return Freezer.Display.Left.clearSelection();
	});
	$("#find_location_btn1").click(function(){
		return Freezer.Display.Left.findLocationOrContainer();
	});
	$("#find_location_btn2").click(function(){
		return Freezer.Display.Right.findLocationOrContainer();
	});
	$("#find_available_location_btn1").click(function(){
		return Freezer.Display.Left.findAvailableLocation();
	});
	$("#find_available_location_btn2").click(function(){
		return Freezer.Display.Right.findAvailableLocation();
	});

	$("#set_active_btn1, #set_inactive_btn1").click(function(event){ 
		Freezer.Display.Left.updateLocations();
		return false;
	});

	$("#show_newest_container_btn1").click(function(){
		return Freezer.Display.Left.showNewestContainer();
	});
	$("#show_newest_container_btn2").click(function(){
		return Freezer.Display.Right.showNewestContainer();
	});
	Freezer.Display.Left.setControls(false);


	/*----- set up left-hand tree -----*/
	Freezer.Display.Left.Model.myTreeElement.dynatree({
		minExpandLevel: 1,
		selectMode: 2,
		checkbox: true,
		initAjax: {
			url: '<?= site_url() ?>freezer/get_freezers',
			data: {}
		},
		onLazyRead: Freezer.Display.Left.getLazyReadHandler(),
		onClick: Freezer.Display.Left.getClickHandler(),
		onDblClick: Freezer.Display.Left.getDblClickHandler(),
		dnd: Freezer.Display.Left.getDndObj(),
		onSelect: Freezer.Display.Left.getSelectionHandler()
	});
	
	/*----- set up right-hand tree -----*/
	Freezer.Display.Right.Model.myTreeElement.dynatree({
		minExpandLevel: 1,
		selectMode: 2,
		checkbox: false,
		initAjax: {
			url: '<?= site_url() ?>freezer/get_freezers', 
			data: {}
		},
		onLazyRead: Freezer.Display.Right.getLazyReadHandler(),
		onClick: Freezer.Display.Right.getClickHandler(),
		onDblClick: Freezer.Display.Right.getDblClickHandler(),
	    dnd: Freezer.Display.Right.getDndObj()
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

