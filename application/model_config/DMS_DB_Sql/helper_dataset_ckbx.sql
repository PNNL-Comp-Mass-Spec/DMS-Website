PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Dataset_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Acq Start');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('list_report_data_cols','ID, ''x'' AS Sel, Dataset, State, Rating, Instrument, Type, Experiment, Campaign, Comment, Request, Batch, [Acq Start], [Created]');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_campaign','Campaign','32','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_experiment','Experiment','35!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_rating','Rating','32','','Rating','MatchesText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_acq_start','Most Recent Weeks','32','','Acq Start','MostRecentWeeks','text','16','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_requestID','Request','12','','Request','Equals','text','16','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_batchID','Batch','12','','Batch','Equals','text','16','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_rating','picker.replace','datasetRatingPickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(3,'pf_experiment','list-report.Chooser','','Chooser_experiment/report','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Dataset','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset','update_opener','value','','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Acq Start','format_date','value','18','{"Format":"Y-m-d"}');
COMMIT;
