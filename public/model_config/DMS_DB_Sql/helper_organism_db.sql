﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_helper_organism_db_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','name');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_data_cols','name, organism, num_proteins, num_residues, id, created, size_mb, description, is_decoy');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','50!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_organism','Organism','32!','','organism','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','20!','','description','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(2,'name','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(3,'num_proteins','format_commas','value','','{"Decimals":"0"}');
INSERT INTO list_report_hotlinks VALUES(4,'num_residues','format_commas','value','','{"Decimals":"0"}');
INSERT INTO list_report_hotlinks VALUES(5,'organism','invoke_entity','value','organism/report/~','');
INSERT INTO list_report_hotlinks VALUES(6,'created','min_col_width','value','15','');
INSERT INTO list_report_hotlinks VALUES(7,'is_decoy','column_tooltip','value','When &quot;Yes&quot;, the FASTA file has forward and reverse proteins','');
COMMIT;
