﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_pipeline_jobs_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_pipeline_jobs_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','job');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('my_db_group','broker');
INSERT INTO general_params VALUES('entry_page_data_table','v_pipeline_jobs_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','job');
INSERT INTO general_params VALUES('entry_sproc','add_update_local_job_in_broker');
INSERT INTO general_params VALUES('entry_submission_cmds','pipeline_jobs_cmds');
INSERT INTO general_params VALUES('detail_report_cmds','pipeline_jobs_cmds');
INSERT INTO general_params VALUES('post_submission_detail_id','job');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','6','','job','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_job_state_b','Job State B','6','','job_state_b','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_script','Script','15!','','script','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_DataPkgID','Data Pkg ID','6','','data_pkg_id','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','pipeline_jobs/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'steps','invoke_entity','job','pipeline_job_steps/report','');
INSERT INTO list_report_hotlinks VALUES(3,'data_pkg_id','invoke_entity','value','data_package/show/','');
INSERT INTO list_report_hotlinks VALUES(4,'owner','invoke_entity','value','user/show','');
INSERT INTO list_report_hotlinks VALUES(5,'comment','min_col_width','value','120','');
INSERT INTO list_report_hotlinks VALUES(6,'runtime_minutes','format_commas','value','','{"Decimals":"1"}');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'job','detail-report','job','analysis_job/show','labelCol','job',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'dataset','detail-report','dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'steps','detail-report','job','pipeline_job_steps/report','labelCol','Steps',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'parameters','xml_params','parameters','','valueCol','param_id',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'transfer_folder_path','href-folder','transfer_folder_path','','valueCol','dh_TransferFolder',NULL);
INSERT INTO detail_report_hotlinks VALUES(6,'script','detail-report','script','pipeline_script/show','valueCol','ID',NULL);
INSERT INTO detail_report_hotlinks VALUES(7,'owner','detail-report','owner','user/show','labelCol','owner',NULL);
INSERT INTO detail_report_hotlinks VALUES(8,'data_package_id','detail-report','data_package_id','data_package/show','labelCol','dh_DataPkgID',NULL);
INSERT INTO detail_report_hotlinks VALUES(9,'settings_file','detail-report','settings_file','settings_files/report/-/','labelCol','dl_SettingsFile','');
INSERT INTO detail_report_hotlinks VALUES(10,'parameter_file','detail-report','parameter_file','param_file/report/-/','labelCol','dl_ParameterFile','');
INSERT INTO detail_report_hotlinks VALUES(11,'runtime_minutes','format_commas','runtime_minutes','','valueCol','dl_runtime_minutes','{"Decimals":"1"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'job','job','int','output','','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(2,'script_name','scriptName','varchar','input','64','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(3,'dataset','datasetName','varchar','input','128','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(4,'priority','priority','int','input','','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(5,'job_param','jobParam','varchar','input','8000','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(6,'comment','comment','varchar','input','512','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(7,'owner_username','ownerUsername','varchar','input','64','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(8,'data_package_id','dataPackageID','int','input','','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(9,'results_folder_name','resultsFolderName','varchar','output','128','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(10,'<local>','mode','varchar','input','12','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(11,'<local>','message','varchar','output','512','add_update_local_job_in_broker');
INSERT INTO sproc_args VALUES(12,'<local>','callingUser','varchar','input','128','add_update_local_job_in_broker');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'job','Job','non-edit','','','','','','trim|default_value[0]|max_length[12]');
INSERT INTO form_fields VALUES(2,'script_name','Script Name','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(3,'dataset','Dataset','text','50','128','','','Aggregation','trim|max_length[128]');
INSERT INTO form_fields VALUES(4,'priority','Priority','text','12','12','','','3','trim|max_length[12]');
INSERT INTO form_fields VALUES(5,'job_param','Job Param','hidden','','','','','','trim|max_length[8000]');
INSERT INTO form_fields VALUES(6,'comment','Comment','area','','','4','120','','trim|max_length[512]');
INSERT INTO form_fields VALUES(7,'owner_username','Owner','text','','','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(8,'data_package_id','Data Package ID','text','12','12','','','','trim|max_length[12]');
INSERT INTO form_fields VALUES(9,'results_folder_name','Results Folder Name','non-edit','50','128','','','','trim|max_length[128]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'job_param','auto_format','xml');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'reset','cmd','Reset Failed Steps or Rerun Job','','');
CREATE TABLE utility_queries ( id integer PRIMARY KEY, name text, label text, db text, "table" text, columns text, sorting text, filters text, hotlinks text );
INSERT INTO utility_queries VALUES(1,'parameter_values','','broker','T_Job_Parameters','Parameters as params','','{"Job":"EQn"}','');
INSERT INTO utility_queries VALUES(2,'parameter_definitions','','broker','V_Pipeline_Script_Parameters','Parameters as params','','{"Script":"MTx"}','');
INSERT INTO utility_queries VALUES(3,'parameter_scripts','','broker','V_Pipeline_Script_With_Parameters','script','','','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'owner_username','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'data_package_id','list-report.helper','','helper_data_package/report','',',','');
COMMIT;
