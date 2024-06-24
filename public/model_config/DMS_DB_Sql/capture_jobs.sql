﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_capture_jobs_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_capture_jobs_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','job');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('my_db_group','capture');
INSERT INTO general_params VALUES('entry_page_data_table','v_capture_jobs_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','job');
INSERT INTO general_params VALUES('entry_sproc','add_update_local_task_in_broker');
INSERT INTO general_params VALUES('post_submission_detail_id','job');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','6','','job','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_job_state_b','Job_State_B','6','','job_state_b','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','45!','','dataset','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_script','Script','6','','script','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','capture_jobs/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'steps','invoke_entity','job','capture_job_steps/report','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'steps','detail-report','job','capture_job_steps/report','labelCol','job',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'dataset','detail-report','dataset','dataset/show','labelCol','dataset',NULL);
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'job','Job','non-edit','','','','','','trim|default_value[0]|max_length[12]');
INSERT INTO form_fields VALUES(2,'script_name','Script Name','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(3,'priority','Priority','text','12','12','','','3','trim|max_length[12]');
INSERT INTO form_fields VALUES(4,'job_param','Job Param','area','','','15','120','','trim|max_length[8000]');
INSERT INTO form_fields VALUES(5,'comment','Comment','area','','','4','120','','trim|max_length[512]');
INSERT INTO form_fields VALUES(6,'results_folder_name','Results Folder Name','non-edit','50','128','','','','trim|max_length[128]');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'job','job','int','output','','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(2,'script_name','scriptName','varchar','input','64','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(3,'priority','priority','int','input','','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(4,'job_param','jobParam','varchar','input','8000','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(5,'comment','comment','varchar','input','512','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(7,'<local>','mode','varchar','input','12','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(8,'<local>','message','varchar','output','512','add_update_local_task_in_broker');
INSERT INTO sproc_args VALUES(9,'<local>','callingUser','varchar','input','128','add_update_local_task_in_broker');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'job_param','auto_format','xml');
COMMIT;
