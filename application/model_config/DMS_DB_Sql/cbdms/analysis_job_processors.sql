﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_Processors_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Analysis_Job_Processors_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateAnalysisJobProcessors');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Analysis_Job_Processors_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'State','State','text','1','1','','','E','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(3,'ProcessorName','ProcessorName','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'Machine','Machine','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'Notes','Notes','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(6,'AnalysisToolsList','AnalysisToolsList','area','','','4','60','','trim|max_length[1024]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'AnalysisToolsList','list-report.helper','','helper_analysis_processor_tool/report','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','32','','Name','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','analysis_job_processors/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(2,'State','State','char','input','1','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(3,'ProcessorName','ProcessorName','varchar','input','64','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(4,'Machine','Machine','varchar','input','64','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(5,'Notes','Notes','varchar','input','512','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(6,'AnalysisToolsList','AnalysisToolsList','varchar','input','1024','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(7,'<local>','mode','varchar','input','12','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(8,'<local>','message','varchar','output','512','AddUpdateAnalysisJobProcessors');
INSERT INTO "sproc_args" VALUES(9,'<local>','callingUser','varchar','input','128','AddUpdateAnalysisJobProcessors');
COMMIT;
