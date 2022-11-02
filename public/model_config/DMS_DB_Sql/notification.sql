﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Notification_By_Research_Team');
INSERT INTO general_params VALUES('entry_page_data_table','v_notification_entry');
INSERT INTO general_params VALUES('entry_sproc','UpdateNotificationUserRegistration');
INSERT INTO general_params VALUES('entry_page_data_id_col','prn');
INSERT INTO general_params VALUES('alternate_title_edit','EMail Notification Settings');
INSERT INTO general_params VALUES('operations_sproc','UpdateResearchTeamObserver');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_user','User','20','','User','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_prn','PRN','20','','#prn','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_event','Event','20','','Event','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_role','Role','20','','Role','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_batch','Entity','20','','Entity','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_name','Name','20','','Name','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_entered','Entered','20','','Entered','LaterThan','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Entity','select_case','#entity_type','','{"1":"requested_run_batch","2":"analysis_job_request", "3":"sample_prep_request"}');
INSERT INTO list_report_hotlinks VALUES(2,'Name','select_case','#entity_type','','{"4":"dataset","5":"dataset"}');
INSERT INTO list_report_hotlinks VALUES(3,'Campaign','invoke_entity','Campaign','campaign/show','');
INSERT INTO list_report_hotlinks VALUES(4,'User','invoke_entity','#prn','user/show','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'prn','PRN','varchar','input','15','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(2,'name','Name','varchar','input','64','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(3,'requested_run_batch','RequestedRunBatch','varchar','input','4','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(4,'analysis_job_request','AnalysisJobRequest','varchar','input','4','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(5,'sample_prep_request','SamplePrepRequest','varchar','input','4','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(6,'dataset_not_released','DatasetNotReleased','varchar','input','4','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(7,'dataset_released','DatasetReleased','varchar','input','4','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(8,'<local>','mode','varchar','input','12','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(9,'<local>','message','varchar','output','512','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(10,'<local>','callingUser','varchar','input','128','UpdateNotificationUserRegistration');
INSERT INTO sproc_args VALUES(11,'ID','campaignNum','varchar','input','64','UpdateResearchTeamObserver');
INSERT INTO sproc_args VALUES(12,'<local>','mode','varchar','input','12','UpdateResearchTeamObserver');
INSERT INTO sproc_args VALUES(13,'<local>','message','varchar','output','512','UpdateResearchTeamObserver');
INSERT INTO sproc_args VALUES(14,'<local>','callingUser','varchar','input','128','UpdateResearchTeamObserver');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'prn','PRN','non-edit','15','15','','','','trim|max_length[15]');
INSERT INTO form_fields VALUES(2,'name','Name','non-edit','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(3,'requested_run_batch','Requested Run Batch (Start or Finish)','text','4','4','','','','trim|max_length[4]');
INSERT INTO form_fields VALUES(4,'analysis_job_request','Analysis Job Request (Start or Finish)','text','4','4','','','','trim|max_length[4]');
INSERT INTO form_fields VALUES(5,'sample_prep_request','Sample Prep Req (Any state change)','text','4','4','','','','trim|max_length[4]');
INSERT INTO form_fields VALUES(6,'dataset_not_released','Dataset Not Released','text','4','4','','','','trim|max_length[4]');
INSERT INTO form_fields VALUES(7,'dataset_released','Dataset Released','text','4','4','','','','trim|max_length[4]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'requested_run_batch','picker.replace','yesNoPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'analysis_job_request','picker.replace','yesNoPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'sample_prep_request','picker.replace','yesNoPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'dataset_not_released','picker.replace','yesNoPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'dataset_released','picker.replace','yesNoPickList','','',',','');
COMMIT;
