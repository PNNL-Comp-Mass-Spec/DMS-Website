|<span><a href='javascript:download_to_graph()'>Graph</a></span>|
<h3><span id="caption_container"></span></h3>
<div id="graph_container" style="width:1100px;height:800px;display:none"></div>


<link rel="stylesheet" type="text/css" href="<?= base_url().'flot/examples/layout.css' ?>" />

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?= base_url().'flot/excanvas.min.js' ?>"></script><![endif]-->
<script src="<?= base_url().'flot/jquery.flot.js' ?>"></script>

<script type='text/javascript'>

// get data rows for list report via an AJAX call
// using all the current search filters
// and convert JSON return to JavaScript array of row objects
function download_to_graph() {
	var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/export_param/json'
	var p = $('#entry_form').serialize();
	gamma.getObjectFromJSON(url, p, 'graph_container', function(rows) {
			draw_graph(rows);
	}); 
}

//build data series from rows, set graph format, and draw graph
function draw_graph(rows) {
	// set caption
	var caption = "Dataset Acquisition/Interval Time For " + $('#instrumentName').val() + " From " + $('#startDate').val() + " To " + $('#endDate').val()
	$('#caption_container').html(caption);
	
	// build data series set from rows
	var dataSeriesSet = make_data_series_from_column(rows, "Duration") ;

	// set up graph formatting
	var graphFormatting = set_graph_format();

	// make plotting container visible and draw graph in it
	$('#graph_container').show();
    var f = Flot.draw($('#graph_container'), dataSeriesSet, graphFormatting);
}

// build data series set from given column data from rows
function make_data_series_from_column(rows, colName) {
	var intervalSeries = [];
	var acquistionSeries = [];
	var index = 0;
	rows.each(
		function(obj) {
			var val = obj[colName];
			if(obj["Seq"] > 0) {
				if(obj["Dataset"] == "Interval") {
					var item = [];
					item.push(index++);
					item.push(val);
					intervalSeries.push(item);
				} else {
					var item = [];
					item.push(index++);
					item.push(val);
					acquistionSeries.push(item);
				}
			}
		}
	);
	return [
		{
			label: "Acquisition Time",
			color: '#0000ff',
			data: acquistionSeries
		},
		{
			label: "Interval Time",
			color: '#ff0000',
			data: intervalSeries
		}
	];
}

// define graph format
function set_graph_format() {
	return {
		yaxis: {
			min: 0
		},
		bars: {
			show:true, 
			barWidth:0.5
		}
	};
}
</script>
