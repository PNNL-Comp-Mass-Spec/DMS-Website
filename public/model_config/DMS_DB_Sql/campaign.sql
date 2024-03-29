﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_campaign_list_report_2');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_campaign_detail_report_ex');
INSERT INTO general_params VALUES('detail_report_data_id_col','campaign');
INSERT INTO general_params VALUES('entry_sproc','add_update_campaign');
INSERT INTO general_params VALUES('entry_page_data_table','v_campaign_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','campaign');
INSERT INTO general_params VALUES('post_submission_detail_id','campaign');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'campaign','Campaign','text-if-new','50','64','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(2,'project','Project','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(3,'description','Description','area','','','4','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(4,'state','State','text','24','24','','','Active','trim|required|max_length[24]');
INSERT INTO form_fields VALUES(5,'data_release_restriction','Data Release Restriction','text','50','128','','','Not yet approved for release','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(6,'project_mgr','Project Manager','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(7,'pi_username','Principal Investigator','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(8,'technical_lead','Technical Lead','area','','','2','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(9,'sample_preparation_staff','Sample Preparation Staff','area','','','2','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(10,'dataset_acquisition_staff','Dataset Acquisition Staff','area','','','2','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(11,'informatics_staff','Informatics Staff','area','','','2','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(12,'collaborators','Collaborators','area','','','2','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(13,'comment','Comment','area','','','2','70','','trim|max_length[500]');
INSERT INTO form_fields VALUES(14,'external_links','External Links','area','','','2','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(15,'epr_list','EPR List','area','','','1','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(16,'eus_proposal_list','EUS Proposal List','area','','','1','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(17,'fraction_emsl_funded','Fraction EMSL Funded','text','24','24','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(18,'eus_usage_type','EUS Usage Type','text','','','','','USER_ONSITE','trim|max_length[50]');
INSERT INTO form_fields VALUES(19,'organisms','Organisms','area','','','1','70','','trim|max_length[256]');
INSERT INTO form_fields VALUES(20,'experiment_prefixes','Experiment Prefixes','area','','','2','70','','trim|max_length[256]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'project_mgr','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'campaign','section','General');
INSERT INTO form_field_options VALUES(3,'project_mgr','section','Team Membership');
INSERT INTO form_field_options VALUES(4,'comment','section','Details');
INSERT INTO form_field_options VALUES(5,'data_release_restriction','permission','DMS_Infrastructure_Administration');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'project_mgr','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'pi_username','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'technical_lead','picker.append','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'state','picker.replace','activeInactivePickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'eus_proposal_list','list-report.helper','','helper_eus_proposal_ckbx/report','',',','');
INSERT INTO form_field_choosers VALUES(7,'organisms','list-report.helper','','helper_organism_ckbx/report','',',','');
INSERT INTO form_field_choosers VALUES(8,'sample_preparation_staff','picker.append','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'dataset_acquisition_staff','picker.append','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(10,'informatics_staff','picker.append','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(11,'data_release_restriction','picker.replace','dataReleaseRestrictionsPicklist','','',',','');
INSERT INTO form_field_choosers VALUES(12,'eus_usage_type','picker.replace','campaignEusUsageTypePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_campaign','Campaign','25!','','campaign','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_state','State','6!','','state','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','15!','','description','ContainsText','text','512','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_ID','ID','6!','','id','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'campaign','invoke_entity','value','campaign/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'biomaterial','invoke_entity','campaign','biomaterial/report/-/~','');
INSERT INTO list_report_hotlinks VALUES(3,'sample_prep_requests','invoke_entity','campaign','sample_prep_request/report/-/-/-/-/~','');
INSERT INTO list_report_hotlinks VALUES(4,'experiments','invoke_entity','campaign','experiment/report/-/~','');
INSERT INTO list_report_hotlinks VALUES(5,'requested_runs','invoke_entity','campaign','requested_run/report/-/-/-/-/~','');
INSERT INTO list_report_hotlinks VALUES(6,'datasets','invoke_entity','campaign','dataset/report/-/-/-/-/-/~','');
INSERT INTO list_report_hotlinks VALUES(7,'analysis_jobs','invoke_entity','campaign','analysis_job/report/-/-/-/-/~','');
INSERT INTO list_report_hotlinks VALUES(8,'state','color_label','','','{"Active":"enabled_clr","Inactive":"warning_clr"}');
INSERT INTO list_report_hotlinks VALUES(9,'description','min_col_width','value','60','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'campaign','campaignName','varchar','input','64','add_update_campaign');
INSERT INTO sproc_args VALUES(2,'project','projectName','varchar','input','64','add_update_campaign');
INSERT INTO sproc_args VALUES(3,'project_mgr','progmgrUsername','varchar','input','64','add_update_campaign');
INSERT INTO sproc_args VALUES(4,'pi_username','piUsername','varchar','input','64','add_update_campaign');
INSERT INTO sproc_args VALUES(5,'technical_lead','technicalLead','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(6,'sample_preparation_staff','samplePreparationStaff','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(7,'dataset_acquisition_staff','datasetAcquisitionStaff','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(8,'informatics_staff','informaticsStaff','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(9,'collaborators','collaborators','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(10,'comment','comment','varchar','input','500','add_update_campaign');
INSERT INTO sproc_args VALUES(11,'state','state','varchar','input','24','add_update_campaign');
INSERT INTO sproc_args VALUES(12,'description','description','varchar','input','512','add_update_campaign');
INSERT INTO sproc_args VALUES(13,'external_links','externalLinks','varchar','input','512','add_update_campaign');
INSERT INTO sproc_args VALUES(14,'epr_list','eprList','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(15,'eus_proposal_list','eusProposalList','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(16,'organisms','organisms','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(17,'experiment_prefixes','experimentPrefixes','varchar','input','256','add_update_campaign');
INSERT INTO sproc_args VALUES(18,'data_release_restriction','dataReleaseRestriction','varchar','input','128','add_update_campaign');
INSERT INTO sproc_args VALUES(19,'fraction_emsl_funded','fractionEMSLFunded','varchar','input','24','add_update_campaign');
INSERT INTO sproc_args VALUES(20,'eus_usage_type','eusUsageType','varchar','input','50','add_update_campaign');
INSERT INTO sproc_args VALUES(21,'<local>','mode','varchar','input','12','add_update_campaign');
INSERT INTO sproc_args VALUES(22,'<local>','message','varchar','output','512','add_update_campaign');
INSERT INTO sproc_args VALUES(23,'<local>','callingUser','varchar','input','128','add_update_campaign');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'biomaterial','detail-report','campaign','biomaterial/report/-/~','valueCol','dl_biomaterial','');
INSERT INTO detail_report_hotlinks VALUES(2,'experiments','detail-report','campaign','experiment/report/-/~','valueCol','dl_experiments','');
INSERT INTO detail_report_hotlinks VALUES(3,'datasets','detail-report','campaign','dataset/report/-/-/-/-/-/~','valueCol','dl_datasets','');
INSERT INTO detail_report_hotlinks VALUES(4,'sample_prep_requests','detail-report','campaign','sample_prep_request/report/-/-/-/-/~','valueCol','dl_sample_prep_req','');
INSERT INTO detail_report_hotlinks VALUES(5,'run_requests','detail-report','campaign','requested_run/report/-/-/-/-/~','valueCol','dl_requested_run','');
INSERT INTO detail_report_hotlinks VALUES(6,'analysis_jobs','detail-report','campaign','analysis_job/report/-/-/-/-/~','valueCol','dl_analysis_job','');
INSERT INTO detail_report_hotlinks VALUES(7,'external_links','link_table','external_links','','valueCol','dl_external_link','');
INSERT INTO detail_report_hotlinks VALUES(8,'team_members','tabular_list','team_members','','valueCol','dl_team_members','');
INSERT INTO detail_report_hotlinks VALUES(9,'+team_members','detail-report','campaign','staff_roles/report/-/-/~','labelCol','dl_team_by_campaign',NULL);
INSERT INTO detail_report_hotlinks VALUES(11,'data_packages','detail-report','campaign','data_package_campaigns/report/-/~','valueCol','dl_data_packages','');
INSERT INTO detail_report_hotlinks VALUES(12,'eus_proposal','detail-report','eus_proposal','eus_proposals/show','valueCol','dl_eus_Proposal','');
INSERT INTO detail_report_hotlinks VALUES(13,'samples_submitted','detail-report','campaign','sample_submission/report/~','valueCol','dl_samples_submitted','');
INSERT INTO detail_report_hotlinks VALUES(14,'work_packages','detail-report','campaign','campaign_dataset_stats/param','labelCol','dl_campaign_dataset_stats','');
INSERT INTO detail_report_hotlinks VALUES(15,'+work_packages','link_list','work_packages','charge_code/show','valueCol','dl_work_packages','{"HideLinkIfValueMatch":"none"}');
INSERT INTO detail_report_hotlinks VALUES(16,'eus_usage_type','detail-report','eus_usage_type','eus_usage_type/report/-/~@/-/-','labelCol','dl_eus_usage_type','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Add me as observer','cmd_op','add','notification','Add currently logged in user as observer to this campaign','');
INSERT INTO detail_report_commands VALUES(2,'Remove me as observer','cmd_op','remove','notification','Remove currently logged in user as observer from this campaign','');
COMMIT;
