﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_run_planning_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_data_sort_col','inst._group, min_request');
INSERT INTO general_params VALUES('list_report_disable_sort_persist','true');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_instrument','Inst. Group','32','','inst._group','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_queued_instrument','Queued Inst.','32','','queued_instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_min_request','Min Request','32','','min_request','GreaterThan','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_request_or_batch_name','Request/Batch Name','32','','request_or_batch_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_requester','Requester','32','','requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_batch_id','Batch','16','','batch','Equals','text','16','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_comment','Comment','32','','comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_work_package','Work Package','32','','work_package','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_proposal','Proposal','32','','proposal','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'days_in_queue','color_label','days_in_queue_bin','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(2,'min_request','invoke_entity','value','requested_run/show/','');
INSERT INTO list_report_hotlinks VALUES(3,'run_count','invoke_entity','request_name_code','requested_run_admin/report/-/-/~active/-/-/-/-/-/~@','');
INSERT INTO list_report_hotlinks VALUES(4,'work_package','invoke_entity','value','charge_code/show/','');
INSERT INTO list_report_hotlinks VALUES(5,'wp_state','color_label','wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_120","4":"clr_120","5":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(6,'proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO list_report_hotlinks VALUES(7,'batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO list_report_hotlinks VALUES(8,'comment','markup','value','60','');
INSERT INTO list_report_hotlinks VALUES(9,'request_or_batch_name','color_label','batch_priority','','{"High":"clr_80"}');
INSERT INTO list_report_hotlinks VALUES(10,'inst._group','color_label','fraction_color_mode','','{"1":"violet_background", "2":"clr_45"}');
INSERT INTO list_report_hotlinks VALUES(11,'separation_group','color_label','fraction_color_mode','','{"1":"violet_background", "2":"clr_45"}');
INSERT INTO list_report_hotlinks VALUES(13,'+batch','copy_color_from','request_or_batch_name','','');
INSERT INTO list_report_hotlinks VALUES(14,'+run_count','copy_color_from','request_or_batch_name','','');
INSERT INTO list_report_hotlinks VALUES(15,'+blocked','copy_color_from','request_or_batch_name','','');
INSERT INTO list_report_hotlinks VALUES(16,'+blkmissing','copy_color_from','request_or_batch_name','','');
INSERT INTO list_report_hotlinks VALUES(17,'+requester','copy_color_from','request_or_batch_name','','');
INSERT INTO list_report_hotlinks VALUES(18,'days_in_queue_bin','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(19,'wp_activation_state','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(20,'batch_priority','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(21,'request_name_code','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(22,'fraction_color_mode','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(23,'++run_count','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(24,'++blocked','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(25,'++blkmissing','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(26,'++batch','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(27,'+days_in_queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(28,'+days_in_prep_queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(29,'+min_request','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(30,'+proposal','export_align','value','','{"Align":"Center"}');
COMMIT;
