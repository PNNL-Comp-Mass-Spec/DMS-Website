﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Secondary_Sep');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Separation_Type_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Separation Type');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','asc');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Separation_Type_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Separation_Type_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateSeparationType');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_separation_type','Type','25!','','Separation Type','ContainsText','text','75','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_separation_type_comment','Comment','20','','Separation Type Comment','ContainsText','text','256','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_separation_group','Group','20','','Separation Group','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_sample_type','Sample Type','20','','Sample Type','ContainsText','text','75','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_dataset_usage_last_12_months','Usage (12 Mo)','20','','Usage Last 12 Months','GreaterThanOrEqualTo','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_dataset_usage_all_years','Usage All','20','','Dataset Usage All Years','GreaterThanOrEqualTo','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_most_recent_use','Most Recent','20','','Most Recent Use','LaterThan','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Separation Type','invoke_entity','ID','separation_type/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Separation Group','invoke_entity','value','separation_group/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Dataset Usage All Years','invoke_entity','Separation Type','dataset/report/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Separation%20Type/MatchesText/','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Separation Group','detail-report','Separation Group','separation_group/show','valueCol','','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Dataset Usage All Years','detail-report','Separation Type','dataset/report/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Separation%20Type/MatchesText/','valueCol','','');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Separation_Name','Name','text','50','50','','','','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(3,'Separation_Group','Separation Group','text','64','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'Comment','Comment','area','256','256','2','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(5,'Sample_Type','Sample Type','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(6,'State','State','text','12','12','','','','trim|max_length[12]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Separation_Group','picker.replace','separationGroupPickList','','','','');
INSERT INTO "form_field_choosers" VALUES(2,'Sample_Type','picker.replace','sampleTypePickList','','','','');
INSERT INTO "form_field_choosers" VALUES(3,'State','picker.replace','activeInactivePickList','','','','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Comment','auto_format','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','input','','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(2,'Separation_Name','sepTypeName','varchar','input','50','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(3,'Separation_Group','sepGroupName','varchar','input','64','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(4,'Comment','comment','varchar','input','256','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(5,'Sample_Type','sampleType','varchar','input','32','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(6,'State','state','varchar','input','12','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(7,'<local>','mode','varchar','input','12','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(8,'<local>','message','varchar','output','512','AddUpdateSeparationType');
INSERT INTO "sproc_args" VALUES(9,'<local>','callingUser','varchar','input','128','AddUpdateSeparationType');
COMMIT;
