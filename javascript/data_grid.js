var gridUtil = {
	markChange: function(dataRow, field, clear) {
		if(!dataRow.mod_axe) dataRow.mod_axe = {};
		dataRow.mod_axe[field] = dataRow[field];		
	},
	fillDown: function (column, grid, clear) {
		var row, field, val;
		var dataRows = grid.getData();
		Slick.GlobalEditorLock.commitCurrentEdit();	
		var lastValueSeen = "";
		var rowsAffected = [];
		for (var i = 0; i < dataRows.length; i++) {
			row = dataRows[i];
			field = column.field;
			val = row[field];
			if (val == null || val === '') {
				if(!clear) {
					if(row[field] != lastValueSeen) {
						row[field] = lastValueSeen;
						rowsAffected.push(i);
						gridUtil.markChange(row, field);
					}
				}
			} else {
				if(clear) {
					row[field] = '';
					rowsAffected.push(i);
					gridUtil.markChange(row, field);
				} else {
					lastValueSeen = row[field];
				}
			}
		}
		grid.invalidateRows(rowsAffected);
		gridUtil.setChangeHighlighting(grid);
		grid.render();
		$('#save_ctls').show();
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
	saveChanges: function (dataRows, idField, mapP2A, type, action) {
		var changes = this.getChanges(dataRows, idField);
		// get XML version of changes from list of change objects
		var factorXML = gamma.getXmlElementsFromObjectArray(changes, 'r', mapP2A);
		factorXML = '<id type="' + type + '" />' + factorXML;
		this.saveChangesXML({ factorList: factorXML }, action);
	},
	saveChangesXML: function(p, action) {
		if ( !confirm("Are you sure that you want to update the database?") ) return;
		Slick.GlobalEditorLock.commitCurrentEdit();	
		var url =  gamma.pageContext.save_changes_url;
		gamma.doOperation(url, p, 'ctl_panel', function(data) {
			if(data.indexOf('was successful') !== -1) data = '';
			if(action) action(data);
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
	staticColumns: [],
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
