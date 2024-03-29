//------------------------------------------
//item_values($target, $category, $subcategory, $id)
function loadItemEntryForm(url){
    var response_container = $('#item_entry_form_container');
    var cat_sub = $('#Category_Subcategory').val().split('|');
    var category = cat_sub[0];
    var subcategory = cat_sub[1];
    p = {};
    p.category = category ;
    p.subcategory = subcategory;
    p.id = $('#TargetID').val();
    response_container.html(gAuxInfoAJAX.progress_message);
    $.post(url, p, function (data) {
            response_container.html(data);
        }
    );
    $('#copy_info_container').css('visibility', 'visible');
    $('#edit_container').css('visibility', 'visible');
    $('#splash_container').css('visibility', 'hidden');
}
//------------------------------------------
function updateAuxInfo(url, show_url) {
    var response_container = $('#update_response');
    var cat_sub = $('#Category_Subcategory').val().split('|');
    var category = cat_sub[0];
    var subcategory = cat_sub[1];
    $('#category_field').val(category);
    $('#subcategory_field').val(subcategory);
    p = $('#item_entry_form').serialize();
    response_container.html(gAuxInfoAJAX.progress_message);
    $.post(url, p, function (data) {
            response_container.html(data);
            showAuxInfo('aux_info_container', show_url);
        }
    );
}
//------------------------------------------
function copyAuxInfo(url, show_url){
    if(!$('#copy_source').val()) {
        alert('You must enter a source to be copied from.');
        return;
    }
    var form_name ='copy_info_form';
    var response_container = $('#copy_response');
    var copy_mode = $('#copy_mode_selector').val();
    var cat_sub = $('#Category_Subcategory').val();
    if(copy_mode != 'copyAll' && !cat_sub) {
        alert('You must select a subcategory for this copy mode.');
        return;
    }
    var cs = (cat_sub)?cat_sub.split('|'):['',''];
    $('#ci_category').val(cs[0]);
    $('#ci_subcategory').val(cs[1]);
    p = $('#copy_info_form').serialize();
    response_container.html(gAuxInfoAJAX.progress_message);
    $.post(url, p, function (data) {
            response_container.html(data);
            showAuxInfo('aux_info_container', show_url);
        }
    );

}
//------------------------------------------
function loadAllowedValueChooser(chooser_container_id, url){
    $.post(url, {}, function (data) {
            $('#' + chooser_container_id).html(data);
        }
    );
}
//------------------------------------------
function showAuxInfo(display_container, url) {
    var container = $('#' + display_container);
    container.html("Loading...");
    $.post(url, {}, function (data) {
            container.html(data);
        }
    );
}
