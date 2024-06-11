﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_ontology_cv_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_ontology_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','term_pk');
INSERT INTO general_params VALUES('my_db_group','ontology');
INSERT INTO general_params VALUES('list_report_data_sort_col','source, identifier');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(3,'ontology','invoke_entity','ontology','ontology_cv/report/~@','');
INSERT INTO list_report_hotlinks VALUES(4,'identifier','invoke_entity','term_pk','ontology/show','');
INSERT INTO list_report_hotlinks VALUES(5,'term_name','invoke_entity','term_name','ontology_cv/report/-/-/~@','');
INSERT INTO list_report_hotlinks VALUES(6,'parent_term_name','invoke_entity','parent_term_name','ontology_cv/report/-/-/~@','');
INSERT INTO list_report_hotlinks VALUES(7,'grandparent_term_name','invoke_entity','grandparent_term_name','ontology_cv/report/-/-/~@','');
INSERT INTO list_report_hotlinks VALUES(8,'term_pk','invoke_entity','term_pk','ontology/report/-/-/-/~@','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_ontology','Ontology','20','','source','StartsWithText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_identifier','Identifier','20','','identifier','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_term_name','Term','30!','','term_name','ContainsText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_term_pk','Term_PK','20','','term_pk','StartsWithText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_parent_term_name','Parent Name','30!','','parent_term_name','ContainsText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_grandparent_term_name','Grandparent Name','30!','','grandparent_term_name','ContainsText','text','255','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'parent_term_pk','detail-report','parent_term_pk','ontology/show','valueCol','',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'grandparent_term_pk','detail-report','grandparent_term_pk','ontology/show','valueCol','',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'term_name','detail-report','term_name','ontology/report/-/-/~@','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(4,'ontology_short_name','detail-report','ontology_short_name','ontology/report/~@','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(5,'parent_term_name','detail-report','parent_term_name','ontology/report/-/-/~@','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(6,'grandparent_term_name','detail-report','grandparent_term_name','ontology/report/-/-/~@','valueCol','','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
COMMIT;
