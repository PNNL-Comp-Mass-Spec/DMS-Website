﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_requested_run_admin_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_cols','request AS Sel, name, campaign, inst_group, type, separation_group, origin, request, status, requester, wpn, wp_state, days_in_queue, queue_state, queued_instrument, queue_date, priority, batch, block, run_order, experiment, dataset, instrument, dataset_comment, request_name_code, days_in_queue_bin, wp_activation_state');
INSERT INTO general_params VALUES('list_report_cmds','requested_run_admin_cmds');
INSERT INTO general_params VALUES('list_report_cmds_url','requested_run_admin/operation');
INSERT INTO general_params VALUES('operations_sproc','update_requested_run_assignments');
INSERT INTO general_params VALUES('list_report_data_sort_col','request');
INSERT INTO general_params VALUES('updatewp_sproc','update_requested_run_wp');
INSERT INTO general_params VALUES('admin_sproc','update_requested_run_admin');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','35!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_request','Request ID','12','','request','Equals','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_status','Status','20','','status','StartsWithText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_batch','Batch','20','','batch','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_campaign','Campaign','30!','','campaign','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_queue_state','Queue State','20','','queue_state','StartsWithText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_queued_instrument','Queued Instrument','20','','queued_instrument','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(11,'pf_requestNameCode','Code','20!','','request_name_code','StartsWithText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'request','invoke_entity','value','requested_run/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'campaign','invoke_entity','value','campaign/show','');
INSERT INTO list_report_hotlinks VALUES(3,'experiment','invoke_entity','value','experiment/show','');
INSERT INTO list_report_hotlinks VALUES(4,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(5,'sel','CHECKBOX','value','','');
INSERT INTO list_report_hotlinks VALUES(6,'days_in_queue','color_label','days_in_queue_bin','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(7,'wp_state','color_label','wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(8,'wpn','invoke_entity','value','charge_code/show','');
INSERT INTO list_report_hotlinks VALUES(9,'batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO list_report_hotlinks VALUES(10,'+sel','no_export','value','','');
INSERT INTO list_report_hotlinks VALUES(11,'days_in_queue_bin','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(12,'wp_activation_state','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(13,'priority','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(14,'+batch','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(15,'block','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(16,'run_order','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(17,'+days_in_queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(18,'+request','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(19,'status','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(20,'+wp_state','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(21,'queue_state','export_align','value','','{"Align":"Center"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'requestList','requestList','text','input','2147483647','update_requested_run_admin');
INSERT INTO sproc_args VALUES(2,'<local>','mode','varchar','input','32','update_requested_run_admin');
INSERT INTO sproc_args VALUES(3,'<local>','message','varchar','output','512','update_requested_run_admin');
INSERT INTO sproc_args VALUES(4,'<local>','callingUser','varchar','input','128','update_requested_run_admin');
INSERT INTO sproc_args VALUES(5,'OldWorkPackage','OldWorkPackage','varchar','input','50','update_requested_run_wp');
INSERT INTO sproc_args VALUES(6,'NewWorkPackage','NewWorkPackage','varchar','input','50','update_requested_run_wp');
INSERT INTO sproc_args VALUES(7,'RequestIdList','RequestIdList','varchar','input','2147483647','update_requested_run_wp');
INSERT INTO sproc_args VALUES(8,'<local>','message','varchar','output','512','update_requested_run_wp');
INSERT INTO sproc_args VALUES(9,'<local>','callingUser','varchar','input','128','update_requested_run_wp');
INSERT INTO sproc_args VALUES(11,'<local>','mode','varchar','input','32','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(12,'param','newValue','varchar','input','512','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(13,'id','reqRunIDList','varchar','input','64000','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(14,'<local>','message','varchar','output','512','update_requested_run_assignments');
INSERT INTO sproc_args VALUES(15,'<local>','callingUser','varchar','input','128','update_requested_run_assignments');
COMMIT;
