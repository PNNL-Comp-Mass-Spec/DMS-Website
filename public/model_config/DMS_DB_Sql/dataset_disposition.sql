﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_dataset_disposition');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_cmds','dataset_disposition_cmds');
INSERT INTO general_params VALUES('list_report_cmds_url','/dataset_disposition/operation');
INSERT INTO general_params VALUES('operations_sproc','UpdateDatasetDispositions');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_dataset','Dataset','45!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_instrument','Instrument','32','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_lc_cart','LC Cart','32','','lc_cart','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_oper','Oper.','24','','oper','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_batch','Batch','12','','batch','Equals','text','24','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_state','State','32','','state','ContainsText','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(2,'pf_lc_cart','picker.replace','lcCartPickList','','',',');
INSERT INTO primary_filter_choosers VALUES(3,'pf_state','picker.replace','datasetStatePickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'sel','CHECKBOX','id','','');
INSERT INTO list_report_hotlinks VALUES(2,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(3,'state','color_label','','','{"Capture Failed":"bad_clr"}');
INSERT INTO list_report_hotlinks VALUES(6,'instrument','invoke_entity','value','instrument_operation_history/report','');
INSERT INTO list_report_hotlinks VALUES(7,'qc_link','image_link','value','index.html','{"width":"400"}');
INSERT INTO list_report_hotlinks VALUES(8,'smaqc','masked_link','value','','{"Label":"Metrics"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'datasetIDList','datasetIDList','varchar','input','6000','UpdateDatasetDispositions');
INSERT INTO sproc_args VALUES(2,'rating','rating','varchar','input','64','UpdateDatasetDispositions');
INSERT INTO sproc_args VALUES(3,'comment','comment','varchar','input','512','UpdateDatasetDispositions');
INSERT INTO sproc_args VALUES(4,'recycleRequest','recycleRequest','varchar','input','32','UpdateDatasetDispositions');
INSERT INTO sproc_args VALUES(5,'<local>','mode','varchar','input','12','UpdateDatasetDispositions');
INSERT INTO sproc_args VALUES(6,'<local>','message','varchar','output','512','UpdateDatasetDispositions');
INSERT INTO sproc_args VALUES(7,'<local>','callingUser','varchar','input','128','UpdateDatasetDispositions');
COMMIT;
