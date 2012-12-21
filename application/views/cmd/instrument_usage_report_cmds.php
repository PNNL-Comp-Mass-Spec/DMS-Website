
<script type="text/javascript" src="<?= base_url().'javascript/factors.js' ?>"></script>

<script type="text/javascript">

function parseUploadText(text_fld) {
	parsed_data = {};
	var lines = $('#' + text_fld).val().split('\n');
	var header = [];
	var data = [];
	lines.each(function(idx, line, lineNumber){
		line = gamma.trim(line);
		if(line) {	
			var fields = parse_lines(line)
			if(lineNumber == 0) {
				header = fields;
			} else {
				data.push(fields); // check length of fields?
			}
		}
	});
	// get rid of goofy parsing artifact last row
	if(!(data[data.length - 1])[0]) {
		data.pop();
	}
	parsed_data.header = header;
	parsed_data.data = data;
	return parsed_data;
}
function updateDatabaseFromList(flist, id_type) {
	if ( !confirm("Are you sure that you want to update the database?") ) return;
	var factorXML = getFactorXMLFromList(flist);
	if(id_type) {
		factorXML = '<id type="' + id_type + '" />' + factorXML;
	}
	var url =  "<?= $ops_url ?>";
	var p = {};
	p.factorList = factorXML;
	p.operation = 'update';
	submitOperation(url, p);
}
function load_delimited_text() {
	var parsed_data = parseUploadText('delimited_text_input');
	var id_type = parsed_data.header[0];
	var col_list = parsed_data.header.without(id_type);
	var flist = getFieldListFromParsedData(parsed_data, col_list);
	updateDatabaseFromList(flist, id_type);
}
function reloadReport(operation) {
	var url =  "<?= $ops_url ?>";
	var p = {};
	p.factorList = '';
	p.operation = operation;
	p.year = $('#pf_year').val();
	p.month = $('#pf_month').val();
	p.instrument = $('#pf_instrument').val();
	submitOperation(url, p);
}
function refresh_report() {
	if ( !confirm("Are you sure that you want to refresh the exiting report") ) return;
	reloadReport('refresh');
}
function reload_report() {
	if ( !confirm("Are you sure that you want to clear the existing report and reload?") ) return;
	reloadReport('reload');
}
</script>


<div class="LRCmds">
<?php $this->load->view("main/list_report_cmd_reporting"); ?>

<form name="DBG" action="">

<hr>
<a href="#" onclick="gamma.sectionToggle('reload_section', 0.5)">Reload commands...</a>
<div id="reload_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Refresh" onClick='refresh_report()' title="Refresh EMSL usage report from DMS usage tracking"  /> Refresh EMSL instrument report from DMS
</div>
<div>
<input class='lst_cmd_btn' type="button" value="Reload" onClick='reload_report()' title="Reload EMSL usage report from DMS usage tracking"  /> Reload EMSL instrument report from DMS (wipe current contents)
</div>
</div>

<hr>
<a href="#" onclick="gamma.sectionToggle('upload_section', 0.5)">Upload commands...</a>
<div id="upload_section" style="display:none;">
<div>
<input class='lst_cmd_btn' type="button" value="Update from list" onClick='load_delimited_text()' title="Test"  /> Update database from delimited list
</div>
<div>
<p>Delimited text input:</p>
<textarea id='delimited_text_input' rows='12' cols='90'></textarea>
</div>
</div>
<hr>

</form>
</div>

