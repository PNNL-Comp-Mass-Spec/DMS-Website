﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Ontology_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Ontology_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','term_pk');
INSERT INTO "general_params" VALUES('my_db_group','ontology');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(2,'Parent_term_name','invoke_entity','Parent_term_name','ontology/report/-/-/-/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Grandparent_term_name','invoke_entity','Grandparent_term_name','ontology/report/-/-/-/-/','');
INSERT INTO "list_report_hotlinks" VALUES(4,'identifier','invoke_entity','Term_PK','ontology/show','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Source','invoke_entity','Source','ontology/report','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Term_Name','invoke_entity','Term_Name','ontology/report/-/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_source','Source','20','','Source','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_term_name','Term','30!','','Term_Name','ContainsText','text','255','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_identifier','Identifier','20','','identifier','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_parent_term_name','Parent','30!','','Parent_term_name','StartsWithText','text','255','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_grandparent_term_name','Grandparent','30!','','Grandparent_term_name','StartsWithText','text','255','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_term_pk','Term_PK','20','','Term_PK','StartsWithText','text','128','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Parent_term_pk','detail-report','Parent_term_pk','ontology/show','valueCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Grandparent_term_pk','detail-report','Grandparent_term_pk','ontology/show','valueCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'term_name','detail-report','term_name','ontology/report/-/','valueCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Ontology_ShortName','detail-report','Ontology_ShortName','ontology/report/','valueCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'Parent_term_name','detail-report','Parent_term_name','ontology/report/-/','valueCol','',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Grandparent_term_name','detail-report','Grandparent_term_name','ontology/report/-/','valueCol','',NULL);
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
COMMIT;
