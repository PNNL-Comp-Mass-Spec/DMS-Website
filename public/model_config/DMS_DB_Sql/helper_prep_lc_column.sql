﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_helper_prep_lc_column_list_report');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_column_name','Column Name','20','','column_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_mfg_name','Mfg Name','20','','mfg_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_comment','Comment','20','','comment','ContainsText','text','244','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'column_name','update_opener','value','','');
COMMIT;
