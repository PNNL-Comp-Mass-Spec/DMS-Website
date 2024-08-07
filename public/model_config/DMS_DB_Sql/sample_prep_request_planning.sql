﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_sample_prep_request_planning_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_data_sort_col','assigned_sort_key, days_in_queue');
INSERT INTO general_params VALUES('list_report_disable_sort_persist','false');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_requester','Requester','32','','requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_assigned','Assigned','32','','assigned','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_state','State','32','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_requestname','Request Name','32','','request_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_campaign','Campaign','20','','campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_WP','WP','32','','wp','ContainsText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','id','sample_prep_request/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'days_in_queue','color_label','days_in_queue_bin','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(3,'wp','invoke_entity','value','charge_code/show','');
INSERT INTO list_report_hotlinks VALUES(4,'wp_state','color_label','wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(5,'campaign','invoke_entity','value','campaign/show','');
INSERT INTO list_report_hotlinks VALUES(6,'days_in_queue_bin','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(7,'wp_activation_state','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(8,'+id','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(9,'num_samples','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(10,'ms_runs_tbg','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(11,'+days_in_queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(12,'+wp','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(13,'+wp_state','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(14,'assigned_sort_key','no_display','value','','');
COMMIT;
