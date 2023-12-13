﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('entry_page_data_id_col','ID');
INSERT INTO general_params VALUES('entry_sproc','add_job_request_psm');
INSERT INTO general_params VALUES('entry_submission_cmds','analysis_job_request_psm');
INSERT INTO general_params VALUES('operations_sproc','get_psm_job_definitions');
INSERT INTO general_params VALUES('cmd_buttons','suppress');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(81,'request_id','requestID','int','output','','add_job_request_psm');
INSERT INTO sproc_args VALUES(82,'request_name','requestName','varchar','input','128','add_job_request_psm');
INSERT INTO sproc_args VALUES(83,'datasets','datasets','varchar','output','2147483647','add_job_request_psm');
INSERT INTO sproc_args VALUES(84,'comment','comment','varchar','input','512','add_job_request_psm');
INSERT INTO sproc_args VALUES(85,'owner_username','ownerUsername','varchar','input','64','add_job_request_psm');
INSERT INTO sproc_args VALUES(87,'prot_coll_name_list','protCollNameList','varchar','input','4000','add_job_request_psm');
INSERT INTO sproc_args VALUES(88,'prot_coll_options_list','protCollOptionsList','varchar','input','256','add_job_request_psm');
INSERT INTO sproc_args VALUES(89,'tool_name','toolName','varchar','input','64','add_job_request_psm');
INSERT INTO sproc_args VALUES(90,'job_type_name','jobTypeName','varchar','input','64','add_job_request_psm');
INSERT INTO sproc_args VALUES(91,'modification_dyn_met_ox','ModificationDynMetOx','varchar','input','24','add_job_request_psm');
INSERT INTO sproc_args VALUES(92,'modification_stat_cys_alk','ModificationStatCysAlk','varchar','input','24','add_job_request_psm');
INSERT INTO sproc_args VALUES(93,'modification_dyn_styphos','ModificationDynSTYPhos','varchar','input','24','add_job_request_psm');
INSERT INTO sproc_args VALUES(94,'<local>','mode','varchar','input','12','add_job_request_psm');
INSERT INTO sproc_args VALUES(95,'<local>','message','varchar','output','512','add_job_request_psm');
INSERT INTO sproc_args VALUES(96,'<local>','callingUser','varchar','input','128','add_job_request_psm');
INSERT INTO sproc_args VALUES(97,'datasets','datasets','varchar','output','2147483647','get_psm_job_definitions');
INSERT INTO sproc_args VALUES(98,'metadata','metadata','varchar','output','2048','get_psm_job_definitions');
INSERT INTO sproc_args VALUES(99,'defaults','defaults','varchar','output','2048','get_psm_job_definitions');
INSERT INTO sproc_args VALUES(100,'<local>','mode','varchar','input','12','get_psm_job_definitions');
INSERT INTO sproc_args VALUES(101,'<local>','message','varchar','output','512','get_psm_job_definitions');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'request_id','Request ID','non-edit','','','','','0','trim|max_length[12]');
INSERT INTO form_fields VALUES(2,'request_name','Request Name','text','50','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(3,'datasets','Datasets','area','','','9','100','','trim|max_length[2147483647]|required');
INSERT INTO form_fields VALUES(5,'comment','Comment','area','','','2','100','','trim|max_length[512]');
INSERT INTO form_fields VALUES(6,'owner_username','Owner Username','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(7,'ignore_me','Defaults','action','','','','','entryCmds.analysis_job_request_psm.getJobDefaults():Get suggested values:for Search Database, Tool, and Modifications based on analysis of datasets','');
INSERT INTO form_fields VALUES(8,'organism_name','Organism','text','50','128','','','','trim|max_length[128]|required');
INSERT INTO form_fields VALUES(9,'prot_coll_name_list','Protein Collection List','area','','','2','70','','trim|max_length[4000]|required');
INSERT INTO form_fields VALUES(10,'prot_coll_options_list','Protein Options List','area','','','2','70','seq_direction=decoy','trim|max_length[256]|required');
INSERT INTO form_fields VALUES(11,'tool_name','Tool Name','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(12,'job_type_name','Job Type Name','text','50','64','','','','trim|max_length[64]|required');
INSERT INTO form_fields VALUES(14,'modification_dyn_met_ox','DynMetOx','checkbox','','','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(15,'modification_stat_cys_alk','StatCysAlk','checkbox','','','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(16,'modification_dyn_styphos','DynSTYPhos','checkbox','','','','','','trim|max_length[24]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'owner_username','default_function','GetUser()');
INSERT INTO form_field_options VALUES(2,'modification_dyn_met_ox','section','Modifications');
INSERT INTO form_field_options VALUES(3,'request_id','section','General');
INSERT INTO form_field_options VALUES(4,'ignore_me','section','Defaults');
INSERT INTO form_field_options VALUES(5,'organism_name','section','Search Database');
INSERT INTO form_field_options VALUES(6,'tool_name','section','Search Tool');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'datasets','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/52','',',','Choose by Dataset Name:');
INSERT INTO form_field_choosers VALUES(2,'datasets','list-report.helper','','helper_data_package_dataset_ckbx/report','',',','Choose by Data Package:');
INSERT INTO form_field_choosers VALUES(3,'prot_coll_name_list','list-report.helper','','helper_protein_collection/report','organism_name',',','');
INSERT INTO form_field_choosers VALUES(4,'prot_coll_options_list','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'owner_username','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'job_type_name','picker.list','psmJobTypePicklist','','',',','');
INSERT INTO form_field_choosers VALUES(7,'tool_name','picker.list','psmToolNamePicklist','','',',','');
INSERT INTO form_field_choosers VALUES(8,'organism_name','list-report.helper','','helper_organism/report','',',','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO entry_commands VALUES(1,'preview','cmd','Preview','Validate the options and view the parameter file that would be used','');
COMMIT;
