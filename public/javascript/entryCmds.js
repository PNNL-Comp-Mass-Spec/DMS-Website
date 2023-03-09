// After updating this page, increment the version ID defined on the base_url line in file app/Views/main/entry_form.php
// This is required to force browsers to update the cached version of this file

var entryCmds = {

    analysis_job_request_psm: {
        createRequest: function() {
            this.submitMainEntryForm('add', this.showPageLinks);
        },
        previewRequest: function() {
            this.submitMainEntryForm('preview', function() {
                var mm = $('#main_outcome_msg');
                var sm = $('#supplement_outcome_msg');
                if(mm && sm) { sm.html(mm.html())}
            });
        },
        submitMainEntryForm: function(mode, followOnAction) {
            $('#requestID').val('0');
            $('#move_next_link').hide();
            var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + "/submit_entry_form";
            entry.submitEntryFormToPage(url, mode, followOnAction);
        },
        showPageLinks: function() {
            var id = $('#requestID').val();
            if(id != '0') {
                var url = gamma.pageContext.site_url + "analysis_job_request/show/" + id;
                $('#move_next_link').attr('href', url);
                $('#move_next_link').show();
            }
        },
        // This method is invoked by analysis_job_request_psm based on the form field with type 'action'
        getJobDefaults: function() {
            var url = gamma.pageContext.my_tag + '/get_defaults';
            this.callOperation(url);
        },
        callOperation: function(url) {
            var caller = this;
            url =  gamma.pageContext.site_url + url;
            var p = { datasets: $('#datasets').val() };
            gamma.loadContainer(url, p, 'supplemental_material', function (data) {
                    $('#sub_cmd_buttons').show();
                    caller.setFieldValues();
                }
            );
        },
        setFieldValues: function() {
            if($('#return_code').val() != 'success') return;

            $('#toolName').val($('#suggested_ToolName').val());
            $('#jobTypeName').val($('#suggested_JobTypeName').val());
            $('#organismName').val($('#suggested_OrganismName').val());
            $('#protCollNameList').val($('#suggested_ProteinCollectionList').val());
            $('#protCollOptionsList').val($('#suggested_ProteinOptionsList').val());

            $('#ModificationDynMetOx').attr('checked', $('#suggested_DynMetOxEnabled').val() == '1');
            $('#ModificationStatCysAlk').attr('checked', $('#suggested_StatCysAlkEnabled').val() == '1');
            $('#ModificationDynSTYPhos').attr('checked', $('#suggested_DynSTYPhosEnabled').val() == '1');

            entry.showHideSections('show', '3,4,5');
        },
        cmdInit: function() {
            $('#move_next_link').hide();
            entry.showHideSections('hide', '3,4,5');
        }
    }, // analysis_job_request_psm
    mac_jobs: {
        // get supplemental form fields via an AJAX call
        load_param_form: function () {
            var caller = this;
            var script = $('#script_name').val();
            var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/parameter_form/' + script;
            entry.load_supplemental_form(url, {}, 'param_container', function() { caller.revealControls(script); });
        },
        choose_template: function (template_name) {
            $('#script_name').val(template_name);
            this.load_param_form();
        },
        set_param_row_visibility: function (class_name, visibility) {
            $('.' + class_name).each(function(idx, obj){
                obj.style.display = visibility;
            });
        },
        revealControls: function (script) {
            this.set_param_row_visibility("hide_input", "none");
            if(script) $('#cmd_buttons').show();
            $('.sel_chooser').select2();
        },
        cmdInit: function () {
            // relocate standard family command buttons
            $('#relocated_buttons').append($('#cmd_buttons'));
            $('#cmd_buttons').hide();

            // define action to capture contents of param form
            // as xml copied to main form field
            entry.actions.before = function() {
                entry.copy_param_form_to_xml_param_field('param_form', 'job_param');
                return true;
            }
            entryCmds.mac_jobs.load_param_form();
        }
    }, // mac_jobs
    pipeline_jobs: {
        load_param_form: function () {
            var url = gamma.pageContext.site_url + gamma.pageContext.my_tag + '/parameter_form/' + $('#job').val() + '/' + $('#script_name').val();
            entry.load_supplemental_form(url, {}, 'param_container', function() {
                entryCmds.pipeline_jobs.set_param_row_visibility("hide_input", "none");
            });
        },
        choose_script: function (script) {
            $('#script_name').val(script);
            this.load_param_form();
        },
        set_param_row_visibility: function (class_name, visibility) {
            $('.' + class_name).each(function(idx, obj) {
                obj.style.display = visibility;
            });
        },
        cmdInit: function () {
            entry.actions.before = function() {
                entry.copy_param_form_to_xml_param_field('param_form', 'job_param', true);
                return true;
            }
            entryCmds.pipeline_jobs.load_param_form();
            gamma.load_script_diagram_cmd();
            return true;
        }

    }, // pipeline_jobs
    sample_prep_request: {
        approveSubmit: function() {
            // State memory of whether special close dialog can be skipped or not.
            // Modal dialog sets it and then re-triggers submit command.
            // state is remembered within closure, so that the subsequent
            // call to aproveSubmit knows that user has approved proceding with action.
            var proceed = false;
            // this function will be called by standard submit sequence
            // prior to actually submitting form to server
            return function(mode) {
                // check whether or not we need to have user confirm submit
                var skip = mode == "add";
                proceed = skip || entryCmds.sample_prep_request.checkMaterial(proceed);
                if(!proceed) {
                    // Prior to September 2018, we would show the user a modal dialog asking:
                    //    "Should the associated containers and biomaterial also be retired?"
                    // Options were:
                    //    "Yes, close the request and retire materials/containers"
                    //    or "No, just close the request"
                    //    or "Cancel"
                    //
                    // This functionality was removed because we no longer
                    // associate biomaterial (aka cell cultures) with sample prep requests
                    // and thus there is nothing to retire

                    // Options for obtaining the text to show the user:
                    // Option 1:
                    //    var text = $('#message_contents').html();

                    // Option 2:
                    //    Hook into a hidden form_field
                    //    To hide a field, update form_field_options to include the field name, type hide, and parameter update
                    //    var text = $('#message_contents').val();

                    // Option 3:
                    //    Hard-code the message
                    var text = 'Should the associated containers and biomaterial also be retired?';

                    $( "<div></div>" ).html(text).dialog({
                        height:200,
                        width: 650,
                        modal: true,
                        buttons: {
                           "Yes, close the request and retire materials/containers": function() {
                                $('#State').val('Closed (containers and material)');
                                $( this ).dialog( "close" );
                                proceed = true;
                                $('#primary_cmd').trigger("click"); // retrigger the submit
                            },
                            "No, just close the request": function() {
                                $( this ).dialog( "close" );
                                proceed = true;
                                $('#primary_cmd').trigger("click"); // retrigger the submit
                           },
                            Cancel: function() {
                                proceed = false;
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                }
                return proceed;
            }
        }(),
        checkMaterial: function (proceed) {
            var state = $('#State').val();
            if((state != 'Closed') ) {
                proceed = true;
            }
            return proceed;
        },
        cmdInit: function () {
            // Prior to September 2018, we would show the user a modal dialog asking
            //    "Should the associated containers and biomaterial also be retired?"
            //
            // This functionality was removed because we no longer
            // associate biomaterial (aka cell cultures) with sample prep requests
            // and thus there is nothing to retire

            /*
             * Disabled
             *
                // Set hook to trap standard page submit sequence
                // See submitStandardEntryPage in entry.js
                entry.actions.before = entryCmds.sample_prep_request.approveSubmit;
             *
             */
        }
    } // sample_prep_request
} // entry
