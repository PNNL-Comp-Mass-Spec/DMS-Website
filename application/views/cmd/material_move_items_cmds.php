<div class='LRCmds'>

<form name="DBG" id="cmds" >

<div>
<span><input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.material_move_items.op("retire_items", "mixed_material")' /></span>
<span>Retire selected material items
</span>
</div>

<div>
<span><input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.material_move_items.op("move_material", "mixed_material", "container_fld")' /></span>
<span>Move selected items to container</span>
<input type='text' name='container_fld' id='container_fld' size='22' maxlength='22' >
<?= $this->choosers->make_chooser('container_fld', 'list-report.helper', '', '/helper_material_container/report', 'choose...', '', '') ?>
</div>

<div>
<div>Comment</div>
<textarea name="comment" cols="70" rows="3" id="comment_fld" ></textarea>
</div>

</form>

</div>
