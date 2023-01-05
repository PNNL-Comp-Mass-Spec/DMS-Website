﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Project_Usage_Stats');
INSERT INTO general_params VALUES('list_report_data_table','v_project_usage_stats');
INSERT INTO general_params VALUES('detail_report_data_id_col','Entry_ID');
INSERT INTO general_params VALUES('list_report_data_sort_col','sort_key');
INSERT INTO general_params VALUES('list_report_data_sort_dir','Desc');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_Year','Year','','','year','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_Week','Week','','','week','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_Project_Type','Project Type','','','project_type','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_Proposal_ID','Proposal','','','proposal_id','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_Work_Package','WP','','','work_package','ContainsText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'proposal_id','invoke_entity','value','eus_proposals/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'proposal_user','invoke_entity','value','eus_proposals/report/-/-/','');
INSERT INTO list_report_hotlinks VALUES(3,'instrument_last','invoke_entity','value','dataset/report/-/-/-/@/-/-/6','');
COMMIT;
