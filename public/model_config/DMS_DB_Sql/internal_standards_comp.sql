﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Internal_Standards_Composition_Report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Internal Std Name','6','','name','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks (  id INTEGER PRIMARY KEY, name text,  LinkType text, WhichArg text,  Target text, Options text);
INSERT INTO list_report_hotlinks VALUES(1,'name','no_display','value','','');
COMMIT;
