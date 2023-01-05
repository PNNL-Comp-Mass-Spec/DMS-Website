﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Separation_Group');
INSERT INTO general_params VALUES('list_report_data_table','v_separation_group_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_separation_group_list_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','separation_group');
INSERT INTO general_params VALUES('entry_page_data_table','v_separation_group_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','separation_group');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateSeparationGroup');
INSERT INTO general_params VALUES('post_submission_detail_id','separation_group');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_sep_group','Sep Group','','','separation_group','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_active','Active','','','active','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_sample_prep_visible','Sample Prep Visible','','','sample_prep_visible','Equals','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'separation_group','invoke_entity','value','separation_group/show','');
INSERT INTO list_report_hotlinks VALUES(2,'separation_types','invoke_entity','separation_group','separation_type/report/-/-/~','');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'separation_group','Separation Group','text-if-new','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(2,'comment','Comment','area','64','512','2','60','','trim|max_length[512]');
INSERT INTO form_fields VALUES(3,'active','Active','text','12','12','','','','trim|numeric');
INSERT INTO form_fields VALUES(4,'sample_prep_visible','Sample Prep Visible','text','12','12','','','','trim|numeric');
INSERT INTO form_fields VALUES(5,'fraction_count','Fraction Count','text','12','12','','','','trim|numeric');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'separation_group','separationGroup','varchar','input','64','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(2,'comment','comment','varchar','input','512','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(3,'active','active','tinyint','input','','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(4,'sample_prep_visible','samplePrepVisible','tinyint','input','','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(5,'fraction_count','fractionCount','smallint','input','','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(6,'<local>','mode','varchar','input','12','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(7,'<local>','message','varchar','output','512','AddUpdateSeparationGroup');
INSERT INTO sproc_args VALUES(8,'<local>','callingUser','varchar','input','128','AddUpdateSeparationGroup');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'separation_types','detail-report','separation_group','separation_type/report/-/-/~','labelCol','dl_separation_types','');
INSERT INTO detail_report_hotlinks VALUES(2,'separation_group','detail-report','separation_group','requested_run/report/-/-/-/-/-/-/-/-/-/-/sfx/and/separation%20group/matchestext/','labelCol','dl_requested_runs_for_separation_group','');
COMMIT;
