﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_requested_run_batch_list_report');
INSERT INTO general_params VALUES('list_report_data_cols','id AS sel, id, name, requests, req_priority, instrument, description, owner, created, comment');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(2,'sel','CHECKBOX','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','20','','id','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_name','Name','40!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','25!','','description','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_comment','Comment','25!','','comment','ContainsText','text','512','','');
COMMIT;
