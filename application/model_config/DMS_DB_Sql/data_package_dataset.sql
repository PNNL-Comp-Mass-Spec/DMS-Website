﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_Datasets_List_Report');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID, Dataset');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','5!','','ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','45!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_experiment','Experiment','15!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','24','','Instrument','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_rating','Rating','24','','Rating','StartsWithText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','data_package/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Experiment','invoke_entity','value','experiment/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'PSM Jobs','invoke_entity','Dataset','analysis_job_psm/report/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Dataset_ID','invoke_entity','ID','data_package_dataset_files/report/@/-/-/-/-','');
COMMIT;
