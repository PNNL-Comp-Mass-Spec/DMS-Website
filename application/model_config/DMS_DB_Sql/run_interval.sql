﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Run_Interval');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Run_Interval_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Run_Interval_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Run_Interval_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateRunInterval');
INSERT INTO "general_params" VALUES('entry_submission_cmds','run_interval_cmds');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_start','Start','20','','Start','LaterThan','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_comment','Comment','20','','Comment','ContainsText','text','2147483647','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','run_interval/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','input','','AddUpdateRunInterval');
INSERT INTO "sproc_args" VALUES(2,'Comment','Comment','varchar','input','2147483647','AddUpdateRunInterval');
INSERT INTO "sproc_args" VALUES(3,'<local>','mode','varchar','input','12','AddUpdateRunInterval');
INSERT INTO "sproc_args" VALUES(4,'<local>','message','varchar','output','512','AddUpdateRunInterval');
INSERT INTO "sproc_args" VALUES(5,'<local>','callingUser','varchar','input','128','AddUpdateRunInterval');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID',' ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'Instrument','Instrument','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(5,'Start','Start','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(6,'Interval','Interval','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(7,'Comment',' Comment','area','','','10','70','','trim|max_length[2147483647]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(6,'Comment','picker.append','longIntervalUsagePickList','','',',','Usage Category');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Usage','tabular_list','Usage','','valueCol','dl_Usage','');
COMMIT;
