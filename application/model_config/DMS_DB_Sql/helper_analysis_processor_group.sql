﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_Processor_Group_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_group_name','Group Name','32','','Group Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_group_description','Group Description','32','','Group Description','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_group_enabled','Group Enabled','32','','Group Enabled','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_general_processing','General Processing','32','','General Processing','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Group Name','update_opener','value','','');
COMMIT;
