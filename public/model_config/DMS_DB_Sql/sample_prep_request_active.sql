﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_sample_prep_request_active_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_state','State','32','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_request_name','Request Name','32','','request_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_requester','Requester','32','','requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_organism','Organism','32','','organism','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_Work_Package','WP','32','','work_package','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_requested_personnel','Req. Personnel','32','','requested_personnel','ContainsText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_assigned_personnel','Assigned Personnel','32','','assigned_personnel','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','id','sample_prep_request/show','');
INSERT INTO list_report_hotlinks VALUES(2,'days_in_queue','color_label','days_in_queue_bin','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(3,'work_package','invoke_entity','value','charge_code/show','');
INSERT INTO list_report_hotlinks VALUES(4,'wp_state','color_label','wp_activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO list_report_hotlinks VALUES(5,'reason','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(6,'comment','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(7,'inst_analysis','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(8,'eus_proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO list_report_hotlinks VALUES(9,'tissue','invoke_entity','value','tissue/report/~','');
INSERT INTO list_report_hotlinks VALUES(10,'organism','invoke_entity','value','organism/report/~','');
INSERT INTO list_report_hotlinks VALUES(11,'campaign','invoke_entity','value','campaign/show','');
INSERT INTO list_report_hotlinks VALUES(13,'days_in_queue_bin','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(14,'wp_activation_state','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(15,'+id','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(16,'num_samples','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(17,'ms_runs_tbg','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(18,'+days_in_queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(19,'+work_package','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(20,'+wp_state','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(21,'+eus_proposal','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(22,'files','invoke_entity','id','file_attachment/report/-/StartsWith__sample_prep_request/@','');
COMMIT;
