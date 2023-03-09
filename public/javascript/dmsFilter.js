//------------------------------------------
// Functions for working with data filters (generally on list report pages)
//------------------------------------------

var dmsFilter = {

    clearSelector: function(name) {
        $('#' + name + ' option').each(function(idx, opt) {
            opt.selected = false;
        });
    },
    /**
     * Loads a SQL comparison selector (via AJAX)
     * @param {type} containerId
     * @param {type} url
     * @param {type} col_sel
     * @returns {undefined}
     */
    loadSqlComparisonSelector: function(containerId, url, col_sel) {
        url += $('#' + col_sel).val();
        gamma.loadContainer(url, {}, containerId);
    },
    /**
     * Clear the specified list report search filter
     * @param {type} filter
     * @returns {undefined}
     */
    clearSearchFilter: function(filter) {
        $( '.' + filter).each(function(idx, obj) {
            obj.value = ''
        });
        dmsFilter.is_filter_active();
    },
    /**
     * Clear the list report search filters
     * @returns {undefined}
     */
    clearSearchFilters: function() {
        $(".filter_input_field").each(function(idx, obj) {
            obj.value = ''
        });
        dmsFilter.is_filter_active();
    },
    /**
     * Toggle filter visibility
     * @param {type} containerId
     * @param {type} duration
     * @param {type} element
     * @returns {undefined}
     */
    toggleFilterVisibility: function(containerId, duration, element) {
        var visible = gamma.toggleVisibility(containerId, duration, element);
        this.adjustFilterVisibilityControl(containerId, visible);
    },
    /**
     * Adjust filter visibility
     * @param {type} containerId
     * @param {type} visible
     * @returns {undefined}
     */
    adjustFilterVisibilityControl: function(containerId, visible) {
        var vCtls = $('.' + containerId);
        vCtls.each(function() {
            gamma.setToggleIcon($(this), visible);
        });
    },
    /**
     * Adjust filter visibility
     * @returns {undefined}
     */
    adjustFilterVisibilityControls: function() {
        $('.filter_container_box').each(function() {
            var id = this.id;
            var visible = $(this).is(':visible');
            dmsFilter.adjustFilterVisibilityControl(id, visible);
        });
    },
    //------------------------------------------
    // search filter change monitoring
    //------------------------------------------

    /**
     * Define the observers for a filter field
     * @returns {undefined}
     */
    set_filter_field_observers: function() {
        var that = this;
        var pFields = $('#filter_form').find(".primary_filter_field");
        pFields.each(function(idx, f) {
                $(this).on("keypress", that.filter_key);
                $(this).on("keypress", that.is_filter_active);
            });
        var sFields = $(".secondary_filter_input");
        sFields.each(function(idx, f) {
                $(this).on("keypress", that.filter_key);
                $(this).on("keypress", that.is_filter_active);
            });
    },
    /**
     * Updates the filter active indicator
     * @returns {undefined}
     */
    is_filter_active: function() {
        var filterFlag = 0;
        var sortFlag = 0;
        var ff = $('#filter_form');
        ff.find(".primary_filter_field").each(function(idx, obj) {
                if(obj.value != '') filterFlag++;
            } );
        ff.find(".secondary_filter_input").each(function(idx, obj) {
                if(obj.value != '') filterFlag++;
            } );
        ff.find(".sorting_filter_input").each(function(idx, obj) {
                if(obj.value != '') sortFlag++;
            } );
        dmsFilter.set_filter_active_indicator(filterFlag, sortFlag);
    },
    /**
     * Filter key
     * @param {type} e
     * @returns {Boolean}
     */
    filter_key: function(e) {
        var code;
    //  if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        if(code == 13) {
            $('#qf_first_row').val(1);
            lambda.reloadListReportData();
            return false;
        }
       return true;
    },
    /**
     * Set filter active indicator
     * @param {type} activeSearchFilters
     * @param {type} activeSorts
     * @returns {undefined}
     */
    set_filter_active_indicator: function(activeSearchFilters, activeSorts) {
        if(!activeSearchFilters) {
            $('#filters_active').html('');
        } else
        if(activeSearchFilters ==1 ){
            $('#filters_active').html('There is ' + activeSearchFilters +  ' filter set');
        } else {
            $('#filters_active').html('There are ' + activeSearchFilters +  ' filters set');
        }
    }

}; // dmsFilter
