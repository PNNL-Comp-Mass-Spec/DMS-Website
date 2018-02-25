PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('entry_sproc','AddRequestedRuns');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Requested_Run_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','RR_Request');
INSERT INTO "general_params" VALUES('post_submission_link_tag','requested_run');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'experimentGroupID','Experiment Group ID','text','12','12','','','','trim|required|max_length[12]');
INSERT INTO "form_fields" VALUES(2,'requestNamePrefix','Request Name Suffix','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(3,'RR_Instrument','Instrument Group','text','25','80','','','(lookup)','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'RR_Type','Run Type','text','25','80','','','(lookup)','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(5,'RR_SecSep','Separation Group','text','25','64','','','(lookup)','trim|required|max_length[64]');
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
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'RR_Requestor','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'experimentGroupID','list-report.helper','','helper_experiment_group/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'RR_Instrument','picker.replace','instrumentGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'RR_Type','list-report.helper','','data/lr/ad_hoc_query/helper_inst_group_dstype/report','RR_Instrument',',','');
INSERT INTO "form_field_choosers" VALUES(4,'RR_SecSep','picker.replace','separationGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'RR_Requestor','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'RR_EUSUsageType','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'RR_EUSUsers','list-report.helper','','helper_eus_user_ckbx/report','RR_EUSProposalID',',','');
INSERT INTO "form_field_choosers" VALUES(9,'MRMAttachment','list-report.helper','','helper_mrm_attachment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'RR_EUSProposalID','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(11,'RR_EUSProposalID','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO "form_field_choosers" VALUES(12,'RR_WorkPackage','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'StagingLocation','list-report.helper','','helper_material_location','',',','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'experimentGroupID','experimentGroupID','varchar','input','12','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(2,'experimentList','experimentList','varchar','input','3500','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(3,'requestNamePrefix','requestNamePrefix','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(4,'RR_Requestor','operPRN','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(5,'RR_Instrument','instrumentName','varchar','input','64','AddRequestedRuns');
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
INSERT INTO "sproc_args" VALUES(18,'RR_SecSep','secSep','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(19,'MRMAttachment','MRMAttachment','varchar','input','128','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(20,'RR_VialingConc','VialingConc','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(21,'RR_VialingVol','VialingVol','varchar','input','32','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(22,'StagingLocation','stagingLocation','varchar','input','64','AddRequestedRuns');
INSERT INTO "sproc_args" VALUES(23,'<local>','callingUser','varchar','input','128','AddRequestedRuns');
COMMIT;
