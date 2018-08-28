PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Run_Assignment_Wellplate_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Well Plate','invoke_entity','value','wellplate/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Requested','invoke_entity','Well Plate','requested_run/report/-/-/-/','');
COMMIT;
