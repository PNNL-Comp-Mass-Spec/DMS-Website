﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_experiment_list_report_2');
INSERT INTO general_params VALUES('list_report_data_sort_col','created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','id, experiment, created, researcher, organism, comment, reason, campaign');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_experimentid','ID','6!','','id','Equals','text','40','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_experiment','Experiment','35!','','experiment','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_researcher','Researcher','6','','researcher','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_organism','Organism','15!','','organism','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_reason','Reason','6','','reason','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_campaign','Campaign','20!','','campaign','ContainsText','text','80','','');
COMMIT;
