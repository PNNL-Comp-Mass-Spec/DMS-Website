﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_eus_proposals_helper_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','proposal_id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_title','Title','32','','title','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_request','Request','35!','','request','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'proposal_id','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(2,'proposal_state','color_label','value','','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
COMMIT;
