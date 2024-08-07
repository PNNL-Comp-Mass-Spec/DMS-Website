﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_dataset_separation_type_usage_2');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'separation_type','update_opener','separation_type','separation_type/show','');
INSERT INTO list_report_hotlinks VALUES(2,'sel','CHECKBOX','separation_type','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_separation_type','Type','35!','','separation_type','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_separation_type_comment','Comment','20','','separation_type_comment','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset_usage_last_12_months','Usage (12 Mo) ΓëÑ','20','','usage_last_12_months','GreaterThanOrEqualTo','text','20','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_dataset_usage_all_years','Usage All ΓëÑ','20','','dataset_usage_all_years','GreaterThanOrEqualTo','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_most_recent_use','Most Recent','20','','most_recent_use','LaterThan','text','20','','');
COMMIT;
