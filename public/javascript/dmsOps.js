//------------------------------------------
// global and general-purpose functions and objects
//------------------------------------------

var dmsOps = {

    /**
     * General AJAX post that fills the given container
     * with returned text and allows
     * pre and post callbacks to be defined
     * @param {string} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    loadContainer: function (url, p, containerId, afterAction, beforeAction) {
        var container = $('#' + containerId);
        var abort = false;
        if(beforeAction) {
            abort = beforeAction();
        }
        if(abort) return;
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
                if(afterAction) {
                    afterAction();
                }
        });
    },
    /**
     * General AJAX post that gets a data object
     * from JSON returned by server and allows
     * pre and post callbacks to be defined
     * @param {string} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    getObjectFromJSON: function (url, p, containerId, afterAction, beforeAction) {
        var container = (containerId) ? $('#' + containerId) : null;
        var abort = false;
        if(beforeAction) {
            abort = beforeAction();
        }
        if(abort) return;
        if(container) container.spin('small');
        $.post(url, p, function (json) {
                if(container) container.spin(false);
                // Safety - if empty JSON response, return an empty array
                var data = [];
                if(json.length > 0) {
                    data = JSON.parse(json);
                }
                if(afterAction) {
                    afterAction(data);
                }
        });
    },
    /**
     * General AJAX post that calls server operation
     * and returns server response via callback
     * @param {string} url
     * @param {type} p
     * @param {type} containerId
     * @param {type} afterAction
     * @param {type} beforeAction
     * @returns {undefined}
     */
    doOperation: function (url, p, containerId, afterAction, beforeAction) {
        // make calling parameters from p
        // p can be form Id, raw object, or falsey
        var px = {};
        if(p) {
            if (typeof p === "string") {
                px = $('#' + p).serialize();
            } else {
                px = p;
            }
        }
        var container = $('#' + containerId);
        var abort = false;
        if(beforeAction) {
            abort = beforeAction();
        }
        if(abort) return;
        container.spin('small');
        $.post(url, px, function (data) {
                container.spin(false);
                afterAction(data, container);
        });
    },
    /**
     * Use to terminate a calling chain
     */
    no_action: {
    },
    /**
     * Go to new web page given by url currently selected in the given selection element
     * @param {type} id
     * @returns {undefined}
     */
    goToSelectedPage: function(id) {
        var node = document.getElementById(id);
        window.location.href = node.options[node.selectedIndex].value;
    },
    load_script_diagram_cmd: function() {
        var scriptName = $('#scriptName').val();
        if(scriptName) {
            var url = dmsjs.pageContext.site_url + 'pipeline_script/dot/' + scriptName
            var p = { datasets: $('#datasets').val() };
            dmsOps.loadContainer(url, p, 'script_diagram_container');
        }
    },
    load_script_diagram: function () {
        var scriptName = $('#lnk_ID').html();
        if(scriptName) {
            var url = dmsjs.pageContext.site_url + 'pipeline_script/dot/' + scriptName
            dmsOps.loadContainer(url, {}, 'script_diagram_container');
        }
    },

    //------------------------------------------
    //These functions are used by list reports
    //------------------------------------------
    /**
     * This function acts as a hook that other functions call to
     * reload the row data container for the list report.
     * it needs to be overridden with the actual loading
     * function defined on the page, which will be set up
     * with page-specific features
     * @returns {undefined}
     */
    reloadListReportData: function() {
        alert('"dmsOps.reloadListReportData" not overridden');
    },
    /**
     * Go get some content from the server using given form and action
     * and put it into the designated container element
     * and initiate the designated follow-on action, if such exists
     * @param {string} action Action (mode)
     * @param {type} formId
     * @param {type} containerId
     * @param {object} follow_on_action
     * @returns {undefined}
     */
    updateContainer: function (action, formId, containerId, follow_on_action) {
        var container = $('#' + containerId);
        container.spin('small');
        var url = dmsjs.pageContext.site_url + dmsjs.pageContext.my_tag + '/' + action;
        var p = $('#' + formId).serialize();
        $.post(url, p, function (data) {
                container.spin(false);
                container.html(data);
                if(follow_on_action && follow_on_action.run) {
                    follow_on_action.run();
                }
            }
        );
    },
    /**
     * Submit list report supplemental command
     * @param {string} url
     * @param {object} p Object to post
     * @param {boolean} show_resp If true, show the response from the post
     * @returns {undefined}
     */
    submitOperation: function(url, p, show_resp) {
        var ctl = $('#' + dmsjs.pageContext.cntrlContainerId);
        var container = $('#' + dmsjs.pageContext.responseContainerId);
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                if(data.indexOf('Update failed') > -1) {
                    container.html(data);
                    ctl.show();
                } else {
                    var msg = 'Operation was successful';
                    if(show_resp) {
                        msg = data;

                        // data should be a JSON-encoded string, for example:
                        //   '{"result":0,"message":"Operation was successful: Deleted 1 analysis job"}'

                        // Look for a message parameter, which many DMS stored procedures have as an output parameter
                        var dataObject = jQuery.parseJSON(data);
                        if (dataObject.message)
                            msg = dataObject.message;
                    }
                    container.html(msg);
                    ctl.hide();
                    dmsOps.reloadListReportData();
                }
            }
        );
    },
    /**
     * Submit list report supplemental command using "call" semantics
     * @param {string} url
     * @param {object} p Object to post
     * @param {boolean} show_resp Show response (unused)
     * @returns {undefined}
     */
    submitCall: function(url, p, show_resp) {
        var ctl = $('#' + dmsjs.pageContext.cntrlContainerId);
        var container = $('#' + dmsjs.pageContext.responseContainerId);
        container.spin('small');
        $.post(url, p, function (data) {
                container.spin(false);
                var obj = $.parseJSON(data);
                container.html(obj.message);
                if(obj.result) {
                    ctl.show();
                } else {
                    ctl.hide();
                    dmsOps.reloadListReportData();
                }
            }
        );
    },
    /**
     * Create a dynamic form with JSON data to submit to a URL and load the new page
     * @param {type} url
     * @param {type} jsonObj
     * @returns {undefined}
     */
    submitDynamicForm: function(url, jsonObj) {
        var keys = Object.getOwnPropertyNames(jsonObj);
        var inputs = "";
        keys.forEach(function(item){
            inputs += '<input type="hidden" name="' + item + '" value="' + jsonObj[item] + '" />'
        });
        $("body").append('<form action="' + url + '" method="post" id="dynamicForm">' + inputs + '</form>');
        $("#dynamicForm").trigger('submit');
    }

}; // dmsOps
