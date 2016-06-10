PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Job');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Analysis_Job_Detail_Report_2');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','JobNum');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateAnalysisJob');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Analysis_Job_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Job');
INSERT INTO "general_params" VALUES('operations_sproc','DoAnalysisJobOperation');
INSERT INTO "general_params" VALUES('list_report_data_cols','[Job],[Pri],[State],[Tool],[Dataset],[Campaign],[Experiment],[Instrument],[Parm File],[Settings_File],[Organism],[Organism DB],[Protein Collection List],[Protein Options],[Comment],[Created],[Started],[Finished],[Runtime],[Job Request],[Results Folder],[Results Folder Path],[Last_Affected],[Rating]');
INSERT INTO "general_params" VALUES('detail_report_data_cols','JobNum,Dataset,Experiment,[Dataset Folder],[Dataset Folder Path],Instrument,[Tool Name],[Parm File],[Parm File Storage Path],[Settings File],Organism,[Organism DB],[Organism DB Storage Path],[Protein Collection List],[Protein Options List],State,[Runtime Minutes],Owner,Comment,[Special Processing],[Results Folder Path],[Data Folder Link],[PSM Stats],Created,Started,Finished,Request,Priority,[Assigned Processor],[AM Code],[DEM Code],[Export Mode],[Dataset Unreviewed] ');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Job','Job','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'AJ_Dataset','Dataset','text','80','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(3,'AJ_priority','Priority','text','3','3','','','','trim|default_value[3]|required|max_length[2]|numeric');
INSERT INTO "form_fields" VALUES(4,'AJ_ToolName','Analysis Tool','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'AJ_ParmFile','Parameter File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(6,'AJ_SettingsFile','Settings File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(7,'AJ_Organism','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(8,'AJ_OrganismDB','Organism DB File','text','100','128','','','na','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(9,'protCollNameList','Protein Collection List','area','','','3','60','na','trim|max_length[4000]');
INSERT INTO "form_fields" VALUES(10,'protCollOptionsList','Protein Options List','area','','','2','60','seq_direction=forward','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(11,'AJ_owner','Owner (PRN)','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(12,'associatedProcessorGroup','Associated Processor Group','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'propagationMode','Export Mode','text','24','24','','','Export','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(14,'stateName','State','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(15,'AJ_comment','Comment','area','','','4','50','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(16,'AJ_specialProcessing','Special Processing','area','','','4','80','','trim|max_length[512]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'AJ_owner','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'AJ_Dataset','list-report.helper','','helper_dataset/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'AJ_priority','picker.replace','analysisJobPriPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'AJ_ToolName','picker.replace','analysisToolPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'AJ_ParmFile','list-report.helper','','helper_aj_param_file/report','AJ_ToolName',',','');
INSERT INTO "form_field_choosers" VALUES(5,'AJ_SettingsFile','list-report.helper','','helper_aj_settings_file/report/~','AJ_ToolName',',','');
INSERT INTO "form_field_choosers" VALUES(6,'AJ_Organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'protCollNameList','list-report.helper','','helper_protein_collection/report','AJ_Organism',',','');
INSERT INTO "form_field_choosers" VALUES(8,'protCollOptionsList','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'AJ_owner','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'associatedProcessorGroup','list-report.helper','','helper_analysis_processor_group/report','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'propagationMode','picker.replace','jobPropagationModePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(12,'stateName','picker.replace','analysisJobStatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_job','Job','12','','Job','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_state','State','20','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_tool','Tool','32','','Tool','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_dataset','Dataset','60','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_parm_file','Parm File','60','','Parm File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_protein_collection_list','Protein Collection List','60','','Protein Collection List','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_comment','Comment','60','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_most_recent_weeks','Most recent weeks','22','','Last_Affected','MostRecentWeeks','text','32','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_state','picker.replace','analysisJobStatePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_tool','picker.replace','analysisToolPickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','analysis_job/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Request','invoke_entity','value','analysis_job_request/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','60','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Make group entry from this job...','copy_from','','analysis_group','Go to job entry page and copy values from this page.','');
INSERT INTO "detail_report_commands" VALUES(2,'Make new request from this job......','copy_from','','analysis_job_request','Go to job request entry page and copy values from this page.','');
INSERT INTO "detail_report_commands" VALUES(3,'Delete this job','cmd_op','delete','analysis_job','Delete an analysis job if it is still in the "new" state','Are you sure that you want to delete this job?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Dataset','detail-report','Dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Dataset Folder Path','href-folder','Dataset Folder Path','','labelCol','dataset_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Archive Results Folder Path','href-folder','Archive Results Folder Path','','labelCol','archive_results_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Archive Folder Path','href-folder','Archive Folder Path','','labelCol','archive_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'Results Folder Path','href-folder','Results Folder Path','','labelCol','results_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Request','detail-report','Request','analysis_job_request/show','labelCol','request',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'State','detail-report','JobNum','pipeline_job_steps/report','labelCol','state',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(8,'Tool Name','detail-report','Tool Name','pipeline_script/report/~','labelCol','tool_name',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(9,'Settings File','detail-report','Settings File','settings_files/report/-/~','labelCol','settings_file',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(10,'MTS PT DB Count','detail-report','JobNum','mts_pt_db_jobs/report','labelCol','PT_DBs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(11,'MTS MT DB Count','detail-report','JobNum','mts_mt_db_jobs/report','labelCol','MT_DBs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(12,'Peak Matching Results','detail-report','JobNum','mts_pm_results/report/-/','labelCol','pmresults',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(14,'MyEMSL URL','masked_link','MyEMSL URL','','valueCol','dl_myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO "detail_report_hotlinks" VALUES(15,'Data Folder Link','literal_link','Data Folder Link','','valueCol','dl_data_folder',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(16,'JobNum','detail-report','JobNum','pipeline_jobs_history/show','labelCol','pipeline_job_detail_history',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(17,'+JobNum','detail-report','JobNum','pipeline_jobs/show','valueCol','pipeline_job_detail',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(18,'Owner','detail-report','Owner','user/show','labelCol','owner',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(19,'Experiment','detail-report','Experiment','experiment/show','labelCol','dl_experiment',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(20,'PSM Stats','detail-report','Dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_analysis_job_psm',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(21,'Protein Collection List','link_table','Protein Collection List','protein_collection/report/~','valueCol','dl_protein_collection','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'previewed in','cmd','Preview','Determine if current values are valid, but do not change database.','');
INSERT INTO "entry_commands" VALUES(2,'reset','cmd','Reset Job','','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO "external_sources" VALUES(1,'predefined_analysis_preview_mds','Job','Literal','0');
INSERT INTO "external_sources" VALUES(2,'predefined_analysis_preview_mds','AJ_Dataset','ColName','Dataset');
INSERT INTO "external_sources" VALUES(3,'predefined_analysis_preview_mds','AJ_Priority','ColName','Pri');
INSERT INTO "external_sources" VALUES(4,'predefined_analysis_preview_mds','AJ_ToolName','ColName','Tool');
INSERT INTO "external_sources" VALUES(5,'predefined_analysis_preview_mds','AJ_ParmFile','ColName','Param_File');
INSERT INTO "external_sources" VALUES(6,'predefined_analysis_preview_mds','AJ_SettingsFile','ColName','Settings_File');
INSERT INTO "external_sources" VALUES(7,'predefined_analysis_preview_mds','AJ_Organism','ColName','Organism');
INSERT INTO "external_sources" VALUES(8,'predefined_analysis_preview_mds','AJ_OrganismDB','ColName','OrganismDB_File');
INSERT INTO "external_sources" VALUES(9,'predefined_analysis_preview_mds','protCollNameList','ColName','proteinCollectionList');
INSERT INTO "external_sources" VALUES(10,'predefined_analysis_preview_mds','protCollOptionsList','ColName','proteinOptionsList');
INSERT INTO "external_sources" VALUES(11,'predefined_analysis_preview_mds','AJ_Owner','ColName','Owner');
INSERT INTO "external_sources" VALUES(12,'predefined_analysis_preview_mds','associatedProcessorGroup','ColName','Processor_Group');
INSERT INTO "external_sources" VALUES(13,'predefined_analysis_preview_mds','AJ_Comment','ColName','Comment');
INSERT INTO "external_sources" VALUES(14,'predefined_analysis_preview_mds','AJ_Request','ColName','');
INSERT INTO "external_sources" VALUES(15,'predefined_analysis_preview','Job','Literal','0');
INSERT INTO "external_sources" VALUES(16,'predefined_analysis_preview','AJ_Dataset','ColName','Dataset');
INSERT INTO "external_sources" VALUES(17,'predefined_analysis_preview','AJ_Priority','ColName','Pri');
INSERT INTO "external_sources" VALUES(18,'predefined_analysis_preview','AJ_ToolName','ColName','Tool');
INSERT INTO "external_sources" VALUES(19,'predefined_analysis_preview','AJ_ParmFile','ColName','Param_File');
INSERT INTO "external_sources" VALUES(20,'predefined_analysis_preview','AJ_SettingsFile','ColName','Settings_File');
INSERT INTO "external_sources" VALUES(21,'predefined_analysis_preview','AJ_Organism','ColName','Organism');
INSERT INTO "external_sources" VALUES(22,'predefined_analysis_preview','AJ_OrganismDB','ColName','OrganismDB_File');
INSERT INTO "external_sources" VALUES(23,'predefined_analysis_preview','protCollNameList','ColName','Protein_Collections');
INSERT INTO "external_sources" VALUES(24,'predefined_analysis_preview','protCollOptionsList','ColName','Protein_Options');
INSERT INTO "external_sources" VALUES(25,'predefined_analysis_preview','AJ_Owner','ColName','Owner');
INSERT INTO "external_sources" VALUES(26,'predefined_analysis_preview','associatedProcessorGroup','ColName','Processor_Group');
INSERT INTO "external_sources" VALUES(27,'predefined_analysis_preview','AJ_Comment','ColName','Comment');
INSERT INTO "external_sources" VALUES(28,'predefined_analysis_preview','AJ_Request','ColName','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'AJ_Dataset','datasetNum','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(2,'AJ_priority','priority','int','input','','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(3,'AJ_ToolName','toolName','varchar','input','64','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(4,'AJ_ParmFile','parmFileName','varchar','input','255','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(5,'AJ_SettingsFile','settingsFileName','varchar','input','255','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(6,'AJ_Organism','organismName','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(7,'protCollNameList','protCollNameList','varchar','input','4000','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(8,'protCollOptionsList','protCollOptionsList','varchar','input','256','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(9,'AJ_OrganismDB','organismDBName','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(10,'AJ_owner','ownerPRN','varchar','input','32','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(11,'AJ_comment','comment','varchar','input','512','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(12,'AJ_specialProcessing','specialProcessing','varchar','input','512','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(13,'associatedProcessorGroup','associatedProcessorGroup','varchar','input','64','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(14,'propagationMode','propagationMode','varchar','input','24','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(15,'stateName','stateName','varchar','input','32','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(16,'Job','jobNum','varchar','output','32','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(17,'<local>','mode','varchar','input','12','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(18,'<local>','message','varchar','output','512','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(19,'<local>','callingUser','varchar','input','128','AddUpdateAnalysisJob');
INSERT INTO "sproc_args" VALUES(20,'ID','jobNum','varchar','input','32','DoAnalysisJobOperation');
INSERT INTO "sproc_args" VALUES(21,'<local>','mode','varchar','input','12','DoAnalysisJobOperation');
INSERT INTO "sproc_args" VALUES(22,'<local>','message','varchar','output','512','DoAnalysisJobOperation');
INSERT INTO "sproc_args" VALUES(23,'<local>','callingUser','varchar','input','128','DoAnalysisJobOperation');
COMMIT;
