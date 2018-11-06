<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>
<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/base2js') ?>

<script src="<?= base_url().'javascript/aux_info.js?version=100' ?>"></script>
<?= $ais->make_aux_info_global_AJAX_definitions() ?>

<script type='text/javascript'>
$(document).ready(function () { showAuxInfo("aux_info_container", '<?= $ais->show_url ?>') }
)</script>

</head>
<body>
<?php $this->load->view('nav_bar') ?>
<h2><?= $heading; ?> for <?= $target; ?>: <?= $id; ?> <span style='font-weight:normal'>(<?= $name ?>)</span></h2>

<div style='font-weight:bold;padding:0 0 4px 0'>Categories and Subcategories</div>

<div><?= $ais->make_category_subcategory_selector($aux_info_def) ?></div>
<input type='hidden' id = 'TargetID'  value='<?= $id ?>' />


<div id='edit_container' style='padding:10px 0 0 0;visibility:hidden;'>
<form id='item_entry_form' >
<input type='hidden' id='TargetName' name='TargetName' value='<?= $target ?>' />
<input type='hidden' id='EntityID' name='EntityID' value='<?= $id ?>' />
<input type='hidden' id='category_field' name='Category' value='' />
<input type='hidden' id='subcategory_field' name='Subcategory' value='' />

<div id='item_entry_form_container' ></div>

<input class="button lst_cmd_btn" type="button" value='Update' onclick="updateAuxInfo('<?= $ais->update_info_url ?>', '<?= $ais->show_url ?>')" />
<a href='javascript:void(0)' title='Clear entry fields' onclick='$(".aiif").each(function(idx, s){s.value=""})'>Clear</a>
<div id='update_response' ></div>

</form>
</div>

<div id='copy_info_container' style='padding:10px 0 0 0;'>
<form id='copy_info_form' >
<input type='hidden' id='ci_target_name' name='TargetName' value='<?= $target ?>' />
<input type='hidden' id='ci_entity_id' name='EntityID' value='<?= $name ?>' />
<input type='hidden' id='ci_category' name='Category' value='' />
<input type='hidden' id='ci_subcategory' name='Subcategory' value='' />
<table class='EPag'>
<tr><th>Copy Info</th></tr>
<tr><td>
Copy
<select name="CopyMode" id='copy_mode_selector'>
    <option value='copySubcategory'>Subcategory</option>
    <option value='copyCategory'>Category</option>
    <option value='copyAll' selected="selected" >All</option>
</select>
from
<input type="text" name="CopySource" id='copy_source' size="40" maxlength="255" value="" />
</td></tr>
</table>
<input class="button lst_cmd_btn" type="button" value="Copy" onclick="copyAuxInfo('<?= $ais->copy_info_url ?>', '<?= $ais->show_url ?>')" />
</form>
</div>

<div id='copy_response' ></div>

<div id='splash_container'></div>

<div style="height:1em;" ></div>
<div class='DrepAuxInfo'>
<span style="font-weight:bold;">Show Aux Info:</span>
<span><a href='javascript:showAuxInfo("aux_info_container", "<?= $ais->show_url ?>")'>Refresh...</a></span>
</div>
<div id= 'aux_info_container'></div>
<div style="height:1em;" ></div>
</body>
</html>
