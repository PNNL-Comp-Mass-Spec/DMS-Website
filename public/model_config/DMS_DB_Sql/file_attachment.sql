﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_File_Attachment');
INSERT INTO general_params VALUES('list_report_data_table','v_file_attachment_list_report');
INSERT INTO general_params VALUES('entry_sproc','add_update_file_attachment');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('operations_sproc','do_file_attachment_operation');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','id','int','input','','add_update_file_attachment');
INSERT INTO sproc_args VALUES(2,'fileName','fileName','varchar','input','256','add_update_file_attachment');
INSERT INTO sproc_args VALUES(3,'description','description','varchar','input','1024','add_update_file_attachment');
INSERT INTO sproc_args VALUES(4,'entityType','entityType','varchar','input','64','add_update_file_attachment');
INSERT INTO sproc_args VALUES(5,'entityID','entityID','varchar','input','256','add_update_file_attachment');
INSERT INTO sproc_args VALUES(6,'fileSizeKB','fileSizeKB','varchar','input','12','add_update_file_attachment');
INSERT INTO sproc_args VALUES(7,'archiveFolderPath','archiveFolderPath','varchar','input','256','add_update_file_attachment');
INSERT INTO sproc_args VALUES(8,'<local>','mode','varchar','input','12','add_update_file_attachment');
INSERT INTO sproc_args VALUES(9,'<local>','message','varchar','output','512','add_update_file_attachment');
INSERT INTO sproc_args VALUES(10,'<local>','callingUser','varchar','input','128','add_update_file_attachment');
INSERT INTO sproc_args VALUES(11,'fileMimeType','fileMimeType','varchar','input','256','add_update_file_attachment');
INSERT INTO sproc_args VALUES(12,'id','id','int','input','','do_file_attachment_operation');
INSERT INTO sproc_args VALUES(13,'<local>','mode','varchar','input','12','do_file_attachment_operation');
INSERT INTO sproc_args VALUES(14,'<local>','message','varchar','output','512','do_file_attachment_operation');
INSERT INTO sproc_args VALUES(15,'<local>','callingUser','varchar','input','128','do_file_attachment_operation');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_file_name','File Name','40!','','file_name','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_entity_type','Entity Type','30!','','entity_type','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_entity_id','Entity ID','20','','entity_id','MatchesText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_description','Description','30!','','description','ContainsText','text','1024','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'file_name','invoke_multi_col','value','file_attachment/retrieve/','{ "Entity_Type":0, "Entity_ID":0,  "File_Name":0 }');
INSERT INTO list_report_hotlinks VALUES(2,'entity_id','invoke_multi_col','value','','{ "Entity_Type":0,  "show":1, "Entity_ID":0 }');
COMMIT;
