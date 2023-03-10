var tracking = {
    instrument_usage_report: {
        parseUploadText: function(text_fld) {
            parsed_data = {};
            var lines = $('#' + text_fld).val().split('\n');
            var header = [];
            var data = [];
            $.each(lines, function(lineNumber, line){
                line = dmsInput.trim(line);
                if(line) {
                    var fields = dmsInput.parse_lines(line)
                    if(lineNumber == 0) {
                        header = fields;
                    } else {
                        data.push(fields); // check length of fields?
                    }
                }
            });
            // get rid of goofy parsing artifact last row
            if(!(data[data.length - 1])[0]) {
                data.pop();
            }
            parsed_data.header = header;
            parsed_data.data = data;
            return parsed_data;
        },
        updateDatabaseFromList: function(flist, id_type) {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var mapPropertiesToAttributes = [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
            var factorXML = dmsInput.getXmlElementsFromObjectArray(flist, 'r', mapPropertiesToAttributes);
            if(id_type) {
                factorXML = '<id type="' + id_type + '" />' + factorXML;
            }
            var url =  dmsjs.pageContext.ops_url;
            var p = {};
            p.factorList = factorXML;
            p.operation = 'update';
            dmsOps.submitOperation(url, p);
        },
        load_delimited_text: function() {
            var parsed_data = this.parseUploadText('delimited_text_input');
            var id_type = parsed_data.header[0];
            var col_list = dmsjs.removeItems(parsed_data.header, [id_type]);
            var flist = theta.getFieldListFromParsedData(parsed_data, col_list);
            this.updateDatabaseFromList(flist, id_type);
        },
        reloadReport: function(operation) {
            // Call stored procedure update_instrument_usage_report with @operation set to either 'refresh' or 'reload'
            var url =  dmsjs.pageContext.ops_url;
            var p = {};
            p.factorList = '';
            p.operation = operation;
            p.year = $('#pf_year').val();
            p.month = $('#pf_month').val();
            p.instrument = $('#pf_instrument').val();
            dmsOps.submitOperation(url, p);
        },
        refresh_report: function() {
            if ( !confirm("Are you sure that you want to refresh the exiting report") ) return;
            this.reloadReport('refresh');
        },
        reload_report: function() {
            if ( !confirm("Are you sure that you want to clear the existing report and reload?") ) return;
            this.reloadReport('reload');
        }
    },
    instrument_allocation: {
        getXMLFromObjList: function(flist) {
            var xml = '';
            if (typeof(flist) != "undefined") {
                $.each(flist, function(idx, obj){
                    xml += '<r p="' + obj.id + '" g="' + obj.factor + '" a="' + obj.value + '" />';
                });
            }
            return xml;
        },
        getFieldListFromParsedData: function(parsed_data, col_list) {
            // make array of id/factor/value objects,
            // one for each row of each column
            var flist = [];
            $.each(col_list, function(idx, factor){
                var idx = parsed_data.header.indexOf(factor);
                if(idx > -1) {
                    $.each(parsed_data.data, function(idx, row){
                        var id = row[0];
                        var value = row[idx];
                        if((typeof(value) != "undefined") && (value != '')) {
                            var obj = {};
                            obj.id = id;
                            obj.factor = factor;
                            obj.value = value;
                            flist.push(obj);
                        }
                    });
                }
            });
            return flist;
        },
        updateDatabaseFromList: function(flist, fiscal_year) {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var allocationXML = this.getXMLFromObjList(flist);
            if(fiscal_year) {
                allocationXML = '<c fiscal_year="' + fiscal_year + '" />' + allocationXML;
            }
            var url =  dmsjs.pageContext.ops_url;
            var p = {};
            p.parameterList = allocationXML;
            dmsOps.submitOperation(url, p);
        },
        load_delimited_text: function() {
            var parsed_data = dmsInput.parseDelimitedText('delimited_text_input');
            var fiscal_year = $('#fiscal_year').val();
            if(fiscal_year == '') {
                alert('You must set the fiscal year for the changes');
                return;
            }
            var col_list = dmsjs.removeItems(parsed_data.header, ['Proposal_ID']);
            var flist = this.getFieldListFromParsedData(parsed_data, col_list);
            this.updateDatabaseFromList(flist, fiscal_year);
        },
        move_allocated_hours: function() {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var xml = '';
            xml = '<c fiscal_year="' + $('#move_fy').val() + '" />';
            xml += '<r ';
            xml += 'o="i" ';
            xml += 'p="' + $('#move_to').val() + '" ';
            xml += 'a="' + $('#move_hours').val() + '" ';
            xml += 'g="' + $('#move_group').val() + '" ';
            xml += 'x="' + $('#move_comment').val() + '" ';
            xml += ' />';
            xml += '<r ';
            xml += 'o="d" ';
            xml += 'p="' + $('#move_from').val() + '" ';
            xml += 'a="' + $('#move_hours').val() + '" ';
            xml += 'g="' + $('#move_group').val() + '" ';
            xml += 'x="' + $('#move_comment').val() + '" ';
            xml += ' />';
            var url =  dmsjs.pageContext.ops_url;
            var p = {};
            p.parameterList = xml;
            dmsOps.submitOperation(url, p);
        },
        set_allocated_hours: function() {
            if ( !confirm("Are you sure that you want to update the database?") ) return;
            var xml = '';
            xml = '<c fiscal_year="' + $('#set_fy').val() + '" />';
            xml += '<r ';
            xml += 'p="' + $('#set_to').val() + '" ';
            xml += 'a="' + $('#set_hours').val() + '" ';
            xml += 'g="' + $('#set_group').val() + '" ';
            xml += 'x="' + $('#set_comment').val() + '" ';
            xml += ' />';
            var url =  dmsjs.pageContext.ops_url;
            var p = {};
            p.parameterList = xml;
            dmsOps.submitOperation(url, p);
        }
    }

}
