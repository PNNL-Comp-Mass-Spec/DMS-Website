﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_analysis_job_report_numeric');
INSERT INTO general_params VALUES('list_report_data_sort_col','created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','''x'' AS Sel, *');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_state','State','6','','state','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_tool_name','Tool Name','15!','','tool_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','60!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_instrument','Instrument','32','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_param_file','Param File','50!','','param_file','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_protein_collection_list','Protein Collection List','50!','','protein_collection_list','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_protein_options','Protein Options','32','','protein_options','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_comment','Comment','32','','comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_request','Request','32','','request','Equals','text','24','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'sel','CHECKBOX','job','','');
INSERT INTO list_report_hotlinks VALUES(2,'job','update_opener','value','','');
COMMIT;
