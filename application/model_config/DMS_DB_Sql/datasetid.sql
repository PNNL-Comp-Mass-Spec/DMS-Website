PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Dataset');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Dataset_List_Report_2');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Dataset_Detail_Report_Ex');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','#DateSortKey');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','datasetid/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Rating','color_label','','','{"Unreviewed":"warning_clr","Not Released":"bad_clr","Data Files Missing":"bad_clr","No Data (Blank\/Bad)":"bad_clr"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'QC_Link','masked_link','value','','{"Label":"QC_Link"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Experiment','invoke_entity','value','experiment/show/','');
INSERT INTO "list_report_hotlinks" VALUES(6,'EMSL Proposal','invoke_entity','value','eus_proposals/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','','','ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_experiment','Experiment','20!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_campaign','Campaign','32','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_most_recent_weeks','Most Recent Weeks','3!','','#DateSortKey','MostRecentWeeks','text','4','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_created_after','Created After','8','','Created','LaterThan','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_rating','Rating','12','','Rating','MatchesText','text','64','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Experiment','detail-report','Experiment','experiment/show','labelCol','experiment','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Dataset Folder Path','href-folder','Dataset Folder Path','','labelCol','dataset_folder_path','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'Archive Folder Path','href-folder','Archive Folder Path','','labelCol','archive_folder_path','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'MyEMSL URL','masked_link','MyEMSL URL','','valueCol','dl_myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Request','detail-report','Request','requested_run/show','labelCol','request','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Jobs','detail-report','Dataset','analysis_job/report/-/-/-/~','labelCol','jobs','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Peak Matching Results','detail-report','Dataset','mts_pm_results/report/~','labelCol','pmresults','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Scan Count','detail-report','Dataset','dataset_scans/show','labelCol','dl_scan_count','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Scan Types','detail-report','Dataset','dataset_scans/report/~','labelCol','dl_scan_types','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'File Info Updated','detail-report','Dataset','dataset_info/report/~','labelCol','dl_file_info_updated','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Instrument','detail-report','Instrument','instrument/report/~','labelCol','dl_instrument','');
INSERT INTO "detail_report_hotlinks" VALUES(12,'+Instrument','detail-report','Instrument','instrument_operation_history/report/~','valueCol','dl_instrument_1','');
INSERT INTO "detail_report_hotlinks" VALUES(13,'QC Link','literal_link','QC Link','','valueCol','dl_qc_link','');
INSERT INTO "detail_report_hotlinks" VALUES(14,'Data Folder Link','literal_link','Data Folder Link','','valueCol','dl_data_folder','');
INSERT INTO "detail_report_hotlinks" VALUES(15,'Archive State','detail-report','Dataset','archive/show','labelCol','dl_archive','');
INSERT INTO "detail_report_hotlinks" VALUES(16,'Factors','detail-report','ID','custom_factors/report/-/-/-/-/-/-/','labelCol','dl_custom_factors','');
INSERT INTO "detail_report_hotlinks" VALUES(17,'+Factors','detail-report','ID','requested_run_factors/param/@/Dataset_ID','valueCol','dl_edit_factors','');
INSERT INTO "detail_report_hotlinks" VALUES(18,'Predefines Triggered','detail-report','ID','data/lr/predefined_analysis/queue/report/-','labelCol','dl_predefines_triggered','');
INSERT INTO "detail_report_hotlinks" VALUES(19,'+Predefines Triggered','detail-report','ID','predefined_analysis_scheduling_queue/report/-/@','valueCol','dl_predefined_jobs','');
INSERT INTO "detail_report_hotlinks" VALUES(20,'State','detail-report','Dataset','capture_job_steps/report/-/-/-/-/-/-/~','labelCol','dl_capture_job_steps','');
INSERT INTO "detail_report_hotlinks" VALUES(21,'QC Metric Stats','detail-report','Instrument','smaqc/report/-/~','labelCol','dl_smaqc_list_report','');
INSERT INTO "detail_report_hotlinks" VALUES(22,'+QC Metric Stats','literal_link','QC Metric Stats','','valueCol','dl_smaqc_data','');
INSERT INTO "detail_report_hotlinks" VALUES(23,'QC 2D','masked_link','QC 2D','','valueCol','dl_qc_2d_link','{"Label":"2D plot of deisotoped data"}');
INSERT INTO "detail_report_hotlinks" VALUES(24,'Organism','detail-report','Organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO "detail_report_hotlinks" VALUES(25,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show','labelCol','dl_eus_proposal','');
INSERT INTO "detail_report_hotlinks" VALUES(26,'Operator','detail-report','Operator','user/report/-/~','labelCol','dl_operator','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(27,'Work Package','detail-report','Work Package','charge_code/report/~','labelCol','dl_work_package','');
INSERT INTO "detail_report_hotlinks" VALUES(28,'LC Cart','detail-report','LC Cart','lc_cart/report/~','labelCol','dl_lc_cart','');
INSERT INTO "detail_report_hotlinks" VALUES(29,'LC Cart Config','detail-report','LC Cart Config','lc_cart_configuration/report/~','labelCol','dl_lc_cart_config','');
INSERT INTO "detail_report_hotlinks" VALUES(30,'PSM Jobs','detail-report','Dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_psm_jobs','');
INSERT INTO "detail_report_hotlinks" VALUES(31,'Dataset','detail-report','Dataset','dataset/show','labelCol','dl_dataset_name','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_state','picker.replace','datasetStatePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(3,'pf_experiment','list-report.Chooser','','Chooser_experiment/report','',',');
INSERT INTO "primary_filter_choosers" VALUES(4,'pf_campaign','list-report.Chooser','','Chooser_campaign/report','',',');
INSERT INTO "primary_filter_choosers" VALUES(5,'pf_created_after','picker.prevDate','','','',',');
COMMIT;
