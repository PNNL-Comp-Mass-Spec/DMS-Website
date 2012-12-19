<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Test</title>

<style type="text/css">
	.labelling {
		padding:5px 0 3px 0;
		font-weight:bold;
	}
</style>

<? $this->load->view('resource_links/base2') ?>

<script type='text/javascript'>

globalAJAX = {};
globalAJAX.site_url = '<?= site_url() ?>';

// AJAX call to get data from predefined query in DMS (specified by config_source and config_name)
// applying secondary filter as defined in filter_string
// and deliver in format specified by output_format
function get_data(output_format, config_source, config_name, filter_string, sorting_string) {
	var url = globalAJAX.site_url + 'data/lz/' + output_format + '/' + config_source + '/' + config_name;

	var container = $('#wall');
	container.val('working...');
	p = {};
	convert_filter_string_to_post_params(filter_string, p);
	convert_sorting_string_to_post_params(sorting_string, p);
	$.post(url, p, function (data) {
		    container.val(data);
		}
	);
}
// converts a filter string which is has delimited fields
// in a format similar to list report secondary filter
// into the actual set of parameters that DMS expects to be in the POST header
// and adds them to the parameter obj p which is supplied by the calling funtion
function convert_filter_string_to_post_params(filter_string, p) {
	p["qf_rel_sel[]"] = [];
	p["qf_col_sel[]"] = [];
	p["qf_comp_sel[]"] = [];
	p["qf_comp_val[]"] = [];
	$A(filter_string.split(';')).each(
			function(obj) {
				var flds = obj.split('/');
				p["qf_rel_sel[]"].push(flds[0]);
				p["qf_col_sel[]"].push(flds[1]);
				p["qf_comp_sel[]"].push(flds[2]);
				p["qf_comp_val[]"].push(flds[3]);
			}
		);
}
//converts a sorting string which is has delimited fields
//in a format similar to the list report sorting filter
//into the actual set of parameters that DMS expects to be in the POST header
//and adds them to the parameter obj p which is supplied by the calling funtion
function convert_sorting_string_to_post_params(sorting_string, p) {
	p["qf_sort_col[]"] = [];
	p["qf_sort_dir[]"] = [];
	$A(sorting_string.split(';')).each(
			function(obj) {
				var flds = obj.split('/');
				p["qf_sort_col[]"].push(flds[0]);
				p["qf_sort_dir[]"].push(flds[1]);
			}
		);
}
// just a mule to call the money function...
function call_get_data() {
	// Collect underpants
	var output_format = $('#output_format_ctl').val();
	var config_source = $('#config_source_ctl').val();
	var config_name = $('#config_name_ctl').val();
	var filter_string = $('#secondary_filter_ctl').val();
	var sorting_string = $('#sorting_ctl').val();
	// ??
	// Profit!
	get_data(output_format, config_source, config_name, filter_string, sorting_string);
}
</script>
</head>

<body>
<h2>Testing</h2>

<form name="frmReport" id="filter_form" action="#">
<div class='labelling' >Config Source</div>
<input id='config_source_ctl' size='60' value='ad_hoc_query' />
<div class='labelling' >Config Name</div>
<input id='config_name_ctl' size='60' value='lcms_requested_run' />
<div class='labelling' >Output Format</div>
<div><select id='output_format_ctl'><option>sql</option> <option>count</option> <option>json</option> <option>tsv</option><option>xml_dataset</option><option>columns</option></select></div>
<div class='labelling' >Filter</div>
<div><textarea id='secondary_filter_ctl' cols='100' rows='4' >AND/Request/GreaterThanOrEqualTo/131145;AND/Request/LessThanOrEqualTo/131150</textarea></div>
<div class='labelling' >Sorting</div>
<div><input id='sorting_ctl' size='100' value='Request/DESC;Name/ASC'/></div>
</form>

<div>
<input type='button' value='Test' onclick='call_get_data()'/>
</div>
<textarea id='wall' cols='100' rows='30' ></textarea>
</body>
</html>