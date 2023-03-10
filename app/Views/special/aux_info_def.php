<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

<style type="text/css">
.header {
    padding:8px 0 2px 0;
    font-weight:bold;
}
</style>

<script type='text/javascript'>
dmsjs.pageContext = {};
dmsjs.pageContext.site_url = '<?= site_url() ?>';
dmsjs.pageContext.my_tag = '<?= $my_tag ?>';
dmsjs.pageContext.hierarchy = {
        "top":"aux_info_targets",
        "aux_info_targets":"aux_info_categories",
        "aux_info_categories":"aux_info_subcategories",
        "aux_info_subcategories":"aux_info_items",
        "aux_info_items":"aux_info_allowed_values",
        "aux_info_allowed_values":""
};
function renameMember(type) {
    var id = $('#' + type).val();
}
function addNewMember(type, parent_id) {
    alert('add new member to ' + type + ' that belongs to parent ' + parent_id);
}
function updateContainer(type, id, follow_on_action) {
    var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag + '/test/' + type + '/' + id;
    var containerId = type + '_container';
    dmsOps.loadContainer(url, {}, containerId);
}
function clearChildren(parent) {
    var child = parent;
    while(child = dmsjs.pageContext.hierarchy[child]) {
        $('#' + child + '_container').html('');
    }
}
function getChildren(parent) {
    id = (parent && parent != 'top')?$('#' + parent).val():'';
    child = dmsjs.pageContext.hierarchy[parent];
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
