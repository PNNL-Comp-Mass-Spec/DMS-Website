PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_Analysis_Job_PSM_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Data Pkg, Job');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Analysis_Job_PSM_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Job');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_data_package_id','Data Pkg','4!','','Data Pkg','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_job','Job','12','','Job','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_state','State','20','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_tool','Tool','32','','Tool','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_dataset','Dataset','60','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_experiment','Experiment','60','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_parm_file','Parm File','60','','Parm File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_instrument','Instrument','60','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_protein_collection_list','Protein Collection List','60','','Protein Collection List','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_comment','Comment','60','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(12,'pf_job_request','Request','12','','Job Request','Equals','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_state','picker.replace','analysisJobStatePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_tool','picker.replace','analysisToolPickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','analysis_job_psm/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Request','invoke_entity','value','analysis_job_request/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Data Pkg','invoke_entity','value','data_package/show/','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Dataset','detail-report','Dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Dataset Folder Path','href-folder','Dataset Folder Path','','labelCol','dataset_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Archive Results Folder Path','href-folder','Archive Results Folder Path','','labelCol','archive_results_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Archive Folder Path','href-folder','Archive Folder Path','','labelCol','archive_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'Results Folder Path','href-folder','Results Folder Path','','labelCol','results_folder_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Request','detail-report','Request','analysis_job_request/show','labelCol','request',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'State','detail-report','Job','pipeline_job_steps/report','labelCol','state',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(8,'Tool Name','detail-report','Tool Name','pipeline_script/report/~','labelCol','tool_name',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(9,'Settings File','detail-report','Settings File','settings_files/report/-/~','labelCol','settings_file',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(10,'MTS PT DB Count','detail-report','Job','mts_pt_db_jobs/report','labelCol','PT_DBs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(11,'MTS MT DB Count','detail-report','Job','mts_mt_db_jobs/report','labelCol','MT_DBs',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(12,'Peak Matching Results','detail-report','Job','mts_pm_results/report/-/','labelCol','pmresults',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(14,'Data Folder Link','literal_link','Data Folder Link','','valueCol','dl_data_folder',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(15,'Job','detail-report','Job','analysis_job/show','labelCol','job_steps',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(16,'Owner','detail-report','Owner','user/show','labelCol','owner',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(17,'Total PSMs','detail-report','Dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_analysis_job_psm',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(18,'Experiment','detail-report','Experiment','experiment/show','labelCol','dl_experiment',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(19,'+Dataset','detail-report','Dataset','analysis_job_psm/report/-/-/-/~','valueCol','dl_analysis_job_psm_ds',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(20,'+Experiment','detail-report','Experiment','analysis_job_psm/report/-/-/-/-/~','valueCol','dl_analysis_job_psm_exp',NULL);
COMMIT;
