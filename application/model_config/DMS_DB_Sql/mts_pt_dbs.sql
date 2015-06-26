PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_MTS_PT_DBs');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Peptide_DB_ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_peptide_db_name','DB_Name','20','','Peptide_DB_Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Description','20','','Description','ContainsText','text','2048','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_state','State','20','','State','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_organism','Organism','20','','Organism','ContainsText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Peptide_DB_Name','invoke_entity','Peptide_DB_Name','mts_pt_db_jobs/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Organism','invoke_entity','Organism','organism/report/~','');
INSERT INTO "list_report_hotlinks" VALUES(3,'MSMS_Jobs','invoke_entity','Peptide_DB_Name','mts_pt_db_jobs/report/-/-/Peptide_Hit/~','');
INSERT INTO "list_report_hotlinks" VALUES(4,'SIC_Jobs','invoke_entity','Peptide_DB_Name','mts_pt_db_jobs/report/-/-/SIC/~','');
COMMIT;
