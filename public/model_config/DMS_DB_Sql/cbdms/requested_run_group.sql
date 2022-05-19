﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('entry_sproc','AddRequestedRuns');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Requested_Run_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','RR_Request');
INSERT INTO "general_params" VALUES('post_submission_link_tag','requested_run');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'experimentGroupID','Experiment Group ID','text','12','12','','','','trim|required|max_length[12]');
INSERT INTO "form_fields" VALUES(2,'requestNameSuffix','Request Name Suffix','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(3,'RR_Instrument_Group','Instrument Group','text','25','80','','','(lookup)','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'RR_Type','Run Type','text','25','80','','','(lookup)','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(5,'SeparationGroup','Separation Group','text','25','64','','','(lookup)','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(6,'RR_Requestor','Requester (Username)','text','25','50','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(7,'RR_Instrument_Settings','Instrument Settings','area','','','6','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(8,'StagingLocation','Staging Location','text','40','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(9,'RR_VialingConc','Vialing Concentration','text','25','80','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(10,'RR_VialingVol','Vialing Volume','text','25','80','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(11,'RR_Comment','Comment','area','','','4','60','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(12,'RR_WorkPackage','Work Package','text','15','50','','','(lookup)','trim|max_length[50]|required');
INSERT INTO "form_fields" VALUES(15,'RR_EUSUsageType','EMSL Usage Type','text','15','50','','','(lookup)','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(16,'RR_EUSProposalID','EMSL Proposal ID','text','10','10','','','(lookup)','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(17,'RR_EUSUsers','EMSL Users List','area','','','4','60','(lookup)','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(18,'MRMAttachment','MRM Transition List Attachment','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(19,'RR_Internal_Standard','Dataset Internal Standard','hidden','','','','','none','trim|max_length[50]|required');
INSERT INTO "form_fields" VALUES(20,'experimentList','','hidden','','','','','','trim|max_length[3500]');
INSERT INTO "form_fields" VALUES(21,'BatchName','Batch Name','text','25','50','','','','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(22,'BatchDescription','Batch Description','text','','','2','60','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(23,'BatchCompletionDate','Batch Completion Date','text','25','32','','','','trim|max_length[32]|valid_date');
INSERT INTO "form_fields" VALUES(24,'BatchPriority','Requested Batch Priority','text','24','','','','Normal','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(25,'BatchPriorityJustification','Justification High Priority','area','','','4','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(26,'BatchComment','Comment','area','','','4','60','','trim|max_length[512]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'RR_Requestor','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'BatchName','section','Batch Information (optional)');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'experimentGroupID','list-report.helper','','helper_experiment_group/report','experimentGroupID',',','');
INSERT INTO "form_field_choosers" VALUES(2,'RR_Instrument_Group','picker.replace','instrumentGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'RR_Type','list-report.helper','','data/lr/ad_hoc_query/helper_inst_group_dstype/report','RR_Instrument_Group',',','');
INSERT INTO "form_field_choosers" VALUES(4,'SeparationGroup','picker.replace','separationGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'RR_Requestor','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'RR_EUSUsageType','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'RR_EUSUsers','list-report.helper','','helper_eus_user_ckbx/report','RR_EUSProposalID',',','');
INSERT INTO "form_field_choosers" VALUES(9,'MRMAttachment','list-report.helper','','helper_mrm_attachment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'RR_EUSProposalID','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(11,'RR_EUSProposalID','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO "form_field_choosers" VALUES(12,'RR_WorkPackage','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'StagingLocation','list-report.helper','','helper_material_location','',',','');
INSERT INTO "form_field_choosers" VALUES(14,'BatchCompletionDate','picker.prevDate','futureDatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(15,'BatchPriority','picker.replace','batchPriorityPickList','','',',','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'experimentGroupID','experimentGroupID','varchar','input','12','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(2,'experimentList','experimentList','varchar','input','3500','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(3,'requestNameSuffix','requestNameSuffix','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(4,'RR_Requestor','operPRN','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(5,'RR_Instrument_Group','instrumentGroup','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(6,'RR_WorkPackage','workPackage','varchar','input','50','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(7,'RR_Type','msType','varchar','input','20','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(8,'RR_Instrument_Settings','instrumentSettings','varchar','input','512','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(11,'RR_EUSProposalID','eusProposalID','varchar','input','10','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(12,'RR_EUSUsageType','eusUsageType','varchar','input','50','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(13,'RR_EUSUsers','eusUsersList','varchar','input','1024','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(14,'RR_Internal_Standard','internalStandard','varchar','input','50','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(15,'RR_Comment','comment','varchar','input','1024','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(16,'<local>','mode','varchar','input','12','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(17,'<local>','message','varchar','output','512','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(18,'SeparationGroup','separationGroup','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(19,'MRMAttachment','mrmAttachment','varchar','input','128','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(20,'RR_VialingConc','VialingConc','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(21,'RR_VialingVol','VialingVol','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(22,'StagingLocation','stagingLocation','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(23,'<local>','callingUser','varchar','input','128','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(24,'BatchName','batchName','varchar','input','50','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(25,'BatchDescription','batchDescription','varchar','input','256','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(26,'BatchCompletionDate','batchCompletionDate','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(27,'BatchPriority','batchPriority','varchar','input','24','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(28,'BatchPriorityJustification','batchPriorityJustification','varchar','input','512','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(29,'BatchComment','batchComment','varchar','input','512','AddRequestedRuns');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'PreviewAdd','cmd','Preview Add','Determine if current values are valid, but do not change database.','');
COMMIT;
