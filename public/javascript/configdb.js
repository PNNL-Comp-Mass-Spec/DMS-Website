//------------------------------------------
// functions for working with the config DBs
//------------------------------------------

var configdb = {

    //------------------------------------------
    // misc functions and objects
    //------------------------------------------

    /**
     * Convert array of objects representing form values
     * where each object has property 'name' and 'value'
     *
     * Fields with shared name have array of values
     *
     * @param {object} fldObjArray
     * @returns {undefined} Single object with each field represented as a property having value of associated field
     */
    reformatFormArray: function(fldObjArray) {
        var obj = {};
        $.each(fldObjArray, function(idx, fldObj) {
            var nm = fldObj.name;
            if(!obj[nm]) {
                obj[nm] = [];
            }
            obj[nm].push(fldObj.value);
        });
        return obj;
    }
};  // configdb

