<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<? $this->load->view('resource_links/base2css') ?>
<? $this->load->view('resource_links/slickgrid2css') ?>

<? $chimg = base_url()."images/chooser.png"; ?>

<style>
    .slick-header-menu {
      border: 1px solid #718BB7;
      background: #f0f0f0;
      padding: 2px;
      -moz-box-shadow: 2px 2px 2px silver;
      -webkit-box-shadow: 2px 2px 2px silver;
      min-width: 100px;
      z-index: 20;
    }
    .slick-header-menuitem {
      padding: 2px 4px;
      border: 1px solid transparent;
      border-radius: 3px;
    }
    .slick-header-menuitem:hover {
      border-color: silver;
      background: white;
    }
    .slick-header-menuitem-disabled {
      border-color: transparent !important;
      background: inherit !important;
    }
	.slick-header-menubutton {
	  display: inline-block;
	}    
</style>

<style>
	.GridContainer {
	    width: 99%;
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
	.ctl_panel {
		margin-top:5px;
		margin-bottom: 5px;
	}
	.nonEditable {
		color:blue;
	}
</style>

</head>

<body>
<? $this->load->view('nav_bar') ?>

<div style='height:1em;'></div>
<div>Requests</div>
<div>
<textarea name="itemList" cols="100" rows="5" id="itemList" onchange="epsilon.convertList('itemList', ',')" ></textarea>
<span>
Requests... <a href="javascript:epsilon.callChooser('itemList', '<?= site_url() ?>helper_requested_run_ckbx/report', ',', '')"><img src='<?= $chimg ?>' border='0'></a>
<span>
</div>

<div id='ctl_panel' class='ctl_panel'>
<span class='ctls'>
	<a id='reload_btn' href='javascript:void(0)' >Show</a> info for requests
</span>
<span class='ctls' style='display:none;'>
	<a id='add_column_btn' href='javascript:void(0)' >Add</a> New Factor
</span>
<span class='ctls' style='display:none;'>
	<input id='add_column_name' type='text' size="20"></input>
</span>

<span id='save_ctls' class='ctls'>
	<input id='save_btn' type='button' value='Save Changes' />
</span>
</div>

<div id="myTable" ></div>

<div class='ctl_panel'>
<a id='delimited_text_panel_btn' href='javascript:void(0)' >Delimited Text</a>
</div>
<div id='delimited_text_panel' class='ctl_panel'>
<div class='ctl_panel'>
<span class='ctls'>
	<a id='import_grid_btn' href='javascript:void(0)' >Import</a> grid contents from delimited text
</span>
<span class='ctls'>
	<a id='export_grid_btn' href='javascript:void(0)' >Export</a> grid contents to delimited text
</span>
</div>

<div>
<textarea id="delimited_text" name="delimited_text" cols="100" rows="5" ></textarea>
</div>
</div>

<? $this->load->view('resource_links/base2js') ?>
<? $this->load->view('resource_links/slickgrid2js') ?>

<script>
	gamma.pageContext.ops_url = '<?= site_url() ?>xxx/operation';
	gamma.pageContext.data_url = '<?= site_url() .  $this->my_tag ?>/grid_data';

	var gridUtil = {
		markChange: function(dataRow, field, clear) {
			if(!dataRow.mod_axe) dataRow.mod_axe = {};
			dataRow.mod_axe[field] = dataRow[field];		
		},
		fillDown: function (column, grid, clear) {
			var dataRows = grid.getData();
			Slick.GlobalEditorLock.commitCurrentEdit();	
			var lastValueSeen = "";
			var rowsAffected = [];
			for (var i = 0; i < dataRows.length; i++) {
				var row = dataRows[i];
				var field = column.field;
				if (row[field]) {
					if(clear) {
						row[field] = '';
						rowsAffected.push(i);
						gridUtil.markChange(row, field);
					} else {
						lastValueSeen = row[field];
					}
				} else {
					if(!clear) {
						row[field] = lastValueSeen;
						rowsAffected.push(i);
						gridUtil.markChange(row, field);
					}
				}
			}
			grid.invalidateRows(rowsAffected);
			grid.render();
			$('#save_ctls').show();
		},
		saveChanges: function (dataRows, idField, action, mapP2A) {
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
			var factorXML = gamma.getXmlElementsFromObjectArray(flist, 'r', mapP2A);
			factorXML = '<id type="Request_ID" />' + factorXML;

			if ( !confirm("Are you sure that you want to update the database?") ) return;

			// update the database
			var url =  gamma.pageContext.ops_url;
			var p = { factorList: factorXML };
			gamma.doOperation(url, p, 'ctl_panel', function(data) {
				if(data.indexOf('was successful') !== -1) data = '';
				if(action) action(data);
			});
		},
		refreshGrid: function (url, itemList, caller) {
			var cntr = $('#' + caller.attachment);
			cntr.spin('small');
		    $.post(
		        url,
		        { itemList:itemList },
		        function (response) {
		        	cntr.spin(false);
		        	obj = $.parseJSON(response);
		            if (obj.Result == "error") {
		                alert(obj.message);
		            } else {
		                caller.setDataRows(obj, true);
		            }
		        }
		    );
		},
		sortByColumn: function (column, grid, sortAsc) {
			var field = column.field;
			grid.getData().sort(function (a, b) {
				var af = a[field] || '';
				var bf = b[field] || '';
				if (column.datesort) {
					af = (af) ? Date.parse(af) : 0;
					bf = (bf) ? Date.parse(bf) : 0;
				}
				var result = af > bf ? 1 : af < bf ? -1 : 0;
				return sortAsc ? result : -result;
			});
			grid.invalidate();
			grid.render();
		},
		// set width property of given column specs according to 
		// size of data in given data rows, and return updated column specs
		sizeColumnsToData: function (currentColumns, dataRows) {
			var textWidthPixels = 8;
			var maxChars, dataChars, val;
			var minChars = 10;
			$.each(currentColumns, function(idx, colSpec) {
				maxChars = minChars;
				if(colSpec.field.length > maxChars) maxChars = colSpec.field.length 
				$.each(dataRows, function(i, dataRow) {
					val = dataRow[colSpec.field];
					if(val) {
						dataChars = val.length;
						if(dataChars > maxChars) maxChars = dataChars;
					}
				});
				colSpec.width = maxChars * textWidthPixels;
			});			
			return currentColumns;
		},
		// convert simple data to format suitable for grid
		convertToGridData: function (rawCols, rawData) {
			var gridData = {
				columns: rawCols,
				rows: []
			};
			var rowObj;
			$.each(rawData, function(idx, row) {
				rowObj = {};
				$.each(row, function(i, fld) {
					rowObj[gridData.columns[i]] = fld;
				});
				gridData.rows.push(rowObj);
			});
			return gridData;
		},
		convertToDelimitedText: function(currentColumns, dataRows) {
			var s = '';
			// header row
			var cols, fields;
			cols = $.map(currentColumns, function(colSpec) {
				return colSpec.field;
			});
			s += cols.join("\t") + "\n";		
			// data rows
			$.each(dataRows, function(rowNum, dataRow) {
				fields = $.map(currentColumns, function(colSpec) {
					return dataRow[colSpec.field];
				});
				s += fields.join("\t") + "\n";
			});
			return s;
		}
	} // gridUtil
	
	var gridHeaderUtil = {
		headerButtons: null, // in case we someday have any header buttons
		buttonCmdHandler: null, // in case we someday have any header buttons
		baseMenuItems: [
			{ title: "Sort Ascending", command: "sort-asc" },
			{ title: "Sort Descending", command: "sort-desc" }
		],
		editMenuItems: [
			{ title: "Fill Down", command: "filldown", tooltip: "Fill empty cells in this column with preceding non-empty values" },
			{ title: "Clear all", command: "clear", tooltip: "Clear all values in this column" }
		],
		menuCmdHandler: function (e, args) {
			if(args.command == 'filldown') {
				gridUtil.fillDown(args.column, args.grid);
			} else
			if(args.command == 'clear') {
				gridUtil.fillDown(args.column, args.grid, true);				
			} else
			if(args.command == 'sort-asc') {
				gridUtil.sortByColumn(args.column, args.grid, true);
			} else
			if(args.command == 'sort-desc') {
				gridUtil.sortByColumn(args.column, args.grid, false);
			}
		}
	} // gridHeaderUtil

	var mainGrid = {
		attachment:'myTable',
		hideColumns: [],
		staticColumns: ['Request', 'Name', 'Status', 'BatchID', 'Instrument', 'Separation_Type', 'Experiment'],
		container: null,
		grid: null,
		options: {
		        editable: true,
		        enableAddRow: false,
		        enableCellNavigation: true,
		        asyncEditorLoading: false,
		        autoHeight: true,
		        autoEdit: true,
		        enableColumnReorder: false,
		        explicitInitialization: true
		},
		setDefaults: function () {
		},
		refreshGrid: function () {
			var itemList = $('#itemList').val();
			gridUtil.refreshGrid(gamma.pageContext.data_url, itemList, this);
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
			var colDefs = this.buildColumns(this.staticColumns, false);
			this.container = $("<div id='myGrid' class='GridContainer'></div>");			
		    this.grid = new Slick.Grid(this.container, [], colDefs, this.options);
		    this.container.appendTo($('#' + this.attachment));
		    this.grid.init();
			this.grid.onCellChange.subscribe(this.cellChanged);
			var headerMenuPlugin = new Slick.Plugins.HeaderMenu({});
			headerMenuPlugin.onCommand.subscribe(gridHeaderUtil.menuCmdHandler);
			this.grid.registerPlugin(headerMenuPlugin);
		},
		buildColumns: function(colNames, editable) {
			var caller = this;
			var colSpecs = [];
			var colSpec;
			$.each(colNames, function(idx, colName) {
				if(caller.hideColumns.length == 0 || caller.hideColumns.indexOf(colName) === -1) {
					colSpec = caller.makeColumnSpec(colName, editable)
					colSpecs.push(colSpec);
				}
			});
			return colSpecs;
		},
		// add new editable columns for given column names
		// that don't already have column defined
		adjustColumns: function(colNames, reset) {
			var caller = this;
			var currentColumns = (reset) ? this.buildColumns(this.staticColumns, false) : this.grid.getColumns();
			var curNames = $.map(currentColumns, function(col) {
				return col.field;
			});
			$.merge(curNames, this.hideColumns);
			var newColNames = [];
			$.each(colNames, function(idx, colName) {
				var cl = curNames.length;
				var ix = curNames.indexOf(colName);
				if(curNames.length == 0 || curNames.indexOf(colName) === -1) {
					currentColumns.push(caller.makeColumnSpec(colName, true));
				}
			});	
			this.grid.setColumns(currentColumns);
		},
		sizeColumnsToData: function (dataRows) {
			var currentColumns = this.grid.getColumns();
			currentColumns = gridUtil.sizeColumnsToData(currentColumns, dataRows);
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
			var menuItems = $.merge([], gridHeaderUtil.baseMenuItems);
			if(editable) {
				colSpec.editor = Slick.Editors.Text;
				menuItems = $.merge(menuItems, gridHeaderUtil.editMenuItems)
			} else {
				colSpec.cssClass = 'nonEditable';
			}
			colSpec.header = { menu: { items: menuItems } };
			return colSpec;
		},
		setDataRows: function (obj, reset) {
			this.adjustColumns(obj.columns, reset);
			this.sizeColumnsToData(obj.rows);
		    this.grid.setData(obj.rows);
		    this.grid.updateRowCount();
		    this.grid.render();
		},
		exportDelimitedData: function() {
			var s = gridUtil.convertToDelimitedText(this.grid.getColumns(), this.grid.getData());
			$('#delimited_text').val(s);
		},
		importDelimitedData: function() {
			var parsed_data = gamma.parseDelimitedText('delimited_text');
			var gridData = gridUtil.convertToGridData(parsed_data.header, parsed_data.data);
			mainGrid.setDataRows(gridData);
		},
		clearGrid: function () {
			this.setDataRows({columns:[],rows:[]}, true);			
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
			alert("Not connected yet"); return;
			var idField = 'Request';
			var dataRows = mainGrid.grid.getData();
			var mapP2A = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
			gridUtil.saveChanges(dataRows, idField, function(data) {
				if(data) {
					alert(data);
				} else {
					$('#reload_btn').click();
				}
			});
		});
		
		$('#delimited_text_panel').hide();
		$('#delimited_text_panel_btn').click(function() {
			$('#delimited_text_panel').toggle();		
		});
		$('#import_grid_btn').click(function() {
			mainGrid.importDelimitedData();
			var x = $.map(mainGrid.grid.getData(), function(row) {return row['Request']; });
			$('#itemList').val(x.join(', '));
			$('#save_ctls').show();
		});
		$('#export_grid_btn').click(function() {
			mainGrid.exportDelimitedData();
		});
		
	    mainGrid.buildGrid();
	});

</script>
	
</body>
</html>