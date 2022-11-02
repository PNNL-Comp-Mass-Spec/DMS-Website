﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_EUS_Proposals_List_Report');
INSERT INTO general_params VALUES('detail_report_data_table','V_EUS_Proposals_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','ID');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateEUSProposals');
INSERT INTO general_params VALUES('entry_page_data_table','v_eus_proposals_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_col','Import Date');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO form_fields VALUES(2,'state','State','text','32','32','','','','trim|required|max_length[2]|numeric');
INSERT INTO form_fields VALUES(3,'title','Title','area','','','4','60','','trim|required|max_length[2048]');
INSERT INTO form_fields VALUES(4,'proposal_type','Proposal Type','text','32','64','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(5,'import_date','Import Date','text','32','50','','','','trim|required|max_length[50]');
INSERT INTO form_fields VALUES(6,'superseded_by','Superseded By','text','10','10','','','','trim');
INSERT INTO form_fields VALUES(7,'eus_users','EMSL Users List','area','','','4','60','','trim|max_length[4096]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'state','picker.replace','EUSProposalStatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'eus_users','list-report.helper','','helper_eus_user_id_ckbx/report','',',','');
INSERT INTO form_field_choosers VALUES(3,'superseded_by','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_state','State','10','','State','StartsWithText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_id','ID','10','','ID','MatchesText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_users','Users','10','','Users','ContainsText','text','32','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_title','Title','50!','','Title','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_proposalType','Type','10','','Proposal Type','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(2,'ID','invoke_entity','value','eus_proposals/show/','');
INSERT INTO list_report_hotlinks VALUES(3,'Users','invoke_entity','ID','eus_proposal_users/report','');
INSERT INTO list_report_hotlinks VALUES(4,'Import Date','format_date','value','15','{"Format":"Y-m-d"}');
INSERT INTO list_report_hotlinks VALUES(5,'Start_Date','format_date','value','15','{"Format":"Y-m-d"}');
INSERT INTO list_report_hotlinks VALUES(6,'End_Date','format_date','value','15','{"Format":"Y-m-d"}');
INSERT INTO list_report_hotlinks VALUES(7,'State','color_label','value','','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','EUSPropID','varchar','input','10','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(2,'state','EUSPropStateID','int','input','','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(3,'title','EUSPropTitle','varchar','input','2048','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(4,'import_date','EUSPropImpDate','varchar','input','50','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(5,'eus_users','EUSUsersList','varchar','input','4096','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(6,'proposal_type','EUSProposalType','varchar','input','100','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(7,'superseded_by','autoSupersedeProposalID','varchar','input','10','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(8,'<local>','mode','varchar','input','12','AddUpdateEUSProposals');
INSERT INTO sproc_args VALUES(9,'<local>','message','varchar','output','512','AddUpdateEUSProposals');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'EUS Users','detail-report','ID','eus_proposal_users/report','labelCol','dl_EUS_users',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'Superseded By','detail-report','Superseded By','eus_proposals/show/','valueCol','dl_superseded_by','');
INSERT INTO detail_report_hotlinks VALUES(3,'Title','detail-report','Title','eus_proposals/report/-/-/-/StartsWith__@','labelCol','dl_title','');
COMMIT;
