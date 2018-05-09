
<div class="LRepChooser">
	
<span class="cmd_link_cartouche"><?= helper_selection_cmd_link('btn_unselect_all', 'Unselect all', 'lambda.setCkbxState("ckbx", 0)', 'UnselAll', 'Clear all checkboxes') ?></span>
<span class="cmd_link_cartouche"><?= helper_selection_cmd_link('btn_select_all', 'Select all', 'lambda.setCkbxState("ckbx", 1)', 'SelAll', 'Check all checkboxes') ?></span>

<?php if($is_ms_helper): ?>
<span class="cmd_link_cartouche"><?= helper_selection_cmd_link('btn_save_selection_replace', 'Save Selection (Replace)', 'opener.epsilon.updateFieldValueFromChooser(lambda.getCkbxList("ckbx"), "replace")', 'cmd', 'Replace entry page field with selected items') ?></span>
<span class="cmd_link_cartouche"><?= helper_selection_cmd_link('btn_save_selection_append', 'Save Selection (Append)', 'opener.epsilon.updateFieldValueFromChooser(lambda.getCkbxList("ckbx"), "append")', 'cmd', 'Append selected items to entry page field') ?></span>
<?php endif; ?>

</div>
