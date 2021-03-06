﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Param_File_Picklist');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','SortKey');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_toolname','ToolName','32','','ToolName','MatchesText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','50!','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_desc','Desc','32','','Desc','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Name','update_opener','value','','');
COMMIT;
