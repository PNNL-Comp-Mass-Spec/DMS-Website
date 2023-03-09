//------------------------------------------
// Input parsing and formatting functions
//------------------------------------------

var dmsInput = {

    //------------------------------------------
    // parsing stuff
    //------------------------------------------

    /**
     * Return a copy of a string with leading and trailing whitespace removed.
     * @param {string} str String to process
     * @returns {undefined}
     */
    trim: function(str) {
        return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    },
    /**
     * Parse tab-delimited line into array of trimmed values
     * @param {string} line Text to process
     * @returns {array} Array of trimmed values
     */
    parse_lines: function(line) {
        flds = [];
        var fields = line.split('\t');
        $.each(fields, function(idx, fld){
            flds.push(dmsInput.trim(fld));
        });
        return flds;
    },
    /**
     * Parse multiple rows of tab-delimited text
     * Values in the first row are treated as a header
     * @param {string} text_fld Text to process
     * @param {boolean} removeArtifact If true, remove a parsing artifact by removing the last row of the returned array
     * @returns {object} Object with two arrays: header and data
     */
    parseDelimitedText: function(text_fld, removeArtifact) {
        parsed_data = {};
        var lines = $('#' + text_fld).val().split('\n');
        var header = [];
        var data = [];
        $.each(lines, function(lineNumber, line){
            line = dmsInput.trim(line);
            if(line) {
                var fields = dmsInput.parse_lines(line);
                if(lineNumber === 0) {
                    header = fields;
                } else {
                    data.push(fields); // check length of fields?
                }
            }
        });
        // get rid of goofy parsing artifact last row
        if(removeArtifact && (data[data.length - 1]).length < header.length) {
            data.pop();
        }
        parsed_data.header = header;
        parsed_data.data = data;
        return parsed_data;
    },
    /**
     * Parse an array of objects using the mapping array to generate a list of XML elements
     * Example mapping: [{p:'id', a:'i'}, {p:'factor', a:'f'}, {p:'value', a:'v'}];
     * @param {object} objArray Object array of pending changes, for example from function getChanges in data_grid.js
     * @param {string} elementName
     * @param {object} mapping Mapping from items in the object array to xml element names
     * @returns {string} XML as a string
     */
    getXmlElementsFromObjectArray: function(objArray, elementName, mapping) {
        var xml = '';
        if (typeof(objArray) != "undefined") {
            $.each(objArray, function(x, obj){
                xml += '<' + elementName;
                $.each(mapping, function(z, map) {
                    if(typeof obj[map.p] != 'undefined'){
                        xml += ' ' + map.a + '="' + obj[map.p] + '"';
                    }
                });
                xml += ' />';
            });
        }
        return xml;
    },
     /**
     * Convert an array of strings into a list of XML elements using the given element name
     * The XML will have one element for each item in the array, assigning the item in the array as the attribute
     * @param {array} itemArray Array of item names
     * @param {string} elementName
     * @param {string} attributeName
     * @returns {string} XML as a string
     */
    getXmlElementsFromArray: function(itemArray, elementName, attributeName) {
        var xml = '';
        $.each(itemArray, function(x, item){
            xml += '<' + elementName;
            xml += ' ' + attributeName + '="' + item + '"';
            xml += ' />';
        });
        return xml;
    },

    /**
     * Convert a list of values spearated by newlines and/or tabs to a list separated by repStr
     * @param {type} fieldName
     * @param {string} repStr List separator
     * @returns {undefined}
     */
    convertList: function(fieldName, repStr) {
        var fld = $('#' + fieldName);
        var findStr = "(\r\n|[\r\n]|\t)";
        var re = new RegExp(new RegExp(findStr, "g"));
        repStr += ' ';
        fld.val(fld.val().replace(re, repStr));
    }

};  // dmsInput
