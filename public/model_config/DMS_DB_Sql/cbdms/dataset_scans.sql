﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Dataset_Scans_List_Report');
INSERT INTO general_params VALUES('detail_report_data_table','V_Dataset_Scans_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Dataset');
INSERT INTO general_params VALUES('list_report_data_sort_dir','Desc');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_dataset','Dataset','45!','','Dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset_type','Type','20','','Dataset Type','ContainsText','text','50','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_scan_type','Scan Type','20','','Scan Type','ContainsText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_scan_filter','Scan Filter','20','','Scan Filter','ContainsText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Dataset','invoke_entity','Dataset','dataset_scans/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Dataset','detail-report','Dataset','dataset/show','labelCol','dataset',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'Instrument','detail-report','Instrument','instrument/show/','labelCol','instrument','');
INSERT INTO detail_report_hotlinks VALUES(3,'Scan Count Total','detail-report','Dataset','dataset_scans/report/~','labelCol','scans_report',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'Elution Time Max','detail-report','Dataset','dataset_info/show','labelCol','dataset_info',NULL);
COMMIT;
