﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
INSERT INTO "general_params" VALUES('my_db_group','package');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','35!','','Name','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_ID','ID','6','','ID','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','6','','Description','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_state','State','6','','State','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_type','Type','6','','Package Type','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_owner','Owner','6','','Owner','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','update_opener','value','','');
COMMIT;