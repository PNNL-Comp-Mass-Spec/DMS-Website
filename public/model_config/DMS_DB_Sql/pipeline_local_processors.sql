﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Pipeline_Local_Processors_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Pipeline_Local_Processors_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Processor Name');
INSERT INTO "general_params" VALUES('my_db_group','broker');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_processor_name','Processor Name','6','','Processor Name','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_machine','Machine','6','','Machine','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','pipeline_local_processors/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Steps','invoke_entity','Job','pipeline_job_steps/report','');
COMMIT;