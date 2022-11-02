﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Data_Analysis_Request');
INSERT INTO general_params VALUES('list_report_data_table','V_Data_Analysis_Request_List_Report');
INSERT INTO general_params VALUES('detail_report_data_table','V_Data_Analysis_Request_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','ID');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_page_data_table','v_data_analysis_request_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateDataAnalysisRequest');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'ID','invoke_entity','ID','data_analysis_request/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'Days In Queue','color_label','#days_in_queue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(3,'Work Package','invoke_entity','value','charge_code/show','');
INSERT INTO list_report_hotlinks VALUES(4,'WP State','color_label','#wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(5,'State Comment','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(6,'Description','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(7,'Batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO list_report_hotlinks VALUES(8,'Data Package','invoke_entity','value','data_package/show','');
INSERT INTO list_report_hotlinks VALUES(9,'Exp. Group','invoke_entity','value','experiment_group/show','');
INSERT INTO list_report_hotlinks VALUES(10,'EUS Proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO list_report_hotlinks VALUES(11,'Organism','invoke_entity','value','organism/report/~','');
INSERT INTO list_report_hotlinks VALUES(12,'Campaign','invoke_entity','value','campaign/show','');
INSERT INTO list_report_hotlinks VALUES(13,'Analysis Type','invoke_entity','value','data_analysis_request/report/-/-/~','');
INSERT INTO list_report_hotlinks VALUES(14,'#days_in_queue','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(15,'#wp_activation_state','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(16,'+ID','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(17,'+Dataset Count','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(18,'+Days In Queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(19,'+Work Package','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(20,'+WP State','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(21,'+EUS Proposal','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(22,'Files','invoke_entity','ID','file_attachment/report/-/StartsWith__data_analysis_request/@','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_request_name','Request Name','32','','Request Name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_type','Type','32','','Analysis Type','ContainsText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_organism','Organism','32','','Organism','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_WP','WP','32','','WP','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_assigned_personnel','Assigned Personnel','32','','Assigned Personnel','ContainsText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_batch','Batch','32','','Batch','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_data_package','Data Package','32','','Data Package','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(11,'pf_experiment_group','Exp. Group','32','','Exp. Group','Equals','text','32','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'Requester','detail-report','Requester','user/report/-/~','labelCol','dl_Researcher','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO detail_report_hotlinks VALUES(2,'Description','markup','Description','','valueCol','dl_Description','');
INSERT INTO detail_report_hotlinks VALUES(3,'Analysis Specifications','markup','Analysis Specifications','','valueCol','dl_Analysis_Specifications','');
INSERT INTO detail_report_hotlinks VALUES(4,'Requested Run Batch IDs','link_list','Requested Run Batch IDs','requested_run_batch/show','valueCol','dl_Requested_Run_Batches','');
INSERT INTO detail_report_hotlinks VALUES(5,'+Requested Run Batch IDs','detail-report','ID','data_analysis_request_batch_datasets/report','labelCol','dl_Requested_Run_Batch_Datasets','');
INSERT INTO detail_report_hotlinks VALUES(6,'Data Package','detail-report','Data Package','data_package/show','valueCol','dl_Data_Package','');
INSERT INTO detail_report_hotlinks VALUES(7,'Experiment Group','detail-report','Experiment Group','experiment_group/show','valueCol','dl_Experiment_Group','');
INSERT INTO detail_report_hotlinks VALUES(8,'Campaign','detail-report','Campaign','campaign/show','valueCol','dl_Campaign','');
INSERT INTO detail_report_hotlinks VALUES(9,'Organism','detail-report','Organism','organism/report/~','valueCol','dl_Organism','');
INSERT INTO detail_report_hotlinks VALUES(10,'Work Package','detail-report','Work Package','charge_code/show','labelCol','dl_Work_Package','');
INSERT INTO detail_report_hotlinks VALUES(11,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show','valueCol','dl_EUS_Proposal','');
INSERT INTO detail_report_hotlinks VALUES(12,'Updates','detail-report','ID','data_analysis_request_updates/report','labelCol','dl_Updates','');
INSERT INTO detail_report_hotlinks VALUES(13,'Work Package State','color_label','#wp_activation_state','','valueCol','dl_Work_Package_State','{"3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO detail_report_hotlinks VALUES(14,'EUS Proposal State','color_label','EUS Proposal State','','valueCol','dl_EUS_Proposal_State','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'request_name','requestName','varchar','input','128','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(2,'analysis_type','analysisType','varchar','input','16','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(3,'requester_prn','requesterPRN','varchar','input','32','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(4,'description','description','varchar','input','1024','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(5,'analysis_specifications','analysisSpecifications','varchar','input','2048','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(6,'comment','Comment','varchar','intput','2048','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(7,'batch_ids','batchIDs','varchar','input','1024','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(8,'data_package_id','dataPackageID','int','input','','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(9,'exp_group_id','expGroupID','int','input','','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(10,'work_package','workPackage','varchar','input','64','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(11,'requested_personnel','requestedPersonnel','varchar','input','256','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(12,'assigned_personnel','assignedPersonnel','varchar','input','256','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(13,'priority','priority','varchar','input','12','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(14,'reason_for_high_priority','reasonForHighPriority','varchar','input','1024','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(15,'estimated_analysis_time_days','estimatedAnalysisTimeDays','int','input','','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(16,'state_name','state','varchar','input','32','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(17,'state_comment','stateComment','varchar','input','512','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(18,'id','id','int','output','','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(19,'<local>','mode','varchar','input','24','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(20,'<local>','message','varchar','output','512','AddUpdateDataAnalysisRequest');
INSERT INTO sproc_args VALUES(21,'<local>','callingUser','varchar','input','128','AddUpdateDataAnalysisRequest');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'request_name','Request Name','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(3,'analysis_type','Analysis Type','text','60','60','','','','trim|max_length[60]|required');
INSERT INTO form_fields VALUES(4,'requester_prn','Requester','text','32','32','','','','trim|max_length[32]|required');
INSERT INTO form_fields VALUES(5,'description','Description','area','','','3','60','','trim|max_length[1024]|required');
INSERT INTO form_fields VALUES(6,'analysis_specifications','Analysis Specifications','area','','','3','60','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(7,'comment','Comment','area','','','3','60','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(8,'batch_ids','Batch IDs','area','','','2','60','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(9,'data_package_id','Data Package ID','text','12','10','','','','trim|max_length[10]|numeric');
INSERT INTO form_fields VALUES(10,'exp_group_id','Experiment Group ID','text','12','10','','','','trim|max_length[10]|numeric');
INSERT INTO form_fields VALUES(11,'work_package','Work Package','text','12','15','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(12,'requested_personnel','Requested Personnel','area','','','2','60','','trim|required|max_length[256]');
INSERT INTO form_fields VALUES(13,'assigned_personnel','Assigned Personnel','area','','','2','60','','trim|max_length[256]');
INSERT INTO form_fields VALUES(14,'priority','Priority','text','12','12','','','Normal','trim|max_length[12]');
INSERT INTO form_fields VALUES(15,'reason_for_high_priority','Reason For High Priority','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(16,'estimated_analysis_time_days','Estimated Analysis Time (days)','text','12','10','','','','trim|max_length[10]|numeric');
INSERT INTO form_fields VALUES(17,'state_name','State','text','32','32','','','New','trim|max_length[32]|required');
INSERT INTO form_fields VALUES(18,'state_comment','State Comment','area','','','3','60','','trim|max_length[512]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'requester_prn','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'analysis_type','picker.replace','dataAnalysisTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'batch_ids','list-report.helper','','helper_requested_run_batch_ckbx/report','batch_ids',',','Choose from:');
INSERT INTO form_field_choosers VALUES(4,'data_package_id','list-report.helper','','helper_data_package/report/-/','data_package_id',',','Choose from:');
INSERT INTO form_field_choosers VALUES(5,'exp_group_id','list-report.helper','','helper_experiment_group/report','exp_group_id',',','Choose from:');
INSERT INTO form_field_choosers VALUES(6,'requested_personnel','picker.append','dataAnalysisRequestUserPickList','','','; ','');
INSERT INTO form_field_choosers VALUES(7,'assigned_personnel','picker.append','dataAnalysisRequestUserPickList','','','; ','');
INSERT INTO form_field_choosers VALUES(8,'state_name','picker.replace','dataAnalysisRequestStatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'priority','picker.replace','operationsTaskPriority','','',',','');
INSERT INTO form_field_choosers VALUES(10,'work_package','list-report.helper','','helper_charge_code/report','',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'id','section','Basic Information');
INSERT INTO form_field_options VALUES(2,'batch_ids','section','Source Data');
INSERT INTO form_field_options VALUES(3,'requested_personnel','section','Assignment and Scheduling');
INSERT INTO form_field_options VALUES(4,'work_package','section','Project Tracking Information');
INSERT INTO form_field_options VALUES(5,'estimated_analysis_time_days','section','Staff Notes');
COMMIT;
