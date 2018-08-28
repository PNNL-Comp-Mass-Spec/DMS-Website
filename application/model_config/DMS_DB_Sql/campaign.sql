﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Campaign_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Campaign_Detail_Report_Ex');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Campaign');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateCampaign');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Campaign_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','campaignNum');
INSERT INTO "general_params" VALUES('post_submission_detail_id','campaignNum');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'campaignNum','Campaign','text-if-new','50','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(2,'projectNum','Project','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'Description','Description','area','','','4','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(4,'State','State','text','24','24','','','Active','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(5,'DataReleaseRestrictions',' Data Release Restrictions','text','50','128','','','Not yet approved for release','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(6,'progmgrPRN','Project Manager','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(7,'piPRN','Principal Investigator','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(8,'TechnicalLead','Technical Lead','area','','','2','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(9,'SamplePreparationStaff','Sample Preparation Staff','area','','','2','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(10,'DatasetAcquisitionStaff','Dataset Acquisition Staff','area','','','2','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(11,'InformaticsStaff','Informatics Staff','area','','','2','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(12,'Collaborators','Collaborators','area','','','2','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(13,'comment','Comment','area','','','2','70','','trim|max_length[500]');
INSERT INTO "form_fields" VALUES(14,'ExternalLinks','External Links','area','','','2','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(15,'EPRList','EPRList','area','','','1','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(16,'EUSProposalList','EUSProposal List','area','','','1','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(17,'FractionEMSLFunded','Fraction EMSL Funded','text','24','24','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(18,'Organisms','Organisms','area','','','1','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(19,'ExperimentPrefixes','Experiment Prefixes','area','','','2','70','','trim|max_length[256]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'progmgrPRN','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'campaignNum','section','General');
INSERT INTO "form_field_options" VALUES(3,'progmgrPRN','section','Team Membership');
INSERT INTO "form_field_options" VALUES(4,'comment','section','Details');
INSERT INTO "form_field_options" VALUES(5,'DataReleaseRestrictions','permission','DMS_Infrastructure_Administration');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'progmgrPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'piPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'TechnicalLead','picker.append','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'State','picker.replace','activeInactivePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'EUSProposalList','list-report.helper','','helper_eus_proposal_ckbx/report','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'Organisms','list-report.helper','','helper_organism_ckbx/report','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'SamplePreparationStaff','picker.append','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'DatasetAcquisitionStaff','picker.append','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'InformaticsStaff','picker.append','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'DataReleaseRestrictions','picker.replace','dataReleaseRestrictionsPicklist','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_campaign','Campaign','25!','','Campaign','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_state','State','6!','','State','ContainsText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','15!','','Description','ContainsText','text','512','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_ID','ID','6!','','ID','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Campaign','invoke_entity','value','campaign/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Biomaterial','invoke_entity','Campaign','cell_culture/report/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Sample Prep Requests','invoke_entity','Campaign','sample_prep_request/report/-/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Experiments','invoke_entity','Campaign','experiment/report/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Requested Runs','invoke_entity','Campaign','requested_run/report/-/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Datasets','invoke_entity','Campaign','dataset/report/-/-/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Analysis Jobs','invoke_entity','Campaign','analysis_job/report/-/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(8,'State','color_label','','','{"Active":"enabled_clr","Inactive":"warning_clr"}');
INSERT INTO "list_report_hotlinks" VALUES(9,'Description','min_col_width','value','60','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'campaignNum','campaignNum','varchar','input','64','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(2,'projectNum','projectNum','varchar','input','64','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(3,'progmgrPRN','progmgrPRN','varchar','input','64','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(4,'piPRN','piPRN','varchar','input','64','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(5,'TechnicalLead','TechnicalLead','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(6,'SamplePreparationStaff','SamplePreparationStaff','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(7,'DatasetAcquisitionStaff','DatasetAcquisitionStaff','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(8,'InformaticsStaff','InformaticsStaff','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(9,'Collaborators','Collaborators','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(10,'comment','comment','varchar','input','500','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(11,'State','State','varchar','input','24','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(12,'Description','Description','varchar','input','512','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(13,'ExternalLinks','ExternalLinks','varchar','input','512','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(14,'EPRList','EPRList','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(15,'EUSProposalList','EUSProposalList','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(16,'Organisms','Organisms','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(17,'ExperimentPrefixes','ExperimentPrefixes','varchar','input','256','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(18,'DataReleaseRestrictions','DataReleaseRestrictions','varchar','input','128','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(19,'FractionEMSLFunded','FractionEMSLFunded','varchar','input','24','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(20,'<local>','mode','varchar','input','12','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(21,'<local>','message','varchar','output','512','AddUpdateCampaign');
INSERT INTO "sproc_args" VALUES(22,'<local>','callingUser','varchar','input','128','AddUpdateCampaign');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Cell Cultures','detail-report','Campaign','cell_culture/report/-/~','valueCol','dl_cell_cultures','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Experiments','detail-report','Campaign','experiment/report/-/~','valueCol','dl_experiments','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'Datasets','detail-report','Campaign','dataset/report/-/-/-/-/-/~','valueCol','dl_datasets','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Sample Prep Requests','detail-report','Campaign','sample_prep_request/report/-/-/-/-/~','valueCol','dl_sample_prep_req','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Run Requests','detail-report','Campaign','requested_run/report/-/-/-/-/~','valueCol','dl_requested_run','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Analysis Jobs','detail-report','Campaign','analysis_job/report/-/-/-/-/~','valueCol','dl_analysis_job','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'External Links','link_table','External Links','','valueCol','dl_external_link','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Team Members','tabular_list','Team Members','','valueCol','dl_team_members','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'+Team Members','detail-report','Campaign','staff_roles/report/-/-/~','labelCol','dl_team_by_campaign',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(10,'MyEMSL URL','masked_link','MyEMSL URL','','valueCol','dl_myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Data Packages','detail-report','Campaign','data_package_campaigns/report/-/~','valueCol','dl_data_packages','');
INSERT INTO "detail_report_hotlinks" VALUES(12,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show/','labelCol','dl_eus_Proposal','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Add me as observer','cmd_op','add','notification','Add currently logged in user as observer to this campaign','');
INSERT INTO "detail_report_commands" VALUES(2,'Remove me as observer','cmd_op','remove','notification','Remove currently logged in user as observer from this campaign','');
COMMIT;
