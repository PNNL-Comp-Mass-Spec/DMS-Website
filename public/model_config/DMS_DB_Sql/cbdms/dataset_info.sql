﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Dataset_Info_List_Report');
INSERT INTO general_params VALUES('detail_report_data_table','V_Dataset_Info_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Dataset');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ID');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_dataset','Dataset','45!','','Dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset_type','Type','20','','Dataset Type','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_scan_types','Scan Types','20','','Scan Types','ContainsText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Dataset','invoke_entity','Dataset','dataset_info/show','');
INSERT INTO list_report_hotlinks VALUES(2,'Comment','min_col_width','value','60','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Dataset','detail-report','Dataset','dataset/show','labelCol','dl_dataset','');
INSERT INTO detail_report_hotlinks VALUES(2,'Instrument','detail-report','Instrument','instrument/show/','labelCol','dl_instrument_name','');
INSERT INTO detail_report_hotlinks VALUES(3,'+Instrument','detail-report','Instrument','instrument_operation_history/report/~','valueCol','dl_instrument_history','');
INSERT INTO detail_report_hotlinks VALUES(4,'Scan Count Total','detail-report','Dataset','dataset_scans/report/~','labelCol','dl_dataset_scans','');
INSERT INTO detail_report_hotlinks VALUES(5,'Dataset Folder Path','href-folder','Dataset Folder Path','','labelCol','dl_dataset_folder','');
INSERT INTO detail_report_hotlinks VALUES(6,'Archive Folder Path','href-folder','Archive Folder Path','','labelCol','dl_archive_folder','');
INSERT INTO detail_report_hotlinks VALUES(8,'TIC_Max_MS','detail-report','Dataset','dataset_info/report/~','labelCol','dl_dataset_info_list_report','');
INSERT INTO detail_report_hotlinks VALUES(9,'QC Link','literal_link','QC Link','','valueCol','dl_qc_link','');
INSERT INTO detail_report_hotlinks VALUES(10,'Data Folder Link','literal_link','Data Folder Link','','valueCol','dl_data_folder',NULL);
COMMIT;
