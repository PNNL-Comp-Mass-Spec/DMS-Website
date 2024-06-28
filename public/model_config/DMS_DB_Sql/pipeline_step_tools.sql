﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_pipeline_step_tools_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('detail_report_data_table','v_pipeline_step_tools_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','name');
INSERT INTO general_params VALUES('entry_sproc','add_update_step_tools');
INSERT INTO general_params VALUES('entry_page_data_table','v_pipeline_step_tools_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','name');
INSERT INTO general_params VALUES('my_db_group','broker');
INSERT INTO general_params VALUES('post_submission_detail_id','name');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'name','Name','text-if-new','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(2,'type','Type','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'description','Description','area','','','3','60','','trim|max_length[512]');
INSERT INTO form_fields VALUES(4,'shared_result_version','Shared Result Version','text','2','2','','','','trim|max_length[2]');
INSERT INTO form_fields VALUES(5,'filter_version','Filter Version','text','2','2','','','','trim|max_length[2]');
INSERT INTO form_fields VALUES(6,'cpu_load','CPU Load','text','2','2','','','','trim|max_length[2]');
INSERT INTO form_fields VALUES(7,'memory_usage_mb','Memory Usage MB','text','24','24','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(8,'parameter_template','Parameter Template','area','','','15','70','','trim|max_length[2147483647]');
INSERT INTO form_fields VALUES(9,'param_file_storage_path','ParamFileStoragePath','text','60','128','','','','trim|max_length[256]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'parameter_template','auto_format','xml');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','6','','name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_type','Type','6','','type','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','6','','description','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'name','invoke_entity','value','pipeline_step_tools/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'name','name','varchar','input','64','add_update_step_tools');
INSERT INTO sproc_args VALUES(2,'type','type','varchar','input','128','add_update_step_tools');
INSERT INTO sproc_args VALUES(3,'description','description','varchar','input','512','add_update_step_tools');
INSERT INTO sproc_args VALUES(4,'shared_result_version','sharedResultVersion','smallint','input','','add_update_step_tools');
INSERT INTO sproc_args VALUES(5,'filter_version','filterVersion','smallint','input','','add_update_step_tools');
INSERT INTO sproc_args VALUES(6,'cpu_load','cpuLoad','smallint','input','','add_update_step_tools');
INSERT INTO sproc_args VALUES(7,'memory_usage_mb','memoryUsageMB','int','input','','add_update_step_tools');
INSERT INTO sproc_args VALUES(8,'parameter_template','parameterTemplate','text','input','2147483647','add_update_step_tools');
INSERT INTO sproc_args VALUES(9,'param_file_storage_path','paramFileStoragePath','varchar','input','256','add_update_step_tools');
INSERT INTO sproc_args VALUES(10,'<local>','mode','varchar','input','12','add_update_step_tools');
INSERT INTO sproc_args VALUES(11,'<local>','message','varchar','output','512','add_update_step_tools');
INSERT INTO sproc_args VALUES(12,'<local>','callingUser','varchar','input','128','add_update_step_tools');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'name','detail-report','name','pipeline_processor_step_tools/report/-/~@','valueCol','dl_name','');
COMMIT;
