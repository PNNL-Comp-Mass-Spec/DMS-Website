﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_Request_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Analysis_Job_Request_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Request');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateAnalysisJobRequest');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Analysis_Job_Request_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','AJR_requestID');
INSERT INTO "general_params" VALUES('rss_data_table','V_Analysis_Job_Request_RSS');
INSERT INTO "general_params" VALUES('rss_description','Analysis job requests with all jobs either completed or failed (within last 30 days).');
INSERT INTO "general_params" VALUES('rss_item_link','analysis_job_request/show');
INSERT INTO "general_params" VALUES('post_submission_detail_id','AJR_requestID');
INSERT INTO "general_params" VALUES('post_submission_link','{"link":"analysis_group/create/analysis_job_request/", "label":"Make jobs from request"}');
INSERT INTO "general_params" VALUES('operations_sproc','DoAnalysisRequestOperation');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'AJR_requestID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'AJR_requestName','Name','text','80','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(4,'AJR_datasets','Datasets','area','','','12','80','','trim');
INSERT INTO "form_fields" VALUES(5,'AJR_analysisToolName','Analysis Tool','text','30','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(6,'AJR_parmFileName','Parameter File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(7,'AJR_settingsFileName','Settings File','text','100','255','','','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(8,'Data_Package_ID','Data Package ID','text','','','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(9,'AJR_organismName','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(10,'protCollNameList','Protein Collection List','area','','','3','60','','trim|max_length[4000]');
INSERT INTO "form_fields" VALUES(11,'protCollOptionsList','Protein Options List','area','','','2','40','seq_direction=forward','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(12,'AJR_organismDBName','Legacy Fasta (typically na)','text','100','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(13,'requestor','Requested by (PRN)','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(15,'State','State','text','24','24','','','New','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(16,'AJR_comment','Comment','area','','','4','50','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(17,'AJR_specialProcessing','Special Processing','area','','','4','80','','trim|max_length[512]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'requestor','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'State','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'AJR_datasets','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/52','',',','Choose by Dataset Name:');
INSERT INTO "form_field_choosers" VALUES(2,'AJR_datasets','list-report.helper','','helper_data_package_dataset_ckbx/report','',',','Choose by Data Package:');
INSERT INTO "form_field_choosers" VALUES(3,'AJR_analysisToolName','picker.replace','analysisToolPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'AJR_parmFileName','list-report.helper','','helper_aj_param_file/report','AJR_analysisToolName',',','');
INSERT INTO "form_field_choosers" VALUES(5,'AJR_settingsFileName','list-report.helper','','helper_aj_settings_file/report/~','AJR_analysisToolName',',','');
INSERT INTO "form_field_choosers" VALUES(6,'AJR_organismName','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'protCollNameList','list-report.helper','','helper_protein_collection/report','AJR_organismName',',','');
INSERT INTO "form_field_choosers" VALUES(8,'protCollOptionsList','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'requestor','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'State','picker.replace','analysisRequestPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(12,'AJR_organismDBName','list-report.helper','','helper_organism_db/report/-/~','AJR_organismName',',','');
INSERT INTO "form_field_choosers" VALUES(13,'Data_Package_ID','list-report.helper','','helper_data_package/report/-/','Data_Package_ID',',','Choose from (only applicable for MaxQuant):');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_state','State','6!','','State','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_requestID','RequestID','6!','','Request','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_name','Name','25!','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_requestor','Requestor','15!','','Requestor','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_tool','Tool','32','','Tool','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_param_file','Param File','30!','','Param File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_proteincollectionlist','Protein Collections','20!','','ProteinCollectionList','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Request','invoke_entity','value','analysis_job_request/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Jobs','invoke_entity','Request','analysis_request_jobs/report','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Organism DB File','invoke_entity','value','helper_organism_db/report','');
INSERT INTO "list_report_hotlinks" VALUES(4,'ProteinCollectionList','link_list','value','protein_collection/report','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Data Package','invoke_entity','value','data_package/show','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(2,'Convert Request to Job(s)','copy_from','','analysis_group','Go to job entry page and copy values from this page.','');
INSERT INTO "detail_report_commands" VALUES(3,'View pre-existing jobs','call','param','existing_jobs_for_request','Show existing jobs made from this request.','');
INSERT INTO "detail_report_commands" VALUES(4,'Delete this request','cmd_op','delete','analysis_job_request','Delete this analysis job request.','Are you sure that you want to delete this request?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Jobs','detail-report','Request','analysis_request_jobs/report','valueCol','jobs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Datasets','link_list','Datasets','dataset/show','valueCol','dl_datasets',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Pre-existing Jobs','link_list','Pre-existing Jobs','analysis_job/show','valueCol','dl_pre-existing_jobs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Settings File','detail-report','Settings File','settings_files/report/-/~','labelCol','dl_settings_file','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Tool','detail-report','Tool','pipeline_script/report/~','labelCol','dl_tool','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Protein Collection List','link_table','Protein Collection List','protein_collection/report/~','valueCol','dl_protein_collection_list','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Requestor Name','detail-report','Requestor Name','user/report/-/~','labelCol','dl_Requestor','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Parameter File','detail-report','Parameter File','param_file/report/-/~','labelCol','dl_param_file','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Legacy Fasta','detail-report','Legacy Fasta','helper_organism_db/report/~','valueCol','dl_legacy_fasta','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'Data Package ID','detail-report','Data Package ID','data_package/show','valueCol','dl_data_package','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'PreviewAdd','cmd','Preview Add','Determine if current values are valid, but do not change database.','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO "external_sources" VALUES(1,'analysis_job','AJR_requestID','Literal','0');
INSERT INTO "external_sources" VALUES(2,'analysis_job','AJR_requestName','Literal','New Request');
INSERT INTO "external_sources" VALUES(3,'analysis_job','AJR_datasets','ColName','Dataset');
INSERT INTO "external_sources" VALUES(4,'analysis_job','AJR_analysisToolName','ColName','Tool Name');
INSERT INTO "external_sources" VALUES(5,'analysis_job','AJR_parmFileName','ColName','Parm File');
INSERT INTO "external_sources" VALUES(6,'analysis_job','AJR_settingsFileName','ColName','Settings File');
INSERT INTO "external_sources" VALUES(7,'analysis_job','AJR_organismName','ColName','Organism');
INSERT INTO "external_sources" VALUES(8,'analysis_job','protCollNameList','ColName','Protein Collection List');
INSERT INTO "external_sources" VALUES(9,'analysis_job','protCollOptionsList','ColName','Protein Options List');
INSERT INTO "external_sources" VALUES(10,'analysis_job','requestor','ColName','Owner');
INSERT INTO "external_sources" VALUES(11,'analysis_job','State','Literal','New');
INSERT INTO "external_sources" VALUES(12,'analysis_job','AJR_Comment','ColName','Comment');
INSERT INTO "external_sources" VALUES(13,'analysis_job','AJR_specialProcessing','ColName','Special Processing');
INSERT INTO "external_sources" VALUES(14,'dataset','AJR_requestID','Literal','0');
INSERT INTO "external_sources" VALUES(15,'dataset','AJR_datasets','ColName','Dataset');
INSERT INTO "external_sources" VALUES(16,'dataset','AJR_organismName','ColName','Organism');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'AJR_datasets','datasets','text','input','2147483647','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(2,'AJR_requestName','requestName','varchar','input','64','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(3,'AJR_analysisToolName','toolName','varchar','input','64','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(4,'AJR_parmFileName','parmFileName','varchar','input','255','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(5,'AJR_settingsFileName','settingsFileName','varchar','input','255','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(6,'protCollNameList','protCollNameList','varchar','input','4000','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(7,'protCollOptionsList','protCollOptionsList','varchar','input','256','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(8,'AJR_organismName','organismName','varchar','input','128','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(9,'AJR_organismDBName','organismDBName','varchar','input','128','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(10,'requestor','requestorPRN','varchar','input','32','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(12,'AJR_comment','comment','varchar','input','512','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(13,'AJR_specialProcessing','specialProcessing','varchar','input','512','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(15,'Data_Package_ID','dataPackageID','int','input','','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(16,'State','state','varchar','input','32','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(17,'AJR_requestID','requestID','int','output','','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(18,'<local>','mode','varchar','input','12','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(19,'<local>','message','varchar','output','512','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(20,'<local>','callingUser','varchar','input','128','AddUpdateAnalysisJobRequest');
INSERT INTO "sproc_args" VALUES(21,'ID','request','varchar','input','32','DoAnalysisRequestOperation');
INSERT INTO "sproc_args" VALUES(22,'<local>','mode','varchar','input','12','DoAnalysisRequestOperation');
INSERT INTO "sproc_args" VALUES(23,'<local>','message','varchar','output','512','DoAnalysisRequestOperation');
COMMIT;
