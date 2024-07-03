﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_data_package_analysis_job_psm_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','data_pkg, job');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('detail_report_data_table','v_analysis_job_psm_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','job');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_data_package_id','Data Pkg','5!','','data_pkg','Equals','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_job','Job','12','','job','Equals','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_state','State','20','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_tool','Tool','32','','tool','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_dataset','Dataset','20!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_experiment','Experiment','15!','','experiment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_campaign','Campaign','20','','campaign','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_param_file','Param File','60','','param_file','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_instrument','Instrument','60','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_protein_collection_list','Protein Collection List','60','','protein_collection_list','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(11,'pf_comment','Comment','60','','comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(12,'pf_job_request','Request','12','','job_request','Equals','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_state','picker.replace','analysisJobStatePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(2,'pf_tool','picker.replace','analysisToolPickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','data_package_analysis_job_psm/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(3,'job_request','invoke_entity','value','analysis_job_request/show','');
INSERT INTO list_report_hotlinks VALUES(4,'comment','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(5,'unique_peptides_msgf','column_tooltip','value','Unique peptide count passing the MS-GF SpecProb threshold','');
INSERT INTO list_report_hotlinks VALUES(6,'total_psms_msgf','column_tooltip','value','Total peptides passing the MS-GF SpecProb threshold','');
INSERT INTO list_report_hotlinks VALUES(7,'unique_proteins_msgf','column_tooltip','value','Unique protein count passing the MS-GF SpecProb threshold','');
INSERT INTO list_report_hotlinks VALUES(8,'total_psms_fdr','column_tooltip','value','Total peptides passing the FDR threshold','');
INSERT INTO list_report_hotlinks VALUES(9,'unique_peptides_fdr','column_tooltip','value','Unique peptide count passing the FDR threshold','');
INSERT INTO list_report_hotlinks VALUES(10,'unique_proteins_fdr','column_tooltip','value','Unique protein count passing the FDR threshold','');
INSERT INTO list_report_hotlinks VALUES(11,'unique_tryptic_peptides','column_tooltip','value','Unique number of peptides that are fully or partially tryptic','');
INSERT INTO list_report_hotlinks VALUES(12,'pct_tryptic','column_tooltip','value','Unique Tryptic Peptides divided by Unique Peptides FDR','');
INSERT INTO list_report_hotlinks VALUES(13,'pct_missed_clvg','column_tooltip','value','Percent of unique peptides with a missed cleavage (internal K or R)','');
INSERT INTO list_report_hotlinks VALUES(14,'keratin_pep','column_tooltip','value','Number of unique peptides that come from Keratin proteins','');
INSERT INTO list_report_hotlinks VALUES(15,'trypsin_pep','column_tooltip','value','Number of unique peptides that come from Trypsin proteins','');
INSERT INTO list_report_hotlinks VALUES(16,'pct_missing_nterm_rep_ion','column_tooltip','value','Percent of filter-passing PSMs that are missing a reporter ion on the peptide N-terminus (only applicable if TMT or iTRAQ was a dynamic mod)','');
INSERT INTO list_report_hotlinks VALUES(17,'pct_missing_rep_ion','column_tooltip','value','Percent of FDR filter-passing PSMs that are missing a reporter ion from any of the lysine residues or from the peptide N-terminus (only applicable if TMT or iTRAQ was a dynamic mod)','');
INSERT INTO list_report_hotlinks VALUES(18,'phospho_pep','column_tooltip','value','Unique phosphopeptides passing the FDR threshold (any S, T, or Y with phospho)','');
INSERT INTO list_report_hotlinks VALUES(19,'cterm_k_phospho_pep','column_tooltip','value','Number of distinct phosphopeptides with K on the C-terminus','');
INSERT INTO list_report_hotlinks VALUES(20,'cterm_r_phospho_pep','column_tooltip','value','Number of distinct phosphopeptides with R on the C-terminus','');
INSERT INTO list_report_hotlinks VALUES(21,'phospho_pct_missed_clvg','column_tooltip','value','Percent of distinct phosphopeptides with a missed cleavage (internal K or R)','');
INSERT INTO list_report_hotlinks VALUES(22,'acetyl_pep','column_tooltip','value','Unique acetylated peptides passing the FDR threshold (any K with acetyl)','');
INSERT INTO list_report_hotlinks VALUES(23,'data_pkg','invoke_entity','value','data_package/show/','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'dataset','detail-report','dataset','dataset/show','labelCol','dataset','');
INSERT INTO detail_report_hotlinks VALUES(2,'results_folder_path','href-folder','results_folder_path','','labelCol','results_folder_path','');
INSERT INTO detail_report_hotlinks VALUES(3,'request','detail-report','request','analysis_job_request/show','labelCol','request','');
INSERT INTO detail_report_hotlinks VALUES(4,'state','detail-report','job','pipeline_job_steps/report','labelCol','state','');
INSERT INTO detail_report_hotlinks VALUES(5,'tool_name','detail-report','tool_name','pipeline_script/report/~','labelCol','tool_name','');
INSERT INTO detail_report_hotlinks VALUES(6,'settings_file','detail-report','settings_file','settings_files/report/-/~','labelCol','settings_file','');
INSERT INTO detail_report_hotlinks VALUES(7,'mts_pt_db_count','detail-report','job','mts_pt_db_jobs/report','labelCol','PT_DBs','');
INSERT INTO detail_report_hotlinks VALUES(8,'mts_mt_db_count','detail-report','job','mts_mt_db_jobs/report','labelCol','MT_DBs','');
INSERT INTO detail_report_hotlinks VALUES(9,'peak_matching_results','detail-report','job','mts_pm_results/report/-/','labelCol','pmresults','');
INSERT INTO detail_report_hotlinks VALUES(10,'data_folder_link','literal_link','data_folder_link','','valueCol','dl_data_folder','');
INSERT INTO detail_report_hotlinks VALUES(11,'job','detail-report','job','analysis_job/show','labelCol','job_steps','');
INSERT INTO detail_report_hotlinks VALUES(12,'owner','detail-report','owner','user/show','labelCol','owner','');
INSERT INTO detail_report_hotlinks VALUES(13,'total_psms_msgf_filtered','detail-report','dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_analysis_job_psm_ds1','');
INSERT INTO detail_report_hotlinks VALUES(14,'total_psms_fdr_filtered','detail-report','dataset','analysis_job_psm/report/-/-/-/~','labelCol','dl_analysis_job_psm_ds2','');
INSERT INTO detail_report_hotlinks VALUES(15,'+dataset','detail-report','dataset','analysis_job_psm/report/-/-/-/~','valueCol','dl_analysis_job_psm_ds3','');
INSERT INTO detail_report_hotlinks VALUES(16,'experiment','detail-report','experiment','experiment/show','labelCol','dl_experiment','');
INSERT INTO detail_report_hotlinks VALUES(17,'+experiment','detail-report','experiment','analysis_job_psm/report/-/-/-/-/~','valueCol','dl_analysis_job_psm_exp','');
COMMIT;
