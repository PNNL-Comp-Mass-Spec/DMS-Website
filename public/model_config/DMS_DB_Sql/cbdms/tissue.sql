﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_BTO_Tissue_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','Usage');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('my_db_group','ontology');
INSERT INTO general_params VALUES('detail_report_data_table','V_BTO_Tissue_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Identifier');
INSERT INTO general_params VALUES('detail_report_data_id_type','string');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Identifier','invoke_entity','value','tissue/show','');
INSERT INTO list_report_hotlinks VALUES(2,'Tissue','invoke_entity','value','tissue/report/','');
INSERT INTO list_report_hotlinks VALUES(3,'Parent Tissue','invoke_entity','value','tissue/report/-/-/','');
INSERT INTO list_report_hotlinks VALUES(4,'Grandparent Tissue','invoke_entity','value','tissue/report/-/-/-/','');
INSERT INTO list_report_hotlinks VALUES(5,'Children','invoke_entity','Tissue','tissue/report/-/-/','');
INSERT INTO list_report_hotlinks VALUES(6,'Parent ID','invoke_entity','value','tissue/show','');
INSERT INTO list_report_hotlinks VALUES(7,'Grandparent ID','invoke_entity','value','tissue/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_Tissue','Tissue','30!','','Tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_identifier','Identifier','20','','Identifier','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_parent','Parent','30!','','Parent Tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_grandparent','Grandparent','30!','','Grandparent Tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_synonyms','Synonym','30!','','Synonyms','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_is_leaf','Leaf node','20','','Is Leaf','Equals','text','8','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'Tissue','detail-report','Tissue','tissue/report/','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(2,'Parent Tissue','detail-report','Parent Tissue','tissue/report/','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(3,'Grandparent Tissue','detail-report','Grandparent Tissue','tissue/report/','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(4,'Parent ID','detail-report','Parent ID','tissue/show/','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(5,'Grandparent ID','detail-report','Grandparent ID','tissue/show/','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(6,'Children','detail-report','Tissue','tissue/report/-/-/','valueCol','','');
COMMIT;
