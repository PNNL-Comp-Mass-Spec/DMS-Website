﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Analysis_Job_Request_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','V_Analysis_Job_Request_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Request');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateAnalysisJobRequest');
INSERT INTO general_params VALUES('entry_page_data_table','v_analysis_job_request_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','request_id');
INSERT INTO general_params VALUES('post_submission_detail_id','request_id');
INSERT INTO general_params VALUES('post_submission_link','{"link":"analysis_group/create/analysis_job_request/", "label":"Make jobs from request"}');
INSERT INTO general_params VALUES('operations_sproc','DoAnalysisRequestOperation');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'request_id','ID','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'request_name','Name','text','80','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(4,'datasets','Datasets','area','','','12','80','','trim');
INSERT INTO form_fields VALUES(5,'analysis_tool','Analysis Tool','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(6,'param_file_name','Parameter File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO form_fields VALUES(7,'settings_file_name','Settings File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO form_fields VALUES(8,'data_package_id','Data Package ID','text','','','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(9,'organism_name','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(10,'prot_coll_name_list','Protein Collection List','area','','','3','60','','trim|max_length[4000]');
INSERT INTO form_fields VALUES(11,'prot_coll_options_list','Protein Options List','area','','','2','40','seq_direction=forward','trim|max_length[256]');
INSERT INTO form_fields VALUES(12,'organism_db_name','Legacy Fasta (typically na)','text','100','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(13,'requester','Requested by','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(15,'state','State','text','24','24','','','New','trim|required|max_length[24]');
INSERT INTO form_fields VALUES(16,'comment','Comment','area','','','4','50','','trim|max_length[512]');
INSERT INTO form_fields VALUES(17,'special_processing','Special Processing','area','','','4','80','','trim|max_length[512]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'requester','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'state','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'datasets','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/52','',',','Choose by Dataset Name:');
INSERT INTO form_field_choosers VALUES(2,'datasets','list-report.helper','','helper_data_package_dataset_ckbx/report','',',','Choose by Data Package:');
INSERT INTO form_field_choosers VALUES(3,'analysis_tool','picker.replace','analysisToolPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'param_file_name','list-report.helper','','helper_aj_param_file/report','analysis_tool',',','');
INSERT INTO form_field_choosers VALUES(5,'settings_file_name','list-report.helper','','helper_aj_settings_file/report/~','analysis_tool',',','');
INSERT INTO form_field_choosers VALUES(6,'organism_name','list-report.helper','','helper_organism/report','',',','');
INSERT INTO form_field_choosers VALUES(7,'prot_coll_name_list','list-report.helper','','helper_protein_collection/report','organism_name',',','');
INSERT INTO form_field_choosers VALUES(8,'prot_coll_options_list','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'requester','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(10,'state','picker.replace','analysisRequestPickList','','',',','');
INSERT INTO form_field_choosers VALUES(12,'organism_db_name','list-report.helper','','helper_organism_db/report/-/~','organism_name',',','');
INSERT INTO form_field_choosers VALUES(13,'data_package_id','list-report.helper','','helper_data_package/report/-/','data_package_id',',','Choose from (only for MaxQuant or MSFragger):');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_state','State','6!','','State','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_requestID','RequestID','6!','','Request','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_name','Name','25!','','Name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_requester','Requester','15!','','Requester','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_tool','Tool','32','','Tool','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_param_file','Param File','45!','','Param File','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_proteincollectionlist','Protein Collection','45!','','ProteinCollectionList','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_data_package','Data Package','6!','','Data Package','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Request','invoke_entity','value','analysis_job_request/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'Jobs','invoke_entity','Request','analysis_request_jobs/report','');
INSERT INTO list_report_hotlinks VALUES(3,'Organism DB File','invoke_entity','value','helper_organism_db/report','');
INSERT INTO list_report_hotlinks VALUES(4,'ProteinCollectionList','link_list','value','protein_collection/report','');
INSERT INTO list_report_hotlinks VALUES(5,'Data Package','invoke_entity','value','data_package/show','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(2,'Convert Request to Job(s)','copy_from','','analysis_group','Go to job entry page and copy values from this page.','');
INSERT INTO detail_report_commands VALUES(3,'View pre-existing jobs','call','param','existing_jobs_for_request','Show existing jobs made from this request.','');
INSERT INTO detail_report_commands VALUES(4,'Delete this request','cmd_op','delete','analysis_job_request','Delete this analysis job request.','Are you sure that you want to delete this request?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Jobs','detail-report','Request','analysis_request_jobs/report','valueCol','jobs',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'Datasets','link_list','Datasets','dataset/show','valueCol','dl_datasets',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'Pre-existing Jobs','link_list','Pre-existing Jobs','analysis_job/show','valueCol','dl_pre-existing_jobs',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'Settings File','detail-report','Settings File','settings_files/report/-/~','labelCol','dl_settings_file','');
INSERT INTO detail_report_hotlinks VALUES(5,'Tool','detail-report','Tool','pipeline_script/report/~','labelCol','dl_tool','');
INSERT INTO detail_report_hotlinks VALUES(6,'Protein Collection List','link_table','Protein Collection List','protein_collection/report/~','valueCol','dl_protein_collection_list','');
INSERT INTO detail_report_hotlinks VALUES(7,'Requester Name','detail-report','Requester','user/show/','labelCol','dl_requester','');
INSERT INTO detail_report_hotlinks VALUES(8,'Parameter File','detail-report','Parameter File','param_file/report/-/~','labelCol','dl_param_file','');
INSERT INTO detail_report_hotlinks VALUES(9,'Legacy Fasta','detail-report','Legacy Fasta','helper_organism_db/report/~','valueCol','dl_legacy_fasta','');
INSERT INTO detail_report_hotlinks VALUES(10,'Data Package ID','detail-report','Data Package ID','data_package/show','valueCol','dl_data_package','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'PreviewAdd','cmd','Preview Add','Determine if current values are valid, but do not change database.','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO external_sources VALUES(1,'analysis_job','request_id','Literal','0');
INSERT INTO external_sources VALUES(2,'analysis_job','request_name','Literal','New Request');
INSERT INTO external_sources VALUES(3,'analysis_job','datasets','ColName','Dataset');
INSERT INTO external_sources VALUES(4,'analysis_job','analysis_tool','ColName','Tool Name');
INSERT INTO external_sources VALUES(5,'analysis_job','param_file_name','ColName','Param File');
INSERT INTO external_sources VALUES(6,'analysis_job','settings_file_name','ColName','Settings File');
INSERT INTO external_sources VALUES(7,'analysis_job','organism_name','ColName','Organism');
INSERT INTO external_sources VALUES(8,'analysis_job','prot_coll_name_list','ColName','Protein Collection List');
INSERT INTO external_sources VALUES(9,'analysis_job','prot_coll_options_list','ColName','Protein Options List');
INSERT INTO external_sources VALUES(10,'analysis_job','requester','ColName','Owner');
INSERT INTO external_sources VALUES(11,'analysis_job','state','Literal','New');
INSERT INTO external_sources VALUES(12,'analysis_job','comment','ColName','Comment');
INSERT INTO external_sources VALUES(13,'analysis_job','special_processing','ColName','Special Processing');
INSERT INTO external_sources VALUES(14,'dataset','request_id','Literal','0');
INSERT INTO external_sources VALUES(15,'dataset','datasets','ColName','Dataset');
INSERT INTO external_sources VALUES(16,'dataset','organism_name','ColName','Organism');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'datasets','datasets','text','input','2147483647','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(2,'request_name','requestName','varchar','input','64','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(3,'analysis_tool','toolName','varchar','input','64','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(4,'param_file_name','paramFileName','varchar','input','255','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(5,'settings_file_name','settingsFileName','varchar','input','255','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(6,'prot_coll_name_list','protCollNameList','varchar','input','4000','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(7,'prot_coll_options_list','protCollOptionsList','varchar','input','256','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(8,'organism_name','organismName','varchar','input','128','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(9,'organism_db_name','organismDBName','varchar','input','128','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(10,'requester','requesterPRN','varchar','input','32','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(12,'comment','comment','varchar','input','512','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(13,'special_processing','specialProcessing','varchar','input','512','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(15,'data_package_id','dataPackageID','int','input','','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(16,'state','state','varchar','input','32','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(17,'request_id','requestID','int','output','','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(18,'<local>','mode','varchar','input','12','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(19,'<local>','message','varchar','output','512','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(20,'<local>','callingUser','varchar','input','128','AddUpdateAnalysisJobRequest');
INSERT INTO sproc_args VALUES(21,'ID','request','varchar','input','32','DoAnalysisRequestOperation');
INSERT INTO sproc_args VALUES(22,'<local>','mode','varchar','input','12','DoAnalysisRequestOperation');
INSERT INTO sproc_args VALUES(23,'<local>','message','varchar','output','512','DoAnalysisRequestOperation');
COMMIT;
