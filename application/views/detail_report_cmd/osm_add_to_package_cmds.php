<div style='padding:5px 0px 5px 5px;'>
<?= general_visibility_control('Add to OSM Package', 'item_section', '') ?>
</div>

<div id='item_section' style='display:none; width:100em;margin:5px 0 0 0;' >
<div style='padding:5px 5px 5px 5px;border:2px solid #AAA;'>
<div id='entry_update_status'></div>

<? $chimg = base_url()."images/chooser.png"; ?>

<form id='entry_form'>
	<input type="hidden" name="command" id="entry_cmd_mode" value=""/>
	<input type='hidden' name='itemList' id='entry_item_list' value='' /> 
	<input type='hidden' name="itemType" id='itemTypeSelector' value='' />
	
	<div style='padding:0px 5px 5px 5px;' >
	Add this item to OSM package <input type='text' name='packageID' id='packageID' size='6'/>
	<span class='chsr'>Choose... <a href="javascript:packages.callOSMChooser()"><img src='<?= $chimg ?>' border='0'></a></span>
	</div>	
	<div style='padding:0px 5px 5px 5px;' >Package Comment</div>
	<div style='padding:0px 5px 5px 5px;' ><textarea name='comment' id='entry_comment' cols='70' rows='2'></textarea></div>
	
	<div style='padding:0px 5px 5px 5px;' >
	<input class='button lst_cmd_btn' type='button' value='Add' onclick='packages.updateOSMPackageItems_2("entry_form", "add")' />
	<span class='LRcmd_cartouche' ><?= detail_report_cmd_link("Go to package item list report page", "packages.goToPage()") ?></span>
	</div>
</form>
</div>
</div>
<div style='height: 1em;'></div>

<script src="<?= base_url().'javascript/packages.js' ?>"></script>

