<div class='LRCmds'>

<form name="DBG" id="cmds" >

<div>
<?php // lcmd.material_move_container is in lcmd.js ?>
<span><input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.material_move_container.op("retire_container")' /></span>
<span>Retire selected containers (must be empty)
</span>
</div>

<div>
<span><input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.material_move_container.op("retire_container_and_contents")' /></span>
<span>Retire selected containers (and their contents)
</span>
</div>

<div>
<span><input class="button lst_cmd_btn" type="button" value="Update" onClick='lcmd.material_move_container.op("move_container", "location_fld")' /></span>
<span>Move selected containers to location</span>
<input type='text' name='location_fld' id='location_fld' size='22' maxlength='22' >
<?= $choosers->make_chooser('location_fld', 'list-report.helper', '', '/helper_material_location/report', 'choose...', '', '') ?>
</div>

<div>
<div>Comment</div>
<textarea name="comment" cols="70" rows="3" id="comment_fld" ></textarea>
</div>

</form>
</div>
