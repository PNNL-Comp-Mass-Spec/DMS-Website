﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Pipeline_Script_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Pipeline_Script_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Script');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateScripts');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Pipeline_Script_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Script');
INSERT INTO "general_params" VALUES('my_db_group','broker');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Script',' Script','text-if-new','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(2,'Description',' Description','area','','','6','90','','trim|max_length[2000]');
INSERT INTO "form_fields" VALUES(3,'Enabled',' Enabled','text','1','1','','','','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(4,'ResultsTag',' Results Tag','text','8','8','','','','trim|max_length[8]');
INSERT INTO "form_fields" VALUES(5,'BackfillToDMS','BackfillToDMS','text','1','1','','','','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(6,'Contents',' Contents','area','','','8','90','','trim|max_length[2147483647]');
INSERT INTO "form_fields" VALUES(7,'Parameters',' Parameters','area','','','8','90','','trim|max_length[2147483647]');
INSERT INTO "form_fields" VALUES(8,'Fields','Fields','area','','','8','90','','trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Contents','auto_format','xml');
INSERT INTO "form_field_options" VALUES(2,'Parameters','auto_format','xml');
INSERT INTO "form_field_options" VALUES(3,'Fields','auto_format','xml');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_script','Script','20','','Script','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Description','20','','Description','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_enabled','Enabled','','','Enabled','MatchesText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Script','invoke_entity','value','pipeline_script/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Enabled','invoke_entity','Script','pipeline_script/dot/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Script','Script','varchar','input','64','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(2,'Description','Description','varchar','input','2000','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(3,'Enabled','Enabled','char','input','1','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(4,'ResultsTag','ResultsTag','varchar','input','8','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(5,'BackfillToDMS','BackfillToDMS','varchar','input','1','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(6,'Contents','Contents','text','input','2147483647','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(7,'Parameters','Parameters','text','input','2147483647','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(8,'Fields','Fields','text','input','2147483647','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(9,'<local>','mode','varchar','input','12','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(10,'<local>','message','varchar','output','512','AddUpdateScripts');
INSERT INTO "sproc_args" VALUES(11,'<local>','callingUser','varchar','input','128','AddUpdateScripts');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Script','detail-report','Script','pipeline_script/dot','labelCol','ID',NULL);
CREATE TABLE utility_queries (id INTEGER PRIMARY KEY, "name" TEXT, "db" TEXT, "table" TEXT, "columns" TEXT, "filters" TEXT);
INSERT INTO "utility_queries" VALUES(1,'dot','broker','T_Scripts','*','{"Script":"MTx"}');
COMMIT;