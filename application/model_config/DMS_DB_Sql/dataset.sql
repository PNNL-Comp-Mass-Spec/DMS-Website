PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Dataset_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Dataset_Detail_Report_Ex');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Dataset');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateDataset');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Dataset_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Dataset_Num');
INSERT INTO "general_params" VALUES('rss_data_table','V_Datasets_RSS');
INSERT INTO "general_params" VALUES('rss_description','Datasets (created within last 30 days).');
INSERT INTO "general_params" VALUES('rss_item_link','dataset/show');
INSERT INTO "general_params" VALUES('alternate_title_create','Create Dataset Trigger File');
INSERT INTO "general_params" VALUES('operations_sproc','DoDatasetOperation');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','#DateSortKey');
INSERT INTO "general_params" VALUES('base_table','T_Dataset');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Experiment_Num','Experiment Name','text','40','80','','','','trim|required|max_length[64]|not_contain[Placeholder]');
INSERT INTO "form_fields" VALUES(2,'DS_Instrument_Name','Instrument Name','text','25','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'Dataset_Num','Dataset Name','text-if-new','70','128','','','','trim|required|not_contain[.raw]|not_contain[.wiff]|not_contain[.]|max_length[128]|alpha_dash|min_length[8]');
INSERT INTO "form_fields" VALUES(4,'DS_sec_sep','Separation Type','text','25','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'DS_LCCartName','LC Cart Name','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(6,'LC_Cart_Config','LC Cart Config','text','40','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(7,'DS_Column','LC Column','text','40','50','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(8,'DS_wellplate_num','Wellplate Number','text','40','50','','','na','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(9,'DS_well_num','Well Number','text','24','50','','','na','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(10,'DS_type_name','Dataset Type','text','25','80','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(11,'DS_Oper_PRN','Operator (PRN)','text','20','80','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(12,'DS_comment','Comment','area','','','4','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(13,'DS_Rating','Interest Rating','text','25','80','','','Unreviewed','trim|default_value[Unknown]|required|max_length[24]');
INSERT INTO "form_fields" VALUES(14,'DS_Request','Request','text','12','24','','','0','trim|required');
INSERT INTO "form_fields" VALUES(15,'DS_EUSUsageType','EMSL Usage Type','text','50','50','','','(lookup)','trim|max_length[50]|not_contain[(unknown)]');
INSERT INTO "form_fields" VALUES(16,'DS_EUSProposalID','EMSL Proposal ID','text','10','10','','','(lookup)','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(17,'DS_EUSUsers','EMSL Users List','area','','','4','60','(lookup)','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(18,'DS_internal_standard','Dataset Internal Standard','non-edit','','','','','none','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(19,'Capture_Subfolder','Capture Subfolder','text','60','255','','','','trim|max_length[255]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'DS_Oper_PRN','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'Dataset_Num','load_key_field','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Experiment_Num','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'DS_Instrument_Name','picker.replace','instrumentNameAdminPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Dataset_Num','list-report.helper','','helper_inst_source/view','DS_Instrument_Name',',','');
INSERT INTO "form_field_choosers" VALUES(4,'DS_sec_sep','list-report.helper','','helper_dataset_separation_type/report','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'DS_Column','picker.replace','LCColumnPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'DS_wellplate_num','picker.replace','wellplatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'DS_type_name','list-report.helper','','helper_instrument_dataset_type/report','DS_Instrument_Name',',','');
INSERT INTO "form_field_choosers" VALUES(8,'DS_Oper_PRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'DS_Rating','picker.replace','datasetRatingPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'DS_Request','list-report.helper','','helper_scheduled_run/report','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'DS_LCCartName','picker.replace','lcCartPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(12,'DS_EUSUsageType','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'DS_EUSUsers','list-report.helper','','helper_eus_user_ckbx/report','DS_EUSProposalID',',','');
INSERT INTO "form_field_choosers" VALUES(14,'DS_EUSProposalID','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(15,'DS_EUSProposalID','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO "form_field_choosers" VALUES(16,'LC_Cart_Config','list-report.helper','','helper_lc_cart_config/report','DS_LCCartName',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_id','ID','','','ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_experiment','Experiment','20!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_campaign','Campaign','32','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_most_recent_weeks','Most Recent Weeks','3!','','#DateSortKey','MostRecentWeeks','text','4','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_created_after','Created After','8','','Created','LaterThan','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_rating','Rating','12','','Rating','MatchesText','text','64','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_state','picker.replace','datasetStatePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(3,'pf_experiment','list-report.Chooser','','Chooser_experiment/report','',',');
INSERT INTO "primary_filter_choosers" VALUES(4,'pf_campaign','list-report.Chooser','','Chooser_campaign/report','',',');
INSERT INTO "primary_filter_choosers" VALUES(5,'pf_created_after','picker.prevDate','','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Dataset','invoke_entity','value','dataset/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Rating','color_label','','','{"Unreviewed":"warning_clr","Not Released":"bad_clr","Data Files Missing":"bad_clr","No Data (Blank\/Bad)":"bad_clr"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'QC_Link','masked_link','value','','{"Label":"QC_Link"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Experiment','invoke_entity','value','experiment/show/','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Retry Capture','cmd_op','reset','dataset','Retry copying the instrument data from the instrument to the storage server.  This can only be used if the dataset folder on the storage server is empty or if it only contains a single corrupt .raw file.','Are you sure that you want to reset this dataset to New?');
INSERT INTO "detail_report_commands" VALUES(2,'Create Data Analysis Request...','copy_from','','analysis_job_request','Open the entry form to create a new data analysis request for this dataset','');
INSERT INTO "detail_report_commands" VALUES(3,'Delete this dataset','cmd_op','delete','dataset','Delete this dataset (only allowed if the dataset has not been copied to a storage server).','Are you sure that you want to delete this dataset?');
INSERT INTO "detail_report_commands" VALUES(4,'Create default jobs','cmd_op','createjobs','dataset','Schedule this dataset to be evaluated against predefined analysis job rules.  New jobs are made if the dataset matches a rule and if a duplicate job does not yet exist.','Are you sure that you want to create default predefined jobs for this dataset?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Experiment','detail-report','Experiment','experiment/show','labelCol','experiment',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Dataset Folder Path','href-folder','Dataset Folder Path','','labelCol','dataset_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Archive Folder Path','href-folder','Archive Folder Path','','labelCol','archive_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'MyEMSL URL','masked_link','MyEMSL URL','','valueCol','dl_myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Request','detail-report','Request','requested_run/show','labelCol','request',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Jobs','detail-report','Dataset','analysis_job/report/-/-/-/~','labelCol','jobs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'Peak Matching Results','detail-report','Dataset','mts_pm_results/report/~','labelCol','pmresults',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(9,'Scan Count','detail-report','Dataset','dataset_scans/show','labelCol','dl_scan_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(11,'Scan Types','detail-report','Dataset','dataset_scans/report/~','labelCol','dl_scan_types',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(12,'File Info Updated','detail-report','Dataset','dataset_info/report/~','labelCol','dl_file_info_updated',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(13,'Instrument','detail-report','Instrument','instrument/report/~','labelCol','dl_instrument',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(14,'+Instrument','detail-report','Instrument','instrument_operation_history/report/~','valueCol','dl_instrument_1',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(16,'QC Link','literal_link','QC Link','','valueCol','dl_qc_link',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(17,'Data Folder Link','literal_link','Data Folder Link','','valueCol','dl_data_folder',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(18,'Archive State','detail-report','Dataset','archive/show','labelCol','dl_archive',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(19,'Factors','detail-report','ID','custom_factors/report/-/-','labelCol','dl_custom_factors',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(20,'+Factors','detail-report','ID','requested_run_factors/param/@/Dataset_ID','valueCol','dl_edit_factors',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(21,'Predefines Triggered','detail-report','ID','data/lr/predefined_analysis/queue/report/-','labelCol','dl_predefines_triggered',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(22,'+Predefines Triggered','detail-report','ID','predefined_analysis_scheduling_queue/report/-/@','valueCol','dl_predefined_jobs','');
INSERT INTO "detail_report_hotlinks" VALUES(23,'State','detail-report','Dataset','capture_job_steps/report/-/-/-/-/-/-/~@','labelCol','dl_capture_job_steps',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(24,'QC Metric Stats','detail-report','Instrument','smaqc/report/-/','labelCol','dl_smaqc_list_report',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(25,'+QC Metric Stats','literal_link','QC Metric Stats','','valueCol','dl_smaqc_data',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(26,'QC 2D','masked_link','QC 2D','','valueCol','dl_qc_2d_link','{"Label":"2D plot of deisotoped data"}');
INSERT INTO "detail_report_hotlinks" VALUES(27,'Organism','detail-report','Organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO "detail_report_hotlinks" VALUES(28,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show','labelCol','dl_eus_proposal','');
INSERT INTO "detail_report_hotlinks" VALUES(29,'Operator','detail-report','Operator','user/report/-/~','labelCol','dl_operator','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(30,'Work Package','detail-report','Work Package','charge_code/report/~','labelCol','dl_work_package','');
INSERT INTO "detail_report_hotlinks" VALUES(31,'LC Cart','detail-report','LC Cart','lc_cart/report/~','labelCol','dl_lc_cart','');
INSERT INTO "detail_report_hotlinks" VALUES(32,'LC Cart Config','detail-report','LC Cart Config','lc_cart_configuration/report/~','labelCol','dl_lc_cart_config','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'add_trigger','override','Create','','add');
INSERT INTO "entry_commands" VALUES(2,'bad','cmd','Bad Dataset - Add For Tracking Only','Create a new dataset in DMS, but mark it as bad instrument run (Rating "No Data").','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO "external_sources" VALUES(1,'requested_run','Experiment_Num','ColName','Experiment');
INSERT INTO "external_sources" VALUES(2,'requested_run','Dataset_Num','ColName','Experiment');
INSERT INTO "external_sources" VALUES(4,'requested_run','DS_sec_sep','ColName','Separation Group');
INSERT INTO "external_sources" VALUES(5,'requested_run','DS_Column','Literal','');
INSERT INTO "external_sources" VALUES(6,'requested_run','DS_internal_standard','Literal','none');
INSERT INTO "external_sources" VALUES(7,'requested_run','DS_type_name','ColName','Type');
INSERT INTO "external_sources" VALUES(8,'requested_run','DS_oper_PRN','Literal','');
INSERT INTO "external_sources" VALUES(9,'requested_run','DS_comment','ColName','Comment');
INSERT INTO "external_sources" VALUES(10,'requested_run','DS_rating','Literal','Unknown');
INSERT INTO "external_sources" VALUES(11,'requested_run','DS_Request','ColName','Request');
INSERT INTO "external_sources" VALUES(12,'prep_lc_run','DS_Instrument_Name','ColName','Instrument');
INSERT INTO "external_sources" VALUES(13,'prep_lc_run','DS_sec_sep','Literal','Prep_HPLC');
INSERT INTO "external_sources" VALUES(14,'prep_lc_run','DS_Column','Literal','Prep_HPLC_Column');
INSERT INTO "external_sources" VALUES(15,'prep_lc_run','DS_type_name','Literal','UV');
INSERT INTO "external_sources" VALUES(16,'prep_lc_run','DS_Rating','Literal','Released');
INSERT INTO "external_sources" VALUES(17,'prep_lc_run','DS_LCCartName','Literal','No_Cart');
INSERT INTO "external_sources" VALUES(18,'prep_lc_run','DS_comment','ColName','ID');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Dataset_Num','datasetNum','varchar','input','128','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(2,'Experiment_Num','experimentNum','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(3,'DS_Oper_PRN','operPRN','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(4,'DS_Instrument_Name','instrumentName','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(5,'DS_type_name','msType','varchar','input','20','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(6,'DS_Column','LCColumnNum','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(7,'DS_wellplate_num','wellplateNum','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(8,'DS_well_num','wellNum','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(9,'DS_sec_sep','secSep','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(10,'DS_internal_standard','internalStandards','varchar','input','64','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(11,'DS_comment','comment','varchar','input','512','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(12,'DS_Rating','rating','varchar','input','32','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(13,'DS_LCCartName','LCCartName','varchar','input','128','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(14,'DS_EUSProposalID','eusProposalID','varchar','input','10','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(15,'DS_EUSUsageType','eusUsageType','varchar','input','50','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(16,'DS_EUSUsers','eusUsersList','varchar','input','1024','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(17,'DS_Request','requestID','int','input','','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(18,'<local>','mode','varchar','input','12','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(19,'<local>','message','varchar','output','512','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(20,'<local>','callingUser','varchar','input','128','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(21,'Capture_Subfolder','captureSubfolder','varchar','input','255','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(22,'LC_Cart_Config','lcCartConfig','varchar','input','128','AddUpdateDataset');
INSERT INTO "sproc_args" VALUES(23,'ID','datasetNum','varchar','input','128','DoDatasetOperation');
INSERT INTO "sproc_args" VALUES(24,'<local>','mode','varchar','input','12','DoDatasetOperation');
INSERT INTO "sproc_args" VALUES(25,'<local>','message','varchar','output','512','DoDatasetOperation');
INSERT INTO "sproc_args" VALUES(26,'<local>','callingUser','varchar','input','128','DoDatasetOperation');
COMMIT;
