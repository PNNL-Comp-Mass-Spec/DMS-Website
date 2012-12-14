<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>
<? $this->load->view('resource_links/cfg') ?>

<script type="text/javascript">

function extractRow(theObject, index) {
	var obj = {};
	for (var propName in theObject) {
		obj[propName] = theObject[propName][index];
	}
	return obj;
}

// do editing action from editing from controls
function ops(index, action) {
	if ( !confirm("Are you sure that you want to modify the config db?") )
		return;
	var container_name = "edit_container";
	url =  "<?= site_url()?>config_db/submit_edit_table/<?= $config_db ?>/<?= $table_name ?>";

	var form_name = 'edit_form';
	var fields = $(form_name).serialize(true);

	var p = extractRow(fields, index);
	p.mode = action;
//var s = Object.toJSON(p);
//alert(s);
//return;
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container_name).update(transport.responseText);
		}
	});
}

// submit sql from entry field and refresh edit table
function do_sql() {
	if ( !confirm("Are you sure that you want to modify the config db?") )
		return;
	var container_name = "edit_container";
	url =  "<?= site_url()?>config_db/exec_sql/<?= $config_db ?>/<?= $table_name ?>";
//	var p = $('#sql_text').serialize(true);
	var p = Form.serialize('sql_text', true);
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport) {
			$(container_name).update(transport.responseText);
		}
	});
}
// get suggested sql for enhancing table
function get_sql(mode){
	var field_name = "sql_text_fld";
	url = "<?= site_url()?>config_db/get_suggested_sql/<?= $config_db ?>/<?= $table_name ?>";
	var p = {};
	p.mode = mode;
	$(field_name).value = '';
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport){
			$(field_name).value = transport.responseText;
		}
	});
}
// move any existing destination id to to source id
// and set destination id to the input id
function set_id(id) {
	$('#source_id').innerHTML = $('#dest_id').innerHTML;
	$('#dest_id').innerHTML = id;

	$('#range_start_id').innerHTML = $('#range_stop_id').innerHTML;
	$('#range_stop_id').innerHTML = $('#range_dest_id').innerHTML;
	$('#range_dest_id').innerHTML = id;
}

// get suggested SQL for moving item(s)
function get_sql_from_range_move(mode){
	if(mode == 'range') {
		var r1_id = $('#range_start_id').innerHTML;
		var r2_id = $('#range_stop_id').innerHTML;
		var d_id = $('#range_dest_id').innerHTML;
	} else {
		var r1_id = $('#source_id').innerHTML;
		var r2_id = $('#source_id').innerHTML;
		var d_id = $('#dest_id').innerHTML;
	}
	if(!r1_id || !r2_id || !d_id) return;

	url = "<?= site_url()?>config_db/move_range/<?= $config_db ?>/<?= $table_name ?>/" + r1_id + "/"  + r2_id + "/" + d_id;
	var field_name = "sql_text_fld";
	var p = {};
	$(field_name).value = '';
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport){
			$(field_name).value = transport.responseText;
		}
	});
}
// get suggested SQL for resequencing id column in table
function get_sql_for_resequence(){
	url = "<?= site_url()?>config_db/resequence_table/<?= $config_db ?>/<?= $table_name ?>";
	var field_name = "sql_text_fld";
	var p = {};
	$(field_name).value = '';
	new Ajax.Request(url, {
		parameters: p,
		onSuccess: function(transport){
			$(field_name).value = transport.responseText;
		}
	});
}
</script>

</head>
<body>
<div style="height:500px;">

<h2 class='page_title'><?= $heading; ?></h2>

<?= make_config_nav_links($config_db)?>

<div id='edit_container'>
<? $this->load->view("config_db/sub_table_edit"); ?>
</div>

<div style="height:1em;"></div>

<table class='cfg_tab' style="width:98%;">
	
<tr><th style="font-weight:bold;text-align:left;">Raw SQL Entry</th></tr>

<tr><td><div id='sql_container' style="padding-right:5px;">
<form id='sql_text' action='post'>
<textarea name='sql_text' id='sql_text_fld' style="height:15em;width:100%;"><?= $sql_text ?></textarea>
</form>
</div></td></tr>

<tr><td>
<a href="javascript:void(0)" onclick="get_sql('suggest')" title='Get suggested SQL for possible new additions to table'>Suggest Additions</a> &nbsp;  &nbsp; 
<a href="javascript:void(0)" onclick="get_sql('dump')" title='Get SQL for existing contents of table.'>Current Content</a> &nbsp;  &nbsp; 
<a href="javascript:void(0)" onclick="do_sql()" title='Run the SQL against the config db.'>Update</a> &nbsp;  &nbsp; 
<a href="javascript:void(0)" onclick="get_sql_from_range_move('item')" title='Get SQL to move items'> <span id='source_id'></span>-><span id='dest_id'></span> </a> &nbsp;  &nbsp; 
<a href="javascript:void(0)" onclick="get_sql_from_range_move('range')" title='Get SQL to move range of items'> <span id='range_start_id'></span>-<span id='range_stop_id'></span>-><span id='range_dest_id'></span> </a> &nbsp;  &nbsp; 
<a href="javascript:void(0)" onclick="get_sql_for_resequence()" title='Get SQL to resequence id col in table'>Resequence</a> &nbsp;  &nbsp; 
<a href="javascript:void(0)" onclick="$('#sql_text_fld').value = ''" title='Clear SQL field'>Clear</a> &nbsp;  &nbsp; 
</td></tr>

</table>

<div id='end_of_content' style="height:1em;" ></div>

</div>
</body>
</html>
