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

<span class="ctls">
	<input class="button" type="button" id="find_location_btn" title="Find and display location or container" value="Find..." />
</span>

</div>

<div id="messages"></div>

<div id='tree'>
<ul>

</ul>
</div>

<script type='text/javascript'>
gamma.pageContext.site_url = '<?= site_url() ?>';

Freezer.Display = {
	initControls: function() {
		$('#set_active_btn').prop("disabled", true).addClass('ui-state-disabled')
		$('#set_inactive_btn').prop("disabled", true).addClass('ui-state-disabled')
		$('#btnClearSelections').prop("disabled", true).addClass('ui-state-disabled')
		$('#move_container_btn').prop("disabled", true).addClass('ui-state-disabled')
	},
	getSelectionPattern: function(selectedNodes) {
		var categorizedNodeList = Freezer.Util.getSelectedNodesByType("tree");
		var selectionPattern = '';
		var locationCount = categorizedNodeList.locations.length;
		var containerCount = categorizedNodeList.containers.length;
		if(locationCount == 0 && containerCount == 0) {
			$('#btnClearSelections').prop("disabled", true).addClass('ui-state-disabled')
		} else {
			$('#btnClearSelections').prop("disabled", false).removeClass('ui-state-disabled')
		}
		if(containerCount == 0 && locationCount > 0) {
			selectionPattern = "Set Location Status";
			$('#set_active_btn').prop("disabled", false).removeClass('ui-state-disabled')
			$('#set_inactive_btn').prop("disabled", false).removeClass('ui-state-disabled')
			$('#move_container_btn').prop("disabled", true).addClass('ui-state-disabled')
			
		} else 
		if(locationCount == 1 && containerCount > 0) {
			selectionPattern = "Move Containers";
			$('#set_active_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#set_inactive_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#move_container_btn').prop("disabled", false).removeClass('ui-state-disabled')
		} else {
			selectionPattern = "";			
			$('#set_active_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#set_inactive_btn').prop("disabled", true).addClass('ui-state-disabled')
			$('#move_container_btn').prop("disabled", true).addClass('ui-state-disabled')
		}
		selectionPattern = (selectionPattern) ? selectionPattern + ": " : "";
		return selectionPattern;
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
			if(node.data.info.Type == 'Container') {
				node.setLazyNodeStatus(DTNodeStatus_Ok);
				return;
			}
			if(node.data.info.Status == 'Active') {
				Freezer.Model.getContainerNodes(node);
			} else {
				if(node.data.info.Type == 'Col') {
					node.setLazyNodeStatus(DTNodeStatus_Ok);
				} else {
					Freezer.Model.getLocationNodes(node);
				}
			}
		},
		onClick: function(node, event) {
			var et = node.getEventTargetType(event);
			switch(et) {
				case 'expander':
					break;
				case 'title':
					if(node.data.info.Type == 'Container') {
						var link = gamma.pageContext.site_url + "material_container/show/" + node.data.info.Name;
						window.open(link);
					}
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
		}
	});

	Freezer.Display.initControls();
		
	$("#btnCollapseAll").click(function(){
		Freezer.Util.getTree("tree").visit(function(node){
			node.expand(false);
		});
		 return false;
	});
	$("#btnClearSelections").click(function(){
		Freezer.Util.getTree("tree").visit(function(node){
			node.select(false);
		});
		return false;
	});
	$("#display_tag_ckbx").on("change", function(event){
		Freezer.Model.setNodeDisplay();
	});	
	$("#display_status_ckbx").on("change", function(event){
		Freezer.Model.setNodeDisplay();
	});	
	$("#display_loading_ckbx").on("change", function(event){
		Freezer.Model.setNodeDisplay();
	});	

	$("#set_active_btn, #set_inactive_btn").click(function(event){
		var changeList = Freezer.Util.getStatusChangeList();
		if(changeList.length == 0) {
			alert("No locations are currently selected");			
		} else {
//			alert("Future: Set locations '" + changesXML + "'");
			Freezer.Model.updateDatabase(changeList);
		}
		return false;
	});
	
	$('#move_container_btn').click(function(event){
		Freezer.Model.moveContainers();
	});
	
	$('#find_location_btn').click(function(event){
		var val = prompt("Enter location path or container ID");
		var identifier = Freezer.Util.getNormalizedIdentifier(val);
		if(identifier.Type == "Container") {
			Freezer.Model.findContainerNode(identifier.NormalizedID);
		} else 
		if(identifier.Type == "Location") {
			Freezer.Model.findLocationNode(identifier.NormalizedID);
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

