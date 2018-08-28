﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Reference_Compound_Helper_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Compound_Name');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','CHECKBOX','ID_Name','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID_Name','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','35!','','Compound_Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Comment','32','','Description','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_ID','ID','12','','ID','Equals','text','12','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_campaign','Campaign','32','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_gene','Gene','32','','Gene','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_ID_Name','ID_Name','35!','','ID_Name','StartsWithText','text','128','','');
COMMIT;
