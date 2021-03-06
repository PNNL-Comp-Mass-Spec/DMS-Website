﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Material_Locations_Available_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Location');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_location','Location','6','','Location','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_freezer','Freezer','6','','Freezer','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_shelf','Shelf','6','','Shelf','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_rack','Rack','6','','Rack','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_row','Row','6','','Row','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_col','Col','6','','Col','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Containers','invoke_entity','Location','material_container/report/-/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Action','invoke_entity','Location','material_container/create/init/-/-/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Location','invoke_entity','value','material_location/show/','');
COMMIT;
