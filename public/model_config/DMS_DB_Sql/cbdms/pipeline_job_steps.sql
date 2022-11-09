﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Pipeline_Job_Steps_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','V_Pipeline_Job_Steps_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','ID');
INSERT INTO general_params VALUES('my_db_group','broker');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','6','','Job','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_script','Script','15!','','Script','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_tool','Tool','15!','','Tool','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_step_state','Step_State','6','','Step_State','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_step','Step','3!','','Step','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_processor','Processor','6','','Processor','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_param_file','Param File','45!','','Parameter_File','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_start','Start','6','','Start','LaterThan','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Job','invoke_entity','value','pipeline_jobs/show','');
INSERT INTO list_report_hotlinks VALUES(2,'Tool','invoke_entity','value','pipeline_step_tools/show','');
INSERT INTO list_report_hotlinks VALUES(3,'Step','invoke_entity','id','pipeline_job_steps/show','');
INSERT INTO list_report_hotlinks VALUES(4,'Processor','invoke_entity','value','pipeline_processor_step_tools/report/@','');
INSERT INTO list_report_hotlinks VALUES(5,'id','no_display','value','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Job','detail-report','Job','analysis_job/show','labelCol','dms_job_detail',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'Dataset','detail-report','Dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'Script','detail-report','Script','pipeline_script/show','labelCol','script',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'Tool','detail-report','Tool','pipeline_step_tools/show','labelCol','tool',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'Dataset Folder Path','href-folder','Dataset Folder Path','','labelCol','dataset_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(6,'Transfer Folder Path','href-folder','Transfer Folder Path','','labelCol','transfer_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(7,'Log File Path','href-folder','Log File Path','','labelCol','log_file_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(8,'StateID','detail-report','Job','pipeline_job_steps/report','labelCol','StateID',NULL);
INSERT INTO detail_report_hotlinks VALUES(9,'+Job','detail-report','Job','pipeline_jobs/show','valueCol','pipeline_job_detail',NULL);
INSERT INTO detail_report_hotlinks VALUES(10,'Processor','detail-report','Processor','pipeline_processor_step_tools/report/@','valueCol','dl_processor','');
COMMIT;
