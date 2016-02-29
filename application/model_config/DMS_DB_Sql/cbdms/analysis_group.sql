PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('entry_sproc','AddAnalysisJobGroup');
INSERT INTO "general_params" VALUES('entry_page_data_cols','Job, AJ_priority, AJ_ToolName, AJ_Dataset, AJ_ParmFile, AJ_SettingsFile, AJ_Organism, AJ_OrganismDB, AJ_owner, AJ_comment, AJ_batchID, ''0'' AS AJ_Request');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Analysis_Job_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Job');
INSERT INTO "general_params" VALUES('post_submission_link','{"link":"analysis_request_jobs/report/", "label":"Show jobs for request"}');
INSERT INTO "general_params" VALUES('post_submission_detail_id','AJ_Request');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'AJ_Dataset','Dataset','area','','','12','80','','trim|required');
INSERT INTO "form_fields" VALUES(2,'removeDatasetsWithJobs','Skip Datasets With Existing Jobs','text','12','12','','','Y','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(3,'AJ_Priority','Priority','text','3','3','','','','trim|default_value[3]|required|max_length[2]|numeric');
INSERT INTO "form_fields" VALUES(4,'AJ_ToolName','Analysis Tool','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'AJ_ParmFile','Parameter File','area','','','2','60','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(6,'AJ_SettingsFile','Settings File','text','80','255','','','','trim|default_value[LCQDefSettings.txt]|required|max_length[255]');
INSERT INTO "form_fields" VALUES(7,'AJ_Organism','Organism','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(8,'protCollNameList','Protein Collection List','area','','','3','60','na','trim|max_length[4000]');
INSERT INTO "form_fields" VALUES(9,'protCollOptionsList','Protein Options List','area','','','2','60','na','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(10,'AJ_OrganismDB','Legacy Fasta (typically na)','text','80','80','','','na','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(11,'AJ_Owner','Owner (PRN)','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(12,'associatedProcessorGroup','Associated Processor Group','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'AJ_Comment','Comment','area','','','4','50','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(14,'specialProcessing','Special Processing','area','','','4','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(15,'AJ_Request','Request','text','12','12','','','','trim|default_value[0]|max_length[12]');
INSERT INTO "form_fields" VALUES(16,'propagationMode','Export Mode','text','24','24','','','Export','trim|max_length[24]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'AJ_Owner','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'AJ_Dataset','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(3,'removeDatasetsWithJobs','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(4,'AJ_Priority','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(5,'AJ_ToolName','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(6,'AJ_ParmFile','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(7,'AJ_SettingsFile','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(12,'AJ_Owner','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(13,'associatedProcessorGroup','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(14,'AJ_Request','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO "form_field_options" VALUES(15,'propagationMode','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'AJ_Priority','picker.replace','analysisJobPriPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'AJ_ToolName','picker.replace','analysisToolPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'AJ_ParmFile','list-report.helper','','helper_aj_param_file/report','AJ_ToolName',',','');
INSERT INTO "form_field_choosers" VALUES(4,'AJ_SettingsFile','list-report.helper','','helper_aj_settings_file/report/~','AJ_ToolName',',','');
INSERT INTO "form_field_choosers" VALUES(5,'AJ_Organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'protCollNameList','list-report.helper','','helper_protein_collection/report','AJ_Organism',',','');
INSERT INTO "form_field_choosers" VALUES(7,'protCollOptionsList','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'AJ_Owner','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'associatedProcessorGroup','list-report.helper','','helper_analysis_processor_group/report','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'propagationMode','picker.replace','jobPropagationModePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'AJ_Dataset','list-report.helper','','helper_dataset_ckbx/report','',',','Choose from all datasets:');
INSERT INTO "form_field_choosers" VALUES(12,'AJ_Dataset','list-report.helper','','helper_aj_request_datasets_ckbx/param','AJ_Request',',','Choose from request datasets:');
INSERT INTO "form_field_choosers" VALUES(13,'removeDatasetsWithJobs','picker.replace','YNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(14,'AJ_OrganismDB','list-report.helper','','helper_organism_db/report/-/~','AJ_Organism',',','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'previewed in','cmd','Preview','Determine if current values are valid, but do not change database.','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO "external_sources" VALUES(1,'analysis_job_request','AJ_Dataset','ColName','Datasets');
INSERT INTO "external_sources" VALUES(2,'analysis_job_request','AJ_ToolName','ColName','Tool');
INSERT INTO "external_sources" VALUES(3,'analysis_job_request','AJ_ParmFile','ColName','Parameter File');
INSERT INTO "external_sources" VALUES(4,'analysis_job_request','AJ_SettingsFile','ColName','Settings File');
INSERT INTO "external_sources" VALUES(5,'analysis_job_request','AJ_Organism','ColName','Organism');
INSERT INTO "external_sources" VALUES(6,'analysis_job_request','protCollNameList','ColName','Protein Collection List');
INSERT INTO "external_sources" VALUES(7,'analysis_job_request','protCollOptionsList','ColName','Protein Options');
INSERT INTO "external_sources" VALUES(8,'analysis_job_request','AJ_OrganismDB','ColName','Legacy Fasta');
INSERT INTO "external_sources" VALUES(9,'analysis_job_request','AJ_Owner','ColName','Requestor');
INSERT INTO "external_sources" VALUES(10,'analysis_job_request','AJ_Comment','ColName.action.Scrub','Comment');
INSERT INTO "external_sources" VALUES(11,'analysis_job_request','AJ_Request','ColName','Request');
INSERT INTO "external_sources" VALUES(12,'analysis_job_request','AJ_Priority','Literal','3');
INSERT INTO "external_sources" VALUES(14,'analysis_job_request','specialProcessing','ColName','Special Processing');
INSERT INTO "external_sources" VALUES(15,'analysis_job','AJ_Dataset','ColName','Dataset');
INSERT INTO "external_sources" VALUES(16,'analysis_job','AJ_Priority','Literal','2');
INSERT INTO "external_sources" VALUES(17,'analysis_job','AJ_ToolName','ColName','Tool Name');
INSERT INTO "external_sources" VALUES(18,'analysis_job','AJ_ParmFile','ColName','Parm File');
INSERT INTO "external_sources" VALUES(19,'analysis_job','AJ_SettingsFile','ColName','Settings File');
INSERT INTO "external_sources" VALUES(20,'analysis_job','AJ_Organism','ColName','Organism');
INSERT INTO "external_sources" VALUES(21,'analysis_job','AJ_OrganismDB','ColName','Organism DB');
INSERT INTO "external_sources" VALUES(22,'analysis_job','protCollNameList','ColName','Protein Collection List');
INSERT INTO "external_sources" VALUES(23,'analysis_job','protCollOptionsList','ColName','Protein Options List');
INSERT INTO "external_sources" VALUES(24,'analysis_job','AJ_Owner','ColName','Owner');
INSERT INTO "external_sources" VALUES(25,'analysis_job','AJ_Comment','ColName','Comment');
INSERT INTO "external_sources" VALUES(26,'analysis_job','AJ_Request','Literal','0');
INSERT INTO "external_sources" VALUES(27,'predefined_analysis_preview','AJ_Dataset','PostName','Dataset');
INSERT INTO "external_sources" VALUES(28,'predefined_analysis_preview','AJ_Priority','PostName','Pri');
INSERT INTO "external_sources" VALUES(29,'predefined_analysis_preview','AJ_ToolName','PostName','Tool');
INSERT INTO "external_sources" VALUES(30,'predefined_analysis_preview','AJ_ParmFile','PostName','Param_File');
INSERT INTO "external_sources" VALUES(31,'predefined_analysis_preview','AJ_SettingsFile','PostName','Settings_File');
INSERT INTO "external_sources" VALUES(32,'predefined_analysis_preview','AJ_Organism','PostName','Organism');
INSERT INTO "external_sources" VALUES(33,'predefined_analysis_preview','AJ_OrganismDB','PostName','OrganismDB_File');
INSERT INTO "external_sources" VALUES(34,'predefined_analysis_preview','protCollNameList','PostName','Protein_Collections');
INSERT INTO "external_sources" VALUES(35,'predefined_analysis_preview','protCollOptionsList','PostName','Protein_Options');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'AJ_Dataset','datasetList','text','input','2147483647','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(2,'AJ_Priority','priority','int','input','','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(3,'AJ_ToolName','toolName','varchar','input','64','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(4,'AJ_ParmFile','parmFileName','varchar','input','255','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(5,'AJ_SettingsFile','settingsFileName','varchar','input','255','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(6,'AJ_OrganismDB','organismDBName','varchar','input','64','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(7,'AJ_Organism','organismName','varchar','input','64','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(8,'protCollNameList','protCollNameList','varchar','input','4000','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(9,'protCollOptionsList','protCollOptionsList','varchar','input','256','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(10,'AJ_Owner','ownerPRN','varchar','input','32','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(11,'AJ_Comment','comment','varchar','input','512','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(12,'specialProcessing','specialProcessing','varchar','input','512','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(13,'AJ_Request','requestID','int','input','','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(14,'associatedProcessorGroup','associatedProcessorGroup','varchar','input','64','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(15,'propagationMode','propagationMode','varchar','input','24','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(16,'removeDatasetsWithJobs','removeDatasetsWithJobs','varchar','input','12','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(17,'<local>','mode','varchar','input','12','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(18,'<local>','message','varchar','output','512','AddAnalysisJobGroup');
INSERT INTO "sproc_args" VALUES(19,'<local>','callingUser','varchar','input','128','AddAnalysisJobGroup');
COMMIT;
