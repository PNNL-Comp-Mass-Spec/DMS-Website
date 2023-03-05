﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_capture_script_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_capture_script_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','script');
INSERT INTO general_params VALUES('entry_sproc','add_update_capture_scripts');
INSERT INTO general_params VALUES('entry_page_data_table','v_capture_script_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','script');
INSERT INTO general_params VALUES('my_db_group','capture');
INSERT INTO general_params VALUES('post_submission_detail_id','script');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'script','Script','text-if-new','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(2,'description','Description','area','','','3','60','This script...','trim|max_length[512]');
INSERT INTO form_fields VALUES(3,'enabled','Enabled','text','1','1','','','Y','trim|max_length[1]');
INSERT INTO form_fields VALUES(4,'results_tag','ResultsTag','text','8','8','','','','trim|max_length[8]');
INSERT INTO form_fields VALUES(5,'contents','Contents','area','','','25','70','','trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'contents','auto_format','xml');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'enabled','picker.replace','YNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_script','Script','6','','script','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'script','invoke_entity','value','capture_script/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'enabled','invoke_entity','script','capture_script/dot/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'script','Script','varchar','input','64','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(2,'description','Description','varchar','input','512','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(3,'enabled','Enabled','char','input','1','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(4,'results_tag','ResultsTag','varchar','input','8','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(5,'contents','Contents','text','input','2147483647','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(6,'<local>','mode','varchar','input','12','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(7,'<local>','message','varchar','output','512','add_update_capture_scripts');
INSERT INTO sproc_args VALUES(8,'<local>','callingUser','varchar','input','128','add_update_capture_scripts');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'script','detail-report','script','capture_script/dot','labelCol','ID',NULL);
CREATE TABLE utility_queries (id INTEGER PRIMARY KEY, "name" TEXT, "db" TEXT, "table" TEXT, "columns" TEXT, "filters" TEXT);
INSERT INTO utility_queries VALUES(1,'dot','capture','T_Scripts','*','{"Script":"MTx"}');
COMMIT;
