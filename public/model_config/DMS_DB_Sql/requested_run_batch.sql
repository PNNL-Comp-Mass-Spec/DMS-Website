﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Requested_Run_Batch_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Requested_Run_Batch_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateRequestedRunBatch');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Requested_Run_Batch_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('rss_data_table','V_Requested_Run_Batch_RSS');
INSERT INTO "general_params" VALUES('rss_description','Requested run batches with all requests satisfied by datasets (completed within last 30 days).');
INSERT INTO "general_params" VALUES('rss_item_link','requested_run_batch/show');
INSERT INTO "general_params" VALUES('operations_sproc','DoRequestedRunBatchOperation');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim|max_length[6]');
INSERT INTO "form_fields" VALUES(2,'Name','Name','text','50','50','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(3,'Description','Description','area','','','2','60','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(4,'RequestedRunList','Requests','area','','','4','60','','trim');
INSERT INTO "form_fields" VALUES(5,'OwnerPRN','Owner','text','24','24','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(6,'RequestedBatchPriority','Requested Batch Priority','text','24','24','','','Normal','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(7,'RequestedCompletionDate','Requested Completion Date','text','32','32','','','','trim|required|max_length[32]|valid_date');
INSERT INTO "form_fields" VALUES(8,'JustificationHighPriority','Justification High Priority','area','','','4','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(9,'RequestedInstrument','Instrument Group','text','24','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(10,'Comment','Comment','area','','','4','60','','trim|max_length[512]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'RequestedRunList','list-report.helper','','helper_requested_run/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'OwnerPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'RequestedBatchPriority','picker.replace','batchPriorityPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'RequestedInstrument','picker.replace','instrumentGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'RequestedCompletionDate','picker.prevDate','futureDatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','32','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_instrument','Inst. Group','32','','Inst. Group','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','32','','Description','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_owner','Owner','32','','Owner','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_days_in_queue','Days In Queue','20','','Days In Queue','GreaterThanOrEqualTo','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_req_priority','Req. Priority','32','','Req. Priority','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_requests','Requests','12','','Requests','GreaterThanOrEqualTo','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_ID','Batch ID','12','','ID','Equals','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_SeparationType','Separation Type','32','','Separation Type','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','requested_run_batch/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Requests','invoke_entity','ID','requested_run/report/-/-/Active','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Runs','invoke_entity','ID','requested_run/report/-/-/Completed/','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Min Days In Queue','color_label','#MinDaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(6,'First Request','invoke_entity','First Request','requested_run/show/','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Last Request','invoke_entity','Last Request','requested_run/show/','');
INSERT INTO "list_report_hotlinks" VALUES(8,'Comment','min_col_width','value','40','');
INSERT INTO "list_report_hotlinks" VALUES(9,'Blocked','invoke_entity','ID','requested_run_batch_blocking/param/@','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Lock batch','cmd_op','LockBatch','requested_run_batch','Lock batch to prevent changes to run order or membership','Are you sure that you want to lock this batch?');
INSERT INTO "detail_report_commands" VALUES(2,'Unlock batch','cmd_op','UnlockBatch','requested_run_batch','Unlock batch to permit changes to run order or membership','Are you sure that you want to unlock this batch?');
INSERT INTO "detail_report_commands" VALUES(3,'Delete batch','cmd_op','delete','requested_run_batch','Delete this requested run batch request.','Are you sure that you want to delete this batch?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Requests','detail-report','ID','requested_run/report/-/-/-','labelCol','requests',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'ID','detail-report','ID','requested_run_batch_blocking/param','labelCol','dl_batch_blocking','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'+ID','detail-report','ID','requested_run_batch/report/-/-/-/-/-/-/-/-/@','valueCol','dl_batch_list_report','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Factors','detail-report','ID','custom_factors/report','labelCol','dl_show_factors',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'+Factors','detail-report','ID','requested_run_factors/param/@/Batch_ID','valueCol','dl_edit_factors',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Instrument Group','detail-report','Instrument Group','instrument_group/show/','labelCol','dl_instrument_group',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'Instrument Used','detail-report','Instrument Used','instrument/show/','labelCol','dl_instrument','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Last Ordered','detail-report','ID','requested_run_batch_blocking/param/@','labelCol','dl_batch_run_order','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','id','int','output','','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(2,'Name','name','varchar','input','50','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(3,'Description','description','varchar','input','256','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(4,'RequestedRunList','requestedRunList','text','input','2147483647','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(5,'OwnerPRN','ownerPRN','varchar','input','24','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(6,'RequestedBatchPriority','requestedBatchPriority','varchar','input','24','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(7,'RequestedCompletionDate','requestedCompletionDate','varchar','input','32','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(8,'JustificationHighPriority','justificationHighPriority','varchar','input','512','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(9,'RequestedInstrument','requestedInstrument','varchar','input','64','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(10,'Comment','comment','varchar','input','512','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(11,'<local>','mode','varchar','input','12','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(12,'<local>','message','varchar','output','512','AddUpdateRequestedRunBatch');
INSERT INTO "sproc_args" VALUES(13,'ID','batchID','int','input','','DoRequestedRunBatchOperation');
INSERT INTO "sproc_args" VALUES(14,'<local>','mode','varchar','input','12','DoRequestedRunBatchOperation');
INSERT INTO "sproc_args" VALUES(15,'<local>','message','varchar','output','512','DoRequestedRunBatchOperation');
COMMIT;
