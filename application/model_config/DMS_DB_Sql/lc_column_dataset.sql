﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Column_Dataset_Count');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_column_number','Column Number','60','','Column Number','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_state','State','60','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_number_of_datasets_greater_than','Number of Datasets (greater than)','12','','Number of Datasets','GreaterThan','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Column Number','invoke_entity','value','lc_column/show','');
COMMIT;
