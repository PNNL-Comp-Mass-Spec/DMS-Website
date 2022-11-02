﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Data_Package_Dataset_PSM_And_PM_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','Data Pkg, Acq Start');
INSERT INTO general_params VALUES('list_report_data_sort_dir','Desc');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(2,'PSM_Job','invoke_entity','value','analysis_job/show','');
INSERT INTO list_report_hotlinks VALUES(3,'PM_Results_URL','masked_link','value','','{"Label":"Results"}');
INSERT INTO list_report_hotlinks VALUES(4,'QC_Link','masked_link','value','','{"Label":"QC_Link"}');
INSERT INTO list_report_hotlinks VALUES(5,'XIC_FWHM_Q3','literal_link','Instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/XIC_FWHM_Q3/filterDS/QC/inst/','');
INSERT INTO list_report_hotlinks VALUES(6,'Mass_Error_PPM','literal_link','Instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/MassErrorPPM/filterDS/QC/inst/','{"Decimals":"3"}');
INSERT INTO list_report_hotlinks VALUES(7,'Mass_Error_AMTs','column_tooltip','value','Median of precursor mass error (ppm), from AMT peak matching','{"Decimals":"3"}');
INSERT INTO list_report_hotlinks VALUES(8,'AMTs_10pct_FDR','literal_link','Instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/AMTs_10pct_FDR/filterDS/QC/inst/','');
INSERT INTO list_report_hotlinks VALUES(9,'Unique Peptides','literal_link','Instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/P_2C/filterDS/QC/inst/','');
INSERT INTO list_report_hotlinks VALUES(10,'PhosphoPep','literal_link','Instrument','http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/Phos_2C/filterDS/STY/inst/','');
INSERT INTO list_report_hotlinks VALUES(11,'Dataset_ID','invoke_entity','value','smaqc/show/','');
INSERT INTO list_report_hotlinks VALUES(12,'+Unique Peptides','column_tooltip','value','Number of tryptic peptides; unique peptide count','');
INSERT INTO list_report_hotlinks VALUES(13,'+XIC_FWHM_Q3','column_tooltip','value','75%ile of peak widths for the wide XICs','');
INSERT INTO list_report_hotlinks VALUES(14,'+Mass_Error_PPM','column_tooltip','value','Median of precursor mass error (ppm), from MS/MS ID','');
INSERT INTO list_report_hotlinks VALUES(15,'+AMTs_10pct_FDR','column_tooltip','value','Number of LC-MS features','');
INSERT INTO list_report_hotlinks VALUES(16,'PctTryptic','column_tooltip','value','Ratio of unique fully tryptic peptides / total unique peptides','');
INSERT INTO list_report_hotlinks VALUES(17,'PctMissedClvg','column_tooltip','value','Ratio of total missed cleavages (among unique peptides) / total unique peptides (P_4B)','');
INSERT INTO list_report_hotlinks VALUES(18,'PSMs','column_tooltip','value','Number of tryptic peptides; total spectra count','');
INSERT INTO list_report_hotlinks VALUES(19,'KeratinPep','column_tooltip','value','Number of keratin peptides (full or partial trypsin); total spectra count (Keratin_2A)','');
INSERT INTO list_report_hotlinks VALUES(20,'+PhosphoPep','column_tooltip','value','Number of tryptic phosphopeptides; unique peptide count','');
INSERT INTO list_report_hotlinks VALUES(21,'TrypsinPep','column_tooltip','value','Number of peptides from trypsin; total spectra count','');
INSERT INTO list_report_hotlinks VALUES(22,'Data Pkg','invoke_entity','value','data_package/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_data_package_id','Data Pkg','4!','','Data Pkg','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_most_recent_weeks','Most recent weeks','3!','','Acq Start','MostRecentWeeks','text','32','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_psm_tool','PSM_Tool','20','','PSM_Tool','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_dataset','Dataset','20!','','Dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_pm_database','PM_Database','20','','PM_Database','ContainsText','text','128','','');
COMMIT;
