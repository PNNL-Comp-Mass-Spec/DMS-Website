﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_MTS_MT_DB_Jobs');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_MTS_MT_DBs_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','MT_DB_Name');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','#SortKey');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','desc');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_job','Job','20','','Job','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','20','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_resulttype','ResultType','20','','ResultType','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_mt_db_name','MT_DB_Name','20','','MT_DB_Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_process_state','State','20','','Process_State','ContainsText','text','512','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_server_name','Server','20','','Server_Name','ContainsText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Dataset','invoke_entity','Dataset','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Job','invoke_entity','Job','analysis_job/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'MT_DB_Name','invoke_entity','MT_DB_Name','mts_mt_db_jobs/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'MSMS_Jobs','detail-report','MT_DB_Name','mts_mt_db_jobs/report/-/-/Peptide_Hit/~','labelCol','MT_DB',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'MS_Jobs','detail-report','MT_DB_Name','mts_mt_db_jobs/report/-/-/HMMA/~','labelCol','MT_DB_HMMA',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Campaign','detail-report','Campaign','campaign/show','valueCol','Campaign',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Organism','detail-report','Organism','organism/report/~','labelCol','Organism',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'PM_Task_Count','detail-report','MT_DB_Name','mts_pm_results/report/-/-/-/~','labelCol','PM_Tasks',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Peptide_DB','detail-report','Peptide_DB','mts_pt_db_jobs/show','valueCol','PeptideDB',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'Server_Name','detail-report','Server_Name','mts_mt_dbs/report/-/-/-/-/-/~','labelCol','Server','');
COMMIT;
