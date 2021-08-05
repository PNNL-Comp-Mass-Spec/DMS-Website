﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_NCBI_Taxonomy_AltName_List_Report');
INSERT INTO "general_params" VALUES('my_db_group','ontology');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_tax_id','Tax_ID','','','Tax_ID','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_scientific_name','Name','','','Scientific_Name','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_synonym_type','Type','','','Synonym_Type','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_synonym','Synonym','','','Synonym','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_division','Division','','','Division','ContainsText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Tax_ID','invoke_entity','value','ncbi_taxonomy/report/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Parent_Tax_ID','invoke_entity','value','ncbi_taxonomy/report/','');
COMMIT;