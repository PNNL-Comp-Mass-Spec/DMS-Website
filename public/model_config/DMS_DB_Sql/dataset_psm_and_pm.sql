﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_dataset_psm_and_pm_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','dataset_id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(2,'psm_job','invoke_entity','value','analysis_job/show','');
INSERT INTO list_report_hotlinks VALUES(3,'pm_results_url','masked_link','value','','{"Label":"Results"}');
INSERT INTO list_report_hotlinks VALUES(4,'qc_link','masked_link','value','','{"Label":"QC_Link"}');
INSERT INTO list_report_hotlinks VALUES(5,'xic_fwhm_q3','literal_link','instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/xic_fwhm_q3/filterds/qc/inst/','');
INSERT INTO list_report_hotlinks VALUES(6,'mass_error_ppm','literal_link','instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/masserrorppm/filterds/qc/inst/','{"Decimals":"3"}');
INSERT INTO list_report_hotlinks VALUES(7,'mass_error_amts','column_tooltip','value','Median of precursor mass error (ppm), from AMT peak matching','{"Decimals":"3"}');
INSERT INTO list_report_hotlinks VALUES(8,'amts_10pct_fdr','literal_link','instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/amts_10pct_fdr/filterds/qc/inst/','');
INSERT INTO list_report_hotlinks VALUES(9,'unique_peptides','literal_link','instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/p_2c/filterds/qc/inst/','');
INSERT INTO list_report_hotlinks VALUES(10,'phospho_pep','literal_link','instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/phos_2c/filterds/sty/inst/','');
INSERT INTO list_report_hotlinks VALUES(11,'dataset_id','invoke_entity','value','smaqc/show/','');
INSERT INTO list_report_hotlinks VALUES(12,'+unique_peptides','column_tooltip','value','Number of tryptic peptides; unique peptide count','');
INSERT INTO list_report_hotlinks VALUES(13,'+xic_fwhm_q3','column_tooltip','value','75%ile of peak widths for the wide XICs','');
INSERT INTO list_report_hotlinks VALUES(14,'+mass_error_ppm','column_tooltip','value','Median of precursor mass error (ppm), from MS/MS ID','');
INSERT INTO list_report_hotlinks VALUES(15,'+amts_10pct_fdr','column_tooltip','value','Number of LC-MS features','');
INSERT INTO list_report_hotlinks VALUES(16,'pct_tryptic','column_tooltip','value','Ratio of unique fully tryptic peptides / total unique peptides','');
INSERT INTO list_report_hotlinks VALUES(17,'pct_missed_clvg','column_tooltip','value','Ratio of total missed cleavages (among unique peptides) / total unique peptides (P_4B)','');
INSERT INTO list_report_hotlinks VALUES(18,'psms','column_tooltip','value','Number of tryptic peptides; total spectra count','');
INSERT INTO list_report_hotlinks VALUES(19,'keratin_pep','column_tooltip','value','Number of keratin peptides (full or partial trypsin); total spectra count (Keratin_2A)','');
INSERT INTO list_report_hotlinks VALUES(20,'+phospho_pep','column_tooltip','value','Number of tryptic phosphopeptides; unique peptide count','');
INSERT INTO list_report_hotlinks VALUES(21,'trypsin_pep','column_tooltip','value','Number of peptides from trypsin; total spectra count','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_most_recent_weeks','Most recent weeks','3!','','acq_start','MostRecentWeeks','text','32','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_psm_tool','PSM_Tool','20','','psm_tool','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_instrument','Instrument','20','','instrument','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_campaign','Campaign','20','','campaign','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_dataset','Dataset','15!','','dataset','ContainsTextTPO','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_pm_database','PM_Database','20','','pm_database','ContainsText','text','128','','');
COMMIT;
