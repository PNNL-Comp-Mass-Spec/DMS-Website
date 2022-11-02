﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Helper_NEWT_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','Term_Name');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
INSERT INTO general_params VALUES('my_db_group','ontology');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'identifier','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_identifier','identifier','20','','identifier','StartsWithText','text','96','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_term_name','Term_Name','20','','Term_Name','ContainsText','text','1020','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_parent_term_name','Parent','20','','Parent','ContainsText','text','1020','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_grandparent_term_name','GrandParent','20','','GrandParent','ContainsText','text','1020','','');
COMMIT;
