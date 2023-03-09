//------------------------------------------
// navBar (main drop-down menu)
//------------------------------------------

// set event handlers for global search panel
$(document).ready(function () {
    var panel = $('.global_search_panel');
    dmsNav.setSearchEventHandlers(panel);
});

// set listener for nav-bar related clicks
$(document).ready(function () {
    $(document.body).on("click", navBar.hide_exposed_menus);
});

var navBar = {
    /**
     * Show a menu
     * @param {type} menu_id Menu ID to expose
     * @returns {undefined}
     */
    expose_menu: function(menu_id) {
        navBar.openMenuId = menu_id;
        var m = $('#' + menu_id);
        m.css('display', 'block');
    },
    /**
     * Hide a menu
     * @param {type} e
     * @returns {undefined}
     */
    hide_exposed_menus: function(e) {
        if(e) {
            var el = e.target;
            var pe = $(el).closest('div')[0];
            var notA = el.tagName.toLowerCase() !== 'a';
            var notM = pe.id !== 'menu';
            if(notA || notM) {
                navBar.openMenuId = '';
            }
        } else {
            navBar.openMenuId = '';
        }
        var menu_list = $('.ddm');
        menu_list.each( function(idx, x) {
                if(x.id !== navBar.openMenuId)
                    x.style.display = 'none';
            });
    },
    /**
     * Invoke an action
     * @param {type} action
     * @param {type} arg
     * @returns {undefined}
     */
    invoke: function(action, arg) {
        if(action) {
            action(arg);
        }
        navBar.hide_exposed_menus();
    }
};

//------------------------------------------
// global navigation and menu-related functions
//------------------------------------------

var dmsNav = {

    /**
     * Event handlers for global search panel
     * @param {type} panel
     * @returns {undefined}
     */
    setSearchEventHandlers: function(panel) {
        var sel = panel.find('select');
        var val = panel.find('input');
        var go = panel.find('a');

        val.on("keypress", function(e) {
            if(e.keyCode === 13) {
                dmsNav.dms_search(sel.val(), val.val());
                return false;
            }
           return true;
        });
        sel.on("change", function(e) {
            dmsNav.dms_search(sel.val(), val.val());
        });
        go.on("click", function(e) {
            dmsNav.dms_search(sel.val(), val.val());
        });
    },

    //------------------------------------------
    //search functions
    //------------------------------------------
    dms_search: function(url, srchVal) {
        if(url == '') return;
        if(srchVal != '') {
            url += srchVal;
            if(typeof top.display_side != 'undefined') {
                top.display_side.location = url;
            } else {
                location = url;
            }
        }
    },
    //------------------------------------------
    // Side menu functions
    // these functions hide and show the side menu
    //------------------------------------------
    kill_frames: function() {
        if(top != self) {
          top.location = location;
        }
    },
    open_frames: function() {
        document.OFS.page.value = location;
        document.OFS.submit();
    },
    toggle_frames: function() {
        if(top != self) {
          top.location = location;
        } else {
          document.OFS.page.value = location;
          document.OFS.submit();
        }
    },
    //------------------------------------------
    // Page cache clearing functions
    //------------------------------------------
    /**
     * For clearing cached page parameters
     * @param {type} pageType
     * @returns {undefined}
     */
    setListReportDefaults: function(pageType) {
        var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/defaults/' + pageType;
        p = {};
        $.post(url, p, function (data) {
                alert(data);
            }
        );
    }

}; // dmsNav
