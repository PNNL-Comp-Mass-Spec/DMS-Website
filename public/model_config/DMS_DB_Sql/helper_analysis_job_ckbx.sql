﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_Report_Numeric');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Created');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_cols','''x'' AS Sel, *');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_state','State','6','','State','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_tool_name','Tool Name','32','','Tool Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_dataset','Dataset','25!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_parm_file','Parm File','32','','Parm File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_protein_collection_list','Protein Collection List','32','','Protein Collection List','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_protein_options','Protein Options','32','','Protein Options','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_request','Request','32','','Request','Equals','text','24','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Job','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Job','update_opener','value','','');
COMMIT;