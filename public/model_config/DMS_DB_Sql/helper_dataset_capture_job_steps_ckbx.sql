﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_helper_dataset_capture_job_steps_ckbx');
INSERT INTO general_params VALUES('list_report_data_cols','''x'' AS Sel, *');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO general_params VALUES('my_db_group','capture');
INSERT INTO general_params VALUES('list_report_data_sort_col','dataset');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'sel','CHECKBOX','dataset',' ',' ');
INSERT INTO list_report_hotlinks VALUES(2,'dataset','update_opener','value',' ',' ');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job_state','Job_State','20','','job_state','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_script','Script','20','','script','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','50!','','dataset','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_job','Job','20','','job','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_storage_server','Storage_Server','20','','storage_server','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_instrument','Instrument','25!','','instrument','ContainsText','text','24','','');
COMMIT;
