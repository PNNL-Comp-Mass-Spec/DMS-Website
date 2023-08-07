﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Dataset');
INSERT INTO general_params VALUES('list_report_data_table','v_dataset_list_report_2');
INSERT INTO general_params VALUES('detail_report_data_table','v_dataset_detail_report_ex');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_data_sort_col','date_sort_key');
INSERT INTO general_params VALUES('entry_sproc','add_update_dataset');
INSERT INTO general_params VALUES('entry_page_data_table','v_dataset_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','dataset_id');
INSERT INTO general_params VALUES('alternate_title_create','Create Dataset Trigger File');
INSERT INTO general_params VALUES('post_submission_detail_id','dataset_id');
INSERT INTO general_params VALUES('operations_sproc','do_dataset_operation');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','datasetid/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'rating','color_label','','','{"Unreviewed":"warning_clr","Not Released":"bad_clr","Data Files Missing":"bad_clr","No Data (Blank\/Bad)":"bad_clr"}');
INSERT INTO list_report_hotlinks VALUES(3,'qc_link','masked_link','value','','{"Label":"QC_Link"}');
INSERT INTO list_report_hotlinks VALUES(4,'comment','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(5,'experiment','invoke_entity','value','experiment/show/','');
INSERT INTO list_report_hotlinks VALUES(6,'proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO list_report_hotlinks VALUES(7,'+id','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(8,'acq_length','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(9,'scan_count','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(10,'file_size_mb','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(11,'request','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(12,'work_package','export_align','value','','{"Align":"Center"}');
INSERT INTO list_report_hotlinks VALUES(13,'date_sort_key','no_display','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','','','id','Equals','text','24','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_state','State','32','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_instrument','Instrument','32','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_experiment','Experiment','20!','','experiment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_campaign','Campaign','32','','campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_most_recent_weeks','Most Recent Weeks','3!','','date_sort_key','MostRecentWeeks','text','4','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_created_after','Created After','8','','created','LaterThan','text','20','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_rating','Rating','12','','rating','StartsWithText','text','64','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'experiment','detail-report','experiment','experiment/show','labelCol','experiment','');
INSERT INTO detail_report_hotlinks VALUES(2,'dataset_folder_path','href-folder','dataset_folder_path','','labelCol','dataset_folder_path','');
INSERT INTO detail_report_hotlinks VALUES(3,'archive_folder_path','href-folder','archive_folder_path','','labelCol','archive_folder_path','');
INSERT INTO detail_report_hotlinks VALUES(4,'myemsl_url','masked_link','myemsl_url','','valueCol','dl_myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO detail_report_hotlinks VALUES(5,'myemsl_upload_ids','masked_link_list','myemsl_upload_ids','','valueCol','dl_myemsl_upload_IDs','{"Label":"UrlSegment3"}');
INSERT INTO detail_report_hotlinks VALUES(6,'request','detail-report','request','requested_run/show','labelCol','request','');
INSERT INTO detail_report_hotlinks VALUES(7,'batch','detail-report','batch','requested_run_batch/show','labelCol','dl_batch_id','');
INSERT INTO detail_report_hotlinks VALUES(8,'jobs','detail-report','dataset','analysis_job/report/-/-/-/~@/-/-/-/-/-','labelCol','jobs','');
INSERT INTO detail_report_hotlinks VALUES(9,'+jobs','detail-report','id','dataset_jobs/report/@/-/-/-/-/-/-/-','valueCol','dl_dataset_jobs','');
INSERT INTO detail_report_hotlinks VALUES(10,'peak_matching_results','detail-report','dataset','mts_pm_results/report/~','labelCol','pmresults','');
INSERT INTO detail_report_hotlinks VALUES(11,'scan_count','detail-report','dataset','dataset_scans/show','labelCol','dl_scan_count','');
INSERT INTO detail_report_hotlinks VALUES(12,'scan_types','detail-report','dataset','dataset_scans/report/~','labelCol','dl_scan_types','');
INSERT INTO detail_report_hotlinks VALUES(13,'file_info_updated','detail-report','dataset','dataset_info/report/~','labelCol','dl_file_info_updated','');
INSERT INTO detail_report_hotlinks VALUES(14,'instrument','detail-report','instrument','instrument/show/','labelCol','dl_instrument','');
INSERT INTO detail_report_hotlinks VALUES(15,'+instrument','detail-report','instrument','instrument_operation_history/report/~','valueCol','dl_instrument_1','');
INSERT INTO detail_report_hotlinks VALUES(16,'qc_link','literal_link','qc_link','','valueCol','dl_qc_link','');
INSERT INTO detail_report_hotlinks VALUES(17,'data_folder_link','literal_link','data_folder_link','','valueCol','dl_data_folder','');
INSERT INTO detail_report_hotlinks VALUES(18,'archive_state','detail-report','dataset','archive/show','labelCol','dl_archive','');
INSERT INTO detail_report_hotlinks VALUES(19,'factors','detail-report','id','custom_factors/report/-/-/-/-/-/-/','labelCol','dl_custom_factors','');
INSERT INTO detail_report_hotlinks VALUES(20,'+factors','detail-report','id','requested_run_factors/param/@/dataset_id','valueCol','dl_edit_factors','');
INSERT INTO detail_report_hotlinks VALUES(21,'predefines_triggered','detail-report','id','data/lr/predefined_analysis/queue/report/-','labelCol','dl_predefines_triggered','');
INSERT INTO detail_report_hotlinks VALUES(22,'+predefines_triggered','detail-report','id','predefined_analysis_scheduling_queue/report/-/@','valueCol','dl_predefined_jobs','');
INSERT INTO detail_report_hotlinks VALUES(23,'state','detail-report','dataset','capture_job_steps/report/-/-/-/-/-/-/~','labelCol','dl_capture_job_steps','');
INSERT INTO detail_report_hotlinks VALUES(24,'qc_metric_stats','detail-report','instrument','smaqc/report/-/~','labelCol','dl_smaqc_list_report','');
INSERT INTO detail_report_hotlinks VALUES(25,'+qc_metric_stats','literal_link','qc_metric_stats','','valueCol','dl_smaqc_data','');
INSERT INTO detail_report_hotlinks VALUES(26,'qc_2d','masked_link','qc_2d','','valueCol','dl_qc_2d_link','{"Label":"2D plot of deisotoped data"}');
INSERT INTO detail_report_hotlinks VALUES(27,'organism','detail-report','organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO detail_report_hotlinks VALUES(28,'eus_proposal','detail-report','eus_proposal','eus_proposals/show','labelCol','dl_eus_proposal','');
INSERT INTO detail_report_hotlinks VALUES(29,'operator','detail-report','operator','user/report/-/~','labelCol','dl_operator','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO detail_report_hotlinks VALUES(30,'work_package','detail-report','work_package','charge_code/report/~','labelCol','dl_work_package','');
INSERT INTO detail_report_hotlinks VALUES(31,'lc_cart','detail-report','lc_cart','lc_cart/report/~','labelCol','dl_lc_cart','');
INSERT INTO detail_report_hotlinks VALUES(32,'lc_cart_config','detail-report','lc_cart_config','lc_cart_configuration/report/~','labelCol','dl_lc_cart_config','');
INSERT INTO detail_report_hotlinks VALUES(33,'psm_jobs','detail-report','dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_psm_jobs','');
INSERT INTO detail_report_hotlinks VALUES(34,'dataset','detail-report','dataset','dataset/show','labelCol','dl_dataset_name','');
INSERT INTO detail_report_hotlinks VALUES(35,'sha1_hash','detail-report','id','dataset_file/report/','labelCol','dl_dataset_file','');
INSERT INTO detail_report_hotlinks VALUES(36,'experiment_tissue','detail-report','experiment_tissue','tissue/report/~','valueCol','dl_experiment_tissue','');
INSERT INTO detail_report_hotlinks VALUES(37,'lc_column','detail-report','lc_column','lc_column/report/@/-/-','labelCol','dl_lc_column','');
INSERT INTO detail_report_hotlinks VALUES(38,'separation_type','detail-report','separation_type','helper_dataset_separation_type/report/@/-/-/-/-/-/-/1','labelCol','dl_separation_type','');
INSERT INTO detail_report_hotlinks VALUES(39,'wellplate','detail-report','wellplate','wellplate/show','valueCol','dl_wellplate','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_state','picker.replace','datasetStatePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(2,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(3,'pf_experiment','list-report.Chooser','','Chooser_experiment/report','',',');
INSERT INTO primary_filter_choosers VALUES(4,'pf_campaign','list-report.Chooser','','Chooser_campaign/report','',',');
INSERT INTO primary_filter_choosers VALUES(5,'pf_created_after','picker.prevDate','','','',',');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'dataset','datasetName','varchar','input','128','add_update_dataset');
INSERT INTO sproc_args VALUES(2,'experiment','experimentName','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(3,'operator_username','operatorUsername','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(4,'instrument_name','instrumentName','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(5,'dataset_type','msType','varchar','input','20','add_update_dataset');
INSERT INTO sproc_args VALUES(6,'lc_column','LCColumnName','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(7,'wellplate','wellplateName','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(8,'well','wellNumber','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(9,'separation_type','secSep','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(10,'internal_standard','internalStandards','varchar','input','64','add_update_dataset');
INSERT INTO sproc_args VALUES(11,'comment','comment','varchar','input','512','add_update_dataset');
INSERT INTO sproc_args VALUES(12,'dataset_rating','rating','varchar','input','32','add_update_dataset');
INSERT INTO sproc_args VALUES(13,'lc_cart_name','LCCartName','varchar','input','128','add_update_dataset');
INSERT INTO sproc_args VALUES(14,'eus_proposal_id','eusProposalID','varchar','input','10','add_update_dataset');
INSERT INTO sproc_args VALUES(15,'eus_usage_type','eusUsageType','varchar','input','50','add_update_dataset');
INSERT INTO sproc_args VALUES(16,'eus_users','eusUsersList','varchar','input','1024','add_update_dataset');
INSERT INTO sproc_args VALUES(17,'request_id','requestID','int','input','','add_update_dataset');
INSERT INTO sproc_args VALUES(18,'<local>','mode','varchar','input','12','add_update_dataset');
INSERT INTO sproc_args VALUES(19,'<local>','message','varchar','output','512','add_update_dataset');
INSERT INTO sproc_args VALUES(20,'<local>','callingUser','varchar','input','128','add_update_dataset');
INSERT INTO sproc_args VALUES(21,'capture_subfolder','captureSubfolder','varchar','input','255','add_update_dataset');
INSERT INTO sproc_args VALUES(22,'lc_cart_config','lcCartConfig','varchar','input','128','add_update_dataset');
INSERT INTO sproc_args VALUES(23,'id','datasetNameOrID','varchar','input','128','do_dataset_operation');
INSERT INTO sproc_args VALUES(24,'<local>','mode','varchar','input','12','do_dataset_operation');
INSERT INTO sproc_args VALUES(25,'<local>','message','varchar','output','512','do_dataset_operation');
INSERT INTO sproc_args VALUES(26,'<local>','callingUser','varchar','input','128','do_dataset_operation');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'experiment','Experiment Name','text','40','80','','','','trim|required|max_length[64]|not_contain[Placeholder]');
INSERT INTO form_fields VALUES(2,'instrument_name','Instrument Name','text','25','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(3,'dataset_id','Dataset ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(4,'dataset','Dataset Name','text-if-new','70','128','','','','trim|required|not_contain[.raw]|not_contain[.wiff]|not_contain[.]|max_length[128]|alpha_dash|min_length[8]');
INSERT INTO form_fields VALUES(5,'separation_type','Separation Type','text','25','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(6,'lc_cart_name','LC Cart Name','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(7,'lc_cart_config','LC Cart Config','text','40','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(8,'lc_column','LC Column','text','40','50','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(9,'wellplate','Wellplate Name','text','40','50','','','na','trim|max_length[50]');
INSERT INTO form_fields VALUES(10,'well','Well Number','text','24','50','','','na','trim|max_length[50]');
INSERT INTO form_fields VALUES(11,'dataset_type','Dataset Type','text','25','80','','','','trim|required|max_length[50]');
INSERT INTO form_fields VALUES(12,'operator_username','Operator (Username)','text','20','80','','','','trim|required|max_length[24]');
INSERT INTO form_fields VALUES(13,'comment','Comment','area','','','4','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(14,'dataset_rating','Dataset Rating','text','25','80','','','Unreviewed','trim|default_value[Unknown]|required|max_length[24]');
INSERT INTO form_fields VALUES(15,'request_id','Request','text','12','24','','','','trim|required');
INSERT INTO form_fields VALUES(16,'eus_usage_type','EMSL Usage Type','text','50','50','','','(lookup)','trim|max_length[50]|not_contain[(unknown)]');
INSERT INTO form_fields VALUES(17,'eus_proposal_id','EMSL Proposal ID','text','10','10','','','(lookup)','trim|max_length[10]');
INSERT INTO form_fields VALUES(18,'eus_users','EMSL Users List','area','','','4','60','(lookup)','trim|max_length[1024]');
INSERT INTO form_fields VALUES(19,'internal_standard','Dataset Internal Standard','non-edit','','','','','none','trim|max_length[64]');
INSERT INTO form_fields VALUES(20,'capture_subfolder','Capture Subfolder','text','60','255','','','','trim|max_length[255]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'experiment','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO form_field_choosers VALUES(2,'instrument_name','picker.replace','instrumentNameAdminPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'dataset','list-report.helper','','helper_inst_source/view','instrument_name',',','');
INSERT INTO form_field_choosers VALUES(4,'separation_type','list-report.helper','','helper_dataset_separation_type/report/-/-/-/-/-/-/-/1','',',','');
INSERT INTO form_field_choosers VALUES(5,'lc_column','picker.replace','LCColumnPickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'wellplate','picker.replace','wellplatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(7,'dataset_type','list-report.helper','','helper_instrument_dataset_type/report','instrument_name',',','');
INSERT INTO form_field_choosers VALUES(8,'operator_username','picker.replace','instrumentUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'dataset_rating','picker.replace','datasetRatingPickList','','',',','');
INSERT INTO form_field_choosers VALUES(10,'request_id','list-report.helper','','helper_scheduled_run/report','',',','');
INSERT INTO form_field_choosers VALUES(11,'lc_cart_name','picker.replace','lcCartPickList','','',',','');
INSERT INTO form_field_choosers VALUES(12,'eus_usage_type','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(13,'eus_users','list-report.helper','','helper_eus_user_ckbx/report','eus_proposal_id',',','');
INSERT INTO form_field_choosers VALUES(14,'eus_proposal_id','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO form_field_choosers VALUES(15,'eus_proposal_id','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO form_field_choosers VALUES(16,'lc_cart_config','list-report.helper','','helper_lc_cart_config/report','lc_cart_name',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'operator_username','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'dataset_id','load_key_field','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'add_trigger','override','Create','','add');
INSERT INTO entry_commands VALUES(2,'bad','cmd','Bad Dataset - Add For Tracking Only','Create a new dataset in DMS, but mark it as bad instrument run (Rating "No Data").','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Retry Capture','cmd_op','reset','datasetid','Retry copying the instrument data from the instrument to the storage server.  This can only be used if the dataset folder on the storage server is empty or if it only contains a single corrupt .raw file.','Are you sure that you want to reset this dataset to New?');
INSERT INTO detail_report_commands VALUES(3,'Delete this dataset','cmd_op','delete','datasetid','Delete this dataset (only allowed if the dataset has not been copied to a storage server).','Are you sure that you want to delete this dataset?');
INSERT INTO detail_report_commands VALUES(4,'Create default jobs','cmd_op','createjobs','datasetid','Schedule this dataset to be evaluated against predefined analysis job rules.  New jobs are made if the dataset matches a rule and if a duplicate job does not yet exist.','Are you sure that you want to create default predefined jobs for this dataset?');
COMMIT;
