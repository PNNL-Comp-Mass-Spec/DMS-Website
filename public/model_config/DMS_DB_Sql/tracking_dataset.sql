﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Dataset');
INSERT INTO general_params VALUES('list_report_data_table','v_tracking_dataset_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_tracking_dataset_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','dataset');
INSERT INTO general_params VALUES('entry_page_data_table','v_tracking_dataset_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','dataset');
INSERT INTO general_params VALUES('entry_sproc','add_update_tracking_dataset');
INSERT INTO general_params VALUES('list_report_data_sort_col','start');
INSERT INTO general_params VALUES('list_report_data_sort_dir','Desc');
INSERT INTO general_params VALUES('post_submission_detail_id','dataset');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'dataset','datasetName','varchar','input','128','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(2,'experiment','experimentName','varchar','input','64','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(3,'operator_username','operatorUsername','varchar','input','64','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(4,'instrument_name','instrumentName','varchar','input','64','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(5,'run_start','runStart','varchar','input','32','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(6,'run_duration','runDuration','varchar','input','16','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(7,'comment','comment','varchar','input','512','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(8,'eus_proposal_id','eusProposalID','varchar','input','10','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(9,'eus_usage_type','eusUsageType','varchar','input','50','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(10,'eus_users_list','eusUsersList','varchar','input','1024','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(11,'<local>','mode','varchar','input','12','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(12,'<local>','message','varchar','output','512','add_update_tracking_dataset');
INSERT INTO sproc_args VALUES(13,'<local>','callingUser','varchar','input','128','add_update_tracking_dataset');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'dataset','Dataset','text-if-new','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(2,'experiment','Experiment','text','50','64','','','Tracking','trim|max_length[64]');
INSERT INTO form_fields VALUES(3,'operator_username','User','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(4,'instrument_name','Instrument','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(5,'run_start','Run Start','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(6,'run_duration','Run Duration','text','32','32','','','10','trim|max_length[16]');
INSERT INTO form_fields VALUES(7,'comment','Comment','area','','','4','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(8,'eus_usage_type','EMSL Usage Type','text','50','50','','','','trim|max_length[50]');
INSERT INTO form_fields VALUES(9,'eus_proposal_id','EMSL Proposal ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO form_fields VALUES(10,'eus_users_list','EMSL Proposal User','area','','','4','70','','trim|max_length[1024]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'experiment','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO form_field_choosers VALUES(2,'instrument_name','picker.replace','instrumentNameAdminPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'operator_username','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'eus_usage_type','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'eus_users_list','list-report.helper','','helper_eus_user_ckbx/report','eus_proposal_id',',','');
INSERT INTO form_field_choosers VALUES(6,'eus_proposal_id','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO form_field_choosers VALUES(7,'eus_proposal_id','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'operator_username','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'run_start','default_function','CurrentDate');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'dataset','invoke_entity','value','tracking_dataset/show/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_instrument','Instrument','20','','instrument','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_emsl_proposal_id','EMSL_Proposal_ID','20','','emsl_proposal_id','ContainsText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_month','Month','20','','month','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_day','Day','20','','day','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_operator','Operator','20','','operator','ContainsText','text','103','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'dataset','detail-report','dataset','dataset/show/','labelCol','dataset','');
COMMIT;
