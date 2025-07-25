﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_requested_run_list_report_2');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_requested_run_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','request');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','add_update_requested_run');
INSERT INTO general_params VALUES('entry_page_data_table','v_requested_run_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','request_id');
INSERT INTO general_params VALUES('post_submission_detail_id','request_id');
INSERT INTO general_params VALUES('operations_sproc','update_requested_run_assignments');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'request_id','Request','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'request_name','Request Name','text','60','90','','','','trim|required|max_length[90]|alpha_dash|min_length[8]');
INSERT INTO form_fields VALUES(3,'experiment','Experiment Name','text','40','80','','','','trim|required|max_length[50]');
INSERT INTO form_fields VALUES(4,'instrument_group','Instrument Group','text','25','80','','','(lookup)','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(5,'dataset_type','Run Type','text','25','80','','','(lookup)','trim|required|max_length[50]');
INSERT INTO form_fields VALUES(6,'separation_group','Separation Group','text','25','80','','','(lookup)','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(7,'requester_username','Requester (Username)','text','25','80','','','','trim|required|max_length[24]');
INSERT INTO form_fields VALUES(8,'instrument_settings','Instrument Settings','area','','','6','60','','trim|max_length[512]');
INSERT INTO form_fields VALUES(9,'staging_location','Staging Location','text','40','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(10,'wellplate','Wellplate','text','40','80','','','(lookup)','trim|max_length[64]');
INSERT INTO form_fields VALUES(11,'well','Well','text','40','80','','','(lookup)','trim|max_length[24]');
INSERT INTO form_fields VALUES(12,'vialing_conc','Vialing Concentration','text','25','80','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(13,'vialing_vol','Vialing Volume','text','25','80','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(14,'comment','Comment','area','','','6','80','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(15,'batch_id','Batch','text','15','24','','','','trim|max_length[24]|Numeric');
INSERT INTO form_fields VALUES(16,'block','Block','text','15','24','','','','trim|max_length[24]|Numeric');
INSERT INTO form_fields VALUES(17,'run_order','Run_Order','text','15','24','','','','trim|max_length[24]|Numeric');
INSERT INTO form_fields VALUES(18,'work_package','Work Package','text','15','50','','','(lookup)','trim|max_length[50]|required');
INSERT INTO form_fields VALUES(19,'eus_usage_type','EMSL Usage Type','text','15','50','','','(lookup)','trim|required|max_length[50]|not_contain[(unknown)]');
INSERT INTO form_fields VALUES(20,'eus_proposal_id','EMSL Proposal ID','text','10','10','','','(lookup)','trim|max_length[10]');
INSERT INTO form_fields VALUES(21,'eus_users','EMSL Proposal User','text','25','1024','','','(lookup)','trim|max_length[1024]');
INSERT INTO form_fields VALUES(22,'mrm_attachment','MRM Transition List Attachment','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(23,'internal_standard','Dataset Internal Standard','hidden','','','','','none','trim|max_length[50]');
INSERT INTO form_fields VALUES(24,'state_name','Status','text','24','24','','','Active','trim|max_length[24]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'requester_username','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'comment','auto_format','none');
INSERT INTO form_field_options VALUES(3,'request_name','load_key_field','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'experiment','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO form_field_choosers VALUES(2,'instrument_group','picker.replace','requestedRunInstrumentGroupPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'dataset_type','list-report.helper','','data/lr/ad_hoc_query/helper_inst_group_dstype/report','instrument_group',',','');
INSERT INTO form_field_choosers VALUES(4,'separation_group','picker.replace','separationGroupPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'requester_username','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'wellplate','picker.replace','wellplatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(7,'eus_usage_type','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(8,'eus_users','list-report.helper','','helper_eus_user/report','eus_proposal_id',',','Select User...');
INSERT INTO form_field_choosers VALUES(9,'mrm_attachment','list-report.helper','','helper_mrm_attachment/report','',',','');
INSERT INTO form_field_choosers VALUES(10,'eus_proposal_id','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO form_field_choosers VALUES(11,'eus_proposal_id','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO form_field_choosers VALUES(12,'state_name','picker.replace','activeInactivePickList','','',',','');
INSERT INTO form_field_choosers VALUES(13,'comment','link.list','multiDatasetRequestCommentTmpl','','',',','Use Template:');
INSERT INTO form_field_choosers VALUES(14,'work_package','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO form_field_choosers VALUES(15,'staging_location','list-report.helper','','helper_material_location/report/Staging','',',','');
INSERT INTO form_field_choosers VALUES(16,'batch_id','list-report.helper','','helper_requested_run_batch/report','batch_id',',','Select Batch...');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','45!','','name','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_request','Request ID','8!','','request','Equals','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_status','Status','8!','','status','StartsWithText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_batch','Batch','6!','','batch','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_campaign','Campaign','20!','','campaign','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_request_name_code','Code','10!','','request_name_code','StartsWithText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_instrument','Instrument','32','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_instrument_group','Inst. Group','32','','inst_group','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_work_package','Work Pkg','32','','work_package','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_comment','Comment','20','','comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(11,'pf_queue_state','Queue State','20','','queue_state','StartsWithText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(12,'pf_experiment','Experiment','30!','','experiment','ContainsTextTPO','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'request','invoke_entity','value','requested_run/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'status','color_label','value','','{"Holding":"light_red_background"}');
INSERT INTO list_report_hotlinks VALUES(3,'campaign','invoke_entity','value','campaign/show','');
INSERT INTO list_report_hotlinks VALUES(4,'experiment','invoke_entity','value','experiment/show','');
INSERT INTO list_report_hotlinks VALUES(5,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(6,'inst_group','invoke_entity','value','instrument_group/show','');
INSERT INTO list_report_hotlinks VALUES(7,'days_in_queue','color_label','days_in_queue_bin','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(8,'batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO list_report_hotlinks VALUES(10,'work_package','invoke_entity','value','charge_code/show','');
INSERT INTO list_report_hotlinks VALUES(11,'wp_state','color_label','wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(12,'proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO list_report_hotlinks VALUES(13,'comment','markup','comment','60','');
INSERT INTO list_report_hotlinks VALUES(14,'proposal_state','color_label','value','','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
INSERT INTO list_report_hotlinks VALUES(15,'queue_state','invoke_entity','campaign','requested_run_admin/report/-/-/-/-/~@/-/-/-','');
INSERT INTO list_report_hotlinks VALUES(16,'days_in_queue_bin','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(17,'wp_activation_state','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(18,'cc_service_type','invoke_entity','value','cost_center_service_type/report/','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Delete this request','cmd_op','delete','requested_run','Delete this requested run.','Are you sure that you want to delete this requested run?');
INSERT INTO detail_report_commands VALUES(2,'Convert Run to Dataset','copy_from','','dataset','Go to dataset entry page and copy information from this scheduled run.','');
INSERT INTO detail_report_commands VALUES(3,'Convert Request Into Fractions','copy_from','','requested_run_fraction','Create a series of new requested run fractions; only applicable for LC-Nano separation groups','');
INSERT INTO detail_report_commands VALUES(4,'Make New Requested Runs (from Experiment Group)','copy_from','','requested_run_group','Create new requested runs from an experiment group copying metadata from this request','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'status','color_label','status','','valueCol','dl_status','{"Holding":"light_red_background"}');
INSERT INTO detail_report_hotlinks VALUES(2,'experiment','detail-report','experiment','experiment/show','labelCol','experiment',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'campaign','detail-report','campaign','campaign/show','labelCol','campaign',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'batch','detail-report','batch','requested_run_batch/show','labelCol','batch',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'block','detail-report','batch','requested_run_batch_blocking/param','valueCol','block','');
INSERT INTO detail_report_hotlinks VALUES(6,'dataset','detail-report','dataset','dataset/show','valueCol','dataset','');
INSERT INTO detail_report_hotlinks VALUES(7,'factors','detail-report','request','custom_factors/report/-','labelCol','dl_show_factors',NULL);
INSERT INTO detail_report_hotlinks VALUES(8,'+factors','detail-report','request','requested_run_factors/param/@/requested_run_id','valueCol','dl_edit_factors',NULL);
INSERT INTO detail_report_hotlinks VALUES(9,'instrument_group','detail-report','instrument_group','instrument_group/show/','valueCol','dl_instrument_group','');
INSERT INTO detail_report_hotlinks VALUES(10,'instrument_used','detail-report','instrument_used','instrument/show/','valueCol','dl_instrument','');
INSERT INTO detail_report_hotlinks VALUES(11,'eus_proposal','detail-report','eus_proposal','eus_proposals/show','valueCol','dl_eus_proposal','');
INSERT INTO detail_report_hotlinks VALUES(12,'work_package','detail-report','work_package','charge_code/show','labelCol','dl_Work_Package','');
INSERT INTO detail_report_hotlinks VALUES(13,'work_package_state','color_label','wp_activation_state','','valueCol','dl_Work_Package_State','{"3":"clr_90","4":"clr_120", "5":"clr_120","10":"clr_120"}');
INSERT INTO detail_report_hotlinks VALUES(14,'requester','detail-report','username','user/show/','labelCol','dl_Requester','');
INSERT INTO detail_report_hotlinks VALUES(15,'separation_group','detail-report','separation_group','separation_group/show','labelCol','dl_separation_group','');
INSERT INTO detail_report_hotlinks VALUES(16,'staging_location','detail-report','staging_location','material_location/report/~@','valueCol','dl_staging_location','');
INSERT INTO detail_report_hotlinks VALUES(17,'comment','markup','comment','','valueCol','dl_comment','');
INSERT INTO detail_report_hotlinks VALUES(18,'eus_proposal_state','color_label','eus_proposal_state','','valueCol','dl_eus_proposal_state',replace(replace('{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}\r\n','\r',char(13)),'\n',char(10)));
INSERT INTO detail_report_hotlinks VALUES(19,'queue_state','detail-report','campaign','requested_run_admin/report/-/-/-/-/~@/-/-/-','labelCol','dl_requested_run_admin','');
INSERT INTO detail_report_hotlinks VALUES(20,'days_in_queue','detail-report','instrument_group','run_planning/report/~@/-/-/-/-/-/-/-','labelCol','dl_run_planning','');
INSERT INTO detail_report_hotlinks VALUES(21,'column_name','detail-report','column_name','lc_column/show','valueCol','dl_column_name','');
INSERT INTO detail_report_hotlinks VALUES(22,'wp_activation_state','no_display','','',NULL,NULL,'');
INSERT INTO detail_report_hotlinks VALUES(23,'wellplate','detail-report','wellplate','wellplate/show','valueCol','dl_wellplate','');
INSERT INTO detail_report_hotlinks VALUES(24,'queued_instrument','detail-report','queued_instrument','instrument/show/','valueCol','dl_queued_instrument','');
INSERT INTO detail_report_hotlinks VALUES(25,'cart_config','detail-report','cart_config','cart_config/report/~@/-','valueCol','dl_cart_config','');
INSERT INTO detail_report_hotlinks VALUES(26,'cart','detail-report','cart','lc_cart/report/~@/-','valueCol','dl_cart','');
INSERT INTO detail_report_hotlinks VALUES(27,'cost_center_service_type_id','detail-report','cost_center_service_type_id','cost_center_service_type/report/','labelCol','dl_cost_center_service_type','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'request_name','requestName','varchar','input','128','add_update_requested_run');
INSERT INTO sproc_args VALUES(2,'experiment','experimentName','varchar','input','64','add_update_requested_run');
INSERT INTO sproc_args VALUES(3,'requester_username','requesterUsername','varchar','input','64','add_update_requested_run');
INSERT INTO sproc_args VALUES(4,'instrument_group','instrumentGroup ','varchar','input','64','add_update_requested_run');
INSERT INTO sproc_args VALUES(5,'work_package','workPackage','varchar','input','50','add_update_requested_run');
INSERT INTO sproc_args VALUES(6,'dataset_type','msType','varchar','input','20','add_update_requested_run');
INSERT INTO sproc_args VALUES(7,'instrument_settings','instrumentSettings','varchar','input','512','add_update_requested_run');
INSERT INTO sproc_args VALUES(8,'wellplate','wellplateName','varchar','input','64','add_update_requested_run');
INSERT INTO sproc_args VALUES(9,'well','wellNumber','varchar','input','24','add_update_requested_run');
INSERT INTO sproc_args VALUES(10,'internal_standard','internalStandard','varchar','input','50','add_update_requested_run');
INSERT INTO sproc_args VALUES(11,'comment','comment','varchar','input','1024','add_update_requested_run');
INSERT INTO sproc_args VALUES(12,'batch_id','batch','int','input','','add_update_requested_run');
INSERT INTO sproc_args VALUES(13,'block','block','int','input','','add_update_requested_run');
INSERT INTO sproc_args VALUES(14,'run_order','runOrder','int','input','','add_update_requested_run');
INSERT INTO sproc_args VALUES(15,'eus_proposal_id','eusProposalID','varchar','input','10','add_update_requested_run');
INSERT INTO sproc_args VALUES(16,'eus_usage_type','eusUsageType','varchar','input','50','add_update_requested_run');
INSERT INTO sproc_args VALUES(17,'eus_users','eusUsersList','varchar','input','1024','add_update_requested_run');
INSERT INTO sproc_args VALUES(18,'state_name','status','varchar','input','24','add_update_requested_run');
INSERT INTO sproc_args VALUES(19,'<local>','mode','varchar','input','12','add_update_requested_run');
INSERT INTO sproc_args VALUES(20,'request_id','request','int','output','','add_update_requested_run');
INSERT INTO sproc_args VALUES(21,'<local>','message','varchar','output','1024','add_update_requested_run');
INSERT INTO sproc_args VALUES(22,'separation_group','secSep','varchar','input','64','add_update_requested_run');
INSERT INTO sproc_args VALUES(23,'mrm_attachment','mrmAttachment','varchar','input','128','add_update_requested_run');
INSERT INTO sproc_args VALUES(24,'<local>','callingUser','varchar','input','128','add_update_requested_run');
INSERT INTO sproc_args VALUES(25,'vialing_conc','vialingConc','varchar','input','32','add_update_requested_run');
INSERT INTO sproc_args VALUES(26,'vialing_vol','vialingVol','varchar','input','32','add_update_requested_run');
INSERT INTO sproc_args VALUES(27,'staging_location','stagingLocation','varchar','input','64','add_update_requested_run');
INSERT INTO sproc_args VALUES(28,'request_id','requestIDForUpdate','int','input','','add_update_requested_run');
INSERT INTO sproc_args VALUES(29,'<local>','mode','varchar','input','32','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(30,'param','newValue','varchar','input','512','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(31,'id','reqRunIDList','varchar','input','64000','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(32,'<local>','message','varchar','output','512','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(33,'<local>','callingUser','varchar','input','128','update_requested_run_assignments');
COMMIT;
