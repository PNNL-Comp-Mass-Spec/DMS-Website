﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_NCBI_Taxonomy_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_NCBI_Taxonomy_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Tax_ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('my_db_group','ontology');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Tax_ID','invoke_entity','value','ncbi_taxonomy/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Parent_Tax_ID','invoke_entity','value','ncbi_taxonomy/report/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Synonyms','invoke_entity','Tax_ID','ncbi_taxonomy_altname/report','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Children','invoke_entity','Tax_ID','ncbi_taxonomy/report/-/-/','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Parent_Tax_ID','detail-report','Parent_Tax_ID','ncbi_taxonomy/show','labelCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Synonyms','detail-report','Tax_ID','ncbi_taxonomy_altname/report/','labelCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Children','detail-report','Tax_ID','ncbi_taxonomy/report/-/-/','labelCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'Taxonomy_List','tabular_list','Taxonomy_List','','valueCol','',NULL);
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_tax_id','Tax ID','20','','Tax_ID','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','','','Name','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_parent_tax_id','Parent Tax ID','20','','Parent_Tax_ID','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_parent_name','Parent Name','20','','Parent_Name','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_rank','Rank','20','','Rank','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_division','Division','20','','Division','ContainsText','text','','','');
COMMIT;
