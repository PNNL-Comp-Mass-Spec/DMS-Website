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
	<a id="btnCollapseAll" title="Collapse all expanded locations" class="button"  href="javascript:void(0)" >Collapse&nbsp;all</a>
</span>
<span class="ctls">
	<a id="btnClearSelections" title="Clear selections" class="button"  href="javascript:void(0)" >Clear Selections</a>
</span>
<span class="ctls">
	<a id="set_active_btn" title="Set selected locations to active" class="button" href="javascript:void(0)">Set Active</a> 
</span>
<span class="ctls">
	<a id="set_inactive_btn" title="Set selected locations to inactive" class="button" href="javascript:void(0)">Set Inactive</a> 
</span>

</div>

<div id='tree'>
<ul>

</ul>
</div>

<script type='text/javascript'>
gamma.pageContext.site_url = '<?= site_url() ?>';

var FreezerModel = {
	setNodeDisplay: function() {
		$("#tree").dynatree("getRoot").visit(function(node){
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
	getChangeList: function(action, value) {
		var tr = $("#tree").dynatree("getTree");
		var nl = tr.getSelectedNodes();
		var changes = [];
		var changesXML = '';
		$.each(nl, function(idx, node) {
			var obj = {
				Location:node.data.info.Tag,
				ID:node.data.info.ID,
				Action:action,
				Value:value
			};
			changes.push(obj);
		});
		if(changes.length > 0) {
			var mapP2A = [{p:'Location', a:'n'}, {p:'ID', a:'i'}, {p:'Action', a:'a'}, {p:'Value', a:'v'}];
			changesXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
		}
		return changesXML;		
	},
	updateDatabase: function(changesXML) {
		var url = gamma.pageContext.site_url + 'freezer/operation';
		var p = { locationList:changesXML };
		gamma.doOperation(url, p, null, function(data, container) {
			var response = (data);
			//if(data.indexOf('Update was successful.') > -1) {}
		});				
	}	
}

$(document).ready(function() {
/*	*/
    // $.ui.dynatree.nodedatadefaults["icon"] = false; // Turn off icons by default

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
		onDblClick: function(node, event) {
			var et = node.getEventTargetType(event);
			switch(et) {
				case 'expander':
					break;
				case 'title':
					node.visit(function(nd){
						nd.expand(true);
					});
					console.log("dbl click->" + node.data.info.Tag);
					return false;
					break;
			}
		}
		
	});
	
	$("#btnCollapseAll").click(function(){
		$("#tree").dynatree("getRoot").visit(function(node){
			node.expand(false);
		});
		 return false;
	});
	$("#btnClearSelections").click(function(){
		$("#tree").dynatree("getRoot").visit(function(node){
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
		var changesXML = FreezerModel.getChangeList('Status', newStatus);
		if(!changesXML) {
			alert("No locations are currently selected");			
		} else {
//			alert("Future: Set locations '" + changesXML + "'");
			FreezerModel.updateDatabase(changesXML);
		}
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

