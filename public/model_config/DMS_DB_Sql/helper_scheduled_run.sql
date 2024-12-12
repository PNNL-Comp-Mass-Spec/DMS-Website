﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_active_requested_runs');
INSERT INTO general_params VALUES('list_report_data_sort_col','created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','request, name, inst_group, experiment, requester, created, comment, wellplate, well');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','15!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_experiment','Experiment','15!','','experiment','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_instrument_group','Inst. Group','32','','inst_group','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_requester','Requester','32','','requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_comment','Comment','32','','comment','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'request','update_opener','value','','');
COMMIT;
