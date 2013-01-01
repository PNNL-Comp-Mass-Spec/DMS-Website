<!--[if IE]><script language="javascript" type="text/javascript" src="<?= base_url().'charting/lib/excanvas.js' ?>"></script><![endif]-->
<script src="<?= base_url().'charting/flotr-0.2.0-alpha.js' ?>"></script>


<script type='text/javascript'>

// get data rows for list report via an AJAX call
// using all the current search filters
// and convert JSON return to JavaScript array of row objects
function download_to_graph() {
	var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/export/json'
	var p = $('#filter_form').serialize();
	gamma.getObjectFromJSON(url, p, 'graph_container', function(rows) {
		draw_graph(rows);
	}); 
}

// build data series from rows, set graph format, and draw graph
function draw_graph(rows) {
	// ask user for name of column to graph (must be exact match)
	var colName = prompt("Column to graph", "File Size MB");
	if (colName == null || colName == "") {
		return;
	}

	// build data series set from rows
	var dataSeriesSet = make_data_series_from_column(rows, colName) ;

	// set up graph formatting
	var graphFormatting = set_graph_format();

	// make plotting container visible and draw graph in it
	$('#graph_container').show();
    var f = Flotr.draw($('#graph_container'), dataSeriesSet, graphFormatting);
}

// build data series set from given column data from rows
function make_data_series_from_column(rows, colName) {
	var data = [];
	var index = 0;
	rows.each(
		function(obj) {
			var item = [];
			item.push(index++);
			item.push(obj[colName]);
			data.push(item);
		}
	);
	return [ data ];
}

// define graph format
function set_graph_format() {
	return {
		yaxis: {
			min: 0
		},
		lines: {
			show: true,		// => setting to true will show lines, false will hide
			lineWidth: 2, 		// => line width in pixels
			fill: false,		// => true to fill the area from the line to the x axis, false for (transparent) no fill
			fillColor: null		// => fill color
		},
		points: {
			show: true,		// => setting to true will show points, false will hide
			radius: 3,		// => point radius (pixels)
			fill: true,		// => true to fill the points with a color, false for (transparent) no fill
			fillColor: '#ffffff'	// => fill color
		}
	};
}

</script>

|<span><a href='javascript:download_to_graph()'>Graph</a></span>|

<div id="graph_container" style="width:1100px;height:800px;display:none"></div>
