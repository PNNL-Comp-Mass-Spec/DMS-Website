<script type="text/javascript">

function performOperation(mode, value) {
	var list = kappa.getCkbxList('ckbx');
	if(list=='') {
		alert('You must select requests.'); 
		return;
	}
	if ( !confirm("Are you sure that you want to update the database?") )
		return;

	url =  gamma.global.site_url + "sample_prep_request_assignment/operation";
	var p = {};
	p.command = mode;
	p.newValue = (value!='')?$F(value):'';
	p.reqIDList = list;
	var opts = {};
	opts.parameters = p;
	delta.submitOperation(url, p);
}
</script>

<div class="LRCmds">


<form name="DBG" action="">

<div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("state", "state_chooser")' /> Set state of selected requests to  
<span><?= $this->choosers->get_chooser('state', 'sampleRequestStatePickList')?></span>
</div>

<div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("priority", "priority_fld")' /> Set priority of selected requests to 
<select name="priority" id='priority_fld'>
	<option></option>
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
</select>
</div>


<div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("est_completion", "cmp_date_fld")' /> Set estimated completion date of selected requests to  
<input type='text' name='cmpDate' id='cmp_date_fld' size='22' maxlength='22' >
<!-- <span><?= $this->choosers->get_chooser('cmp_date_fld', 'futureDatePickList')?></span> -->
</div>

<div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("assignment", "user_list_fld")' /> Assign selected requests to preparer(s) 
<span style='display:block'><textarea style="vertical-align:top" name='userList' id='user_list_fld' rows='3' cols='60' ></textarea></span>
<span style="display:block" ><?= $this->choosers->get_chooser('user_list_fld', 'userNamePickList', 'append-comma')?>(select user to add to list)</span>
</div>

<div>
<input class="lst_cmd_btn" type="button" value="Update" onClick='performOperation("req_assignment", "")' /> Assign selected requests to requested personnel 
</div>

</form>
</div>





