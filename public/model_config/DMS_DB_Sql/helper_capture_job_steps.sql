﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Capture_Job_Steps_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_cols',' ''x'' AS Sel, *');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('my_db_group','capture');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Job');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Job','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Job','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_tool','Tool','6','','Tool','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_script','Script','6','','Script','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_step_state','Step_State','6','','Step_State','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_job_state_b','Job_State (Not)','20','','Job_State_B','DoesNotContainText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_job','Job','6','','Job','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_step','Step','6','','Step','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_processor','Processor','6','','Processor','ContainsText','text','80','','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'JobList','list-report.helper','','helper_capture_job_steps/report','',',','Job steps...');
COMMIT;