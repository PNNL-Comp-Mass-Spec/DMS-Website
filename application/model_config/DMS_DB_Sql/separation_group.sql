﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Separation_Group');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Separation_Group_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Separation_Group_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Separation_Group');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Separation_Group_List_Report');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Separation_Group');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateSeparationGroup');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_sep_group','Sep Group','','','Separation_Group','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_active','Active','','','Active','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_sample_prep_visible','Sample Prep Visible','','','Sample_Prep_Visible','Equals','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Separation_Group','invoke_entity','value','separation_group/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Separation_Types','invoke_entity','Separation_Group','separation_type/report/-/-/~','');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Separation_Group','Separation Group','text-if-new','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(2,'Comment','Comment','area','64','512','2','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(3,'Active','Active','text','12','12','','','','trim|numeric');
INSERT INTO "form_fields" VALUES(4,'Sample_Prep_Visible','Sample Prep Visible','text','12','12','','','','trim|numeric');
INSERT INTO "form_fields" VALUES(5,'Fraction_Count','Fraction Count','text','12','12','','','','trim|numeric');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Separation_Group','separationGroup','varchar','input','64','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(2,'Comment','comment','varchar','input','512','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(3,'Active','active','tinyint','input','','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(4,'Sample_Prep_Visible','samplePrepVisible','tinyint','input','','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(5,'Fraction_Count','fractionCount','smallint','input','','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(6,'<local>','mode','varchar','input','12','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(7,'<local>','message','varchar','output','512','AddUpdateSeparationGroup');
INSERT INTO "sproc_args" VALUES(8,'<local>','callingUser','varchar','input','128','AddUpdateSeparationGroup');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Separation_Types','detail-report','Separation_Group','separation_type/report/-/-/~','labelCol','dl_separation_types','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Separation_Group','detail-report','Separation_Group','requested_run/report/-/-/-/-/-/-/-/-/-/-/sfx/AND/Separation%20Group/MatchesText/','labelCol','dl_requested_runs_for_separation_group','');
COMMIT;
