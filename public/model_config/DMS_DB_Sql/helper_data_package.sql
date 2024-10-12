﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_data_package_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
INSERT INTO general_params VALUES('my_db_group','package');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','50!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_ID','ID','6','','id','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','20','','description','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_owner','Owner','20!','','owner','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_state','State','6','','state','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_type','Type','6','','package_type','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','update_opener','value','','');
COMMIT;
