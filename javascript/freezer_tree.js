var Freezer = {};

Freezer.Model = {
	myTreeElement: '',
	create: function(elementName) {
		var me = $.extend({}, Freezer.Model);
		me.myTreeElement = $('#' + elementName);
		return me;
	},
	getTree: function() {
		return this.myTreeElement.dynatree("getTree");
	},
	getSelectedNodes: function() {
		return this.getTree().getSelectedNodes();
	},	
	getSelectedNodesByType: function() {
		var selectedNodes = this.getSelectedNodes();
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
		var newStatus = (cmd.indexOf("set_active") != -1) ? "Active": "Inactive";
		var selectedNodes = this.getSelectedNodes();
		return this.getChangeList(selectedNodes, 'Status', newStatus);
	},
	getNodeName: function(node, itemName){
		return node.data.info[itemName];
	},
	normalizeLocationPath: function(locTag) {
		// fill out locTag to full location path (in case it is partial)
		var segs = 'na.na.na.na.na'.split('.');
		var inSegs = locTag.split('.');
		$.each(inSegs, function(idx, s) {
			if(idx == 0) s = s.toUpperCase();
			segs[idx] = s;
		});
		return segs.join('.');		
	},
	getNormalizedIdentifier: function(id) {
		var result = { Type:"unknown", NormalizedID:id};
		var patt1 = /MC\-\d\d\d\d/i;
		var patt2 = /^\d\d\d\d$/i;
		if(patt1.test(id)) {
			result.Type = "Container";
			result.NormalizedID = id.toUpperCase();
		} else 
		if(patt2.test(id)) {
			result.Type = "Container";
			result.NormalizedID = "MC-" + id;			
		} else {
			result.Type = "Location";
			result.NormalizedID = this.normalizeLocationPath(id);						
		}
		return result;
	},
	exposeLocation: function(locTag) {
		var locTag = this.normalizeLocationPath(locTag);
		var segs = locTag.split(".");
		// get hierarchy of parent locations
		var tagSegs = $.merge([], segs);
		var parentLocTags = [locTag];
		for(var i = 4; i > 0; i--) {
			var seg = segs[i];
			if(seg != 'na') {
				tagSegs[i] = 'na';
				parentLocTags.push(tagSegs.join('.'));
			}
		}
		// expand parents as needed
		var tree = this.getTree();
		var topDown = parentLocTags.reverse();
		var level = 0;
		this.nextLevel(tree, topDown, level);
	},
	nextLevel: function(tree, topDown, level) {
		if(level >= topDown.length) {
			return;
		}
		var tag = topDown[level++];
		var node = tree.getNodeByKey(tag);
		var that = this;
		if(node.hasChildren()) {
			this.nextLevel(tree, topDown, level);
		} else {
			node.reloadChildren(function(node, isOk){
			    that.nextLevel(tree, topDown, level);
			});
		}
		node.expand(true);
	},
	setNodeDisplay: function() {
		var that = this;
		this.getTree().visit(function(node){
			if(node.data.info.Type == 'Container') {
				that.displayContainerNode(node);
			} else {
				that.displayLocationNode(node);
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
		var extra = "";
		extra +=" [" + node.data.info.Items + "] ";
		extra +=" " +  node.data.info.Researcher + " ";
		extra +=" '" + node.data.info.Comment + "' ";
//		extra +=" " +  node.data.info.Files + " ";
		node.data.tooltip = extra;
		node.data.hideCheckbox = true;
		node.setTitle(newTitle);			
	},
	getLocationNodes: function(node) {
		var that = this;
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
				that.setNodeDisplay();
			}
		});		
	},
	getContainerNodes: function(node) {
		var that = this;
		node.appendAjax({
			url: gamma.pageContext.site_url + 'freezer/get_containers',
			type: "POST",			
			data: {
				"Location": node.data.info.Tag,
			},
			success: function(node) {
				that.setNodeDisplay();
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
		var that = this;
		var changesXML = this.getChangeXML(changeList);
		var url = gamma.pageContext.site_url + 'freezer/operation';
		var p = { locationList:changesXML };
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			var response = (data);
			if(data.indexOf('Update was successful.') > -1) {
				that.updateLocationNodes(changeList);
			} else {
				$("#messages").html(data);				
			}
		});				
	},
	updateLocationNodes: function(changeList) {
		var that = this;
		var locationList = this.getDelimitedList(changeList, "Location");
		var url = gamma.pageContext.site_url + 'freezer/get_locations';
		var p = { "Type":"Tag", "Freezer":locationList, "Shelf":"", "Rack":"", "Row":"", "Col":"" };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			var objArray = $.parseJSON(json);
			var tree = that.getTree();
			$.each(objArray, function(idx, obj) {
				var node = tree.getNodeByKey(obj.key);
				node.data.info.Status = obj.info.Status;
				node.data.info.Containers = obj.info.Containers;
				node.data.info.Limit = obj.info.Limit;
				that.displayLocationNode(node);
			});
		});		
	},
	findLocationNode: function(location) {
		var that = this;
		var url = gamma.pageContext.site_url + 'freezer/find_location';
		var p = { "Location":location };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			var objArray = $.parseJSON(json);
			if(objArray.length == 0) { 
				alert("location could not be found"); 
			} else {
				that.exposeLocation(objArray[0].info.Tag);
			}
		});		
	},	
	findContainerNode: function(container) {
		var that = this;
		var url = gamma.pageContext.site_url + 'freezer/find_container';
		var p = { "Container":container };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			var objArray = $.parseJSON(json);
			if(objArray.length == 0) { 
				alert("container could not be found"); 
			} else {
				that.exposeLocation(objArray[0].info.Location);
			}
		});		
	},	
	findAvailableLocationNode: function(location) {
		var that = this;
		var url = gamma.pageContext.site_url + 'freezer/find_available_location';
		var p = { "Location":location };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			var objArray = $.parseJSON(json);
			if(objArray.length == 0) { 
				alert("location could not be found"); 
			} else {
				that.exposeLocation(objArray[0].info.Tag);
			}
		});		
	},	
	findNewestContainerNode: function(callback) {
		var that = this;
		var url = gamma.pageContext.site_url + 'freezer/find_newest_containers';
		var p = { };
		gamma.getObjectFromJSON(url, p, null, function(json) {
			if(callback) {
				var objArray = $.parseJSON(json);
				callback(objArray);
			}
		});		
	},	
	moveContainers: function() {
		var that = this;
		var catNodes = this.getSelectedNodesByType("tree");
		var moveParms = this.getContainerMoveParameters(catNodes);
		var url = gamma.pageContext.site_url + 'material_move_container/operation';
		var p = {
			mode:'move_container',
			containerList:moveParms.containers,
			newValue:moveParms.destinationLoc,
			comment:'' // Future: prompt for comment
		};
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			if(data.indexOf('Update was successful.') > -1) {
				$.each(catNodes.affectedLocs, function(idx, node) { node.reloadChildren(); });
				var changeList = that.getChangeList(catNodes.affectedLocs, '', '');
				that.updateLocationNodes(changeList);
				catNodes.locations[0].expand(true);
			} else {
				$("#messages").html(data);
			}
		});		
	},
	moveContainer: function(containerNode, locationNode, callback) {
		var url = gamma.pageContext.site_url + 'material_move_container/operation';
		var p = {
			mode:'move_container',
			containerList: containerNode.data.info.ID,
			newValue: locationNode.data.info.Tag,
			comment:'' // Future: prompt for comment
		};
		$("#messages").html("");
		gamma.doOperation(url, p, null, function(data, container) {
			if(data.indexOf('Update was successful.') > -1) {
				if(callback) callback(containerNode, locationNode);
			} else {
				$("#messages").html(data);
			}
		});		
	}	
	
}
