﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_pipeline_jobs_history_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_pipeline_jobs_history_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','job');
INSERT INTO general_params VALUES('my_db_group','broker');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','6','','job','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_job_state_b','Job State B','6','','job_state_b','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_script','Script','15!','','script','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_DataPkgID','Data Pkg ID','6','','data_pkg_id','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','pipeline_jobs_history/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'steps','invoke_entity','job','pipeline_job_steps_history/report','');
INSERT INTO list_report_hotlinks VALUES(3,'data_pkg_id','invoke_entity','value','data_package/show/','');
INSERT INTO list_report_hotlinks VALUES(4,'owner','invoke_entity','value','user/show','');
INSERT INTO list_report_hotlinks VALUES(5,'comment','min_col_width','value','120','');
INSERT INTO list_report_hotlinks VALUES(6,'runtime_minutes','format_commas','value','','{"Decimals":"1"}');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'job','detail-report','job','analysis_job/show','labelCol','job','');
INSERT INTO detail_report_hotlinks VALUES(2,'dataset','detail-report','dataset','dataset/show','labelCol','dataset','');
INSERT INTO detail_report_hotlinks VALUES(3,'steps','detail-report','job','pipeline_job_steps_history/report','labelCol','Steps','');
INSERT INTO detail_report_hotlinks VALUES(4,'parameters','xml_params','parameters','','valueCol','param_id','');
INSERT INTO detail_report_hotlinks VALUES(5,'transfer_folder_path','href-folder','transfer_folder_path','','valueCol','dh_TransferFolder','');
INSERT INTO detail_report_hotlinks VALUES(6,'script','detail-report','script','pipeline_script/show','valueCol','ID','');
INSERT INTO detail_report_hotlinks VALUES(7,'owner','detail-report','owner','user/show','labelCol','owner','');
INSERT INTO detail_report_hotlinks VALUES(8,'data_package_id','detail-report','data_package_id','data_package/show','labelCol','dh_DataPkgID','');
INSERT INTO detail_report_hotlinks VALUES(9,'settings_file','detail-report','settings_file','settings_files/report/-/','labelCol','dl_SettingsFile','');
INSERT INTO detail_report_hotlinks VALUES(10,'parameter_file','detail-report','parameter_file','param_file/report/-/','labelCol','dl_ParameterFile','');
INSERT INTO detail_report_hotlinks VALUES(11,'runtime_minutes','format_commas','runtime_minutes','','valueCol','dl_runtime_minutes','{"Decimals":"1"}');
CREATE TABLE utility_queries ( id integer PRIMARY KEY, name text, label text, db text, "table" text, columns text, sorting text, filters text, hotlinks text );
INSERT INTO utility_queries VALUES(1,'parameter_values','','broker','v_pipeline_jobs_history_detail_report','parameters as params','','{"job":"EQn"}','');
INSERT INTO utility_queries VALUES(2,'parameter_definitions','','broker','v_pipeline_script_parameters','parameters as params','','{"script":"MTx"}','');
INSERT INTO utility_queries VALUES(3,'parameter_scripts','','broker','v_pipeline_scripts_enabled','*','','','');
COMMIT;
