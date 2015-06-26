PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Experiment_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Created');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_cols','''x'' AS Sel, Experiment, Created, Researcher, Organism, Comment, Reason');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_experiment','Experiment','6','','Experiment','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_researcher','Researcher','6','','Researcher','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_organism','Organism','6','','Organism','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_reason','Reason','6','','Reason','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Experiment','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Experiment','update_opener','value','','');
COMMIT;
