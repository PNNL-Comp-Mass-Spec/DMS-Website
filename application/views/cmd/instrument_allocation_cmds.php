<div class="LRCmds">

<form name="DBG" action="">

<hr>
<a href="#" onclick="gamma.sectionToggle('upload_section', 0.5)">Upload allocations...</a>
<div id="upload_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Update from list" onClick='load_delimited_text()' title="Test"  /> Update database from delimited list
</div>
<div>
<div>
Fiscal Year:<input id='fiscal_year' size='8' value='' ></input>
</div>
<p>Delimited text input:</p>
<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>

<hr>
<a href="#" onclick="gamma.sectionToggle('move_cmd_section', 0.5)">Move allocation...</a>
<div id="move_cmd_section" style="display:none;">
<div>
Move <input id='move_hours' size='5' /> hours for instrument group <input id='move_group' size='5' /> 
from proposal <input id='move_from' size='5' /> to proposal <input id='move_to' size='5' /> 
for fiscal year <input id='move_fy' size='5' /> 
<input type="button" value="Update" onClick='move_allocated_hours()' />
</div>
<div>Comment</div>
<div><textarea id='move_comment' rows='2' cols='80' ></textarea></div>
</div>

<hr>
<a href="#" onclick="gamma.sectionToggle('set_cmd_section', 0.5)">Set allocation...</a>
<div id="set_cmd_section" style="display:none;">
<div>
Set <input id='set_hours' size='5' /> hours for instrument group <input id='set_group' size='5' /> 
to proposal <input id='set_to' size='5' /> 
for fiscal year <input id='set_fy' size='5' /> 
<input type="button" value="Update" onClick='set_allocated_hours()' />
</div>
<div>Comment</div>
<div><textarea id='set_comment' rows='2' cols='80' ></textarea></div>
</div>
<hr>
</form>
</div>

<script type="text/javascript">
function getXMLFromObjList(flist) {
	var xml = '';
	if (typeof(flist) != "undefined") {
		$.each(flist, function(idx, obj){
			xml += '<r p="' + obj.id + '" g="' + obj.factor + '" a="' + obj.value + '" />';
		});
	}
	return xml;
}
function getFieldListFromParsedData(parsed_data, col_list) {
	// make array of id/factor/value objects,
	// one for each row of each column
	var flist = [];
	$.each(col_list, function(idx, factor){
		var idx = parsed_data.header.indexOf(factor);
		if(idx > -1) {
			$.each(parsed_data.data, function(idx, row){
				var id = row[0];
				var value = row[idx];
				if((typeof(value) != "undefined") && (value != '')) {
					var obj = {};
					obj.id = id;
					obj.factor = factor;
					obj.value = value;
					flist.push(obj);
				}
			});				
		}
	});
	return flist;
}

function updateDatabaseFromList(flist, fiscal_year) {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var allocationXML = getXMLFromObjList(flist);
	if(fiscal_year) {
		allocationXML = '<c fiscal_year="' + fiscal_year + '" />' + allocationXML;
	}
	var url =  gamma.pageContext.ops_url;
	var p = {};
	p.parameterList = allocationXML;
	lambda.submitOperation(url, p);
}
function load_delimited_text() {
	var parsed_data = gamma.parseDelimitedText('delimited_text_input');
	var fiscal_year = $('#fiscal_year').val();
	if(fiscal_year == '') {
		alert('You must set the fiscal year for the changes');
		return;
	}
	var col_list = parsed_data.header.without('Proposal_ID');
	var flist = getFieldListFromParsedData(parsed_data, col_list);
	updateDatabaseFromList(flist, fiscal_year);
}
function move_allocated_hours() {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var xml = '';
	xml = '<c fiscal_year="' + $('#move_fy').val() + '" />';
	xml += '<r ';
	xml += 'o="i" ';
	xml += 'p="' + $('#move_to').val() + '" ';
	xml += 'a="' + $('#move_hours').val() + '" ';
	xml += 'g="' + $('#move_group').val() + '" ';
	xml += 'x="' + $('#move_comment').val() + '" ';	
	xml += ' />';
	xml += '<r ';
	xml += 'o="d" ';
	xml += 'p="' + $('#move_from').val() + '" ';
	xml += 'a="' + $('#move_hours').val() + '" ';
	xml += 'g="' + $('#move_group').val() + '" ';
	xml += 'x="' + $('#move_comment').val() + '" ';	
	xml += ' />';
	var url =  gamma.pageContext.ops_url;
	var p = {};
	p.parameterList = xml;
	lambda.submitOperation(url, p);
}
function set_allocated_hours() {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var xml = '';
	xml = '<c fiscal_year="' + $('#set_fy').val() + '" />';
	xml += '<r ';
	xml += 'p="' + $('#set_to').val() + '" ';
	xml += 'a="' + $('#set_hours').val() + '" ';
	xml += 'g="' + $('#set_group').val() + '" ';
	xml += 'x="' + $('#set_comment').val() + '" ';	
	xml += ' />';	
	var url =  gamma.pageContext.ops_url;
	var p = {};
	p.parameterList = xml;
	lambda.submitOperation(url, p);
}
</script>
