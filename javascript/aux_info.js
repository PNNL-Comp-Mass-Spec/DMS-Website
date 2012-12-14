//------------------------------------------
//item_values($target, $category, $subcategory, $id)
function loadItemEntryForm(url){
	var response_container = 'item_entry_form_container';
	var cat_sub = $('#Category_Subcategory').val().split('|');
	var category = cat_sub[0]; 
	var subcategory = cat_sub[1];
	p = {};
	p.category = category ;
	p.subcategory = subcategory;
	p.id = $('#TargetID').val();
	$(response_container).update(gAuxInfoAJAX.progress_message);
	new Ajax.Updater(response_container, url, {parameters:p});	
	$('#copy_info_container').style.visibility='visible';
	$('#edit_container').style.visibility='visible';
	$('#splash_container').style.visibility='hidden';
}
//------------------------------------------
function updateAuxInfo(url, show_url) {
	var form_name ='item_entry_form';
	var response_container = 'update_response';
	var display_container = 'aux_info_container';
	var cat_sub = $('#Category_Subcategory').val().split('|');
	var category = cat_sub[0]; 
	var subcategory = cat_sub[1];
	$('#category_field').value = category;
	$('#subcategory_field').value = subcategory;
	p = Form.serialize(form_name, true);
	$(response_container).update(gAuxInfoAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(response_container).update(transport.responseText);
			showAuxInfo(display_container, show_url);
		}
	});
}
//------------------------------------------
function copyAuxInfo(url, show_url){
	if(!$('#copy_source').val()) {
		alert('You must enter a source to be copied from.');
		return;
	}
	var form_name ='copy_info_form';
	var response_container = 'copy_response';
	var display_container = 'aux_info_container';
	var copy_mode = $('#copy_mode_selector').val();
	var cat_sub = $('#Category_Subcategory').val();
	if(copy_mode != 'copyAll' && !cat_sub) {
		alert('You must select a subcategory for this copy mode.');
		return;
	}
	var cs = (cat_sub)?cat_sub.split('|'):['',''];
	$('#ci_category').value = cs[0];
	$('#ci_subcategory').value = cs[1];
	p = Form.serialize(form_name, true);
	$(response_container).update(gAuxInfoAJAX.progress_message);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(response_container).update(transport.responseText);
			showAuxInfo(display_container, show_url);
		}
	});
}
//------------------------------------------
function loadAllowedValueChooser(chooser_container_id, url){
	new Ajax.Updater(chooser_container_id, url);	
}
//------------------------------------------
function showAuxInfo(display_container, url) {
	$(display_container).update("Loading...");
	new Ajax.Updater(display_container, url);	
}
