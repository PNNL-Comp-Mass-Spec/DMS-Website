﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Campaign_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','Created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','''x'' AS Sel, Campaign, Created, PI, Comment');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_campaign','Campaign','30!','','Campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_pi','PI','20!','','PI','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Campaign','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(2,'Sel','CHECKBOX','Campaign','','');
COMMIT;
