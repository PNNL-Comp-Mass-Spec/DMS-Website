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
		if(range && colIndex >= range.fromCell && colIndex <= range.toCell) {
			sel = range;
		} 
		var lastValueSeen = "";
		var rowsAffected = [];
		for (var i = sel.fromRow; i <= sel.toRow; i++) {
//		for (var i = 0; i < dataRows.length; i++) {
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
		$('#save_ctls').show();
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
			dataRows[row][column.field] = '';
			gridUtil.markChange(dataRows[row], column.field);
			rowsAffected.push(row);
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
	saveChanges: function (url, p, action) {
		if ( !confirm("Are you sure that you want to update the database?") ) return;
		gamma.doOperation(url, p, 'ctl_panel', function(data) {
			if(data.indexOf('was successful') !== -1) {
				if(action) action(data);
			} else {
				alert(data);
			}
		});
	},
	refreshGrid: function (url, p, caller) {
		var cntr = $('#' + caller.attachment);
		cntr.spin('small');
	    $.post(
	        url, p, function (response) {
	        	cntr.spin(false);
	        	obj = $.parseJSON(response);
	            if (obj.result == "error") {
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

var gridHeaderUtil = {
	headerButtons: null, // in case we someday have any header buttons
	buttonCmdHandler: null, // in case we someday have any header buttons
	baseMenuItems: [
		{ title: "Sort Ascending", command: "sort-asc" },
		{ title: "Sort Descending", command: "sort-desc" }
	],
	editMenuItems: [
		{ title: "Fill Down", command: "filldown", tooltip: "Fill empty cells in this column with preceding non-empty values" },
		{ title: "Clear selected", command: "clear-selected", tooltip: "Clear all values in selected cells" },
		{ title: "Clear all", command: "clear-all", tooltip: "Clear all values in this column" }
	],
	menuCmdHandler: function (e, args) {
		if(args.command == 'filldown') {
			gridUtil.fillDown(args.column, args.grid);
		} else
		if(args.command == 'clear-all') {
			gridUtil.clearSelectedCells(args.column, args.grid, true);				
		} else
		if(args.command == 'clear-selected') {
			gridUtil.clearSelectedCells(args.column, args.grid);				
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
	hiddenColumns: [],
	staticColumns: [],
	container: null,
	grid: null,
	options: {
	        editable: true,
	        enableAddRow: true,
	        enableCellNavigation: true,
	        asyncEditorLoading: false,
	        autoHeight: true,
	        autoEdit: false,
	        enableColumnReorder: false,
	        explicitInitialization: true
	},
	setDefaults: function () {
	},
	refreshGrid: function (p) {
		var itemList = $('#itemList').val();
		gridUtil.refreshGrid(gamma.pageContext.data_url, p,  this);
	},
	cellChanged: function (e, args) {
		$('#save_ctls').show();
		var field = args.grid.getColumns()[args.cell].field;
		gridUtil.markChange(args.item, field);
		gridUtil.setChangeHighlighting(args.grid);
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
		gridUtil.setChangeHighlighting(this.grid);
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
