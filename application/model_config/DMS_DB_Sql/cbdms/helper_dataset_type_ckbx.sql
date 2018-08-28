﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Helper_Dataset_Type');
INSERT INTO "general_params" VALUES('list_report_data_cols',' ''x'' AS Sel, *');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Dataset_Type');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Dataset_Type','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset_Type','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_dataset_type','Dataset_Type','20','','Dataset_Type','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Description','20','','Description','ContainsText','text','128','','');
COMMIT;
