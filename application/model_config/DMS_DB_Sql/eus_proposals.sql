﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_EUS_Proposals_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_EUS_Proposals_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateEUSProposals');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_EUS_Proposals_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_cols','''x'' as Sel, *');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Import Date');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(2,'State','State','text','32','32','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(3,'Title','Title','area','','','4','60','','trim|required|max_length[2048]');
INSERT INTO "form_fields" VALUES(4,'Proposal_Type','Proposal Type','text','32','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'ImportDate','Import Date','text','32','50','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(6,'EUSUsers','EMSL Users List','area','','','4','60','','trim|max_length[4096]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'State','picker.replace','EUSProposalStatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'EUSUsers','list-report.helper','','helper_eus_user_id_ckbx/report','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_state','State','10','','State','MatchesText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_id','ID','10','','ID','MatchesText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_users','Users','10','','Users','ContainsText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_title','Title','10','','Title','ContainsText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_proposalType','Type','10','','Proposal Type','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','ID','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','eus_proposals/show/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Users','invoke_entity','ID','eus_proposal_users/report','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','EUSPropID','varchar','input','10','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(2,'State','EUSPropState','varchar','input','32','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(3,'Title','EUSPropTitle','varchar','input','2048','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(4,'ImportDate','EUSPropImpDate','varchar','input','50','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(5,'EUSUsers','EUSUsersList','varchar','input','4096','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(6,'Proposal_Type','EUSProposalType','varchar','input','100','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(7,'<local>','mode','varchar','input','12','AddUpdateEUSProposals');
INSERT INTO "sproc_args" VALUES(8,'<local>','message','varchar','output','512','AddUpdateEUSProposals');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'EUS Users','detail-report','ID','eus_proposal_users/report','labelCol','dl_EUS_users',NULL);
COMMIT;
