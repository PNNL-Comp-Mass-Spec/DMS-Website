﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_mts_pt_db_jobs');
INSERT INTO general_params VALUES('detail_report_data_table','v_mts_pt_dbs_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','peptide_db_name');
INSERT INTO general_params VALUES('list_report_data_sort_col','sort_key');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','20','','job','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_dataset','Dataset','20','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_resulttype','ResultType','20','','result_type','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_peptide_db_name','Peptide_DB_Name','20','','peptide_db_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_campaign','Campaign','20','','campaign','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_process_state','Process_State','20','','process_state','ContainsText','text','512','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_instrument','Instrument','20','','instrument','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_server_name','Server_Name','20','','server_name','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'dataset','invoke_entity','dataset','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(2,'job','invoke_entity','job','analysis_job/show','');
INSERT INTO list_report_hotlinks VALUES(3,'peptide_db_name','invoke_entity','peptide_db_name','mts_pt_db_jobs/show','');
INSERT INTO list_report_hotlinks VALUES(4,'sort_key','no_display','value','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'msms_jobs','detail-report','peptide_db_name','mts_pt_db_jobs/report/-/-/peptide_hit/~','labelCol','Peptide_DB',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'organism','detail-report','organism','organism/report/~','labelCol','Organism',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'sic_jobs','detail-report','peptide_db_name','mts_pt_db_jobs/report/-/-/sic/~','labelCol','Peptide_DB_SIC',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'mass_tag_dbs','link_list','mass_tag_dbs','mts_mt_db_jobs/show','valueCol','Mass_Tag_DB',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'server_name','detail-report','server_name','mts_pt_dbs/report/-/-/-/-/~','labelCol','Server_Name','');
COMMIT;
