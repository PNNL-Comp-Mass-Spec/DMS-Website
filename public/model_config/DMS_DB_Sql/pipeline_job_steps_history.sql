﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_pipeline_job_steps_history_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_pipeline_job_steps_history_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('my_db_group','broker');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','6','','job','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_script','Script','15!','','script','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_tool','Tool','15!','','tool','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_step_state','Step_State','6','','step_state','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_step','Step','6','','step','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_processor','Processor','6','','processor','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','pipeline_jobs_history/show','');
INSERT INTO list_report_hotlinks VALUES(2,'tool','invoke_entity','value','pipeline_step_tools/show','');
INSERT INTO list_report_hotlinks VALUES(3,'step','invoke_entity','id','pipeline_job_steps_history/show','');
INSERT INTO list_report_hotlinks VALUES(4,'id','no_display','value','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'job','detail-report','job','analysis_job/show','labelCol','job',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'dataset','detail-report','dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'script','detail-report','script','pipeline_script/show','labelCol','script',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'tool','detail-report','tool','pipeline_step_tools/show','labelCol','tool',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'dataset_folder_path','href-folder','dataset_folder_path','','labelCol','dataset_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(6,'transfer_folder_path','href-folder','transfer_folder_path','','labelCol','transfer_folder_path','');
INSERT INTO detail_report_hotlinks VALUES(8,'stateid','detail-report','job','pipeline_job_steps/report','labelCol','StateID',NULL);
COMMIT;
