﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_sample_prep_request_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_sample_prep_request_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','add_update_sample_prep_request');
INSERT INTO general_params VALUES('entry_page_data_table','v_sample_prep_request_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('entry_submission_cmds','sample_prep_cmds');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO general_params VALUES('base_table','T_Sample_Prep_Request');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'request_name','Request Name','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(3,'requester_username','Requester','text','32','32','','','','trim|max_length[32]|required');
INSERT INTO form_fields VALUES(4,'campaign','Campaign','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(5,'reason','Reason for Experiment','area','','','3','60','','trim|max_length[512]|required');
INSERT INTO form_fields VALUES(7,'material_container_list','Material Container List','area','','','2','70','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(8,'organism','Organism','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(10,'tissue','Plant/Animal Tissue','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(11,'biohazard_level','Biohazard Level','text','12','12','','','BSL1','trim|max_length[12]');
INSERT INTO form_fields VALUES(12,'number_of_samples','Number of Samples','text','4','4','','','1','trim|max_length[4]');
INSERT INTO form_fields VALUES(13,'block_and_randomize_samples','Block And Randomize Samples','text','3','3','','','','trim|required|max_length[3]');
INSERT INTO form_fields VALUES(14,'sample_name_list','Source Sample Names','area','','','4','60','','trim|max_length[1500]');
INSERT INTO form_fields VALUES(15,'sample_type','Sample Type','text','60','128','','','Cell pellet','trim|max_length[128]');
INSERT INTO form_fields VALUES(16,'prep_method','Prep Method','area','','','3','60','','trim|max_length[512]');
INSERT INTO form_fields VALUES(20,'comment','Comment','area','','','8','80','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(21,'estimated_ms_runs','MS Runs To Be Generated','text','16','16','','','','trim|required|max_length[16]');
INSERT INTO form_fields VALUES(23,'instrument_group','Instrument Group','text','24','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(24,'dataset_type','Dataset Type','text','24','50','','','','trim|max_length[50]');
INSERT INTO form_fields VALUES(25,'separation_group','Separation Group','text','24','128','','','','trim|max_length[256]');
INSERT INTO form_fields VALUES(26,'instrument_analysis_specifications','Instrument Analysis Specifications','area','','','3','60','','trim|max_length[512]');
INSERT INTO form_fields VALUES(28,'block_and_randomize_runs','Block And Randomize Runs','text','3','3','','','','trim|required|max_length[3]');
INSERT INTO form_fields VALUES(29,'sample_naming_convention','Sample Group Naming Prefix','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(30,'work_package','Work Package','text','15','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(32,'eus_usage_type','EMSL Usage Type','text','50','50','','','','trim|required|max_length[50]|not_contain[(unknown)]');
INSERT INTO form_fields VALUES(33,'eus_proposal_id','EMSL Proposal ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO form_fields VALUES(34,'eus_user_id','EMSL User ID','text','10','10','','','','trim|max_length[10]|numeric');
INSERT INTO form_fields VALUES(35,'requested_personnel','Requested Personnel','area','','','2','60','','trim|required|max_length[256]');
INSERT INTO form_fields VALUES(36,'assigned_personnel','Assigned Personnel','area','','','2','60','','trim|max_length[256]');
INSERT INTO form_fields VALUES(38,'priority','Priority','text','12','12','','','Normal','trim|max_length[12]');
INSERT INTO form_fields VALUES(39,'reason_for_high_priority','Reason for High Priority','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(41,'estimated_prep_time_days','Estimated Prep Time (days)','text','32','32','','','','trim|max_length[10]|numeric');
INSERT INTO form_fields VALUES(42,'state','State','text','32','32','','','New','trim|max_length[32]|required');
INSERT INTO form_fields VALUES(43,'state_comment','State Comment','area','','','3','60','','trim|max_length[512]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'id','section','Basic Information');
INSERT INTO form_field_options VALUES(2,'requester_username','default_function','GetUser()');
INSERT INTO form_field_options VALUES(3,'material_container_list','section','Biomaterial Information');
INSERT INTO form_field_options VALUES(4,'estimated_ms_runs','section','Instrument Run Information');
INSERT INTO form_field_options VALUES(5,'number_of_samples','section','Preparation Information');
INSERT INTO form_field_options VALUES(7,'sample_naming_convention','section','Project Tracking Information');
INSERT INTO form_field_options VALUES(9,'requested_personnel','section','Assignment and Scheduling');
INSERT INTO form_field_options VALUES(10,'state','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration,DMS_Sample_Preparation');
INSERT INTO form_field_options VALUES(11,'comment','auto_format','none');
INSERT INTO form_field_options VALUES(12,'estimated_prep_time_days','section','Staff Notes');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'requester_username','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO form_field_choosers VALUES(4,'tissue','list-report.helper','','helper_tissue/report','',',','');
INSERT INTO form_field_choosers VALUES(5,'biohazard_level','picker.replace','samplePrepReqBiohazardPickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'campaign','list-report.helper','','helper_campaign/report/Active/','campaign',',','');
INSERT INTO form_field_choosers VALUES(7,'instrument_group','picker.replace','samplePrepInstrumentGroupPickList','','',',','');
INSERT INTO form_field_choosers VALUES(8,'dataset_type','list-report.helper','','data/lr/ad_hoc_query/helper_inst_group_dstype/report','instrument_group',',','');
INSERT INTO form_field_choosers VALUES(10,'sample_type','picker.replace','samplePrepReqTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(11,'prep_method','picker.append','samplePrepReqMethodPickList','','',',','');
INSERT INTO form_field_choosers VALUES(15,'eus_usage_type','picker.replace','samplePrepEusUsageTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(16,'eus_user_id','list-report.helper','','helper_eus_user/report','eus_proposal_id',',','Select User...');
INSERT INTO form_field_choosers VALUES(17,'requested_personnel','picker.append','samplePrepUserPickList','','','; ','');
INSERT INTO form_field_choosers VALUES(18,'assigned_personnel','picker.append','samplePrepUserPickList','','','; ','');
INSERT INTO form_field_choosers VALUES(20,'state','picker.replace','sampleRequestStatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(21,'eus_proposal_id','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO form_field_choosers VALUES(22,'eus_proposal_id','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO form_field_choosers VALUES(23,'separation_group','picker.replace','samplePrepSeparationGroupPickList','','',',','');
INSERT INTO form_field_choosers VALUES(25,'block_and_randomize_runs','picker.replace','yesNoPickList','','',',','');
INSERT INTO form_field_choosers VALUES(26,'block_and_randomize_samples','picker.replace','yesNoNAPickList','','',',','');
INSERT INTO form_field_choosers VALUES(27,'priority','picker.replace','operationsTaskPriority','','',',','');
INSERT INTO form_field_choosers VALUES(28,'work_package','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO form_field_choosers VALUES(29,'material_container_list','list-report.helper','','helper_material_container_ckbx/report','',',','Select Container...');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_state','State','32','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_requestname','Request Name','32','','request_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_requester','Requester','32','','requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_organism','Organism','32','','organism','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_campaign','Campaign','32','','campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_WorkPackage','WP','32','','work_package','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_ID','ID','20','','id','Equals','text','12','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_Container','Container','32','','containers','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_assigned_personnel','Assigned Personnel','32','','assigned_personnel','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','id','sample_prep_request/show/','');
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
INSERT INTO list_report_hotlinks VALUES(12,'containers','min_col_width','value','35','');
INSERT INTO list_report_hotlinks VALUES(13,'days_in_queue_bin','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(14,'wp_activation_state','no_display','value','','');
INSERT INTO list_report_hotlinks VALUES(15,'+id','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(16,'num_samples','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(17,'ms_runs_tbg','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(18,'+days_in_queue','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(19,'+work_package','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(20,'+wp_state','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(21,'+eus_proposal','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(22,'experiments_last_7days','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(23,'experiments_last_31days','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(24,'experiments_last_180days','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(25,'experiments_total','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(26,'files','invoke_entity','id','file_attachment/report/-/StartsWith__sample_prep_request/@','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Convert Request to Experiment','copy_from','','experiment','Go to experiment entry page and copy information from this sample prep request.','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'experiments','detail-report','id','sample_prep_request_experiments/report','labelCol','experiments',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'updates','detail-report','id','sample_prep_request_updates/report','labelCol','updates',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'campaign','detail-report','campaign','campaign/show','labelCol','campaign',NULL);
INSERT INTO detail_report_hotlinks VALUES(6,'instrument_group','detail-report','instrument_group','instrument_group/show','valueCol','dl_instrumentGroup',NULL);
INSERT INTO detail_report_hotlinks VALUES(7,'dataset_type','detail-report','instrument_group','instrument_allowed_dataset_type/report','valueCol','dl_DatasetType',NULL);
INSERT INTO detail_report_hotlinks VALUES(8,'eus_proposal','detail-report','eus_proposal','eus_proposals/show','valueCol','dl_EUS_Proposal',NULL);
INSERT INTO detail_report_hotlinks VALUES(9,'work_package','detail-report','work_package','charge_code/show','labelCol','dl_Work_Package','');
INSERT INTO detail_report_hotlinks VALUES(12,'material_containers_item_count','detail-report','id','sample_prep_request_items/report/material_container/','labelCol','dl_material_containers_item_count','');
INSERT INTO detail_report_hotlinks VALUES(14,'experiment_group_item_count','detail-report','id','sample_prep_request_items/report/experiment_group/','labelCol','dl_experiment_group_item_count','');
INSERT INTO detail_report_hotlinks VALUES(15,'experiment_item_count','detail-report','id','sample_prep_request_items/report/experiment/','labelCol','dl_experiment_item_count','');
INSERT INTO detail_report_hotlinks VALUES(16,'hplc_runs_item_count','detail-report','id','sample_prep_request_items/report/prep_lc_run/','labelCol','dl_hplc_runs_item_count','');
INSERT INTO detail_report_hotlinks VALUES(17,'biomaterial_item_count','detail-report','id','sample_prep_request_items/report/biomaterial/','labelCol','dl_biomaterial_item_count','');
INSERT INTO detail_report_hotlinks VALUES(18,'total_item_count','detail-report','id','sample_prep_request_items/report/-/','labelCol','dl_total_item_count','');
INSERT INTO detail_report_hotlinks VALUES(19,'requested_run_item_count','detail-report','id','sample_prep_request_items/report/requested_run/','labelCol','dl_requested_run_item_count','');
INSERT INTO detail_report_hotlinks VALUES(20,'dataset_item_count','detail-report','id','sample_prep_request_items/report/dataset/','labelCol','dl_dataset_item_count','');
INSERT INTO detail_report_hotlinks VALUES(21,'work_package_state','color_label','wp_activation_state','','valueCol','dl_Work_Package_State',replace('{"3":"clr_90","4":"clr_120",\n"5":"clr_120","10":"clr_120"}','\n',char(10)));
INSERT INTO detail_report_hotlinks VALUES(22,'material_containers','link_list','material_containers','material_container/show','valueCol','dl_material_containers','');
INSERT INTO detail_report_hotlinks VALUES(23,'requester','detail-report','requester','user/report/-/~','labelCol','dl_researcher','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO detail_report_hotlinks VALUES(24,'comment','markup','comment','','valueCol','dl_Comment','');
INSERT INTO detail_report_hotlinks VALUES(25,'plant_or_animal_tissue','detail-report','plant_or_animal_tissue','tissue/report/~','valueCol','dl_tissue','');
INSERT INTO detail_report_hotlinks VALUES(26,'organism','detail-report','organism','organism/report/~','valueCol','dl_organism','');
INSERT INTO detail_report_hotlinks VALUES(27,'eus_proposal_state','color_label','eus_proposal_state','','valueCol','dl_eus_proposal_state','{"Active":"clr_30", "Permanently Active":"clr_60", "Closed":"clr_90", "Inactive":"clr_90"}');
INSERT INTO detail_report_hotlinks VALUES(28,'state','detail-report','state','sample_prep_request_planning/report/-/-/~','valueCol','dl_prep_request_planning','{"HideLinkIfValueMatch":"Closed"}');
INSERT INTO detail_report_hotlinks VALUES(29,'wp_activation_state','no_display','value','',NULL,NULL,'');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'request_name','requestName','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(2,'requester_username','requesterUsername','varchar','input','32','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(3,'reason','reason','varchar','input','512','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(4,'material_container_list','materialContainerList','varchar','input','2048','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(5,'organism','organism','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(6,'biohazard_level','biohazardLevel','varchar','input','12','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(7,'campaign','campaign','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(8,'number_of_samples','numberofSamples','int','input','','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(9,'sample_name_list','sampleNameList','varchar','input','1500','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(10,'sample_type','sampleType','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(11,'prep_method','prepMethod','varchar','input','512','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(12,'sample_naming_convention','sampleNamingConvention','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(13,'assigned_personnel','assignedPersonnel','varchar','input','256','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(14,'requested_personnel','requestedPersonnel','varchar','input','256','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(15,'estimated_prep_time_days','estimatedPrepTimeDays','int','input','','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(16,'estimated_ms_runs','estimatedMSRuns','varchar','input','16','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(17,'work_package','workPackageNumber','varchar','input','64','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(18,'eus_proposal_id','eusProposalID','varchar','input','10','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(19,'eus_usage_type','eusUsageType','varchar','input','50','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(20,'eus_user_id','eusUserID','int','input','','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(21,'instrument_group','instrumentGroup','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(22,'dataset_type','datasetType','varchar','input','50','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(23,'instrument_analysis_specifications','instrumentAnalysisSpecifications','varchar','input','512','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(24,'comment','comment','varchar','input','2048','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(25,'priority','priority','varchar','input','12','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(26,'state','state','varchar','input','32','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(27,'state_comment','stateComment','varchar','input','512','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(28,'id','id','int','output','','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(29,'separation_group','separationGroup','varchar','input','256','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(30,'block_and_randomize_samples','blockAndRandomizeSamples','char','input','3','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(31,'block_and_randomize_runs','blockAndRandomizeRuns','char','input','3','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(32,'reason_for_high_priority','reasonForHighPriority','varchar','input','1024','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(33,'tissue','tissue','varchar','input','128','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(34,'<local>','mode','varchar','input','12','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(35,'<local>','message','varchar','output','512','add_update_sample_prep_request');
INSERT INTO sproc_args VALUES(36,'<local>','callingUser','varchar','input','128','add_update_sample_prep_request');
COMMIT;
