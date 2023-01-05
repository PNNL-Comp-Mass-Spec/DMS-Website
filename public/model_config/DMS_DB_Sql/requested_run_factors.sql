﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_sproc','GetRequestedRunFactorsForEdit');
INSERT INTO general_params VALUES('list_report_cmds','requested_run_factors_cmds');
INSERT INTO general_params VALUES('list_report_cmds_url','requested_run_factors/operation');
INSERT INTO general_params VALUES('operations_sproc','UpdateRequestedRunFactors');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(5,'factorList','factorList','text','input','2147483647','UpdateRequestedRunFactors');
INSERT INTO sproc_args VALUES(6,'<local>','message','varchar','output','512','UpdateRequestedRunFactors');
INSERT INTO sproc_args VALUES(7,'<local>','callingUser','varchar','input','128','UpdateRequestedRunFactors');
INSERT INTO sproc_args VALUES(8,'item_list','itemList','text','input','2147483647','GetRequestedRunFactorsForEdit');
INSERT INTO sproc_args VALUES(9,'item_type','itemType','varchar','input','32','GetRequestedRunFactorsForEdit');
INSERT INTO sproc_args VALUES(10,'info_only','infoOnly','tinyint','input','','GetRequestedRunFactorsForEdit');
INSERT INTO sproc_args VALUES(11,'<local>','message','varchar','output','512','GetRequestedRunFactorsForEdit');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'item_list','Item List','area','','','5','100','','trim|max_length[2147483647]');
INSERT INTO form_fields VALUES(2,'item_type','Item Type','text','32','32','','','Batch_ID','trim|max_length[32]');
INSERT INTO form_fields VALUES(3,'info_only','Info Only','hidden','','','','','0','trim|max_length[12]');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Sel','CHECKBOX','Request','','');
INSERT INTO list_report_hotlinks VALUES(2,'@exclude','inplace_edit','Request','ajax','["Sel","BatchID", "Name", "Status", "Request","Experiment", "Dataset"]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'item_type','picker.replace','itemTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'item_list','list-report.helper','','helper_requested_run_batch_ckbx/report','',',','Choose Batches');
INSERT INTO form_field_choosers VALUES(3,'item_list','list-report.helper','','helper_requested_run_ckbx/report','',',','Choose Requests');
INSERT INTO form_field_choosers VALUES(4,'item_list','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/52','',',','Choose Datasets');
INSERT INTO form_field_choosers VALUES(5,'item_list','list-report.helper','','helper_experiment_ckbx/report','',',','Choose Experiments');
COMMIT;
