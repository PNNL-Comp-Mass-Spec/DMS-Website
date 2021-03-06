﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Dataset');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Tracking_Dataset_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Tracking_Dataset_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Dataset');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Tracking_Dataset_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','datasetNum');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateTrackingDataset');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'datasetNum','datasetNum','varchar','input','128','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(2,'experimentNum','experimentNum','varchar','input','64','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(3,'operPRN','operPRN','varchar','input','64','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(4,'instrumentName','instrumentName','varchar','input','64','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(5,'runStart','runStart','varchar','input','32','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(6,'runDuration','runDuration','varchar','input','16','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(7,'comment','comment','varchar','input','512','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(8,'eusProposalID','eusProposalID','varchar','input','10','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(9,'eusUsageType','eusUsageType','varchar','input','50','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(10,'eusUsersList','eusUsersList','varchar','input','1024','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(11,'<local>','mode','varchar','input','12','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(12,'<local>','message','varchar','output','512','AddUpdateTrackingDataset');
INSERT INTO "sproc_args" VALUES(13,'<local>','callingUser','varchar','input','128','AddUpdateTrackingDataset');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'datasetNum','Dataset','text-if-new','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(2,'experimentNum','Experiment','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'operPRN','User','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'instrumentName','Instrument','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'runStart','Run Start','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(6,'runDuration','Run Duration','text','32','32','','','10','trim|max_length[16]');
INSERT INTO "form_fields" VALUES(7,'comment','Comment','area','','','4','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(8,'eusProposalID','Eus Proposal ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(9,'eusUsageType','Eus Usage Type','text','50','50','','','','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(10,'eusUsersList','Eus Users List','area','','','4','70','','trim|max_length[1024]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'experimentNum','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'instrumentName','picker.replace','instrumentNameAdminPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'operPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'eusUsageType','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'eusUsersList','list-report.helper','','helper_eus_user_ckbx/report','DS_EUSProposalID',',','');
INSERT INTO "form_field_choosers" VALUES(6,'eusProposalID','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(7,'eusProposalID','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'operPRN','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'runStart','default_function','CurrentDate');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Dataset','invoke_entity','value','tracking_dataset/show/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_emsl_proposal_id','EMSL_Proposal_ID','20','','EMSL_Proposal_ID','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_month','Month','20','','Month','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_day','Day','20','','Day','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_operator','Operator','20','','Operator','ContainsText','text','103','','');
COMMIT;
