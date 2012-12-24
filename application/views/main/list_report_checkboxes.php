
<div class="LRepChooser">
|<span><a id="btn_unselect_all" href='javascript:kappa.setCkbxState("ckbx", 0)' title="Clear all checkboxes" >Unselect all</a></span>
|<span><a id="btn_select_all"href='javascript:kappa.setCkbxState("ckbx", 1)' title="Check all checkboxes" >Select all</a></span>

<?php if($is_ms_helper): ?>
|<span><a id="btn_save_selection_replace" href='javascript:opener.epsilon.updateFieldValueFromChooser(kappa.getCkbxList("ckbx"), "replace")' title="Replace entry page field with selected items">Save Selection (Replace)</a></span>
|<span><a id="btn_save_selection_append" href='javascript:opener.epsilon.updateFieldValueFromChooser(kappa.getCkbxList("ckbx"), "append")' title="Append selected items to entry page field">Save Selection (Append)</a></span>
|
<?php endif; ?>
</div>
