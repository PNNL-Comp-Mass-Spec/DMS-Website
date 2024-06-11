﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_newt_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_newt_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','identifier');
INSERT INTO general_params VALUES('my_db_group','ontology');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_term_name','Name Starts With','40!','','term_name','StartsWithText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_identifier','ID','6!','','identifier','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_parent_term_id','Parent Term ID','6!','','parent_term_id','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_grandparent_term_id','Grandparent Term ID','6!','','grandparent_term_id','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_rank','Rank','','','rank','StartsWithText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_is_leaf','Is Leaf','6!','','is_leaf','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_parent_term_name','Parent Name Starts With','40!','','parent_term_name','StartsWithText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_grandparent_term_name','Grandparent Name Starts With','40!','','grandparent_term_name','StartsWithText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_term_name_contains','Name Contains','35!','','term_name','ContainsText','text','255','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_common_name','Common Name Contains','35!','','common_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(11,'pf_synonym','Synonym Contains','35!','','synonym','ContainsText','text','128','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'term_name','detail-report','identifier','ncbi_taxonomy/show/','labelCol','dl_identifier_ncbi_info','');
INSERT INTO detail_report_hotlinks VALUES(2,'identifier','detail-report','identifier','ontology/show/@newt1','labelCol','dl_identifier_ontology_info','');
INSERT INTO detail_report_hotlinks VALUES(3,'+identifier','detail-report','identifier','newt/report/-/@/-/-/','valueCol','dl_identifier_list_report','');
INSERT INTO detail_report_hotlinks VALUES(4,'parent_term_identifier','detail-report','parent_term_identifier','newt/show/','labelCol','dl_parent_time_id','');
INSERT INTO detail_report_hotlinks VALUES(5,'+parent_term_identifier','detail-report','parent_term_identifier','newt/report/-/-/@/-/','valueCol','dl_parent_time_identifier_list_report','');
INSERT INTO detail_report_hotlinks VALUES(6,'grandparent_term_identifier','detail-report','grandparent_term_identifier','newt/show/','labelCol','dl_grandparent_time_id','');
INSERT INTO detail_report_hotlinks VALUES(7,'+grandparent_term_identifier','detail-report','grandparent_term_identifier','newt/report/-/-/-/@/','valueCol','dl_grandparent_time_identifier','');
INSERT INTO detail_report_hotlinks VALUES(8,'children','detail-report','identifier','newt/report/-/-/@/-/','valueCol','dl_children','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'identifier','invoke_entity','value','newt/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'parent_term_id','invoke_entity','value','newt/show/','');
INSERT INTO list_report_hotlinks VALUES(3,'grandparent_term_id','invoke_entity','value','newt/show/','');
COMMIT;