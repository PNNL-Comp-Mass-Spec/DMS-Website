<script type="text/javascript">

function performOperation(mode, val) {
	var list = gamma.getCkbxList('ckbx');
	if(list=='') {
		alert('You must select requests.'); 
		return;
	}
	if (!confirm("Are you sure that you want to update the database?")) return;
	
	url =  "<?= $ops_url ?>";
	var p = {};
	p.command = mode;
	p.containerList = list;
	p.newValue = (val)?$F(val):'';
	p.comment = $('#comment_fld').val();
	submitOperation(url, p);
}
</script>

<div class='LRCmds'>
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" id="cmds" >

<div>
<span><input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("retire_container")' /></span>
<span>Retire selected containers (must be empty)
</span>
</div>

<div>
<span><input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("retire_container_and_contents")' /></span>
<span>Retire selected containers (and their contents)
</span>
</div>

<div>
<span><input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("move_container", "location_fld")' /></span>
<span>Move selected containers to location</span>
<input type='text' name='location_fld' id='location_fld' size='22' maxlength='22' >
<?= $this->choosers->make_chooser('location_fld', 'list-report.helper', '', '/helper_material_location/report', 'choose...', '', '') ?>
</div>

<div>
<div>Comment</div>
<textarea name="comment" cols="70" rows="3" id="comment_fld" ></textarea>
</div>


</form>

</div>
