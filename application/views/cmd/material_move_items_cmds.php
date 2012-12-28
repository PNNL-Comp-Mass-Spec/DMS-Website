<script type="text/javascript">

function performOperation(mode, itemType, val) {
	var list = kappa.getCkbxList('ckbx');
	if(list=='') {
		alert('You must select items.'); 
		return;
	}
	if(list.length > 4096) {
		alert('You have selected more items than system can handle at one time.  Please select fewer items and try again.');
		return;
	}
	if (!confirm("Are you sure that you want to update the database?")) return;
	
	url = gamma.pageContext.ops_url;
	var p = {};
	p.command = mode;
	p.itemType = itemType;
	p.itemList = list;
	p.newValue = (val)?$F(val):'';
	p.comment = $('#comment_fld').val();
	theta.submitOperation(url, p);
}
</script>


<div class='LRCmds'>


<form name="DBG" id="cmds" >

<div>
<span><input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("retire_items", "mixed_material")' /></span>
<span>Retire selected material items 
</span>
</div>

<div>
<span><input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("move_material", "mixed_material", "container_fld")' /></span>
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



