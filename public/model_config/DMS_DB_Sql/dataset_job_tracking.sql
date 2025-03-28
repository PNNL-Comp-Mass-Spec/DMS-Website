﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_analysis_job_report_numeric');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_dataset','Dataset','40!','','dataset','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_instrument','Instrument','','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_tool','Tool','','','tool_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_state','State','','','state','ContainsText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_job','Job','8!','','job','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_most_recent_weeks','Most recent weeks','','','created','MostRecentWeeks','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','analysis_job/show','');
INSERT INTO list_report_hotlinks VALUES(2,'dataset','invoke_entity','value','dataset/show/','');
COMMIT;
