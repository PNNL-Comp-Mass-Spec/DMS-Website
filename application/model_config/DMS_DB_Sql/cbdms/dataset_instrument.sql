PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Dataset_Instrument_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Instrument');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','32','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_experiment','Experiment','32','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_requestor','Requestor','32','','Requestor','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Experiment','invoke_entity','value','experiment/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Request','invoke_entity','value','requested_run/show','');
COMMIT;
