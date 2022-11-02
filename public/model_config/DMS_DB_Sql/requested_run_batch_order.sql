﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Requested_Run_List_Report_2');
INSERT INTO general_params VALUES('list_report_data_sort_col','Created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','Request, Name, Batch, Block, [Run Order], Cart, Experiment, Wellplate, Well');
INSERT INTO general_params VALUES('alternate_title_report','Requested Run Batch Block');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Request','invoke_entity','value','requested_run/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Request','20','','Request','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_batch','Name','20','','Name','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_batchid','Batch','20','','Batch','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_block','Block','20','','Block','Equals','text','20','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_batchid','list-report.helper','','helper_requested_run_batch/report','',',');
COMMIT;
