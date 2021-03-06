﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_EUS_Proposals_Helper_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Proposal ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_title','Title','32','','Title','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_request','Request','35!','','Request','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Proposal ID','update_opener','value','','');
COMMIT;
