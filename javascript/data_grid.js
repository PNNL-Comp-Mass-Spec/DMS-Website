/*
 * utility functions shared by all grid instances
 */
var gridUtil = {
	markChange: function(dataRow, field) {
		if(!dataRow.mod_axe) dataRow.mod_axe = {};
		dataRow.mod_axe[field] = dataRow[field];		
	},
	visitRange: function(cell, range, grid, visitor) {
		var dataRows = grid.getData();
		var cols = this.getColumnsInRange(range, grid);
		var rows = this.getRowsInRange(range);
		var rowsAffected = [];
		$.each(rows, function(x, row) {
			$.each(cols, function(z, column) {
				if(visitor) visitor(row, column, dataRows);
			});
			rowsAffected.push(row);					
		});
		grid.invalidateRows(rowsAffected);
		gridUtil.setChangeHighlighting(grid);
		grid.render();
	},
	getColumnsInRange: function(range, grid) {
		var cols = [];
		for(var col = range.fromCell; col <= range.toCell; col++) {
			cols.push(grid.getColumns()[col]);
		}
		return cols;
	},
	getRowsInRange: function(range, grid) {
		var rows = [];
		for(var row = range.fromRow; row <= range.toRow; row++) {
			rows.push(row);
		}
		return rows;			
	},
	getClearCellVisitor: function(cellProtectionChecker) {
		var checkProtection = cellProtectionChecker;
		return function(row, column, dataRows) {
			if(!column.editor) return;
			if(checkProtection && !checkProtection(column.field, dataRows[row])) return;
			var val = dataRows[row][column.field];
			if (!(val == null || val === '')) {
				dataRows[row][column.field] = '';
				gridUtil.markChange(dataRows[row], column.field);
			}			
		};
	},
	getFilldownVisitor: function(cellProtectionChecker) {
		var checkProtection = cellProtectionChecker;
		var lastValueSeen = {};
		return function(row, column, dataRows) {
			if(!column.editor) return;
			var dataRow = dataRows[row];
			var field = column.field;
			if(checkProtection && !checkProtection(field, dataRow)) return;
			if(!lastValueSeen[field]) {
				lastValueSeen[field] = '';
			}
			var val = dataRow[field];
			if (val == null || val === '') {
				if(dataRow[field] != lastValueSeen[field]) {
					dataRow[field] = lastValueSeen[field];
					gridUtil.markChange(dataRow, field);
				}
			} else {
				lastValueSeen[field] = dataRow[field];
			}
		};
	},
	getFilldownOverwriteVisitor: function(cellProtectionChecker) {
		var checkProtection = cellProtectionChecker;
		var lastValueSeen = {};
		return function(row, column, dataRows) {
			if(!column.editor) return;
			var dataRow = dataRows[row];
			var field = column.field;
			if(checkProtection && !checkProtection(field, dataRow)) return;
			var val = dataRow[field];
			if(!lastValueSeen[field] && val) {
				lastValueSeen[field] = dataRow[field];
				return;
			}
			if (lastValueSeen[field]) {
				if(dataRow[field] != lastValueSeen[field]) {
					dataRow[field] = lastValueSeen[field];
					gridUtil.markChange(dataRow, field);
				}
			}
		};
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
	// update current data rows in grid from input rows based on given keyColumn
	// limited to given changeable columns
	updateCurrentValues: function(grid, keyColumn, changeColumns, inputRows) {
		var inputIndex = {};
		$.each(inputRows, function(idx, row) {
			inputIndex[row[keyColumn]] = row;
		});
		var key, currentValue, inputValue;
		var rowsAffected = [];
		var currentRows = grid.getData();
		$.each(currentRows, function(currentRowIndex, currentRow) {
			key = currentRow[keyColumn];
			$.each(changeColumns, function(i, colName) {
				if(colName == keyColumn) return;
				currentValue = currentRow[colName] || '';
				inputValue = inputIndex[key][colName] || '';
				if(inputValue != currentValue) {
					currentRow[colName] = inputValue;
					gridUtil.markChange(currentRow, colName);
					rowsAffected.push(currentRowIndex);
				}
			});
		});
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
			if(data.charAt(0) === '{' ) {
	        	var obj = $.parseJSON(data);
	        	if(obj.result !== 0) {
	        		alert(obj.message);
	        	} else {
					if(caller.afterSaveAction) caller.afterSaveAction(obj);	
				}			
			} else
			if(data.charAt(0) === '{' || data.indexOf('was successful') !== -1) {
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
	sizeColumnsToData: function (currentColumns, dataRows, maxColumnChars) {
		var textWidthPixels = 8.1;
		var maxChars, dataChars, val, colLabel;
		var minChars = 7;
		$.each(currentColumns, function(idx, colSpec) {
			maxChars = minChars;
			colLabelChars = colSpec.field.length + 2; // allow space for drop-down menu control
			if(colLabelChars > maxChars) maxChars = colLabelChars; 
			$.each(dataRows, function(i, dataRow) {
				val = dataRow[colSpec.field];
				if(val) {
					dataChars = val.length;
					if(dataChars > maxChars) maxChars = dataChars;
				}
			});
			maxChars = (maxColumnChars && maxColumnChars < maxChars) ? maxColumnChars : maxChars;
			colSpec.width = Math.round(maxChars * textWidthPixels);
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
				return dataRow[colSpec.field] || '';
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
	maxColumnChars: null,
	container: null, // generated page element that contains grid
	options: { // SlickGrid options
	        editable: true,
	        autoEdit:true,
//	        enableAddRow: true,
	        enableCellNavigation: true,
	        asyncEditorLoading: false,
	        autoHeight: true,
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
	getClickHandler: null,
	getContextMenuHandler: null,
	editPermissionFilter: null,
	//
	// the following properties SHOULD NOT be overridden
	grid: null,
	pendingOp: false,
	init: function(gridConfig) {
		return $.extend({}, mainGrid, gridConfig);
	},
	loadGrid: function () {
		var url = this.getLoadUrl();
		var p = this.getLoadParameters();
		if(p === false) return;
		gridUtil.refreshGrid(url, p,  this);
	},
	saveGrid: function() {
		Slick.GlobalEditorLock.commitCurrentEdit();	
		var url = this.getSaveUrl();
		var p = this.getSaveParameters();
		if(p === false) return;
		gridUtil.saveChanges(url, p, this);
	},
	getCellChangeHandler: function() {
		var context = this;
		return function (e, args) {
			var field = args.grid.getColumns()[args.cell].field;
			gridUtil.markChange(args.item, field);
			gridUtil.setChangeHighlighting(args.grid);
			if(context.handleDataChanged) context.handleDataChanged(args);
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
		if(this.editPermissionFilter) this.grid.onBeforeEditCell.subscribe(this.editPermissionFilter);
		var headerMenuPlugin = new Slick.Plugins.HeaderMenu({});
		headerMenuPlugin.onCommand.subscribe(this.headerUtil.getMenuCmdHandler(this.handleDataChanged));
		if(this.columnMenuHook) {
			headerMenuPlugin.onBeforeMenuShow.subscribe(this.columnMenuHook);			
		}
		if(this.getClickHandler) {
			this.grid.onClick.subscribe(this.getClickHandler());
		}
		if(this.getContextMenuHandler) {
			this.grid.onContextMenu.subscribe(this.getContextMenuHandler());
		}
		this.grid.registerPlugin(headerMenuPlugin);
		this.grid.registerPlugin(new Slick.AutoTooltips());
		this.grid.setSelectionModel(new Slick.CellSelectionModel());
	},
	buildColumns: function(colNames, editable) {
		var caller = this;
		var colSpecs = [];
		var colSpec;
		$.each(colNames, function(idx, colName) {
			if(caller.hiddenColumns.length > 0 && !caller.hiddenColumns.indexOf(colName) === -1) return;
			colSpec = caller.makeColumnSpec(colName, editable);
			colSpecs.push(colSpec);
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
		currentColumns = gridUtil.sizeColumnsToData(currentColumns, dataRows, this.maxColumnChars);
		this.grid.setColumns(currentColumns);
	},
	addColumn: function(colName) {
		if(!colName) {
			alert('Column name cannot be blank');
			return;
		}
		this.addColumns([colName]);
	},
	addColumns: function(columnNames) {
		this.adjustColumns(columnNames);
		var that = this;
		$.each(columnNames, function(idx, colName) {
			that.setEmptyColumnInData(colName);					
		});
	},
	setEmptyColumnInData: function (colName) {
		$.each(this.grid.getData(), function(idx, dataRow) {
			dataRow[colName] = '';
		});			
	},
	makeColumnSpec: function(colName, editable) {
		var colSpec;
		var colSpecType = typeof colName;
		if(colSpecType === 'string') {
			colSpec = { id:colName };
			if(editable) colSpec.editor = Slick.Editors.Text;
		} else 
		if(colSpecType === 'object') {
			colSpec = colName;
			if(!colSpec.ned && !colSpec.editor) colSpec.editor = Slick.Editors.Text;
		}
		if(!colSpec.name) colSpec.name = colSpec.id;
		if(!colSpec.field) colSpec.field = colSpec.id;
		
		var menuItems = $.merge([], this.headerUtil.baseMenuItems);
		if(colSpec.editor) {
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
	},
	setColumnMenuCmds: function(colName, cmds, useSep) {
		var col = this.grid.getColumns()[this.grid.getColumnIndex(colName)];
		if(!col) return;
		if(useSep) col.header.menu.items.push( { command:'', title:'-----' });	
		col.header.menu.items = $.merge(col.header.menu.items, cmds)
	},
	registerColumnMenuCmdHandlers: function(handlers) {
		$.extend(this.headerUtil.commands, handlers);
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
	preUpdateAction: null,
	postUpdateAction: null,
	keyColumnForUpdate: null,
	acceptNewColumnsOnUpdate: false,
	myMainGrid: null,
	init: function(wrapper, config) {
		var obj = $.extend({}, gridImportExport, config);
		obj.myMainGrid = wrapper;	
		$('#export_grid_btn').click(function() {
			obj.exportDelimitedData(obj);
		});
		$('#import_grid_btn').click(function() {
		    obj.myMainGrid.buildGrid();
			obj.importDelimitedData(obj);
		});
		$('#update_grid_btn').click(function() {
			obj.updateFromDelimitedData(obj);
		});
		return obj;
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
		var inputData = gridUtil.convertToGridData(parsed_data.header, parsed_data.data);
		if(context.preImportAction) {
			if(context.preImportAction(inputData) === false) return;
		}
		context.myMainGrid.setDataRows(inputData, true);
		if(context.postImportAction) context.postImportAction();
	},
	updateFromDelimitedData: function(context) {
		if(!context.myMainGrid) return;
		var newColumns;
		var parsed_data = gamma.parseDelimitedText('delimited_text');
		var inputData = gridUtil.convertToGridData(parsed_data.header, parsed_data.data);

		if(context.preUpdateAction) { 
			if(context.preUpdateAction(inputData) === false) return;
		}
		
		var keyColumn = context.keyColumnForUpdate || inputData.columns[0];
		var changeColumns = $.map(inputData.columns, function(colName) {
			return ($.inArray(colName, context.myMainGrid.staticColumns) === -1)? colName : null;
		});
		var curColumns = $.map(context.myMainGrid.grid.getColumns(), function(col) {
			return col.field;
		});
		if(context.acceptNewColumnsOnUpdate) {
			newColumns = $.map(inputData.columns, function(colName) {
				return ($.inArray(colName, curColumns) === -1)? colName : null;
			});		
			context.myMainGrid.addColumns(newColumns);
		}
		var grid = context.myMainGrid.grid;
		var inputRows = inputData.rows;

		gridUtil.updateCurrentValues(grid, keyColumn, changeColumns, inputRows);
		if(context.postUpdateAction) context.postUpdateAction(newColumns);
	}
} // gridImportExport

var commonGridControls = {
	myMainGrid: null,
	addColCtlEnabled: false,
	beforeAddCol: null,
	afterAddCol: null,
	init: function(wrapper) {
		var obj =  $.extend({}, commonGridControls);
		obj.myMainGrid = (wrapper) ? wrapper : obj.myMainGrid;	
		$('#reload_btn').click(function() {
			if(obj.myMainGrid.grid && gridUtil.hasChanged(obj.myMainGrid.grid.getData())) {
				if(!confirm("Reloading will destroy your unsaved changes: Do you wish to continue?")) return;
			}
			obj.reload();
		});
		$('#save_btn').click(function() {	
			obj.myMainGrid.saveGrid();
		});
		$('#add_column_btn').click(function() {
			var name = $('#add_column_name').val();
			var ok = true;
			if(obj.beforeAddCol) {
				ok = obj.beforeAddCol(name);
			}
			if(!ok) return;
			obj.myMainGrid.addColumn(name);
			if(obj.afterAddCol) obj.afterAddCol(name);
		});
		return obj;
	},
	showControls: function(showOrHide) {
		if(this.addColCtlEnabled) {
			$('#add_col_ctl_panel').toggle(showOrHide);	
		}
		$('#ctl_panel').toggle(showOrHide);
		$('#delimited_text_ctl_panel').toggle(showOrHide);		
	},
	enableSave: function(showOrHide) {
		$('#save_ctls').toggle(showOrHide);		
	},
	enableAddColumn: function(enabled) {
		this.addColCtlEnabled = enabled;
		$('#add_col_ctl_panel').toggle(enabled);	
	},
	setAddColumnLegend: function(legend) {
		$('#add_column_legend').html(legend);
	},
	reload: function() {
	    this.myMainGrid.buildGrid();
		this.myMainGrid.loadGrid();
	}
} // commonGridControls

var contextMenuManager = {
	menuId: 'contextMenu',
	menuObj: null,
	myMainGrid: null,
	range: null,
	cell: null,
	init: function(grid, menuId) {	
		if(menuId) this.menuId = menuId;
		var obj = $.extend({}, this);
		obj.myMainGrid = grid;
		return obj;
	},
	actions: {},
	menuExists: function() {
		if(this.menuObj) return;
		this.menuObj = $('<ul id="' + this.menuId + '" class="context_popup" style="display:none;"></ul>').appendTo('body');
		this.setMenuClickHandler();
	},
	addMenuAction: function(action, handler) {
		this.actions[action] = handler ;
	},
	addMenuLink: function(action, label) {
		this.menuExists();
		this.menuObj.append('<li data-action="' + action + '">' + label + '</li>');
	},
	addMenuItem: function(action, label, handler) {
		this.addMenuLink(action, label);
		this.addMenuAction(action, handler)
	},
	menuEvtHandler: function(e) {
		this.cell = this.myMainGrid.grid.getCellFromEvent(e);
		this.range = this.inCurrentSelection(this.cell);
		if(this.range) {
			e.preventDefault();
			this.showMenu(this.cell, e.pageX, e.pageY);
		}
	},
	inCurrentSelection: function(cell) {
		var range = this.myMainGrid.grid.getSelectionModel().getSelectedRanges()[0];
		if(!range) return false;
		var inSel = range.contains(cell.row, cell.cell);
		return (inSel) ? range : null ;
	},
	showMenu: function(cell, x, y) {
		var theMenu = this.menuObj;
		theMenu.css("top", y).css("left", x).show();
		$("body").one("click", function () { 
			theMenu.hide(); 
		});
	},
	setMenuClickHandler: function() {
		var theMenu = this.menuObj;
		var me = this;
		theMenu.click(function(e) {
			if (!$(e.target).is("li")) return;
			if (!me.myMainGrid.grid.getEditorLock().commitCurrentEdit()) return;
			var action = $(e.target).data('action');
			var func = me.actions[action];
			if(!func) return;
			func(action, me.cell, me.range, me.myMainGrid.grid); // (cell, range, grid)
			if(me.myMainGrid.handleDataChanged && gridUtil.hasChanged(me.myMainGrid.grid.getData())) {
				me.myMainGrid.handleDataChanged ();
			}
			theMenu.hide(); 
		});
	},
	buildBasicMenu: function(cellProtectionChecker) {
		this.addMenuItem("clear", "Clear Selection", function(action, cell, range, grid) {
			gridUtil.visitRange(cell, range, grid, gridUtil.getClearCellVisitor(cellProtectionChecker));
		});
		this.addMenuItem("filldown", "Filldown", function(action, cell, range, grid) {
			gridUtil.visitRange(cell, range, grid, gridUtil.getFilldownVisitor(cellProtectionChecker));
		});
		this.addMenuItem("filldownOverwrite", "Filldown (Overwrite)", function(action, cell, range, grid) {
			gridUtil.visitRange(cell, range, grid, gridUtil.getFilldownOverwriteVisitor(cellProtectionChecker));
		});
		return this;
	}	
} // contextMenuManager



var cellLinkFormatterFactory = {
	specs: null,
	init: function(specs) {
		var obj = $.extend({}, this);
		obj.specs = specs;
		return obj;
	},
	makeLink: function(page, value, target) {
	    if (!value) return "";
	    var link = gamma.pageContext.site_url + page + value;
	    return '<a href="' + link + '" target="' + target + '">' + value + '</a>';
	},
	makeFor: function(colName) {
		var context = this;
		var target = '_blank' + '_' + colName.toLowerCase().replace(' ', '_');
		var spec = this.specs[colName];
		if(!spec) return null;
		if(typeof spec == 'string') {
			return function (row, cell, value, columnDef, dataContext) {
			    return context.makeLink(spec, value, target);
			}
		}
		if(spec.condition_field) {
			return function (row, cell, value, columnDef, dataContext) {
				var condition = dataContext[spec.condition_field];
				var page = spec[condition];
				if(page) { 
					return context.makeLink(page, value, target);
				} else {
					return value;
				}
			}
		}
	}
} // cellLinkFormatterFactory

var sourceListUtil = {
	setup: function() {
		// wire up click handlers to filter buttons
		// that have appropriate spec
		var context = this;
		$('.ctls_grp a.button').click(function(event) {
			context.getItemsFromSource(event.target);
		});
	},
	// get list of items from given ad hoc query (via AJAX)
	// filtered by single value from given filter field
	// and placed into given target field
	getItemsFromSource: function(el) {
		var ctlsEl = $(el).closest('.ctls');
		var queryName = ctlsEl.data('query');
		var filterEl = ctlsEl.find('input');
		var targetFld = ctlsEl.closest('.ctls_grp').data('target');
		var id = filterEl.val();
		if(!id) { alert('Filter field cannot be blank'); return; }
		var url = gamma.pageContext.site_url + 'data/json/ad_hoc_query/' + queryName;
		var p = { filter_values:id };
		gamma.getObjectFromJSON(url, p, filterEl.attr('id'), function(json) {
			var obj = $.parseJSON(json);
			if(!typeof obj == 'array') return;
			if(obj.length == 0) return;
			var d = $.map(obj, function(item) {
				return item.id;
			});
			var list = d.join(', ');
			$('#' + targetFld).val(list);
		});
	}		
} // sourceListUtil
	
