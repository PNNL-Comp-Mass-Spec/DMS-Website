﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_notification_event_list_report');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'entity','select_case','entity_type','','{"1":"requested_run_batch","2":"analysis_job_request", "3":"sample_prep_request", "4":"dataset_id", "5":"dataset_id"}');
INSERT INTO list_report_hotlinks VALUES(2,'entity_type','no_display','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_event','Event','30!','','event','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_entity','Entity','10','','entity','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_entered','Entered','20','','entered','LaterThan','text','20','','');
COMMIT;
