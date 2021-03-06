﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Requested_Run_Unified_List');
INSERT INTO "general_params" VALUES('list_report_data_cols','Request as Sel, Request, Name, BatchID, Dataset, Experiment ');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Request');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Request','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Request','update_opener','Request','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_batchid','BatchID','20','','BatchID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','20','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_experiment','Experiment','30!','','Experiment','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_request_id','RequestID','20','','Request','Equals','text','50','','');
COMMIT;
