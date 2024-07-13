﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_helper_prep_lc_run_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','id AS Sel, *');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','6!','','id','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_prep_run_name','Prep Run','15!','','prep_run_name','ContainsText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_instrument','Instrument','25!','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_type','Type','20','','type','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_column','LC Column','','','lc_column','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_comment','Comment','20','','comment','ContainsText','text','1024','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_created','Created','20','','created','LaterThan','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'sel','CHECKBOX','id','','');
COMMIT;
