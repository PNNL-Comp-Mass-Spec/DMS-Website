﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Requested_Run_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Requested_Run_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Request');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateRequestedRun');
INSERT INTO "general_params" VALUES('entry_page_data_table','v_requested_run_entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','request_id');
INSERT INTO "general_params" VALUES('post_submission_detail_id','request_id');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateRequestedRunAssignments');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'request_id','Request','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'request_name','Request Name','text','60','80','','','','trim|required|max_length[90]|alpha_dash|min_length[8]');
INSERT INTO "form_fields" VALUES(3,'experiment','Experiment Name','text','40','80','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(4,'instrument_group','Instrument Group','text','25','80','','','(lookup)','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(5,'dataset_type','Run Type','text','25','80','','','(lookup)','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(6,'separation_group','Separation Group','text','25','80','','','(lookup)','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(7,'requester_prn','Requester (Username)','text','25','80','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(8,'instrument_settings','Instrument Settings','area','','','6','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(9,'staging_location','Staging Location','text','40','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(10,'wellplate','Wellplate','text','40','80','','','(lookup)','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(11,'well','Well','text','40','80','','','(lookup)','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(12,'vialing_conc','Vialing Concentration','text','25','80','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(13,'vialing_vol','Vialing Volume','text','25','80','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(14,'comment','Comment','area','','','6','80','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(15,'batch_id','Batch','text','15','24','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(16,'block','Block','text','15','24','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(17,'run_order','Run_Order','text','15','24','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(18,'work_package','Work Package','text','15','50','','','(lookup)','trim|max_length[50]|required');
INSERT INTO "form_fields" VALUES(19,'eus_usage_type','EMSL Usage Type','text','15','50','','','(lookup)','trim|required|max_length[50]|not_contain[(unknown)]');
INSERT INTO "form_fields" VALUES(20,'eus_proposal_id','EMSL Proposal ID','text','10','10','','','(lookup)','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(21,'eus_users','EMSL Proposal User','text','25','1024','','','(lookup)','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(22,'mrm_attachment','MRM Transition List Attachment','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(23,'internal_standard','Dataset Internal Standard','hidden','','','','','none','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(24,'state_name','Status','text','24','24','','','Active','trim|max_length[24]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'requester_prn','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'comment','auto_format','none');
INSERT INTO "form_field_options" VALUES(3,'request_name','load_key_field','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'experiment','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'instrument_group','picker.replace','requestedRunInstrumentGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'dataset_type','list-report.helper','','data/lr/ad_hoc_query/helper_inst_group_dstype/report','instrument_group',',','');
INSERT INTO "form_field_choosers" VALUES(4,'separation_group','picker.replace','separationGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'requester_prn','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'wellplate','picker.replace','wellplatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'eus_usage_type','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'eus_users','list-report.helper','','helper_eus_user/report','eus_proposal_id',',','Select User...');
INSERT INTO "form_field_choosers" VALUES(9,'mrm_attachment','list-report.helper','','helper_mrm_attachment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'eus_proposal_id','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(11,'eus_proposal_id','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO "form_field_choosers" VALUES(12,'state_name','picker.replace','activeInactivePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'comment','link.list','multiDatasetRequestCommentTmpl','','',',','Use Template:');
INSERT INTO "form_field_choosers" VALUES(14,'work_package','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO "form_field_choosers" VALUES(15,'staging_location','list-report.helper','','helper_material_location','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','45!','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_request','RequestID','6!','','Request','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_status','Status','6!','','Status','StartsWithText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_batch','Batch','4!','','Batch','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','20!','','Campaign','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_requestNameCode','Code','10!','','Request Name Code','StartsWithText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_instrument_group','Inst. Group','32','','Inst. Group','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_work_package','Work Pkg','32','','Work Package','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_comment','Comment','20','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_queue_state','Queue State','20','','Queue State','StartsWithText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(12,'pf_experiment','Experiment','20!','','Experiment','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Request','invoke_entity','value','requested_run/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Campaign','invoke_entity','value','campaign/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Experiment','invoke_entity','value','experiment/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(6,'Batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO "list_report_hotlinks" VALUES(8,'Work Package','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(9,'WP State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(10,'Proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO "list_report_hotlinks" VALUES(11,'Comment','markup','Comment','60','');
INSERT INTO "list_report_hotlinks" VALUES(12,'Proposal State','color_label','value','','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
INSERT INTO "list_report_hotlinks" VALUES(13,'Queue State','invoke_entity','Campaign','requested_run_admin/report/-/-/-/-/~@/-/-/-','');
INSERT INTO "list_report_hotlinks" VALUES(14,'#DaysInQueue','no_export','value','','');
INSERT INTO "list_report_hotlinks" VALUES(15,'#WPActivationState','no_export','value','','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Delete this request','cmd_op','delete','requested_run','Delete this requested run.','Are you sure that you want to delete this requested run?');
INSERT INTO "detail_report_commands" VALUES(2,'Convert Run to Dataset','copy_from','','dataset','Go to dataset entry page and copy information from this scheduled run.','');
INSERT INTO "detail_report_commands" VALUES(3,'Convert Request Into Fractions','copy_from','','requested_run_fraction','Created a series of new requested run fractions; only applicable for LC-Nano separation groups','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Experiment','detail-report','Experiment','experiment/show','labelCol','experiment',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Batch','detail-report','Batch','requested_run_batch/show','labelCol','batch',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Block','detail-report','Batch','requested_run_batch_blocking/param','valueCol','block','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Dataset','detail-report','Dataset','dataset/show','valueCol','dataset','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Factors','detail-report','Request','custom_factors/report/-','labelCol','dl_show_factors',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'+Factors','detail-report','Request','requested_run_factors/param/@/Requested_Run_ID','valueCol','dl_edit_factors',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(8,'Instrument Group','detail-report','Instrument Group','instrument_group/show/','valueCol','dl_instrument_group','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Instrument Used','detail-report','Instrument Used','instrument/show/','valueCol','dl_instrument','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show','valueCol','dl_eus_proposal','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Work Package','detail-report','Work Package','charge_code/show','labelCol','dl_Work_Package',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(12,'Work Package State','color_label','#WPActivationState','','valueCol','dl_Work_Package_State','{"3":"clr_90","4":"clr_120", "5":"clr_120","10":"clr_120"}');
INSERT INTO "detail_report_hotlinks" VALUES(13,'Requester','detail-report','Username','user/show/','labelCol','dl_Requester','');
INSERT INTO "detail_report_hotlinks" VALUES(14,'Separation Group','detail-report','Separation Group','separation_group/show','labelCol','dl_separation_group','');
INSERT INTO "detail_report_hotlinks" VALUES(15,'Staging Location','detail-report','Staging Location','material_location/report/~@','valueCol','dl_staging_location','');
INSERT INTO "detail_report_hotlinks" VALUES(16,'Comment','markup','Comment','','valueCol','dl_comment','');
INSERT INTO "detail_report_hotlinks" VALUES(17,'EUS Proposal State','color_label','EUS Proposal State','','valueCol','dl_eus_proposal_state','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}

');
INSERT INTO "detail_report_hotlinks" VALUES(18,'Queue State','detail-report','Campaign','requested_run_admin/report/-/-/-/-/~@/-/-/-','labelCol','dl_requested_run_admin','');
INSERT INTO "detail_report_hotlinks" VALUES(19,'Days In Queue','detail-report','Instrument Group','run_planning/report/~@/-/-/-/-/-/-/-','labelCol','dl_run_planning','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'request_name','reqName','varchar','input','128','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(2,'experiment','experimentNum','varchar','input','64','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(3,'requester_prn','requesterPRN','varchar','input','64','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(4,'instrument_group','instrumentName','varchar','input','64','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(5,'work_package','workPackage','varchar','input','50','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(6,'dataset_type','msType','varchar','input','20','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(7,'instrument_settings','instrumentSettings','varchar','input','512','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(8,'wellplate','wellplateNum','varchar','input','64','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(9,'well','wellNum','varchar','input','24','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(10,'internal_standard','internalStandard','varchar','input','50','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(11,'comment','comment','varchar','input','1024','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(12,'batch_id','batch','int','input','','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(13,'block','block','int','input','','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(14,'run_order','runOrder','int','input','','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(15,'eus_proposal_id','eusProposalID','varchar','input','10','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(16,'eus_usage_type','eusUsageType','varchar','input','50','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(17,'eus_users','eusUsersList','varchar','input','1024','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(18,'state_name','status','varchar','input','24','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(19,'<local>','mode','varchar','input','12','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(20,'request_id','request','int','output','','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(21,'<local>','message','varchar','output','1024','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(22,'separation_group','secSep','varchar','input','64','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(23,'mrm_attachment','MRMAttachment','varchar','input','128','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(24,'<local>','callingUser','varchar','input','128','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(25,'vialing_conc','VialingConc','varchar','input','32','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(26,'vialing_vol','VialingVol','varchar','input','32','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(27,'staging_location','stagingLocation','varchar','input','64','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(28,'request_id','requestIDForUpdate','int','input','','AddUpdateRequestedRun');
INSERT INTO "sproc_args" VALUES(29,'<local>','mode','varchar','input','32','UpdateRequestedRunAssignments');
INSERT INTO "sproc_args" VALUES(30,'Param','newValue','varchar','input','512','UpdateRequestedRunAssignments');
INSERT INTO "sproc_args" VALUES(31,'ID','reqRunIDList','varchar','input','64000','UpdateRequestedRunAssignments');
INSERT INTO "sproc_args" VALUES(32,'<local>','message','varchar','output','512','UpdateRequestedRunAssignments');
INSERT INTO "sproc_args" VALUES(33,'<local>','callingUser','varchar','input','128','UpdateRequestedRunAssignments');
COMMIT;
