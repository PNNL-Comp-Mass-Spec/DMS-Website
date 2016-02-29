PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_File_Attachment');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Experiment_File_Attachment_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'File Name','invoke_multi_col','value','file_attachment/retrieve/','{ "Entity Type":0, "Entity ID":0,  "File Name":0 }');
INSERT INTO "list_report_hotlinks" VALUES(2,'Entity ID','invoke_multi_col','value','','{ "Entity Type":0,  "show":1, "Entity ID":0 }');
INSERT INTO "list_report_hotlinks" VALUES(3,'Exp_ID','invoke_entity','Experiment','experiment/show/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_exp_id','Experiment ID','20','','Exp_ID','Equals','int','','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_file_name','File Name','','','File Name','ContainsText','text','256','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_entity_type','Entity Type','','','Entity Type','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_entity_id','Entity ID','','','Entity ID','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_description','Description','','','Description','ContainsText','text','1024','','');
COMMIT;
