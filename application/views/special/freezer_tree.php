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

<div class='local_title'>Freezer Location Admin</div>

<div class='ctl_panel'>
<span class="ctls">
	<input class="button" type="button"  id="btnCollapseAll" title="Collapse all expanded locations" value="Collapse&nbsp;all"</a" />
</span>
<span class="ctls">
	<input class="button" type="button" id="btnClearSelections" title="Clear selections" value="Clear Selections" />
</span>
<span class="ctls">
	<input class="button" type="button"  id="set_active_btn" title="Set selected locations to active" value="Set Active" /> 
</span>
<span class="ctls">
	<input class="button" type="button"  id="set_inactive_btn" title="Set selected locations to inactive" value="Set Inactive" /> 
</span>
<span class="ctls">
	<input class="button" type="button" id="move_container_btn" title="Move selected container(s) to selected location" value="Move Containers" />
</span>

</div>

<div id="messages"></div>

<div id='tree'>
<ul>

</ul>
</div>

<script type='text/javascript'>
gamma.pageContext.site_url = '<?= site_url() ?>';

var FreezerUtil = {
	getTree: function(elementName) {
		return $('#' + elementName).dynatree("getTree");
	},
	getSelectedNodes: function(elementName) {
		return FreezerUtil.getTree(elementName).getSelectedNodes();
	},	
	getSelectedNodesByType: function(elementName) {
		var selectedNodes = FreezerUtil.getSelectedNodes(elementName);
		var selections = {
			containers: [],
			locations: []
		}
		$.each(selectedNodes, function(idx, node) {
			if(node.data.info.Type == 'Container') {
				selections.containers.push(node);
			} else {
				selections.locations.push(node);
			}
		});
		return selections;
	},
	getDelimitedList: function(objectArray, propertyName) {
		var itemArray = [];
		$.each(objectArray, function(idx, obj) {
			itemArray.push("'" + obj[propertyName] + "'");
		});		
		return itemArray.join(',');
	},
	getNodeName: function(node, itemName){
		return node.data.info[itemName];
	},
	getSelectionPattern: function(selectedNodes) {
		var categorizedNodeList = FreezerUtil.getSelectedNodesByType("tree");
		var selectionPattern = '';
		var locationCount = categorizedNodeList.locations.length;
		var containerCount = categorizedNodeList.containers.length;
		if(locationCount == 0 && containerCount == 0) {
			$('#btnClearSelections').prop("disabled", true).addClass('ui-state-disabled')
		} else {
			$('#btnClearSelections').prop("disabled", false).removeClass('ui-state-disabled')
		}
		if(containerCount == 0 && locationCount > 0) {
			selectionPattern = "Location Ops";
			$('#set_active_btn').prop("disabled", false).removeClass('ui-state-disabled')
			$('#set_inactive_btn').prop("disabled", false).removeClass('ui-state-disabled')
			$('#move_container_btn').prop("disabled", true).addClass('ui-state-disabled')
			
		} else 
		if(locationCount == 1 && containerCount > 0) {
			selectionPattern = "Container Ops";
			$('#set_active_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#set_inactive_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#move_container_btn').prop("disabled", false).removeClass('ui-state-disabled')
		} else {
			selectionPattern = "Not Viable";			
			$('#set_active_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#set_inactive_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#move_container_btn').prop("disabled", true).addClass('ui-state-disabled')
		}
		return selectionPattern;
	}	
}

var FreezerModel = {
	setNodeDisplay: function() {
		FreezerUtil.getTree("tree").visit(function(node){
			if(node.data.info.Type == 'Container') {
				FreezerModel.displayContainerNode(node);
			} else {
				FreezerModel.displayLocationNode(node);
			}
		});
	},
	displayLocationNode: function(node) {
		var newTitle = node.data.info.Type + " " + node.data.info.Name;
		if(node.data.info.Status == "Active") {
			newTitle += " [" + node.data.info.Containers + "/" + node.data.info.Limit + "]";								
		}
		node.data.tooltip = node.data.info.Tag;
		node.setTitle(newTitle);
	},
	displayContainerNode: function(node) {
		var newTitle = node.data.info.ContainerType + " " + node.data.info.Name;
		newTitle +=" [" + node.data.info.Items + "] ";
		newTitle +=" " +  node.data.info.Researcher + " ";
		newTitle +=" '" + node.data.info.Comment + "' ";
//		newTitle +=" " +  node.data.info.Files + " ";
		node.data.tooltip = node.data.info.Tag;
		node.setTitle(newTitle);			
	},
	getLocationNodes: function(node) {
			node.appendAjax({
				url: '<?= site_url() ?>freezer/get_locations',
				type: "POST",			
				data: {
					"Type": node.data.info.Type,
					"Freezer":node.data.info.Freezer,
					"Shelf":node.data.info.Shelf,
					"Rack":node.data.info.Rack,
					"Row":node.data.info.Row,
					"Col":node.data.info.Col
				},
				success: function(node) {
					FreezerModel.setNodeDisplay();
				}
			});		
	},
	getContainerNodes: function(node) {
			node.appendAjax({
				url: '<?= site_url() ?>freezer/get_containers',
				type: "POST",			
				data: {
					"Location": node.data.info.Tag,
				},
				success: function(node) {
					FreezerModel.setNodeDisplay();
				}
			});		
	},
	getChangeList: function(selectedNodes, action, value) {
		var changeList = [];
		$.each(selectedNodes, function(idx, node) {
			var obj = {
				Location:node.data.info.Tag,
				ID:node.data.info.ID,
				Action:action,
				Value:value
			};
			changeList.push(obj);
		});
		return changeList;
	},
	getChangeXML: function(changeList) {
		var changesXML = '';
		if(changeList.length > 0) {
			var mapP2A = [{p:'Location', a:'n'}, {p:'ID', a:'i'}, {p:'Action', a:'a'}, {p:'Value', a:'v'}];
			changesXML = gamma.getXmlElementsFromObjectArray(changeList, 'r', mapP2A);
		}
		return changesXML;		
	},
	updateDatabase: function(changeList) {
		var changesXML = FreezerModel.getChangeXML(changeList);
		var url = gamma.pageContext.site_url + 'freezer/operation';
		var p = { locationList:changesXML };
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			var response = (data);
			if(data.indexOf('Update was successful.') > -1) {
				FreezerModel.updateLocationNodes(changeList);
			} else {
				$("#messages").html(data);				
			}
		});				
	},
	updateLocationNodes: function(changeList) {
		var locationList = FreezerUtil.getDelimitedList(changeList, "Location");
		var url = '<?= site_url() ?>freezer/get_locations';
		var p = { "Type":"Tag", "Freezer":locationList, "Shelf":"", "Rack":"", "Row":"", "Col":"" };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			var objArray = $.parseJSON(json);
			var tree = FreezerUtil.getTree("tree");
			$.each(objArray, function(idx, obj) {
				var node = tree.getNodeByKey(obj.key);
				node.data.info.Status = obj.info.Status;
				FreezerModel.displayLocationNode(node);
			});
		});		
	},
	moveContainers: function() {
		var catNodes = FreezerUtil.getSelectedNodesByType("tree");
		var containerList = $.map(catNodes.containers, function(node) { return node.data.info.ID; });
		var list = containerList.join(', ');
		var locationList = $.map(catNodes.locations, function(node) { return node.data.info.Tag; });
		var destinationLoc = locationList[0];
		var mode = 'move_container';
		var url = gamma.pageContext.site_url + 'material_move_container/operation';
		var p = {};
		p.command = mode;
		p.containerList = list;
		p.newValue = destinationLoc;
		p.comment = ''; // Future: prompt for comment
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			var response = (data);
			if(data.indexOf('Update was successful.') > -1) {
				$.each(catNodes.containers, function(idx, node) {
					node.getParent().reloadChildren();
					node.getParent().expand(true);				
				});
				$.each(catNodes.locations, function(idx, node) {
					node.reloadChildren();										
					node.getParent().reloadChildren();					
				});
			} else {
				$("#messages").html(data);
			}
		});		
	}
	
}

$(document).ready(function() {

	// set up tree menu
	$("#tree").dynatree({
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
			if(node.data.info.Status == 'Active') {
				FreezerModel.getContainerNodes(node);
			} else {
				FreezerModel.getLocationNodes(node);
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
			var selectionPattern = FreezerUtil.getSelectionPattern(selectedNodes);
			// convert to title/key array
			var selKeys = $.map(selectedNodes, function(node){
				return node.data.info.Type + "[" + node.data.key + "]";
			});
			$("#messages").text(selectionPattern + "->" + selKeys.join(", "));
		}
	});
	
	$("#btnCollapseAll").click(function(){
		FreezerUtil.getTree("tree").visit(function(node){
			node.expand(false);
		});
		 return false;
	});
	$("#btnClearSelections").click(function(){
		FreezerUtil.getTree("tree").visit(function(node){
			node.select(false);
		});
		return false;
	});
	$("#display_tag_ckbx").on("change", function(event){
		FreezerModel.setNodeDisplay();
	});	
	$("#display_status_ckbx").on("change", function(event){
		FreezerModel.setNodeDisplay();
	});	
	$("#display_loading_ckbx").on("change", function(event){
		FreezerModel.setNodeDisplay();
	});	

	$("#set_active_btn, #set_inactive_btn").click(function(event){
		var cmd = event.target.id;
		var newStatus = (cmd == "set_active_btn") ? "Active": "Inactive";
		var selectedNodes = FreezerUtil.getSelectedNodes("tree");
		var changeList = FreezerModel.getChangeList(selectedNodes, 'Status', newStatus);
		if(changeList.length == 0) {
			alert("No locations are currently selected");			
		} else {
//			alert("Future: Set locations '" + changesXML + "'");
			FreezerModel.updateDatabase(changeList);
		}
		return false;
	});
	
	$('#move_container_btn').click(function(event){
		FreezerModel.moveContainers();
	});
	
	$('#set_active_btn').prop("disabled", true).addClass('ui-state-disabled')
	$('#set_inactive_btn').prop("disabled", true).addClass('ui-state-disabled')
	$('#btnClearSelections').prop("disabled", true).addClass('ui-state-disabled')
	$('#move_container_btn').prop("disabled", true).addClass('ui-state-disabled')
	
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

