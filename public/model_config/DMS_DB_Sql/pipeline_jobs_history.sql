﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Pipeline_Jobs_History_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Pipeline_Jobs_History_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Job');
INSERT INTO "general_params" VALUES('my_db_group','broker');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_job','Job','6','','Job','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_job_state_b','Job_State_B','6','','Job_State_B','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_script','Script','15!','','Script','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_DataPkgID','DataPkgID','6','','DataPkgID','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','pipeline_jobs_history/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Steps','invoke_entity','Job','pipeline_job_steps_history/report','');
INSERT INTO "list_report_hotlinks" VALUES(3,'DataPkgID','invoke_entity','value','data_package/show/','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Owner','invoke_entity','value','user/show','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Comment','min_col_width','value','120','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Runtime_Minutes','format_commas','value','','{"Decimals":"1"}');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Job','detail-report','Job','analysis_job/show','labelCol','job','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Dataset','detail-report','Dataset','dataset/show','labelCol','dataset','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'Steps','detail-report','Job','pipeline_job_steps_history/report','labelCol','Steps','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Parameters','xml_params','Parameters','','valueCol','param_id','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Transfer_Folder_Path','href-folder','Transfer_Folder_Path','','valueCol','dh_TransferFolder','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Script','detail-report','Script','pipeline_script/show','valueCol','ID','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Owner','detail-report','Owner','user/show','labelCol','owner','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Data_Package_ID','detail-report','Data_Package_ID','data_package/show','labelCol','dh_DataPkgID','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Settings_File','detail-report','Settings_File','settings_files/report/-/','labelCol','dl_SettingsFile','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'Parameter_File','detail-report','Parameter_File','param_file/report/-/','labelCol','dl_ParameterFile','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Runtime_Minutes','format_commas','Runtime_Minutes','','valueCol','dl_runtime_minutes','{"Decimals":"1"}');
CREATE TABLE utility_queries ( id integer PRIMARY KEY, name text, label text, db text, "table" text, columns text, sorting text, filters text, hotlinks text );
INSERT INTO "utility_queries" VALUES(1,'parameter_values','','broker','T_Job_Parameters','CONVERT(varchar(MAX), Parameters)  as params','','{"Job":"EQn"}','');
INSERT INTO "utility_queries" VALUES(2,'parameter_definitions','','broker','T_Scripts','CONVERT(varchar(MAX), Parameters)  as params','','{"Script":"MTx"}','');
INSERT INTO "utility_queries" VALUES(3,'parameter_scripts','','broker','V_Pipeline_Script_With_Parameters','*','','','');
COMMIT;
