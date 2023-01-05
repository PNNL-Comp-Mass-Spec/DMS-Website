﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_eus_users_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_eus_users_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateEUSUsers');
INSERT INTO general_params VALUES('entry_page_data_table','v_eus_users_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('list_report_data_cols','''x'' AS Sel, *');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','text','32','32','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(2,'name','Name','text','50','50','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(3,'hanford_id','HanfordID','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(4,'site_status_id','Site Status','text','32','32','','','','trim|required|max_length[32]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'site_status_id','picker.replace','EUSUserSiteStatusPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','12','','id','Equals','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_name','Name','60','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_site_status','Site Status','60','','site_status','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_proposals','Proposals','60','','proposals','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'sel','CHECKBOX','id','','');
INSERT INTO list_report_hotlinks VALUES(2,'id','invoke_entity','value','eus_users/show/','');
INSERT INTO list_report_hotlinks VALUES(3,'proposals','invoke_entity','name','eus_proposals/report/-/-/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','EUSPersonID','varchar','input','32','AddUpdateEUSUsers');
INSERT INTO sproc_args VALUES(2,'name','EUSNameFm','varchar','input','50','AddUpdateEUSUsers');
INSERT INTO sproc_args VALUES(3,'site_status_id','EUSSiteStatus','varchar','input','32','AddUpdateEUSUsers');
INSERT INTO sproc_args VALUES(4,'hanford_id','HanfordID','varchar','input','50','AddUpdateEUSUsers');
INSERT INTO sproc_args VALUES(5,'<local>','mode','varchar','input','12','AddUpdateEUSUsers');
INSERT INTO sproc_args VALUES(6,'<local>','message','varchar','output','512','AddUpdateEUSUsers');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'prn','detail-report','prn','user/show/','labelCol','dl_PRN','');
INSERT INTO detail_report_hotlinks VALUES(2,'id','detail-report','id','eus_proposal_users/report/-/','labelCol','dl_eus_user_id','');
COMMIT;
