﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('entry_page_data_id_col','ID');
INSERT INTO general_params VALUES('entry_sproc','AddJobRequestPSM');
INSERT INTO general_params VALUES('entry_submission_cmds','analysis_job_request_psm');
INSERT INTO general_params VALUES('operations_sproc','GetPSMJobDefinitions');
INSERT INTO general_params VALUES('cmd_buttons','suppress');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(81,'requestID','requestID','int','output','','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(82,'requestName','requestName','varchar','input','128','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(83,'datasets','datasets','varchar','output','2147483647','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(84,'comment','comment','varchar','input','512','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(85,'ownerPRN','ownerPRN','varchar','input','64','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(86,'organismName','organismName','varchar','input','128','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(87,'protCollNameList','protCollNameList','varchar','input','4000','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(88,'protCollOptionsList','protCollOptionsList','varchar','input','256','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(89,'toolName','toolName','varchar','input','64','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(90,'jobTypeName','jobTypeName','varchar','input','64','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(91,'ModificationDynMetOx','ModificationDynMetOx','varchar','input','24','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(92,'ModificationStatCysAlk','ModificationStatCysAlk','varchar','input','24','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(93,'ModificationDynSTYPhos','ModificationDynSTYPhos','varchar','input','24','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(94,'<local>','mode','varchar','input','12','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(95,'<local>','message','varchar','output','512','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(96,'<local>','callingUser','varchar','input','128','AddJobRequestPSM');
INSERT INTO sproc_args VALUES(97,'datasets','datasets','varchar','output','2147483647','GetPSMJobDefinitions');
INSERT INTO sproc_args VALUES(98,'metadata','metadata','varchar','output','2048','GetPSMJobDefinitions');
INSERT INTO sproc_args VALUES(99,'defaults','defaults','varchar','output','2048','GetPSMJobDefinitions');
INSERT INTO sproc_args VALUES(100,'<local>','mode','varchar','input','12','GetPSMJobDefinitions');
INSERT INTO sproc_args VALUES(101,'<local>','message','varchar','output','512','GetPSMJobDefinitions');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'requestID','Request ID','non-edit','','','','','0','trim|max_length[12]');
INSERT INTO form_fields VALUES(2,'requestName','Request Name','text','50','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(3,'datasets','Datasets','area','','','9','100','','trim|max_length[2147483647]|required');
INSERT INTO form_fields VALUES(5,'comment','Comment','area','','','2','100','','trim|max_length[512]');
INSERT INTO form_fields VALUES(6,'ownerPRN','Owner PRN','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(7,'IgnoreMe','Defaults','action','','','','','entry.analysis_job_request_psm.getJobDefaults():Get suggested values:for Search Database, Tool, and Modifications based on analysis of datasets','');
INSERT INTO form_fields VALUES(8,'organismName','Organism','text','50','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(9,'protCollNameList','Protein Collection List','area','','','2','70','','trim|max_length[4000]|required');
INSERT INTO form_fields VALUES(10,'protCollOptionsList','Protein Options List','area','','','2','70','seq_direction=decoy','trim|max_length[256]|required');
INSERT INTO form_fields VALUES(11,'toolName','Tool Name','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(12,'jobTypeName','Job Type Name','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(14,'ModificationDynMetOx','DynMetOx','checkbox','','','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(15,'ModificationStatCysAlk','StatCysAlk','checkbox','','','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(16,'ModificationDynSTYPhos','DynSTYPhos','checkbox','','','','','','trim|max_length[24]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'ownerPRN','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'ModificationDynMetOx','section','Modifications');
INSERT INTO form_field_options VALUES(3,'requestID','section','General');
INSERT INTO form_field_options VALUES(4,'IgnoreMe','section','Defaults');
INSERT INTO form_field_options VALUES(5,'organismName','section','Search Database');
INSERT INTO form_field_options VALUES(6,'toolName','section','Search Tool');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'datasets','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/52','',',','Choose by Dataset Name:');
INSERT INTO form_field_choosers VALUES(2,'datasets','list-report.helper','','helper_data_package_dataset_ckbx/report','',',','Choose by Data Package:');
INSERT INTO form_field_choosers VALUES(3,'protCollNameList','list-report.helper','','helper_protein_collection/report','organismName',',','');
INSERT INTO form_field_choosers VALUES(4,'protCollOptionsList','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'ownerPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'jobTypeName','picker.list','psmJobTypePicklist','','',',','');
INSERT INTO form_field_choosers VALUES(7,'toolName','picker.list','psmToolNamePicklist','','',',','');
INSERT INTO form_field_choosers VALUES(8,'organismName','list-report.helper','','helper_organism/report','',',','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'preview','cmd','Preview','Validate the options and view the parameter file that would be used','');
COMMIT;
