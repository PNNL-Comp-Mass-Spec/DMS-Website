﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Run_Planning_Report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_data_sort_col','Inst. Group, Min Request');
INSERT INTO general_params VALUES('list_report_disable_sort_persist','true');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_instrument','Inst. Group','32','','Inst. Group','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_queued_instrument','Queued Inst.','32','','Queued Instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_min_request','Min Request','32','','Min Request','GreaterThan','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_request_or_batch_name','Request/Batch Name','32','','Request or Batch Name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_batch_id','Batch','16','','Batch','Equals','text','16','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_work_package','Work Package','32','','Work Package','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_proposal','Proposal','32','','Proposal','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Days in Queue','color_label','#days_in_queue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(2,'Min Request','invoke_entity','value','requested_run/show/','');
INSERT INTO list_report_hotlinks VALUES(3,'Run Count','invoke_entity','Request Name Code','requested_run_admin/report/-/-/~active/-/-/-/-/-/~@','');
INSERT INTO list_report_hotlinks VALUES(4,'Work Package','invoke_entity','value','charge_code/show/','');
INSERT INTO list_report_hotlinks VALUES(5,'WP State','color_label','#wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_120","4":"clr_120","5":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(6,'Proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO list_report_hotlinks VALUES(7,'Batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO list_report_hotlinks VALUES(8,'Comment','markup','value','60','');
INSERT INTO list_report_hotlinks VALUES(9,'Request or Batch Name','color_label','#batch_priority','','{"High":"clr_80"}');
INSERT INTO list_report_hotlinks VALUES(10,'Inst. Group','color_label','#fraction_color_mode','','{"1":"violet_background", "2":"clr_45"}');
INSERT INTO list_report_hotlinks VALUES(11,'Separation Group','color_label','#fraction_color_mode','','{"1":"violet_background", "2":"clr_45"}');
INSERT INTO list_report_hotlinks VALUES(13,'+Batch','copy_color_from','Request or Batch Name','','');
INSERT INTO list_report_hotlinks VALUES(14,'+Run Count','copy_color_from','Request or Batch Name','','');
INSERT INTO list_report_hotlinks VALUES(15,'+Blocked','copy_color_from','Request or Batch Name','','');
INSERT INTO list_report_hotlinks VALUES(16,'+BlkMissing','copy_color_from','Request or Batch Name','','');
INSERT INTO list_report_hotlinks VALUES(17,'+Requester','copy_color_from','Request or Batch Name','','');
INSERT INTO list_report_hotlinks VALUES(18,'#days_in_queue','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(19,'#wp_activation_state','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(20,'#batch_priority','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(21,'Request Name Code','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(22,'#fraction_color_mode','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(23,'++Run Count','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(24,'++Blocked','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(25,'++BlkMissing','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(26,'++Batch','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(27,'+Days in Queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(28,'+Days in Prep Queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(29,'+Min Request','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(30,'+Proposal','export_align','value','','{"Align":"Center"}');
COMMIT;
