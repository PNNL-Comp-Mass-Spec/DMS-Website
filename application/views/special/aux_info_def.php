<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<style type="text/css">
.header {
	padding:8px 0 2px 0;
	font-weight:bold;
}
</style>

<script type='text/javascript'>
globalAJAX = {};
globalAJAX.progress_message = '<span class="LRepProgress"><img src="<?= base_url() ?>images/throbber.gif" /></span>';
globalAJAX.site_url = '<?= site_url() ?>';
globalAJAX.my_tag = '<?= $this->my_tag ?>';
globalAJAX.hierarchy = {
		"top":"aux_info_targets", 
		"aux_info_targets":"aux_info_categories", 
		"aux_info_categories":"aux_info_subcategories", 
		"aux_info_subcategories":"aux_info_items", 
		"aux_info_items":"aux_info_allowed_values",
		"aux_info_allowed_values":""
};
function renameMember(type) {
	var id = $F(type);
}
function addNewMember(type, parent_id) {
	alert('add new member to ' + type + ' that belongs to parent ' + parent_id);
}
function updateContainer(type, id, follow_on_action) { 
	var url = globalAJAX.site_url + globalAJAX.my_tag + '/test/' + type + '/' + id;
	var container = type + '_container';
	$(container).update(globalAJAX.progress_message);
	var p = {};
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container).update(transport.responseText);
		}
	});
}
function clearChildren(parent) {
	var child = parent;
	while(child = globalAJAX.hierarchy[child]) {
		$(child + '_container').update('');
	}
}
function getChildren(parent) {
	id = (parent && parent != 'top')?$F(parent):'';
	child = globalAJAX.hierarchy[parent];
	if(child) {
		clearChildren(child);
		updateContainer(child, id);
	}
}
//after the page loads, set things in motion to populate it
$(document).ready(function () { 
		getChildren('top');
	}
);
</script>

</head>
<body>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $title; ?></h2>
</div>

<div class='header' >Aux Info Targets</div>
<div id='aux_info_targets_container' > </div>
<div class='header' >Categories</div>
<div id='aux_info_categories_container' > </div>
<div class='header' >Subcategories</div>
<div id='aux_info_subcategories_container' > </div>
<div class='header' >Items</div>
<div id='aux_info_items_container' > </div>
<div class='header' >Allowed Values</div>
<div id='aux_info_allowed_values_container' > </div>

</body>
</html>
