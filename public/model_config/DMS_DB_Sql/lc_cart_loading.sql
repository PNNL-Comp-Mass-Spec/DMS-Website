﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Cart_Loading_2');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_cart','Cart','20','','Cart','ContainsText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_batchid','BatchID','20','','Batch','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_name','Name','20','','Name','ContainsText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'BatchID','invoke_entity','BatchID','requested_run_batch/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Request','invoke_entity','Request','requested_run/show','');
COMMIT;