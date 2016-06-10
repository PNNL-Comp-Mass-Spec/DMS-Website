PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_MTS_PM_Results_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Job');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Results_URL','masked_link','value','','{"Label":"Results"}');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset','invoke_entity','value','dataset/show/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Job','invoke_entity','value','analysis_job/show/','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Output_Folder_Path','masked_href-folder','value','','{"Label":"Folder"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Task_Database','invoke_entity','value','mts_mt_dbs/report','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Task_Server','invoke_entity','value','mts_mt_dbs/report/-/-/-/-/-/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_Dataset','Dataset','32','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pb_Job','Job','12','','Job','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_Instrument','Instrument','15','','Instrument','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_TaskDB','Task Database','32','','Task_Database','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_MostRecent','Most recent weeks','8','','Task_Start','MostRecentWeeks','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_StartedAfter','Started after','8','','Task_Start','LaterThan','text','20','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_StartedAfter','picker.prevDate','','','',',');
COMMIT;
