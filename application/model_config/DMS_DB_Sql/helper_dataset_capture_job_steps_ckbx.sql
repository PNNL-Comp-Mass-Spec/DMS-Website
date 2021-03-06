﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Helper_Dataset_Capture_Job_Steps_Ckbx');
INSERT INTO "general_params" VALUES('list_report_data_cols',' ''x'' AS Sel, *');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('my_db_group','capture');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Dataset');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Dataset',' ',' ');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset','update_opener','value',' ',' ');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_job_state','Job_State','20','','Job_State','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_script','Script','20','','Script','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_dataset','Dataset','30!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_job','Job','20','','Job','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_storage_server','Storage_Server','20','','Storage_Server','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
COMMIT;
