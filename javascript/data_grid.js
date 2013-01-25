/*
 * utility functions shared by all grid instances
 */
var gridUtil = {
	markChange: function(dataRow, field, clear) {
		if(!dataRow.mod_axe) dataRow.mod_axe = {};
		dataRow.mod_axe[field] = dataRow[field];		
	},
	fillDown: function (column, grid) {
		Slick.GlobalEditorLock.commitCurrentEdit();	
		var row, field, val;
		var dataRows = grid.getData();
		var sel = {fromRow:0, toRow:dataRows.length - 1};
		var colIndex = grid.getColumnIndex(column.name);
		var ranges = grid.getSelectionModel().getSelectedRanges();
		var range = ranges[0];
		if(range && range.fromRow != range.toRow && colIndex >= range.fromCell && colIndex <= range.toCell) {
				sel = range;
		} 
		var lastValueSeen = "";
		var rowsAffected = [];
		for (var i = sel.fromRow; i <= sel.toRow; i++) {
			row = dataRows[i];
			field = column.field;
			val = row[field];
			if (val == null || val === '') {
				if(row[field] != lastValueSeen) {
					row[field] = lastValueSeen;
					rowsAffected.push(i);
					gridUtil.markChange(row, field);
				}
			} else {
				lastValueSeen = row[field];
			}
		}
		grid.invalidateRows(rowsAffected);
		gridUtil.setChangeHighlighting(grid);
		grid.render();
	},
	clearAllCells: function(column, grid) {
		this.clearSelectedCells(column, grid, true);
	},
	clearSelectedCells: function (column, grid, all) {
		Slick.GlobalEditorLock.commitCurrentEdit();	
		var dataRows = grid.getData();
		var sel = {fromRow:0, toRow:dataRows.length - 1};
		var colIndex = grid.getColumnIndex(column.name);
		var range = grid.getSelectionModel().getSelectedRanges()[0];
		if(!all && range && colIndex >= range.fromCell && colIndex <= range.toCell) {
			sel = range;
			if (!confirm('Are you sure you want to clear the selected cells in this column?')) return;
		} else {
			if (!confirm('Are you sure you want to clear all the cells in this column?')) return;
		}
		var rowsAffected = [];
		for (var row = sel.fromRow; row <= sel.toRow; row++) {
			var val = dataRows[row][column.field];
			if (!(val == null || val === '')) {
				dataRows[row][column.field] = '';
				gridUtil.markChange(dataRows[row], column.field);
				rowsAffected.push(row);
			}
		}
		grid.invalidateRows(rowsAffected);
		gridUtil.setChangeHighlighting(grid);
		grid.render();
	},
	// extract list of change objects from dataRows
	getChanges: function(dataRows, idField) {
		var changes = [];
		$.each(dataRows, function(idx, row) {
			if(row.mod_axe) {
				var id = row[idField];
				$.each(row.mod_axe, function(k, v) {
					changes.push( {id:id, factor:k, value:v});
				});
			}
		});
		return changes;
	},
	hasChanged: function(dataRows) {
		var changed = false;
		$.each(dataRows, function(idx, row) {
			if(row.mod_axe) {
				changed = true;
			}
			return !changed;
		});
		return changed;
	},
	setChangeHighlighting: function(grid) {
		var styledCells = this.getChangeHighlighting(grid.getData(), 'changed');
		grid.setCellCssStyles("highlight", styledCells);
	},
	getChangeHighlighting: function(dataRows, styleClass) {
		var styledCells = {};
		$.each(dataRows, function(idx, row) {
			if(row.mod_axe) {
				$.each(row.mod_axe, function(k, v) {
					if (!styledCells[idx]) styledCells[idx] = {};
					styledCells[idx][k] = styleClass;
				});
			}
		});
		return styledCells;		
	},
	saveChanges: function (url, p, caller) {
		if ( !confirm("Are you sure that you want to update the database?") ) return;
		if(caller.beforeSaveAction) caller.beforeSaveAction();
		gamma.doOperation(url, p, 'ctl_panel', function(data) {
			if(data.indexOf('was successful') !== -1) {
				if(caller.afterSaveAction) caller.afterSaveAction(data);
			} else {
				alert(data);
			}
		});
	},
	refreshGrid: function (url, p, caller) {
		var cntr = $('#' + caller.externalContainerId);
		cntr.spin('small');
		if(caller.beforeLoadAction) caller.beforeLoadAction();
	    $.post(
	        url, p, function (response) {
	        	cntr.spin(false);
	        	obj = $.parseJSON(response);
	            if (obj.result == "error") {
	                alert(obj.message);
	            } else {
	                caller.setDataRows(obj, true);
	                if(caller.afterLoadAction) caller.afterLoadAction();
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
		gridUtil.setChangeHighlighting(grid);
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

/*
 * general grid header behaviors that are meant 
 * to extend a specific header object
 */
var gridHeaderUtil = {
	baseMenuItems: [
		{ title: "Sort Ascending", command: "sort-asc" },
		{ title: "Sort Descending", command: "sort-desc" }
	],
	editMenuItems: [
		{ title: "Fill Down", command: "filldown", tooltip: "Fill empty cells in this column with preceding non-empty values" },
		{ title: "Clear selected", command: "clear-selected", tooltip: "Clear all values in selected cells" },
		{ title: "Clear all", command: "clear-all", tooltip: "Clear all values in this column" }
	],
	commands: {
		'filldown': function (column, grid) {
			gridUtil.fillDown(column, grid);
			return true;
		},
		'clear-all': function (column, grid) {
			gridUtil.clearAllCells(column, grid);				
			return true;
		},
		'clear-selected': function (column, grid) {
			gridUtil.clearSelectedCells(column, grid);				
			return true;
		},
		'sort-asc': function (column, grid) {
			gridUtil.sortByColumn(column, grid, true);
			return false;
		},
		'sort-desc': function (column, grid) {
			gridUtil.sortByColumn(column, grid, false);
			return false;
		}
	},
	getMenuCmdHandler: function(changeHandler) {
		var ch = changeHandler;
		var context = this;
		return function(e, args) {
			var cmdFunc = context.commands[args.command];
			if(cmdFunc) {
				var possibleChanges = cmdFunc(args.column, args.grid);
				if(possibleChanges && ch && gridUtil.hasChanged(args.grid.getData())) ch();
			}
		}
	}
} // gridHeaderUtil

/*
 * grid behaviors that are meant to extend a specific instance of an object.
 * certain properties and functions must or can be overridden by subsequent 
 * extension by a configuration object
 */
var mainGrid = {
	// the following properties MUST be overridden
	getLoadParameters: null,
	getSaveParameters: null,
	//
	// the following properties MAY be overridden
	headerUtil: null,
	externalContainerId:'myTable', // existing page tag that grid will occupy
	internalContainerId: "myGrid", // name of page element that is generated by grid
	handleDataChanged: null, // optional external method called when data is changed
	hiddenColumns: [],
	staticColumns: [],
	container: null, // generated page element that contains grid
	options: { // SlickGrid options
	        editable: true,
	        enableAddRow: true,
	        enableCellNavigation: true,
	        asyncEditorLoading: false,
	        autoHeight: true,
	        autoEdit: false,
	        enableColumnReorder: false,
	        explicitInitialization: true
	},
	getLoadUrl: function() {
		return gamma.pageContext.data_url;
	},
	getSaveUrl: function() {
		return gamma.pageContext.save_changes_url;
	},
	beforeLoadAction: null,
	afterLoadAction: null,
	beforeSaveAction: null,
	afterSaveAction: null,
	columnMenuHook: null,
	//
	// the following properties SHOULD NOT be overridden
	grid: null,
	pendingOp: false,
	loadGrid: function () {
		var url = this.getLoadUrl();
		var p = this.getLoadParameters();
		gridUtil.refreshGrid(url, p,  this);
	},
	saveGrid: function() {
		var url = this.getSaveUrl();
		var p = this.getSaveParameters();
		gridUtil.saveChanges(url, p, this);
	},
	getCellChangeHandler: function() {
		var context = this;
		return function (e, args) {
			var field = args.grid.getColumns()[args.cell].field;
			gridUtil.markChange(args.item, field);
			gridUtil.setChangeHighlighting(args.grid);
			if(context.handleDataChanged) context.handleDataChanged();
		}
	},
	initGrid: function(elementName) {
	    this.container.appendTo($("#" + elementName));
	    grid.init();
	},
	buildGrid: function () {
		if(this.grid) return;
		if(!this.headerUtil) {
			this.headerUtil = $.extend({}, gridHeaderUtil);
		}
		var colDefs = this.buildColumns(this.staticColumns, false);
		this.container = $("<div id='" + this.internalContainerId + "' class='GridContainer'></div>");			
	    this.grid = new Slick.Grid(this.container, [], colDefs, this.options);
	    this.container.appendTo($('#' + this.externalContainerId));
	    this.grid.init();
		this.grid.onCellChange.subscribe(this.getCellChangeHandler());
		var headerMenuPlugin = new Slick.Plugins.HeaderMenu({});
		headerMenuPlugin.onCommand.subscribe(this.headerUtil.getMenuCmdHandler(this.handleDataChanged));
		if(this.columnMenuHook) {
			headerMenuPlugin.onBeforeMenuShow.subscribe(this.columnMenuHook);			
		}
		this.grid.registerPlugin(headerMenuPlugin);
		this.grid.setSelectionModel(new Slick.CellSelectionModel());
	},
	buildColumns: function(colNames, editable) {
		var caller = this;
		var colSpecs = [];
		var colSpec;
		$.each(colNames, function(idx, colName) {
			if(caller.hiddenColumns.length == 0 || caller.hiddenColumns.indexOf(colName) === -1) {
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
		$.merge(curNames, this.hiddenColumns);
		var newColNames = [];
		$.each(colNames, function(idx, colName) {
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
		var menuItems = $.merge([], this.headerUtil.baseMenuItems);
		if(editable) {
			colSpec.editor = Slick.Editors.Text;
			menuItems = $.merge(menuItems, this.headerUtil.editMenuItems)
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
		gridUtil.setChangeHighlighting(this.grid);
	    this.grid.render();
	},
	clearGrid: function () {
		this.setDataRows({columns:[],rows:[]}, true);			
	}

} // mainGrid

/*
 * these behaviors are tied to the shared "delimited text import/export" panel
 */
var gridImportExport = {
	preImportAction: null,
	postImportAction: null,
	preExportAction: null,
	postExportAction: null,
	myMainGrid: null,
	init: function(wrapper) {
		var context = this;
		this.myMainGrid = (wrapper) ? wrapper : this.myMainGrid;	
		$('#delimited_text_panel').hide();
		$('#delimited_text_panel_btn').click(function() {
			$('#delimited_text_panel').toggle();		
			$('#delimited_expd_ctl').toggleClass('ui-icon-circle-plus ui-icon-circle-minus');
		});
		$('#export_grid_btn').click(function() {
			context.exportDelimitedData(context);
		});
		$('#import_grid_btn').click(function() {
		    context.myMainGrid.buildGrid();
			context.importDelimitedData(context);
		});
		$('#update_grid_btn').click(function() {
			context.updateFromDelimitedData(context);
		});
	},
	exportDelimitedData: function(context) {
		if(!context.myMainGrid) return;
		if(context.preExportAction) context.preExportAction();
		var s = gridUtil.convertToDelimitedText(context.myMainGrid.grid.getColumns(), context.myMainGrid.grid.getData());
		$('#delimited_text').val(s);
		if(context.postExportAction) context.postExportAction();
	},
	importDelimitedData: function(context) {
		if(!context.myMainGrid) return;
		var parsed_data = gamma.parseDelimitedText('delimited_text');
		var gridData = gridUtil.convertToGridData(parsed_data.header, parsed_data.data);
		if(context.preImportAction) context.preImportAction();
		context.myMainGrid.setDataRows(gridData, true);
		if(context.postImportAction) context.postImportAction();
	},
	updateFromDelimitedData: function(context) {
		if(!context.myMainGrid) return;
		alert('This function not implmented yet');
	}
} // gridImportExport

var commonGridControls = {
} // commonGridControls

