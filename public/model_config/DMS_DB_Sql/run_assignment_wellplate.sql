﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_run_assignment_wellplate_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'wellplate','invoke_entity','value','wellplate/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'requested','invoke_entity','wellplate','requested_run/report/-/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Wellplate/MatchesText/@','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_wellplate','Wellplate','','','wellplate','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_description','Description','','','description','ContainsText','text','','','');
COMMIT;
