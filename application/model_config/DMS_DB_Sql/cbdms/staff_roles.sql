﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Staff_Roles_List_Report');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_person','Person','20','','Person','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_role','Role','20','','Role','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_state','State','20','','State','MatchesText','text','24','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Campaign','invoke_entity','Campaign','campaign/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'State','color_label','','','{"Active":"enabled_clr","Inactive":"warning_clr"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Person','invoke_entity','Person','user/report/-/~','');
COMMIT;
