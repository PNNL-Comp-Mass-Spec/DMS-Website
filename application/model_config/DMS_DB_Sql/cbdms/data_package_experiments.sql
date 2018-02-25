PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_Experiments_List_Report');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID, Experiment');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','4!','','ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_experiment','Experiment','20!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_campaign','Campaign','20!','','Campaign','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Experiment','invoke_entity','value','experiment/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','data_package/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Reason','min_col_width','value','50','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','50','');
COMMIT;
