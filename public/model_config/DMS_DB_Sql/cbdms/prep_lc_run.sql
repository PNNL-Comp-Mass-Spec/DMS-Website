﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Prep_LC_Run_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Prep_LC_Run_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Prep_LC_Run_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdatePrepLCRun');
INSERT INTO "general_params" VALUES('post_submission_detail_id','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID',' ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Tab',' Tab','text','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(3,'Instrument',' Instrument','text','50','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(4,'Type',' Type','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO "form_fields" VALUES(5,'LCColumn',' LCColumn','text','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(6,'LCColumn2',' LCColumn2','text','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(7,'Comment',' Comment','area','','','4','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(8,'GuardColumn',' Guard Column','text','12','12','','','','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(9,'QualityControl',' Quality Control','area','','','4','70','','trim|max_length[2048]|required');
INSERT INTO "form_fields" VALUES(10,'OperatorPRN',' Operator PRN','text','50','50','','','','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(11,'DigestionMethod',' Digestion Method','text','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(12,'SampleType',' Sample Type','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'SamplePrepRequest',' Sample Prep Request','area','','','4','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(14,'NumberOfRuns',' Number Of Runs','text','12','12','','','','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(15,'InstrumentPressure',' Instrument Pressure','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(16,'Datasets',' HPLC Datasets','area','','','4','70','','trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'OperatorPRN','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'OperatorPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'LCColumn','list-report.helper','','helper_prep_lc_column/report','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Instrument','picker.replace','prepInstrumentPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'Type','picker.replace','prepLCRunTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'GuardColumn','picker.replace','prepLCRunGuardColPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'Tab','picker.replace','prepLCRunTabPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'SamplePrepRequest','list-report.helper','','helper_sample_prep_ckbx/report','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'LCColumn2','list-report.helper','','helper_prep_lc_column/report','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'Datasets','list-report.helper','','helper_prep_lc_run_dataset_ckbx/report','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','20','','ID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_tab','Tab','20','','Tab','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_lc_column','LC Column','20','','LC Column','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_comment','Comment','20','','Comment','ContainsText','text','1024','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_type','Type','20','','Type','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_created','Created','20','','Created','LaterThan','text','20','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','prep_lc_run/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Sample Prep Request','link_list','value','sample_prep_request/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'LC Column','invoke_entity','value','prep_lc_column/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Sample Prep Request','link_list','Sample Prep Request','sample_prep_request/show','valueCol','dl_sample_prep_request',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Experiment Groups','link_list','Experiment Groups','experiment_group/show','valueCol','dl_experiment_groups',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Folder','href-folder','Folder','','valueCol','dl_folder',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Instrument','detail-report','Instrument','instrument/show','labelCol','dl_instrument','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Datasets','link_list','Datasets','dataset/show','valueCol','dl_datasets',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'LC Column','detail-report','LC Column','prep_lc_column/show','valueCol','dl_lc_column','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(2,'Tab','Tab','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(3,'Instrument','Instrument','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(4,'Type','Type','varchar','input','64','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(5,'LCColumn','LCColumn','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(6,'LCColumn2','LCColumn2','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(7,'Comment','Comment','varchar','input','1024','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(8,'GuardColumn','GuardColumn','varchar','input','12','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(9,'OperatorPRN','OperatorPRN','varchar','input','50','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(10,'DigestionMethod','DigestionMethod','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(11,'SampleType','SampleType','varchar','input','64','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(12,'SamplePrepRequest','SamplePrepRequest','varchar','input','1024','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(13,'NumberOfRuns','NumberOfRuns','varchar','input','12','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(14,'InstrumentPressure','InstrumentPressure','varchar','input','32','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(15,'QualityControl','QualityControl','varchar','input','2048','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(16,'Datasets','Datasets','varchar','input','2147483647','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(17,'<local>','mode','varchar','input','12','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(18,'<local>','message','varchar','output','512','AddUpdatePrepLCRun');
INSERT INTO "sproc_args" VALUES(19,'<local>','callingUser','varchar','input','128','AddUpdatePrepLCRun');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Capture Dataset','copy_from','create','dataset','Create capture job','');
COMMIT;