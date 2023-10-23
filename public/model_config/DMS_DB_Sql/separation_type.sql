﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Secondary_Sep');
INSERT INTO general_params VALUES('list_report_data_table','v_separation_type_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','separation_type');
INSERT INTO general_params VALUES('list_report_data_sort_dir','asc');
INSERT INTO general_params VALUES('detail_report_data_table','v_separation_type_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_page_data_table','v_separation_type_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('entry_sproc','add_update_separation_type');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_separation_type','Type','25!','','separation_type','ContainsText','text','75','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_separation_type_comment','Comment','20','','separation_type_comment','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_separation_group','Group','20','','separation_group','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_sample_type','Sample Type','20','','sample_type','ContainsText','text','75','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_dataset_usage_last_12_months','Usage (12 Mo)','20','','usage_last_12_months','GreaterThanOrEqualTo','text','20','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_dataset_usage_all_years','Usage All','20','','dataset_usage_all_years','GreaterThanOrEqualTo','text','20','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_most_recent_use','Most Recent','20','','most_recent_use','LaterThan','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'separation_type','invoke_entity','id','separation_type/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'separation_group','invoke_entity','value','separation_group/show','');
INSERT INTO list_report_hotlinks VALUES(3,'dataset_usage_all_years','invoke_entity','separation_type','dataset/report/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Separation_Type/MatchesText/','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'separation_group','detail-report','separation_group','separation_group/show','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(2,'dataset_usage_all_years','detail-report','separation_type','dataset/report/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Separation_Type/MatchesText/','valueCol','','');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'separation_name','Name','text','50','50','','','','trim|max_length[50]');
INSERT INTO form_fields VALUES(3,'separation_group','Separation Group','text','64','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(4,'comment','Comment','area','256','256','2','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(5,'sample_type','Sample Type','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(6,'state','State','text','12','12','','','','trim|max_length[12]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'separation_group','picker.replace','separationGroupPickList','','','','');
INSERT INTO form_field_choosers VALUES(2,'sample_type','picker.replace','sampleTypePickList','','','','');
INSERT INTO form_field_choosers VALUES(3,'state','picker.replace','activeInactivePickList','','','','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'comment','auto_format','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','id','int','input','','add_update_separation_type');
INSERT INTO sproc_args VALUES(2,'separation_name','sepTypeName','varchar','input','50','add_update_separation_type');
INSERT INTO sproc_args VALUES(3,'separation_group','sepGroupName','varchar','input','64','add_update_separation_type');
INSERT INTO sproc_args VALUES(4,'comment','comment','varchar','input','256','add_update_separation_type');
INSERT INTO sproc_args VALUES(5,'sample_type','sampleType','varchar','input','32','add_update_separation_type');
INSERT INTO sproc_args VALUES(6,'state','state','varchar','input','12','add_update_separation_type');
INSERT INTO sproc_args VALUES(7,'<local>','mode','varchar','input','12','add_update_separation_type');
INSERT INTO sproc_args VALUES(8,'<local>','message','varchar','output','512','add_update_separation_type');
INSERT INTO sproc_args VALUES(9,'<local>','callingUser','varchar','input','128','add_update_separation_type');
COMMIT;
