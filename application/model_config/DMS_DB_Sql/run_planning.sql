﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Run_Planning_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Inst. Group, Min Request');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Inst. Group','32','','Inst. Group','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_min_request','Min Request','32','','Min Request','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_request_or_batch_name','Request/Batch Name','32','','Request or Batch Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_batch_id','Batch','16','','Batch','Equals','text','16','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_work_package','Work Package','32','','Work Package','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_proposal','Proposal','32','','Proposal','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Days in Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(2,'Min Request','invoke_entity','value','requested_run/show/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Run Count','invoke_entity','Request Name Code','requested_run_admin/report/-/-/~active/-/-/-/-/-/~@','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Work Package','invoke_entity','value','charge_code/show/','');
INSERT INTO "list_report_hotlinks" VALUES(5,'WP State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_120","4":"clr_120","5":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(6,'Proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO "list_report_hotlinks" VALUES(8,'Comment','markup','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(9,'Request or Batch Name','color_label','#BatchPriority','','{"High":"clr_80"}');
COMMIT;
