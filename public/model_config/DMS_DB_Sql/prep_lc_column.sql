﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_prep_lc_column_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_prep_lc_column_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','column_name');
INSERT INTO general_params VALUES('entry_page_data_table','v_prep_lc_column_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','column_name');
INSERT INTO general_params VALUES('entry_sproc','AddUpdatePrepLCColumn');
INSERT INTO general_params VALUES('post_submission_detail_id','column_name');
INSERT INTO general_params VALUES('list_report_data_sort_col','created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'column_name','Column Name','text-if-new','50','128','','','','trim|max_length[128]|alpha_dash|min_length[8]');
INSERT INTO form_fields VALUES(2,'mfg_name','Mfg Name','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'mfg_model','Mfg Model','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(4,'mfg_serial_number','Mfg Serial Number','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(5,'packing_mfg','Packing Mfg','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(6,'packing_type','Packing Type','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(7,'particle_size','Particle Size','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(8,'particle_type','Particle Type','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(9,'column_inner_dia','Column Inner Dia','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(10,'column_outer_dia','Column Outer Dia','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(11,'length','Length','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(12,'state','State','text','32','32','','','New','trim|max_length[32]');
INSERT INTO form_fields VALUES(13,'operator_prn','Operator PRN','text','50','50','','','','trim|max_length[50]');
INSERT INTO form_fields VALUES(14,'comment','Comment','area','','','4','70','','trim|max_length[244]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'state','picker.replace','LCColumnStatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'operator_prn','picker.replace','userPRNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_column_name','Column Name','20','','column_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_mfg_name','Mfg Name','20','','mfg_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_mfg_model','Mfg Model','20','','mfg_model','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_comment','Comment','20','','comment','ContainsText','text','244','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_state','State','20','','state','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'column_name','invoke_entity','value','prep_lc_column/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'runs','invoke_entity','column_name','prep_lc_run/report/-/-/-/~','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'column_name','ColumnName','varchar','input','128','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(2,'mfg_name','MfgName','varchar','input','128','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(3,'mfg_model','MfgModel','varchar','input','128','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(4,'mfg_serial_number','MfgSerialNumber','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(5,'packing_mfg','PackingMfg','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(6,'packing_type','PackingType','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(7,'particle_size','Particlesize','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(8,'particle_type','Particletype','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(9,'column_inner_dia','ColumnInnerDia','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(10,'column_outer_dia','ColumnOuterDia','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(11,'length','Length','varchar','input','64','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(12,'state','State','varchar','input','32','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(13,'operator_prn','OperatorPRN','varchar','input','50','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(14,'comment','Comment','varchar','input','244','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(15,'<local>','mode','varchar','input','12','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(16,'<local>','message','varchar','output','512','AddUpdatePrepLCColumn');
INSERT INTO sproc_args VALUES(17,'<local>','callingUser','varchar','input','128','AddUpdatePrepLCColumn');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'operator_prn','default_function','GetUser()');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'runs','detail-report','column_name','prep_lc_run/report/-/-/-/~','labelCol','dl_column_name','');
COMMIT;
