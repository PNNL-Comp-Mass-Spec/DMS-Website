﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Material_Log_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_type','Type','32','','Type','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_item','Item','32','','Item','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_initial','Initial','32','','Initial','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_final','Final','32','','Final','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_user','User','32','','User','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_date','Most Recent Weeks','32','','Date','MostRecentWeeks','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(2,'Final','select_case','Item_Type','','{"Biomaterial":"material_container","Experiment":"material_container","RefCompound":"material_container","Container":"material_location"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'Initial','select_case','Item_Type','','{"Biomaterial":"material_container","Experiment":"material_container","RefCompound":"material_container","Container":"material_location"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'Item','select_case','Item_Type','','{"Biomaterial":"cell_culture","Experiment":"experiment","RefCompound":"reference_compound/report","Container":"material_container"}');
COMMIT;
