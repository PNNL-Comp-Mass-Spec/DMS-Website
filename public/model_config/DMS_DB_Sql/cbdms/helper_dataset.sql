﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Dataset_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Created');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_cols','Dataset, State, Rating, Instrument, Experiment, Created');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_dataset','Dataset','50!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_experiment','Experiment','25!','','Experiment','ContainsText','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_state','picker.replace','datasetStatePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(3,'pf_experiment','list-report.Chooser','','Chooser_experiment/report','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Dataset','update_opener','value','','');
COMMIT;