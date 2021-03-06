﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_Aggregation_List_Report');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('list_report_cmds','data_package_job_coverage_cmds');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateXxx');
INSERT INTO "general_params" VALUES('list_report_cmds_url','/xxx/operation');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Dataset');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel.','CHECKBOX','value','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Job','invoke_entity','Job','analysis_job/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Data_Package_ID','invoke_entity','value','data_package/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_data_package_id','ID','4!','','Data_Package_ID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','30!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_tool','Tool','','','Tool','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_in_package','In_Package','3!','','In_Package','ContainsText','text','3','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_state','State','','','State','ContainsText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_param_file','Param_File','20!','','Parm File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_settings_file','Settings_File','','','Settings_File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_protein_collection','Protein_Col','30!','','Protein Collection List','ContainsText','text','256','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_job','Job','','','Job','Equals','text','20','','');
COMMIT;
