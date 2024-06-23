﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_capture_step_tools_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','name');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('detail_report_data_table','v_capture_step_tools_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','name');
INSERT INTO general_params VALUES('entry_sproc','add_update_capture_step_tools');
INSERT INTO general_params VALUES('entry_page_data_table','v_capture_step_tools_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','name');
INSERT INTO general_params VALUES('my_db_group','capture');
INSERT INTO general_params VALUES('post_submission_detail_id','name');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'name','Name','text-if-new','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(2,'description','Description','area','','','4','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(3,'bionet_required','Bionet Required','text','1','1','','','N','trim|max_length[1]');
INSERT INTO form_fields VALUES(4,'only_on_storage_server','Only On Storage Server','text','1','1','','','N','trim|max_length[1]');
INSERT INTO form_fields VALUES(5,'instrument_capacity_limited','Instrument Capacity Limited','text','1','1','','','N','trim|max_length[1]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'bionet_required','picker.replace','YNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'only_on_storage_server','picker.replace','YNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'instrument_capacity_limited','picker.replace','YNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','6','','name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_type','Type','6','','type','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','6','','description','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'name','invoke_entity','value','capture_step_tools/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'name','Name','varchar','input','64','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(2,'description','Description','varchar','input','512','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(3,'bionet_required','BionetRequired','char','input','1','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(4,'only_on_storage_server','OnlyOnStorageServer','char','input','1','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(5,'instrument_capacity_limited','InstrumentCapacityLimited','char','input','1','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(6,'<local>','mode','varchar','input','12','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(7,'<local>','message','varchar','output','512','add_update_capture_step_tools');
INSERT INTO sproc_args VALUES(8,'<local>','callingUser','varchar','input','128','add_update_capture_step_tools');
COMMIT;
