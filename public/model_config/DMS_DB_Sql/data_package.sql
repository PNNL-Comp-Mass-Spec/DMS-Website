﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Data_Package_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','V_Data_Package_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','ID');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateDataPackage');
INSERT INTO general_params VALUES('entry_page_data_table','v_data_package_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('operations_sproc','UpdateDataPackageItems');
INSERT INTO general_params VALUES('my_db_group','package');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('detail_report_cmds','data_package_cmds');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'name','Name','text','128','128','','','','trim|max_length[128]|os_filename|min_length[8]');
INSERT INTO form_fields VALUES(3,'package_type','Package Type','text','50','128','','','General','trim|max_length[128]');
INSERT INTO form_fields VALUES(4,'description','Description','area','','','4','70','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(5,'comment','Comment','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(6,'owner','Owner','text','40','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(7,'requester','Requester','text','40','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(8,'state','State','text','32','32','','','Active','trim|max_length[32]');
INSERT INTO form_fields VALUES(9,'team','Team','text','50','64','','','Public','trim|max_length[64]');
INSERT INTO form_fields VALUES(10,'mass_tag_database','AMT Tag Database','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(11,'prismwiki_link','PRISMWiki Link','text','128','1024','','','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(12,'data_doi','Data DOI','text','128','255','','','','trim|max_length[255]');
INSERT INTO form_fields VALUES(13,'manuscript_doi','Manuscript DOI','text','128','255','','','','trim|max_length[255]');
INSERT INTO form_fields VALUES(14,'creation_params','creationParams','hidden','','','','','','trim');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'prismwiki_link','hide','add');
INSERT INTO form_field_options VALUES(2,'requester','default_function','GetUser()');
INSERT INTO form_field_options VALUES(3,'owner','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'package_type','picker.replace','dataPackageTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'owner','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'requester','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'team','picker.replace','dataPackageTeamPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'state','picker.replace','dataPackageStatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','25!','','Name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_ID','ID','6','','ID','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','25!','','Description','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_state','State','6','','State','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_type','Type','6','','Package Type','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_owner','Owner','6','','Owner','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'ID','invoke_entity','value','data_package/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'Jobs','invoke_entity','ID','data_package_analysis_jobs/report','');
INSERT INTO list_report_hotlinks VALUES(3,'Datasets','invoke_entity','ID','data_package_dataset/report','');
INSERT INTO list_report_hotlinks VALUES(4,'Experiments','invoke_entity','ID','data_package_experiments/report','');
INSERT INTO list_report_hotlinks VALUES(5,'Biomaterial','invoke_entity','ID','data_package_biomaterial/report','');
INSERT INTO list_report_hotlinks VALUES(6,'Total','invoke_entity','ID','data_package_items/report','');
INSERT INTO list_report_hotlinks VALUES(7,'Proposals','invoke_entity','ID','data_package_proposals/report','');
INSERT INTO list_report_hotlinks VALUES(8,'Description','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(9,'Name','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(10,'Data DOI','doi_link','value','','');
INSERT INTO list_report_hotlinks VALUES(11,'Manuscript DOI','doi_link','value','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Share Path','href-folder','Share Path','','valueCol','share_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'Web Path','literal_link','Web Path','','valueCol','web_path',NULL);
INSERT INTO detail_report_hotlinks VALUES(3,'Biomaterial Item Count','detail-report','ID','data_package_biomaterial/report','labelCol','biomaterial_item_count',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'Experiment Item Count','detail-report','ID','data_package_experiments/report','labelCol','experiment_item_count',NULL);
INSERT INTO detail_report_hotlinks VALUES(5,'+Experiment Item Count','detail-report','ID','data_package_experiment_plex_members/report','valueCol','experiment_item_count','');
INSERT INTO detail_report_hotlinks VALUES(6,'Dataset Item Count','detail-report','ID','data_package_dataset/report','labelCol','dataset_item_count',NULL);
INSERT INTO detail_report_hotlinks VALUES(7,'+Dataset Item Count','detail-report','ID','data_package_dataset_files/report','valueCol','dataset_item_count','');
INSERT INTO detail_report_hotlinks VALUES(8,'Analysis Job Item Count','detail-report','ID','data_package_analysis_jobs/report','labelCol','analysis_job_item_count',NULL);
INSERT INTO detail_report_hotlinks VALUES(9,'Total Item Count','detail-report','ID','data_package_items/report','labelCol','total_item_count',NULL);
INSERT INTO detail_report_hotlinks VALUES(10,'PRISM Wiki','literal_link','PRISM Wiki','','valueCol','prism_wiki',NULL);
INSERT INTO detail_report_hotlinks VALUES(11,'MyEMSL URL','masked_link','MyEMSL URL','','valueCol','myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO detail_report_hotlinks VALUES(12,'Campaign Count','detail-report','ID','data_package_campaigns/report','labelCol','campaign_count','');
INSERT INTO detail_report_hotlinks VALUES(13,'EUS Proposals Count','detail-report','ID','data_package_proposals/report','labelCol','eus_proposals_count','');
INSERT INTO detail_report_hotlinks VALUES(14,'EUS User ID','detail-report','EUS User ID','eus_users/show','labelCol','eus_user_id','');
INSERT INTO detail_report_hotlinks VALUES(15,'EUS Proposal ID','detail-report','EUS Proposal ID','eus_proposals/show','labelCol','eus_proposal_id','');
INSERT INTO detail_report_hotlinks VALUES(16,'Data DOI','doi_link','Data DOI','','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(17,'Manuscript DOI','doi_link','Manuscript DOI','','valueCol','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','id','int','output','','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(2,'name','name','varchar','input','128','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(3,'package_type','packageType','varchar','input','128','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(4,'description','description','varchar','input','2048','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(5,'comment','comment','varchar','input','1024','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(6,'owner','owner','varchar','input','128','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(7,'requester','requester','varchar','input','128','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(8,'state','state','varchar','input','32','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(9,'team','team','varchar','input','64','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(10,'mass_tag_database','massTagDatabase','varchar','input','1024','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(11,'data_doi','dataDOI','varchar','input','255','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(12,'manuscript_doi','manuscriptDOI','varchar','input','255','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(13,'creation_params','creationParams','varchar','output','4096','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(14,'prismwiki_link','prismWikiLink','varchar','output','1024','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(15,'<local>','mode','varchar','input','12','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(16,'<local>','message','varchar','output','512','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(17,'<local>','callingUser','varchar','input','128','AddUpdateDataPackage');
INSERT INTO sproc_args VALUES(18,'packageID','packageID','int','input','','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(19,'itemType','itemType','varchar','input','128','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(20,'itemList','itemList','text','input','2147483647','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(21,'comment','comment','varchar','input','512','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(22,'<local>','mode','varchar','input','12','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(23,'removeParents','removeParents','tinyint','input','','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(24,'<local>','message','varchar','output','512','UpdateDataPackageItems');
INSERT INTO sproc_args VALUES(25,'<local>','callingUser','varchar','input','128','UpdateDataPackageItems');
COMMIT;
