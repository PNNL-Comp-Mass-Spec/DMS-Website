<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<link rel="stylesheet" type="text/css" href="<?= base_url().'css/jquery-ui-1.8.21.custom.css' ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url().'SlickGrid/slick.grid.css' ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url().'SlickGrid/examples/slick-default-theme.css' ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url().'SlickGrid/plugins/slick.headerbuttons.css' ?>" />


<script type="text/javascript" src="<?= base_url().'javascript/jquery-1.7.2.min.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'javascript/jquery-ui-1.8.21.custom.min.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'javascript/jquery.event.drag-2.0.min.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'javascript/jquery.unobtrusive-ajax.min.js' ?>"></script>


<script type="text/javascript" src="<?= base_url().'SlickGrid/slick.core.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/plugins/slick.cellrangedecorator.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/plugins/slick.cellrangeselector.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/plugins/slick.cellselectionmodel.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/plugins/slick.headerbuttons.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/plugins/slick.autotooltips.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/slick.formatters.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/slick.editors.js' ?>"></script>
<script type="text/javascript" src="<?= base_url().'SlickGrid/slick.grid.js' ?>"></script>

<style>
	
.GridContainer {
    width: 1100px;
	min-height: 500px;
	font-size: 8pt;
	border-color:grey;
	border-width:thin;
	border-style:solid;
}


input.editor-text {
    width: 100%;
    height: 100%;
    border: 0;
    margin: 0;
    background: transparent;
    outline: 0;
    padding: 0;
}
	
</style>

</head>

<body>
<p>Howdy</p>

Instrument:<input id="instrument_fld" />
Year:<input id="year_fld""/>
Month:<input id="month_fld"" />
<input type="button" onclick="refreshGrid()" value="Reload" />
	
<div id="myGrid" class="GridContainer" ></div>
	
<script>
  	var grid;
	var dataRows = [];
	var columns = [
			{id: "Instrument", name: "Instrument", field: "Instrument"},
			{id: "Type", name: "Type", field: "Type"},
			{id: "Start", name: "Start", field: "Start"},
			{id: "Minutes", name: "Minutes", field: "Minutes"},
			{id: "Proposal", name: "Proposal", field: "Proposal", editor: Slick.Editors.Text},
			{id: "Usage", name: "Usage", field: "Usage", editor: Slick.Editors.Text},
			{id: "Users", name: "Users", field: "Users", editor: Slick.Editors.Text},
			{id: "Operator", name: "Operator", field: "Operator", editor: Slick.Editors.Text},
			{id: "Comment", name: "Comment", field: "Comment", editor: Slick.Editors.LongText},
			{id: "Year", name: "Year", field: "Year"},
			{id: "Month", name: "Month", field: "Month"},
			{id: "ID", name: "ID", field: "ID"},
			{id: "Seq", name: "Seq", field: "Seq"}
	];

	var options = {
            editable: true,
            enableAddRow: false,
            enableCellNavigation: true,
            asyncEditorLoading: false,
            autoEdit: true,
            enableColumnReorder: false
	};

	$(function () {
	    grid = new Slick.Grid("#myGrid", dataRows, columns, options);
	    setDefaults();
	    refreshGrid();
	});

 	function setDefaults() {
 		$('#instrument_fld').val('VOrbiETD01');
 		$('#year_fld').val('2012');
 		$('#month_fld').val('3');
	}
    
    function refreshGrid() {
        $.post(
            "<?= site_url() ?>agrid/get_usage_data",
            { instrument:'' },
            function (response) {
            	obj = $.parseJSON(response);
                if (obj.Result == "Error") {
                    alert(obj.Message);
                } else {
                    setDataRows(obj.Records);
                }
            }
        );
    }

     function setDataRows(data) {
        dataRows = data;
        grid.setData(dataRows);
        grid.updateRowCount();
        grid.render();
    }
 
</script>
	
</body>
</html>