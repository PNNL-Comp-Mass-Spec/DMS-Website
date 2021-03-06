﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_EUS_Active_Proposal_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Proposal ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_title','Title','32','','Title','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_proposal','ID','32','','Proposal ID','StartsWithText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_user_last_name','Last Name','32','','User Last Names','ContainsText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Proposal ID','update_opener','value','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Start_Date','format_date','value','15','{"Format":"Y-m-d"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'End_Date','format_date','value','15','{"Format":"Y-m-d"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'State','color_label','value','','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
COMMIT;
