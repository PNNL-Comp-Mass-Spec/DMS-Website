﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Requested_Run_Helper_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Request');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('list_report_data_cols','Request AS Sel,  Request, Name, Batch, Experiment, Instrument, Requester, Wellplate, Created');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','35!','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_experiment','Experiment','25!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Request','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Request','update_opener','value','','');
COMMIT;
