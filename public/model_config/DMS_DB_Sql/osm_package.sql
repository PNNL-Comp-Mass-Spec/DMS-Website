﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_OSM_Package');
INSERT INTO general_params VALUES('my_db_group','package');
INSERT INTO general_params VALUES('list_report_data_table','v_osm_package_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_osm_package_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds, osm_package_cmds');
INSERT INTO general_params VALUES('entry_page_data_table','v_osm_package_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateOSMPackage');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('operations_sproc','UpdateOSMPackage');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','osm_package/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(20,'id','id','int','output','','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(21,'name','Name','varchar','input','128','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(22,'package_type','PackageType','varchar','input','128','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(23,'description','Description','varchar','input','2048','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(24,'keywords','Keywords','varchar','input','2048','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(25,'comment','Comment','varchar','input','1024','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(26,'owner','Owner','varchar','input','128','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(27,'state','State','varchar','input','32','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(28,'sample_prep_request_list','SamplePrepRequestList','varchar','input','4096','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(30,'user_folder_path','UserFolderPath','varchar','input','512','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(31,'<local>','mode','varchar','input','12','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(32,'<local>','message','varchar','output','512','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(33,'<local>','callingUser','varchar','input','128','AddUpdateOSMPackage');
INSERT INTO sproc_args VALUES(34,'osmPackageID','osmPackageID','int','input','','UpdateOSMPackage');
INSERT INTO sproc_args VALUES(35,'<local>','mode','varchar','input','32','UpdateOSMPackage');
INSERT INTO sproc_args VALUES(36,'<local>','message','varchar','output','512','UpdateOSMPackage');
INSERT INTO sproc_args VALUES(37,'<local>','callingUser','varchar','input','128','UpdateOSMPackage');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'name','Name','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'package_type','Package Type','text','50','128','','','General','trim|max_length[128]');
INSERT INTO form_fields VALUES(4,'description','Description','area','','','4','70','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(5,'sample_prep_request_list','Sample Prep Request List','area','','','4','70','','trim|max_length[4096]');
INSERT INTO form_fields VALUES(6,'keywords','Keywords','area','','','4','70','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(7,'comment','Comment','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(8,'owner','Owner','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(9,'state','State','text','32','32','','','Active','trim|max_length[32]');
INSERT INTO form_fields VALUES(10,'user_folder_path','User Folder Path','area','','','4','70','','trim|max_length[512]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'package_type','picker.replace','osmPackageTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'owner','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'state','picker.replace','osmPackageStatePickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'sample_prep_request_list','list-report.helper','','helper_sample_prep_ckbx/report','',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(2,'owner','default_function','GetUser()');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'wiki_page_link','literal_link','wiki_page_link','','valueCol','dl_wiki_page_link',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'sample_prep_requests','link_list','sample_prep_requests','sample_prep_request/show','valueCol','dl_sample_prep_requests',NULL);
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','20','','id','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_name','Name','20','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_keywords','Keywords','20','','keywords','ContainsText','text','2048','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_type','Type','20','','type','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_description','Description','20','','description','ContainsText','text','2048','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_comment','Comment','20','','comment','ContainsText','text','1024','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_owner','Owner','20','','owner','ContainsText','text','181','','');
COMMIT;
