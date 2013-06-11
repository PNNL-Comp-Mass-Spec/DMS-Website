var Freezer = {};

Freezer.Util = {
	getTree: function(elementName) {
		return $('#' + elementName).dynatree("getTree");
	},
	getSelectedNodes: function(elementName) {
		return Freezer.Util.getTree(elementName).getSelectedNodes();
	},	
	getSelectedNodesByType: function(elementName) {
		var selectedNodes = Freezer.Util.getSelectedNodes(elementName);
		var catNodes = {
			containers: [],
			locations: [],
			affectedLocs: []
		}
		$.each(selectedNodes, function(idx, node) {
			if(node.data.info.Type == 'Container') {
				catNodes.containers.push(node);
				catNodes.affectedLocs.push(node.getParent());
			} else {
				catNodes.locations.push(node);
				catNodes.affectedLocs.push(node);
			}
		});
		return catNodes;
	},
	getDelimitedList: function(objectArray, propertyName) {
		var itemArray = [];
		$.each(objectArray, function(idx, obj) {
			itemArray.push("'" + obj[propertyName] + "'");
		});		
		return itemArray.join(',');
	},
	getContainerMoveParameters: function(catNodes) {
		var containerList = $.map(catNodes.containers, function(node) { return node.data.info.ID; });
		var containers = containerList.join(', ');
		var locationList = $.map(catNodes.locations, function(node) { return node.data.info.Tag; });
		var destinationLoc = locationList[0];
		return {
			containers: containers,
			destinationLoc: destinationLoc
		}
	},
	getStatusChangeList: function() {
		var cmd = event.target.id;
		var newStatus = (cmd == "set_active_btn") ? "Active": "Inactive";
		var selectedNodes = Freezer.Util.getSelectedNodes("tree");
		return Freezer.Model.getChangeList(selectedNodes, 'Status', newStatus);
	},
	getNodeName: function(node, itemName){
		return node.data.info[itemName];
	}
}

Freezer.Model = {
	setNodeDisplay: function() {
		Freezer.Util.getTree("tree").visit(function(node){
			if(node.data.info.Type == 'Container') {
				Freezer.Model.displayContainerNode(node);
			} else {
				Freezer.Model.displayLocationNode(node);
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
				url: gamma.pageContext.site_url + 'freezer/get_locations',
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
					Freezer.Model.setNodeDisplay();
				}
			});		
	},
	getContainerNodes: function(node) {
			node.appendAjax({
				url: gamma.pageContext.site_url + 'freezer/get_containers',
				type: "POST",			
				data: {
					"Location": node.data.info.Tag,
				},
				success: function(node) {
					Freezer.Model.setNodeDisplay();
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
		var changesXML = Freezer.Model.getChangeXML(changeList);
		var url = gamma.pageContext.site_url + 'freezer/operation';
		var p = { locationList:changesXML };
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			var response = (data);
			if(data.indexOf('Update was successful.') > -1) {
				Freezer.Model.updateLocationNodes(changeList);
			} else {
				$("#messages").html(data);				
			}
		});				
	},
	updateLocationNodes: function(changeList) {
		var locationList = Freezer.Util.getDelimitedList(changeList, "Location");
		var url = gamma.pageContext.site_url + 'freezer/get_locations';
		var p = { "Type":"Tag", "Freezer":locationList, "Shelf":"", "Rack":"", "Row":"", "Col":"" };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			var objArray = $.parseJSON(json);
			var tree = Freezer.Util.getTree("tree");
			$.each(objArray, function(idx, obj) {
				var node = tree.getNodeByKey(obj.key);
				node.data.info.Status = obj.info.Status;
				node.data.info.Containers = obj.info.Containers;
				node.data.info.Limit = obj.info.Limit;
				Freezer.Model.displayLocationNode(node);
			});
		});		
	},
	moveContainers: function() {
		var catNodes = Freezer.Util.getSelectedNodesByType("tree");
		var moveParms = Freezer.Util.getContainerMoveParameters(catNodes);
		var url = gamma.pageContext.site_url + 'material_move_container/operation';
		var p = {
			command:'move_container',
			containerList:moveParms.containers,
			newValue:moveParms.destinationLoc,
			comment:'' // Future: prompt for comment
		};
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			if(data.indexOf('Update was successful.') > -1) {
				$.each(catNodes.affectedLocs, function(idx, node) { node.reloadChildren(); });
				var changeList = Freezer.Model.getChangeList(catNodes.affectedLocs, '', '');
				Freezer.Model.updateLocationNodes(changeList);
				catNodes.locations[0].expand(true);
			} else {
				$("#messages").html(data);
			}
		});		
	}
	
}
