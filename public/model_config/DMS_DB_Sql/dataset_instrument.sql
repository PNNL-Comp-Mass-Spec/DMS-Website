﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_dataset_instrument_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','instrument, id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_instrument','Instrument','32','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_dataset','Dataset','45!','','dataset','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_experiment','Experiment','32','','experiment','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_requester','Requester','32','','requester','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(2,'experiment','invoke_entity','value','experiment/show','');
INSERT INTO list_report_hotlinks VALUES(3,'request','invoke_entity','value','requested_run/show','');
COMMIT;
