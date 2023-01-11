﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_prep_lc_run_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_prep_lc_run_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('entry_page_data_table','v_prep_lc_run_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('entry_sproc','AddUpdatePrepLCRun');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'prep_run_name','Name','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'instrument','Instrument','text','50','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(4,'type','Type','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(5,'lc_column','LC Column','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(6,'lc_column_2','LC Column 2','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(7,'comment','Comment','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(8,'guard_column','Guard Column','text','12','12','','','','trim|max_length[12]');
INSERT INTO form_fields VALUES(9,'quality_control','Quality Control','area','','','4','70','','trim|max_length[2048]|required');
INSERT INTO form_fields VALUES(10,'operator_prn','Operator PRN','text','50','50','','','','trim|max_length[50]');
INSERT INTO form_fields VALUES(11,'digestion_method','Digestion Method','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(12,'sample_type','Sample Type','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(13,'sample_prep_request','Sample Prep Request','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(14,'number_of_runs','Number Of Runs','text','12','12','','','','trim|max_length[12]');
INSERT INTO form_fields VALUES(15,'instrument_pressure','Instrument Pressure','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(16,'datasets','HPLC Datasets','area','','','4','70','','trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'operator_prn','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'operator_prn','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'lc_column','list-report.helper','','helper_prep_lc_column/report','',',','');
INSERT INTO form_field_choosers VALUES(3,'instrument','picker.replace','prepInstrumentPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'type','picker.replace','prepLCRunTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'guard_column','picker.replace','prepLCRunGuardColPickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'prep_run_name','picker.replace','prepLCRunNamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(7,'sample_prep_request','list-report.helper','','helper_sample_prep_ckbx/report','',',','');
INSERT INTO form_field_choosers VALUES(8,'lc_column_2','list-report.helper','','helper_prep_lc_column/report','',',','');
INSERT INTO form_field_choosers VALUES(9,'datasets','list-report.helper','','helper_prep_lc_run_dataset_ckbx/report','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','20','','id','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_prep_run_name','Name','20','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_instrument','Instrument','20','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_lc_column','LC Column','20','','lc_column','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_comment','Comment','20','','comment','ContainsText','text','1024','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_type','Type','20','','type','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_created','Created','20','','created','LaterThan','text','20','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','prep_lc_run/show','');
INSERT INTO list_report_hotlinks VALUES(2,'sample_prep_request','link_list','value','sample_prep_request/show','');
INSERT INTO list_report_hotlinks VALUES(3,'lc_column','invoke_entity','value','prep_lc_column/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'sample_prep_request','link_list','sample_prep_request','sample_prep_request/show','valueCol','dl_sample_prep_request',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'experiment_groups','link_list','experiment_groups','experiment_group/show','valueCol','dl_experiment_groups',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'instrument','detail-report','instrument','instrument/show','valueCol','dl_instrument','');
INSERT INTO detail_report_hotlinks VALUES(5,'datasets','link_list','datasets','dataset/show','valueCol','dl_datasets',NULL);
INSERT INTO detail_report_hotlinks VALUES(6,'lc_column','detail-report','lc_column','prep_lc_column/show','valueCol','dl_lc_column','');
INSERT INTO detail_report_hotlinks VALUES(7,'operator_prn','detail-report','operator_prn','user/show','valueCol','dl_operator','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','id','int','output','','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(2,'prep_run_name','prepRunName','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(3,'instrument','Instrument','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(4,'type','Type','varchar','input','64','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(5,'lc_column','LCColumn','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(6,'lc_column_2','LCColumn2','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(7,'comment','Comment','varchar','input','1024','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(8,'guard_column','GuardColumn','varchar','input','12','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(9,'operator_prn','OperatorPRN','varchar','input','50','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(10,'digestion_method','DigestionMethod','varchar','input','128','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(11,'sample_type','SampleType','varchar','input','64','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(12,'sample_prep_request','SamplePrepRequest','varchar','input','1024','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(13,'number_of_runs','NumberOfRuns','varchar','input','12','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(14,'instrument_pressure','InstrumentPressure','varchar','input','32','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(15,'quality_control','QualityControl','varchar','input','2048','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(16,'datasets','Datasets','varchar','input','2147483647','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(17,'<local>','mode','varchar','input','12','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(18,'<local>','message','varchar','output','512','AddUpdatePrepLCRun');
INSERT INTO sproc_args VALUES(19,'<local>','callingUser','varchar','input','128','AddUpdatePrepLCRun');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Capture Dataset','copy_from','create','dataset','Create capture job','');
COMMIT;
