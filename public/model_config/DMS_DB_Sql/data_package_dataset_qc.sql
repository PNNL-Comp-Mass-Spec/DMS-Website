﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_data_package_dataset_qc_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','data_pkg_id, dataset_id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(3,'state','color_label','','','{"Capture Failed":"bad_clr"}');
INSERT INTO list_report_hotlinks VALUES(4,'instrument','invoke_entity','value','instrument_operation_history/report','');
INSERT INTO list_report_hotlinks VALUES(5,'qc_link','image_link','value','index.html','{"width":"400"}');
INSERT INTO list_report_hotlinks VALUES(6,'qc_2d','image_link','value','index.html','{"width":"340"}');
INSERT INTO list_report_hotlinks VALUES(7,'qc_decontools','image_link','value','index.html','{"width":"340"}');
INSERT INTO list_report_hotlinks VALUES(8,'data_pkg_id','invoke_entity','value','data_package/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_data_package_id','Data Pkg ID','8!','','data_pkg_id','Equals','text','24','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_dataset_id','Dataset ID','8!','','dataset_id','Equals','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_dataset','Dataset','40!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_state','State','6!','','state','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_instrument','Instrument','32','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_experiment','Experiment','32','','experiment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_campaign','Campaign','32','','campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_most_recent_weeks','Most Recent Weeks','3!','','created','MostRecentWeeks','text','20','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_created_after','Created After','8','','created','LaterThan','text','20','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_minimum_acq_length','Min. Acq Length','4!','','acq_length','GreaterThanOrEqualTo','text','20','','');
INSERT INTO list_report_primary_filter VALUES(11,'pf_batch','Batch','6!','','batch','Equals','text','20','','');
COMMIT;