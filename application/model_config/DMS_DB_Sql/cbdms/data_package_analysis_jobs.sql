﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_Analysis_Jobs_List_Report');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID, Job');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','asc');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','12','','ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_tool','Tool','22','','Tool','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_job','Job','12','','Job','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_dataset','Dataset','45!','','Dataset','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','analysis_job/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','data_package/show','');
COMMIT;
