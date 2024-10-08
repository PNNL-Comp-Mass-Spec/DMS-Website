﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_EMSL_Instrument_Usage_Report');
INSERT INTO general_params VALUES('list_report_data_table','v_instrument_usage_report_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_instrument_usage_report_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','seq');
INSERT INTO general_params VALUES('entry_page_data_table','v_instrument_usage_report_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','seq');
INSERT INTO general_params VALUES('entry_sproc','add_update_instrument_usage_report');
INSERT INTO general_params VALUES('list_report_cmds','instrument_usage_report_cmds');
INSERT INTO general_params VALUES('list_report_cmds_url','instrument_usage_report/operation');
INSERT INTO general_params VALUES('operations_sproc','update_instrument_usage_report');
INSERT INTO general_params VALUES('post_submission_detail_id','seq');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_year','Year','20','','year','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_month','Month','20','','month','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_instrument','Instrument','20','','instrument','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_type','Type','6!','','type','StartsWithText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_proposal','Proposal','8!','','proposal','ContainsText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_usage','Usage','8!','','usage','ContainsText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_comment','Comment','20','','comment','ContainsText','text','128','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'seq','Seq','int','input','','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(2,'emsl_inst_id','EMSLInstID','int','input','','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(3,'instrument','Instrument','varchar','input','64','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(4,'type','Type','varchar','input','128','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(5,'start','Start','varchar','input','32','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(6,'minutes','Minutes','int','input','','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(7,'year','Year','int','input','','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(8,'month','Month','int','input','','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(9,'dataset_id','ID','int','input','','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(10,'proposal','Proposal','varchar','input','32','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(11,'usage','Usage','varchar','input','32','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(12,'users','Users','varchar','input','1024','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(13,'operator','Operator','varchar','input','64','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(14,'comment','Comment','varchar','input','4096','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(15,'<local>','mode','varchar','input','12','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(16,'<local>','message','varchar','output','512','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(17,'<local>','callingUser','varchar','input','128','add_update_instrument_usage_report');
INSERT INTO sproc_args VALUES(18,'factorList','factorList','text','input','2147483647','update_instrument_usage_report');
INSERT INTO sproc_args VALUES(19,'operation','operation','varchar','input','32','update_instrument_usage_report');
INSERT INTO sproc_args VALUES(20,'year','year','varchar','input','12','update_instrument_usage_report');
INSERT INTO sproc_args VALUES(21,'month','month','varchar','input','12','update_instrument_usage_report');
INSERT INTO sproc_args VALUES(22,'instrument','instrument','varchar','input','128','update_instrument_usage_report');
INSERT INTO sproc_args VALUES(23,'<local>','message','varchar','output','512','update_instrument_usage_report');
INSERT INTO sproc_args VALUES(24,'<local>','callingUser','varchar','input','128','update_instrument_usage_report');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'seq','Seq','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'emsl_inst_id','EMSL Inst ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(3,'instrument','Instrument','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(4,'type','Type','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(5,'start','Start','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(6,'minutes','Minutes','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(7,'year','Year','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(8,'month','Month','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(9,'dataset_id','ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(10,'proposal','Proposal','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(11,'usage','Usage','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(12,'users','Users (EUS User IDs)','area','','','2','50','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(13,'operator','Operator (EUS User ID)','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(14,'comment','Comment','area','','','4','70','','trim|max_length[4096]');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'seq','invoke_entity','value','instrument_usage_report/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'dataset_id','invoke_entity','value','datasetid/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'dataset_id','detail-report','dataset_id','datasetid/show','valueCol','dl_dataset_id','');
INSERT INTO detail_report_hotlinks VALUES(2,'instrument','detail-report','instrument','instrument/show/','valueCol','dl_instrument','');
INSERT INTO detail_report_hotlinks VALUES(3,'operator','detail-report','operator','eus_users/show/','valueCol','dl_operator','');
INSERT INTO detail_report_hotlinks VALUES(4,'proposal','detail-report','proposal','eus_proposals/show/','valueCol','dl_proposal','');
INSERT INTO detail_report_hotlinks VALUES(5,'users','link_list','users','eus_users/show/','valueCol','dl_userse','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'proposal','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO form_field_choosers VALUES(2,'usage','picker.replace','emslInstrumentUsagePickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'users','list-report.helper','','helper_eus_user_id_ckbx/report','',',','Select Users');
INSERT INTO form_field_choosers VALUES(4,'operator','list-report.helper','','helper_eus_user_id/report','',',','Select Operator');
COMMIT;
