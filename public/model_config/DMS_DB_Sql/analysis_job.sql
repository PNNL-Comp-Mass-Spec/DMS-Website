﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_analysis_job_list_report_2');
INSERT INTO general_params VALUES('list_report_data_sort_col','job');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_analysis_job_detail_report_2');
INSERT INTO general_params VALUES('detail_report_data_id_col','job');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateAnalysisJob');
INSERT INTO general_params VALUES('entry_page_data_table','v_analysis_job_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','job');
INSERT INTO general_params VALUES('operations_sproc','DoAnalysisJobOperation');
INSERT INTO general_params VALUES('post_submission_detail_id','job');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'job','Job','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'dataset','Dataset','text','80','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(3,'priority','Priority','text','3','3','','','','trim|default_value[3]|required|max_length[2]|numeric');
INSERT INTO form_fields VALUES(4,'tool_name','Analysis Tool','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(5,'param_file','Parameter File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO form_fields VALUES(6,'settings_file','Settings File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO form_fields VALUES(7,'organism','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(8,'organism_db','Organism DB File','text','100','128','','','na','trim|max_length[128]');
INSERT INTO form_fields VALUES(9,'prot_coll_name_list','Protein Collection List','area','','','3','60','na','trim|max_length[4000]');
INSERT INTO form_fields VALUES(10,'prot_coll_options_list','Protein Options List','area','','','2','60','seq_direction=forward','trim|max_length[256]');
INSERT INTO form_fields VALUES(11,'owner','Owner','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(12,'associated_processor_group','Associated Processor Group','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(13,'propagation_mode','Export Mode','text','24','24','','','Export','trim|max_length[24]');
INSERT INTO form_fields VALUES(14,'state_name','State','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(15,'comment','Comment','area','','','4','50','','trim|max_length[512]');
INSERT INTO form_fields VALUES(16,'special_processing','Special Processing','area','','','4','80','','trim|max_length[512]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'owner','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'dataset','list-report.helper','','helper_dataset/report','',',','');
INSERT INTO form_field_choosers VALUES(2,'priority','picker.replace','analysisJobPriPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'tool_name','picker.replace','analysisToolPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'param_file','list-report.helper','','helper_aj_param_file/report','tool_name',',','');
INSERT INTO form_field_choosers VALUES(5,'settings_file','list-report.helper','','helper_aj_settings_file/report/~','tool_name',',','');
INSERT INTO form_field_choosers VALUES(6,'organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO form_field_choosers VALUES(7,'prot_coll_name_list','list-report.helper','','helper_protein_collection/report','organism',',','');
INSERT INTO form_field_choosers VALUES(8,'prot_coll_options_list','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'owner','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(10,'associated_processor_group','list-report.helper','','helper_analysis_processor_group/report','',',','');
INSERT INTO form_field_choosers VALUES(11,'propagation_mode','picker.replace','jobPropagationModePickList','','',',','');
INSERT INTO form_field_choosers VALUES(12,'state_name','picker.replace','analysisJobStatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_job','Job','6!','','job','Equals','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_state','State','','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_tool','Tool','','','tool','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_dataset','Dataset','30!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_campaign','Campaign','','','campaign','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_param_file','Param File','25!','','param_file','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_protein_collection_list','Protein Collection List','25!','','protein_collection_list','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_comment','Comment','','','comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_most_recent_weeks','Most recent weeks','3!','','last_affected','MostRecentWeeks','text','32','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_settings_file','Settings File','15!','','settings_file','ContainsText','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_state','picker.replace','analysisJobStatePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(2,'pf_tool','picker.replace','analysisToolPickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','analysis_job/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(3,'job_request','invoke_entity','value','analysis_job_request/show','');
INSERT INTO list_report_hotlinks VALUES(4,'comment','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(5,'results_url','masked_link','value','','{"Label":"Browse"}');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(2,'Make new request from this job......','copy_from','','analysis_job_request','Go to job request entry page and copy values from this page.','');
INSERT INTO detail_report_commands VALUES(3,'Delete this job','cmd_op','delete','analysis_job','Delete an analysis job if it is still in the "new" state','Are you sure that you want to delete this job?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'dataset','detail-report','dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'dataset_folder_path','href-folder','dataset_folder_path','','labelCol','dataset_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'archive_results_folder_path','href-folder','archive_results_folder_path','','labelCol','archive_results_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'archive_folder_path','href-folder','archive_folder_path','','labelCol','archive_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'results_folder_path','href-folder','results_folder_path','','labelCol','results_folder_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(6,'request','detail-report','request','analysis_job_request/show','labelCol','request',NULL);
INSERT INTO detail_report_hotlinks VALUES(7,'state','detail-report','job','pipeline_job_steps/report','labelCol','state','');
INSERT INTO detail_report_hotlinks VALUES(8,'tool_name','detail-report','tool_name','pipeline_script/report/~','labelCol','tool_name',NULL);
INSERT INTO detail_report_hotlinks VALUES(9,'settings_file','detail-report','settings_file','settings_files/report/-/~','labelCol','settings_file',NULL);
INSERT INTO detail_report_hotlinks VALUES(10,'mts_pt_db_count','detail-report','job','mts_pt_db_jobs/report','labelCol','PT_DBs','');
INSERT INTO detail_report_hotlinks VALUES(11,'mts_mt_db_count','detail-report','job','mts_mt_db_jobs/report','labelCol','MT_DBs','');
INSERT INTO detail_report_hotlinks VALUES(12,'peak_matching_results','detail-report','job','mts_pm_results/report/-/','labelCol','pmresults','');
INSERT INTO detail_report_hotlinks VALUES(15,'data_folder_link','literal_link','data_folder_link','','valueCol','dl_data_folder',NULL);
INSERT INTO detail_report_hotlinks VALUES(16,'job','detail-report','job','pipeline_jobs_history/show','labelCol','pipeline_job_detail_history','');
INSERT INTO detail_report_hotlinks VALUES(17,'+job','detail-report','job','pipeline_jobs/show','valueCol','pipeline_job_detail','');
INSERT INTO detail_report_hotlinks VALUES(18,'owner','detail-report','owner','user/show','labelCol','owner',NULL);
INSERT INTO detail_report_hotlinks VALUES(19,'experiment','detail-report','experiment','experiment/show','labelCol','dl_experiment',NULL);
INSERT INTO detail_report_hotlinks VALUES(20,'psm_stats','detail-report','dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_analysis_job_psm',NULL);
INSERT INTO detail_report_hotlinks VALUES(21,'protein_collection_list','link_table','protein_collection_list','protein_collection/report/~','valueCol','dl_protein_collection','');
INSERT INTO detail_report_hotlinks VALUES(22,'param_file','detail-report','param_file','param_file/report/-/~@','labelCol','dl_param_file','');
INSERT INTO detail_report_hotlinks VALUES(23,'organism','detail-report','organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO detail_report_hotlinks VALUES(24,'organism_db','detail-report','organism_db','helper_organism_db/report/~','labelCol','dl_organism_db','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'preview','cmd','Preview','Determine if current values are valid, but do not change database.','');
INSERT INTO entry_commands VALUES(2,'reset','cmd','Reset Job','','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO external_sources VALUES(1,'predefined_analysis_preview_mds','job','Literal','0');
INSERT INTO external_sources VALUES(2,'predefined_analysis_preview_mds','dataset','ColName','dataset');
INSERT INTO external_sources VALUES(3,'predefined_analysis_preview_mds','priority','ColName','pri');
INSERT INTO external_sources VALUES(4,'predefined_analysis_preview_mds','tool_name','ColName','tool');
INSERT INTO external_sources VALUES(5,'predefined_analysis_preview_mds','param_file','ColName','param_file');
INSERT INTO external_sources VALUES(6,'predefined_analysis_preview_mds','settings_file','ColName','settings_file');
INSERT INTO external_sources VALUES(7,'predefined_analysis_preview_mds','organism','ColName','organism');
INSERT INTO external_sources VALUES(8,'predefined_analysis_preview_mds','organism_db','ColName','organism_db_file');
INSERT INTO external_sources VALUES(9,'predefined_analysis_preview_mds','prot_coll_name_list','ColName','protein_collection_list');
INSERT INTO external_sources VALUES(10,'predefined_analysis_preview_mds','prot_coll_options_list','ColName','protein_options_list');
INSERT INTO external_sources VALUES(11,'predefined_analysis_preview_mds','owner','ColName','owner');
INSERT INTO external_sources VALUES(12,'predefined_analysis_preview_mds','associated_processor_group','ColName','processor_group');
INSERT INTO external_sources VALUES(13,'predefined_analysis_preview_mds','comment','ColName','comment');
INSERT INTO external_sources VALUES(14,'predefined_analysis_jobs_preview','job','Literal','0');
INSERT INTO external_sources VALUES(15,'predefined_analysis_jobs_preview','dataset','ColName','dataset');
INSERT INTO external_sources VALUES(16,'predefined_analysis_jobs_preview','priority','ColName','pri');
INSERT INTO external_sources VALUES(17,'predefined_analysis_jobs_preview','tool_name','ColName','tool');
INSERT INTO external_sources VALUES(18,'predefined_analysis_jobs_preview','param_file','ColName','param_file');
INSERT INTO external_sources VALUES(19,'predefined_analysis_jobs_preview','settings_file','ColName','settings_file');
INSERT INTO external_sources VALUES(20,'predefined_analysis_jobs_preview','organism','ColName','organism');
INSERT INTO external_sources VALUES(21,'predefined_analysis_jobs_preview','organism_db','ColName','organism_db_file');
INSERT INTO external_sources VALUES(22,'predefined_analysis_jobs_preview','prot_coll_name_list','ColName','protein_collections');
INSERT INTO external_sources VALUES(23,'predefined_analysis_jobs_preview','prot_coll_options_list','ColName','protein_options');
INSERT INTO external_sources VALUES(24,'predefined_analysis_jobs_preview','owner','ColName','owner');
INSERT INTO external_sources VALUES(25,'predefined_analysis_jobs_preview','associated_processor_group','ColName','processor_group');
INSERT INTO external_sources VALUES(26,'predefined_analysis_jobs_preview','comment','ColName','comment');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'dataset','datasetNum','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(2,'priority','priority','int','input','','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(3,'tool_name','toolName','varchar','input','64','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(4,'param_file','paramFileName','varchar','input','255','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(5,'settings_file','settingsFileName','varchar','input','255','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(6,'organism','organismName','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(7,'prot_coll_name_list','protCollNameList','varchar','input','4000','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(8,'prot_coll_options_list','protCollOptionsList','varchar','input','256','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(9,'organism_db','organismDBName','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(10,'owner','ownerPRN','varchar','input','32','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(11,'comment','comment','varchar','input','512','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(12,'special_processing','specialProcessing','varchar','input','512','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(13,'associated_processor_group','associatedProcessorGroup','varchar','input','64','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(14,'propagation_mode','propagationMode','varchar','input','24','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(15,'state_name','stateName','varchar','input','32','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(16,'job','jobNum','varchar','output','32','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(17,'<local>','mode','varchar','input','12','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(18,'<local>','message','varchar','output','512','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(19,'<local>','callingUser','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO sproc_args VALUES(20,'id','jobNum','varchar','input','32','DoAnalysisJobOperation');
INSERT INTO sproc_args VALUES(21,'<local>','mode','varchar','input','12','DoAnalysisJobOperation');
INSERT INTO sproc_args VALUES(22,'<local>','message','varchar','output','512','DoAnalysisJobOperation');
INSERT INTO sproc_args VALUES(23,'<local>','callingUser','varchar','input','128','DoAnalysisJobOperation');
COMMIT;
