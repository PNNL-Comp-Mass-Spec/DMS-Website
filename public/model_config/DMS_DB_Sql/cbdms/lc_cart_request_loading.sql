﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Cart_Request_Loading_List_Report');
INSERT INTO "general_params" VALUES('alternate_title_report','LC Cart Request Assignment');
INSERT INTO "general_params" VALUES('list_report_cmds','lc_cart_request_loading_cmds');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateLCCartRequestAssignments');
INSERT INTO "general_params" VALUES('list_report_cmds_url','lc_cart_request_loading/operation');
INSERT INTO "general_params" VALUES('list_report_data_cols','Request as Sel, *');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Request');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_batchid','BatchID','20','','BatchID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','20','','Name','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_cart','Cart','20','','Cart','ContainsText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_cart_config','Cart Config','20','','Cart_Config','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(4,'BatchID','invoke_entity','BatchID','requested_run_batch/show','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Cart','inplace_edit','Request','','{"width":"10"}');
INSERT INTO "list_report_hotlinks" VALUES(6,'Cart_Config','inplace_edit','Request','','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Col','inplace_edit','Request','','{"width":"5"}');
INSERT INTO "list_report_hotlinks" VALUES(8,'Request','invoke_entity','Request','requested_run/show','');
INSERT INTO "list_report_hotlinks" VALUES(9,'Sel','CHECKBOX','value','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'cartAssignmentList','cartAssignmentList','text','input','2147483647','UpdateLCCartRequestAssignments');
INSERT INTO "sproc_args" VALUES(2,'<local>','mode','varchar','input','32','UpdateLCCartRequestAssignments');
INSERT INTO "sproc_args" VALUES(3,'<local>','message','varchar','output','512','UpdateLCCartRequestAssignments');
COMMIT;