﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Helper_Organism_DB_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Name');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_data_cols','Name, Organism, NumProteins, NumResidues, ID, Created, Size_MB, Description');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','50!','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_organism','Organism','32!','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','20!','','Description','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(2,'Name','update_opener','value','','');
INSERT INTO "list_report_hotlinks" VALUES(3,'NumProteins','format_commas','value','','{"Decimals":"0"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'NumResidues','format_commas','value','','{"Decimals":"0"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Organism','invoke_entity','value','organism/report/~','');
COMMIT;
