﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_eus_proposal_users');
INSERT INTO general_params VALUES('list_report_data_sort_col','user_name');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'user_id','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(2,'proposal','no_display','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_proposal','Proposal','6','','proposal','MatchesText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_user_name','User Name','35!','','user_name','ContainsText','text','128','','');
COMMIT;
