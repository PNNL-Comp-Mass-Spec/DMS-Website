﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Wellplate_List_Report');
INSERT INTO general_params VALUES('detail_report_data_table','V_Wellplate_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Wellplate_Name');
INSERT INTO general_params VALUES('entry_page_data_table','v_wellplate_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','wellplate');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateWellplate');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('post_submission_detail_id','wellplate');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'wellplate','Wellplate Name','text','50','64','','','(generate name)','trim|max_length[64]');
INSERT INTO form_fields VALUES(2,'description','Description','area','','','4','70','','trim|max_length[512]');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_1','Wellplate Name','12','','Wellplate_Name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_2','Description','24','','Description','ContainsText','text','80','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'wellplate','wellplate','varchar','output','64','AddUpdateWellplate');
INSERT INTO sproc_args VALUES(2,'description','description','varchar','input','512','AddUpdateWellplate');
INSERT INTO sproc_args VALUES(3,'<local>','mode','varchar','input','12','AddUpdateWellplate');
INSERT INTO sproc_args VALUES(4,'<local>','message','varchar','output','512','AddUpdateWellplate');
INSERT INTO sproc_args VALUES(5,'<local>','callingUser','varchar','input','128','AddUpdateWellplate');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Wellplate_Name','invoke_entity','value','wellplate/show/','');
COMMIT;
