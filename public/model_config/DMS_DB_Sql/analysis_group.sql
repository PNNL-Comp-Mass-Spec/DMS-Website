﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('entry_sproc','add_analysis_job_group');
INSERT INTO general_params VALUES('entry_page_data_cols','job, priority, tool_name, dataset, param_file, settings_file, organism, organism_db, owner, comment, batch_id, ''0'' AS request');
INSERT INTO general_params VALUES('entry_page_data_table','v_analysis_job_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','job');
INSERT INTO general_params VALUES('post_submission_link','{"link":"analysis_request_jobs/report/", "label":"Show jobs for request"}');
INSERT INTO general_params VALUES('post_submission_detail_id','request');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'dataset','Datasets','area|non-edit-if-data-package','','','12','80','','trim|required');
INSERT INTO form_fields VALUES(2,'remove_datasets_with_jobs','Skip Datasets With Existing Jobs','text','12','12','','','Y','trim|max_length[12]');
INSERT INTO form_fields VALUES(3,'priority','Priority','text','3','3','','','','trim|default_value[3]|required|max_length[2]|numeric');
INSERT INTO form_fields VALUES(4,'tool_name','Analysis Tool','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(5,'param_file','Parameter File','text','80','255','','','','trim|required|max_length[255]');
INSERT INTO form_fields VALUES(6,'settings_file','Settings File','text','80','255','','','','trim|default_value[IonTrapDefSettings_MzML.xml]|required|max_length[255]');
INSERT INTO form_fields VALUES(7,'data_package_id','Data Package ID','non-edit','','','','','','trim|default_value[0]');
INSERT INTO form_fields VALUES(8,'organism','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(9,'prot_coll_name_list','Protein Collection List','area','','','3','60','na','trim|max_length[4000]');
INSERT INTO form_fields VALUES(10,'prot_coll_options_list','Protein Options List','area','','','2','60','na','trim|max_length[256]');
INSERT INTO form_fields VALUES(11,'organism_db','Individual FASTA (typically na)','text','80','80','','','na','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(12,'owner','Owner (Username)','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(13,'associated_processor_group','Associated Processor Group','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(14,'comment','Comment','area','','','4','80','','trim|max_length[512]');
INSERT INTO form_fields VALUES(15,'special_processing','Special Processing','area','','','4','80','','trim|max_length[512]');
INSERT INTO form_fields VALUES(16,'request','Request','non-edit','12','12','','','','trim|default_value[0]|max_length[12]');
INSERT INTO form_fields VALUES(17,'propagation_mode','Export Mode','text','24','24','','','Export','trim|max_length[24]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'owner','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'dataset','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(3,'remove_datasets_with_jobs','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration,DMS_Analysis_Job_Administration');
INSERT INTO form_field_options VALUES(4,'priority','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(5,'tool_name','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(6,'param_file','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(7,'settings_file','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(12,'owner','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(13,'associated_processor_group','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(14,'request','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
INSERT INTO form_field_options VALUES(15,'propagation_mode','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'priority','picker.replace','analysisJobPriPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'tool_name','picker.replace','analysisToolPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'param_file','list-report.helper','','helper_aj_param_file/report/~','tool_name',',','');
INSERT INTO form_field_choosers VALUES(4,'settings_file','list-report.helper','','helper_aj_settings_file/report/~','tool_name',',','');
INSERT INTO form_field_choosers VALUES(5,'organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO form_field_choosers VALUES(6,'prot_coll_name_list','list-report.helper','','helper_protein_collection/report','organism',',','');
INSERT INTO form_field_choosers VALUES(7,'prot_coll_options_list','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO form_field_choosers VALUES(8,'owner','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'associated_processor_group','list-report.helper','','helper_analysis_processor_group/report','',',','');
INSERT INTO form_field_choosers VALUES(10,'propagation_mode','picker.replace','jobPropagationModePickList','','',',','');
INSERT INTO form_field_choosers VALUES(11,'dataset','list-report.helper','','helper_dataset_ckbx/report','',',','Choose from all datasets:');
INSERT INTO form_field_choosers VALUES(12,'dataset','list-report.helper','','helper_aj_request_datasets_ckbx/param','request',',','Choose from request datasets:');
INSERT INTO form_field_choosers VALUES(13,'remove_datasets_with_jobs','picker.replace','YNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(14,'organism_db','list-report.helper','','helper_organism_db/report/-/~','organism',',','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'preview','cmd','Preview','Determine if current values are valid, but do not change database.','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO external_sources VALUES(1,'analysis_job_request','dataset','ColName','datasets');
INSERT INTO external_sources VALUES(2,'analysis_job_request','tool_name','ColName','tool');
INSERT INTO external_sources VALUES(3,'analysis_job_request','param_file','ColName','parameter_file');
INSERT INTO external_sources VALUES(4,'analysis_job_request','settings_file','ColName','settings_file');
INSERT INTO external_sources VALUES(5,'analysis_job_request','organism','ColName','organism');
INSERT INTO external_sources VALUES(6,'analysis_job_request','prot_coll_name_list','ColName','protein_collection_list');
INSERT INTO external_sources VALUES(7,'analysis_job_request','prot_coll_options_list','ColName','protein_options');
INSERT INTO external_sources VALUES(8,'analysis_job_request','organism_db','ColName','organism_db_file');
INSERT INTO external_sources VALUES(9,'analysis_job_request','owner','ColName','requester');
INSERT INTO external_sources VALUES(10,'analysis_job_request','comment','ColName.action.Scrub','comment');
INSERT INTO external_sources VALUES(11,'analysis_job_request','request','ColName','request');
INSERT INTO external_sources VALUES(12,'analysis_job_request','priority','Literal','3');
INSERT INTO external_sources VALUES(13,'analysis_job_request','special_processing','ColName','special_processing');
INSERT INTO external_sources VALUES(14,'analysis_job_request','data_package_id','ColName','data_package_id');
INSERT INTO external_sources VALUES(15,'analysis_job','dataset','ColName','dataset');
INSERT INTO external_sources VALUES(16,'analysis_job','priority','Literal','2');
INSERT INTO external_sources VALUES(17,'analysis_job','tool_name','ColName','tool_name');
INSERT INTO external_sources VALUES(18,'analysis_job','param_file','ColName','param_file');
INSERT INTO external_sources VALUES(19,'analysis_job','settings_file','ColName','settings_file');
INSERT INTO external_sources VALUES(20,'analysis_job','organism','ColName','organism');
INSERT INTO external_sources VALUES(21,'analysis_job','organism_db','ColName','organism_db');
INSERT INTO external_sources VALUES(22,'analysis_job','prot_coll_name_list','ColName','protein_collection_list');
INSERT INTO external_sources VALUES(23,'analysis_job','prot_coll_options_list','ColName','protein_options_list');
INSERT INTO external_sources VALUES(24,'analysis_job','owner','ColName','owner');
INSERT INTO external_sources VALUES(25,'analysis_job','comment','ColName','comment');
INSERT INTO external_sources VALUES(26,'analysis_job','request','Literal','0');
INSERT INTO external_sources VALUES(27,'predefined_analysis_jobs_preview','dataset','PostName','dataset');
INSERT INTO external_sources VALUES(28,'predefined_analysis_jobs_preview','priority','PostName','pri');
INSERT INTO external_sources VALUES(29,'predefined_analysis_jobs_preview','tool_name','PostName','tool');
INSERT INTO external_sources VALUES(30,'predefined_analysis_jobs_preview','param_file','PostName','param_file');
INSERT INTO external_sources VALUES(31,'predefined_analysis_jobs_preview','settings_file','PostName','settings_file');
INSERT INTO external_sources VALUES(32,'predefined_analysis_jobs_preview','organism','PostName','organism');
INSERT INTO external_sources VALUES(33,'predefined_analysis_jobs_preview','organism_db','PostName','organism_db_name');
INSERT INTO external_sources VALUES(34,'predefined_analysis_jobs_preview','prot_coll_name_list','PostName','protein_collections');
INSERT INTO external_sources VALUES(35,'predefined_analysis_jobs_preview','prot_coll_options_list','PostName','protein_options');
INSERT INTO external_sources VALUES(36,'predefined_analysis_preview_mds','dataset','PostName','dataset');
INSERT INTO external_sources VALUES(37,'predefined_analysis_preview_mds','priority','PostName','pri');
INSERT INTO external_sources VALUES(38,'predefined_analysis_preview_mds','tool_name','PostName','tool');
INSERT INTO external_sources VALUES(39,'predefined_analysis_preview_mds','param_file','PostName','param_file');
INSERT INTO external_sources VALUES(40,'predefined_analysis_preview_mds','settings_file','PostName','settings_file');
INSERT INTO external_sources VALUES(41,'predefined_analysis_preview_mds','organism','PostName','organism');
INSERT INTO external_sources VALUES(42,'predefined_analysis_preview_mds','organism_db','PostName','organism_db_name');
INSERT INTO external_sources VALUES(43,'predefined_analysis_preview_mds','prot_coll_name_list','PostName','protein_collections');
INSERT INTO external_sources VALUES(44,'predefined_analysis_preview_mds','prot_coll_options_list','PostName','protein_options');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'dataset','datasetList','text','input','2147483647','add_analysis_job_group');
INSERT INTO sproc_args VALUES(2,'priority','priority','int','input','','add_analysis_job_group');
INSERT INTO sproc_args VALUES(3,'tool_name','toolName','varchar','input','64','add_analysis_job_group');
INSERT INTO sproc_args VALUES(4,'param_file','paramFileName','varchar','input','255','add_analysis_job_group');
INSERT INTO sproc_args VALUES(5,'settings_file','settingsFileName','varchar','input','255','add_analysis_job_group');
INSERT INTO sproc_args VALUES(6,'organism_db','organismDBName','varchar','input','128','add_analysis_job_group');
INSERT INTO sproc_args VALUES(7,'organism','organismName','varchar','input','128','add_analysis_job_group');
INSERT INTO sproc_args VALUES(8,'prot_coll_name_list','protCollNameList','varchar','input','4000','add_analysis_job_group');
INSERT INTO sproc_args VALUES(9,'prot_coll_options_list','protCollOptionsList','varchar','input','256','add_analysis_job_group');
INSERT INTO sproc_args VALUES(10,'owner','ownerUsername','varchar','input','32','add_analysis_job_group');
INSERT INTO sproc_args VALUES(11,'comment','comment','varchar','input','512','add_analysis_job_group');
INSERT INTO sproc_args VALUES(12,'special_processing','specialProcessing','varchar','input','512','add_analysis_job_group');
INSERT INTO sproc_args VALUES(13,'request','requestID','int','input','','add_analysis_job_group');
INSERT INTO sproc_args VALUES(14,'data_package_id','dataPackageID','int','input','','add_analysis_job_group');
INSERT INTO sproc_args VALUES(15,'associated_processor_group','associatedProcessorGroup','varchar','input','64','add_analysis_job_group');
INSERT INTO sproc_args VALUES(16,'propagation_mode','propagationMode','varchar','input','24','add_analysis_job_group');
INSERT INTO sproc_args VALUES(17,'remove_datasets_with_jobs','removeDatasetsWithJobs','varchar','input','12','add_analysis_job_group');
INSERT INTO sproc_args VALUES(18,'<local>','mode','varchar','input','12','add_analysis_job_group');
INSERT INTO sproc_args VALUES(19,'<local>','message','varchar','output','512','add_analysis_job_group');
INSERT INTO sproc_args VALUES(20,'<local>','callingUser','varchar','input','128','add_analysis_job_group');
COMMIT;
