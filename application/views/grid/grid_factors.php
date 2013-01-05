<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url().'SlickGrid/slick.grid.css' ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url().'SlickGrid/examples/slick-default-theme.css' ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url().'SlickGrid/plugins/slick.headerbuttons.css' ?>" />

<script  src="<?= base_url().'SlickGrid/slick.core.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/plugins/slick.cellrangedecorator.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/plugins/slick.cellrangeselector.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/plugins/slick.cellselectionmodel.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/plugins/slick.headerbuttons.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/plugins/slick.autotooltips.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/slick.formatters.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/slick.editors.js' ?>"></script>
<script  src="<?= base_url().'SlickGrid/slick.grid.js' ?>"></script>

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

.ctls {
	margin-right: 10px;
}

#ctl_panel {
	margin-bottom: 5px;
}
</style>

</head>

<body>
<div style='height:1em;'></div>

<div>Datasets</div>
<div>
<textarea name="itemList" cols="100" rows="5" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
</div>

<div id='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' href='javascript:void(0)' >Show</a> Factors For Datasets
</span>
<span id='col_ctls' class='ctls'>
	<a id='add_column_btn' href='javascript:void(0)' >Add</a> New Factor
	<input id='add_column_name' type='text' size="20"></input>
</span>

<span id='save_ctls' class='ctls'>
	<input id='save_btn' type='button' value='Save Changes' />
</span>
</div>

<div id="myTable" ></div>

	
<script>
gamma.pageContext.ops_url = '<?= site_url() ?>requested_run_factors/operation';

/*
 * autosize columns
 * detect changes to columns
 * mark changed cells/rows
 * get suitable list of changes for sending to sproc
 * add a 'delete column' feature
 * automatically remove columns not present in data array
 * 
 * adapt MIRA MiraGrid to DMSGrid
 * sorting
 */
	var gridUtil = {
		markChange: function(dataRow, field) {
			if(!dataRow.mod_axe) dataRow.mod_axe = {};
			dataRow.mod_axe[field] = dataRow[field];		
		},
		fillDown: function (e, args) {
			var column = args.column;
			var grid = args.grid;
			var dataRows = grid.getData();
			Slick.GlobalEditorLock.commitCurrentEdit();	
			var lastValueSeen = "";
			var rowsAffected = [];
			for (var i = 0; i < dataRows.length; i++) {
				var row = dataRows[i];
				var field = column.field;
				if (row[field]) {
					lastValueSeen = row[field];
				} else {
					row[field] = lastValueSeen;
					rowsAffected.push(i);
					gridUtil.markChange(row, field);
				}
			}
			grid.invalidateRows(rowsAffected);
			grid.render();
		},
		fillDownButton: [
			{ 
				command: "fill-down", 
				tooltip: "Fill empty cells below last non-empty cell", 
				cssClass: "fillDownBtn"
			}
		],
		saveChanges: function (dataRows, idField, action) {
			// extract list of change objects from dataRows
			var flist = [];
			$.each(dataRows, function(idx, row) {
				if(row.mod_axe) {
					var id = row[idField];
					$.each(row.mod_axe, function(k, v) {
						flist.push( {id:id, factor:k, value:v});
					});
				}
			});
			// get XML version of changes from list of change objects
			var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			var factorXML = gamma.getXmlElementsFromObjectArray(flist, 'r', mapPropertiesToAttributes);
			factorXML = '<id type="Dataset_Name" />' + factorXML;

			if ( !confirm("Are you sure that you want to update the database?") ) return;

			// update the database
			var url =  gamma.pageContext.ops_url;
			var p = { factorList: factorXML };
			gamma.doOperation(url, p, 'ctl_panel', function(data) {
				if(data.indexOf('was successful') !== -1) data = '';
				if(action) action(data);
			});
		}		
	}

	var mainGrid = {
		attachment:'myTable',
		hideColumns: ['Sel', 'BatchID'],
		staticColumns: ['Experiment','Dataset','Name','Status','Request'],
		container: null,
		grid: null,
		options: {
		        editable: true,
		        enableAddRow: false,
		        enableCellNavigation: true,
		        asyncEditorLoading: false,
		        autoEdit: true,
		        enableColumnReorder: false,
		        explicitInitialization: true
		},
		setDefaults: function () {
		},
		refreshGrid: function () {
			var caller = this;
			var cntr = $('#' + caller.attachment);
			cntr.spin('small');
		    $.post(
		        "<?= site_url() ?>agrid/factor_data",
		        { itemList:$('#itemList').val(),  itemType:'Dataset_Name'},
		        function (response) {
		        	cntr.spin(false);
		        	obj = $.parseJSON(response);
		            if (obj.Result == "error") {
		                alert(obj.message);
		            } else {
		                caller.setDataRows(obj);
		            }
		        }
		    );
		},
		cellChanged: function (e, args) {
			$('#save_ctls').show();
			var field = args.grid.getColumns()[args.cell].field;
			gridUtil.markChange(args.item, field);
		},
		initGrid: function(elementName) {
		    this.container.appendTo($("#" + elementName));
		    grid.init();
		},
		buildGrid: function () {
			if(this.grid) return true;
			var colDefs = this.buildColumns(this.staticColumns, false);
			this.container = $("<div id='myGrid' class='GridContainer'></div>");			
		    this.grid = new Slick.Grid(this.container, [], colDefs, this.options);
		    this.container.appendTo($('#' + this.attachment));
		    this.grid.init();
			this.grid.onCellChange.subscribe(this.cellChanged);
			var headerButtonsPlugin = new Slick.Plugins.HeaderButtons();
			headerButtonsPlugin.onCommand.subscribe(gridUtil.fillDown);
			this.grid.registerPlugin(headerButtonsPlugin);

		    return false;
		},
		buildColumns: function(colNames, editable) {
			var caller = this;
			var colSpecs = [];
			var colSpec;
			$.each(colNames, function(idx, colName) {
				if(caller.hideColumns.indexOf(colName) === -1) {
					colSpec = caller.makeColumnSpec(colName, editable)
					colSpecs.push(colSpec);
				}
			});
			return colSpecs;
		},
		// add new editable columns for given column names
		// that don't already have column defined
		adjustColumns: function(colNames) {
			var caller = this;
			var currentColumns = this.grid.getColumns();
			var curNames = $.map(currentColumns, function(col) {
				return col.field;
			});
			$.merge(curNames, this.hideColumns);
			var newColNames = [];
			$.each(colNames, function(idx, colName) {
				if(curNames.indexOf(colName) === -1) {
					currentColumns.push(caller.makeColumnSpec(colName, true));
				}
			});	
			this.grid.setColumns(currentColumns);
		},
		addColumn: function(colName) {
			if(!colName) {
				alert('Column name cannot be blank');
				return;
			}
			this.adjustColumns([colName]);
			this.setEmptyColumnInData(colName);
		},
		setEmptyColumnInData: function (colName) {
			$.each(this.grid.getData(), function(idx, dataRow) {
				dataRow[colName] = '';
			});			
		},
		makeColumnSpec: function(colName, editable) {
			colSpec = {
				id:colName,
				name:colName,
				field:colName
			};
			if(editable) {
				colSpec.editor = Slick.Editors.Text;
				colSpec.header = { buttons: gridUtil.fillDownButton };
			}
			return colSpec;
		},
		setDataRows: function (obj) {
			this.adjustColumns(obj.columns);
		    this.grid.setData(obj.rows);
		    this.grid.updateRowCount();
		    this.grid.render();
		},
		saveChanges: function () {
			var idField = 'Dataset';
			var dataRows = this.grid.getData();
			gridUtil.saveChanges(dataRows, idField);
		}
	} // mainGrid

	$(document).ready(function () { 
		$('#col_ctls').hide();
		$('#save_ctls').hide();

		$('#reload_btn').click(function() {
			mainGrid.refreshGrid();
			$('#col_ctls').show();
			$('#save_ctls').hide();
		});
		$('#add_column_btn').click(function() {
			var name = $('#add_column_name').val();
			mainGrid.addColumn(name);
		});
		$('#save_btn').click(function() {
			var idField = 'Dataset';
			var dataRows = mainGrid.grid.getData();
			gridUtil.saveChanges(dataRows, idField, function(data) {
				if(data) {
					alert(data);
				} else {
					$('#reload_btn').click();
				}
			});
		});
		
	    mainGrid.buildGrid();
	});

</script>
	
</body>
</html>