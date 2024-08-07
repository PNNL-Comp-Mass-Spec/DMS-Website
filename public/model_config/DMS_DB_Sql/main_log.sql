﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_log_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_type','Type','32','','type','ContainsText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_message','Message','6','','message','ContainsText','text','80','2','60');
INSERT INTO list_report_primary_filter VALUES(3,'pf_entry','Entry','20','','entry','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_posted_by','Posted By','60','','posted_by','ContainsText','text','64','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_type','picker.replace','logEntryTypePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(2,'pf_posted_by','picker.replace','logEntryPostedByPickList','','',',');
COMMIT;
