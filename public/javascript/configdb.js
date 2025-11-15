//------------------------------------------
// functions for working with the config DBs
//------------------------------------------

var configdb = configdb || {

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
    },

    search: function() {
        var file_filter = $('#file_filter').val();
        var table_filter = $('#table_filter').val();
        var url = dmsjs.pageContext.site_url + 'config_db/search/' + file_filter + '/' + table_filter;
        if($('#text_only').checked) {
            url += '/text';
        }
        location = url;
    },

    /*********************************************
    * Functions used by table_edit (table editing, by row or with SQL)
    *********************************************/
    extractRow: function(theObject, index) {
        var obj = {};
        for (var propName in theObject) {
            obj[propName] = theObject[propName][index];
        }
        return obj;
    },

    // do editing action from editing from controls
    rowOps: function(index, action) {
        if ( !confirm("Are you sure that you want to modify the config db?") )
            return;
        var container = $('#edit_container');
        var url = dmsjs.pageContext.site_url + 'config_db/submit_edit_table/' + dmsjs.pageContext.config_db + '/' + dmsjs.pageContext.table_name;
        var fields = $('#edit_form').serializeArray();
        var flds = configdb.reformatFormArray(fields);
        var p = configdb.extractRow(flds, index);
        p.mode = action;
        $.post(url, p, function (data) {
                container.html(data);
            }
        );
    },

    // submit sql from entry field and refresh edit table
    do_sql: function() {
        if ( !confirm("Are you sure that you want to modify the config db?") ) return;
        var url = dmsjs.pageContext.site_url + 'config_db/exec_sql/' + dmsjs.pageContext.config_db + '/' + dmsjs.pageContext.table_name;
        var p = $('#sql_text').serialize();
        $.post(url, p, function (data) {
                $('#edit_container').html(data);
            }
        );
    },

    // get suggested sql for enhancing table
    get_sql: function(mode){
        var field = $('#sql_text_fld');
        var url = dmsjs.pageContext.site_url + 'config_db/get_suggested_sql/' + dmsjs.pageContext.config_db + '/' + dmsjs.pageContext.table_name;
        var p = {};
        p.mode = mode;
        field.val('');
        $.post(url, p, function (data) {
                field.val(data);
            }
        );
    },

    // move any existing destination id to to source id
    // and set destination id to the input id
    set_id: function(id) {
        $('#source_id').html($('#dest_id').html());
        $('#dest_id').html(id);

        $('#range_start_id').html($('#range_stop_id').html());
        $('#range_stop_id').html($('#range_dest_id').html());
        $('#range_dest_id').html(id);
    },

    // get suggested SQL for moving item(s)
    get_sql_from_range_move: function(mode){
        if(mode === 'range') {
            var r1_id = $('#range_start_id').html();
            var r2_id = $('#range_stop_id').html();
            var d_id = $('#range_dest_id').html();
        } else {
            var r1_id = $('#source_id').html();
            var r2_id = $('#source_id').html();
            var d_id = $('#dest_id').html();
        }
        if(!r1_id || !r2_id || !d_id) return;

        var url = dmsjs.pageContext.site_url + 'config_db/move_range/' + dmsjs.pageContext.config_db + '/' + dmsjs.pageContext.table_name + '/' + r1_id + '/'  + r2_id + '/' + d_id;
        var field = $('#sql_text_fld');
        var p = {};
        field.val('');
        $.post(url, p, function (data) {
                field.val(data);
            }
        );
    },

    // get suggested SQL for resequencing id column in table
    get_sql_for_resequence: function(){
        var url = dmsjs.pageContext.site_url + 'config_db/resequence_table/' + dmsjs.pageContext.config_db + '/' + dmsjs.pageContext.table_name;
        var field = $('#sql_text_fld');
        var p = {};
        field.val('');
        $.post(url, p, function (data) {
                field.val(data);
            }
        );
    },

    /*********************************************
    * Functions used by show_db (table listing)
    *********************************************/
    tableOps: function(submit_url) {
        if ( !confirm("Are you sure that you want to modify the config db?") ) return;
        var container_name = "display_container";
        var url = dmsjs.pageContext.site_url + 'config_db/' + submit_url;
        $('#' + container_name).load(url); // dmsOps.loadContainer(url, {}, container_name);
    },

    show_hide_all: function(mode) {
        $('div.block_content').each(function(idx, s){
                s.style.display=mode;
            });
    },

    show_hide_block: function(name) {
        $('#' + name).toggle();
    },

    make_controller: function() {
        var reply = prompt("Base title for page family", '');
        if (reply)
        {
            var page = dmsjs.pageContext.site_url + 'config_db/make_controller/' + dmsjs.pageContext.config_db + '/' + reply;
            window.open(page, "HW", "scrollbars,resizable,height=550,width=1000,menubar");
        }
    },
};  // configdb

