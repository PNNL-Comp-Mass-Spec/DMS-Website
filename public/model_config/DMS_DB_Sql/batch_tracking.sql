﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_batch_tracking_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','batch');
INSERT INTO general_params VALUES('list_report_data_sort_dir','desc');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_batch','Batch','20','','batch','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_name','Name','20','','name','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_status','Status','20','','status','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_request','Request','20','','request','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_dataset_id','Dataset_ID','20','','dataset_id','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_dataset','Dataset','20','','dataset','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_block','Block','20','','block','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_start','Start','20','','start','LaterThan','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(2,'batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO list_report_hotlinks VALUES(3,'request','invoke_entity','value','requested_run/show','');
COMMIT;
