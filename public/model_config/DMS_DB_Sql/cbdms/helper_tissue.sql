﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Helper_BTO_Tissue_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','Usage');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
INSERT INTO general_params VALUES('my_db_group','ontology');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(2,'Tissue','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_Tissue','Tissue','32','','Tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_identifier','Identifier','24','','Identifier','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_parent','Parent','32','','Parent Tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_grandparent','Grandparent','32','','Grandparent Tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_synonyms','Synonym','32','','Synonyms','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_is_leaf','Leaf node','32','','Is Leaf','Equals','text','8','','');
COMMIT;
