﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Experiment_Groups_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Group_Name','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','32','','ID','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_name','Group Name','32','','Group_Name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','32','','Description','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_parent_experiment','Parent Experiment','32','','Parent_Experiment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_group_type','Group ype','32','','Group_Type','ContainsText','text','128','','');
COMMIT;
