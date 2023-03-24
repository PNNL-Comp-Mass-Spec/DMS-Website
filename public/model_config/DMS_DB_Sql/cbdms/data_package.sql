﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_data_package_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_data_package_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','add_update_data_package');
INSERT INTO general_params VALUES('entry_page_data_table','v_data_package_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('operations_sproc','update_data_package_items');
INSERT INTO general_params VALUES('my_db_group','package');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
INSERT INTO general_params VALUES('detail_report_cmds','data_package_cmds');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_cols','id, name, description, owner, team, state, package_type, requester, total, jobs, datasets, experiments, biomaterial, last_modified, created');
INSERT INTO general_params VALUES('detail_report_data_cols','id, name, package_type, description, comment, owner, requester, team, created, last_modified, state, package_file_folder, share_path, web_path, amt_tag_database, biomaterial_count, experiment_count, eus_proposal_count, dataset_count, analysis_job_count, campaign_count, total_item_count');
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
INSERT INTO form_field_choosers VALUES(2,'owner','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'requester','picker.replace','userUsernamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'team','picker.replace','dataPackageTeamPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'state','picker.replace','dataPackageStatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','25!','','name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_ID','ID','6','','id','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','25!','','description','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_state','State','6','','state','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_type','Type','6','','package_type','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_owner','Owner','6','','owner','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','data_package/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'jobs','invoke_entity','id','data_package_analysis_jobs/report','');
INSERT INTO list_report_hotlinks VALUES(3,'datasets','invoke_entity','id','data_package_dataset/report','');
INSERT INTO list_report_hotlinks VALUES(4,'experiments','invoke_entity','id','data_package_experiments/report','');
INSERT INTO list_report_hotlinks VALUES(5,'biomaterial','invoke_entity','id','data_package_biomaterial/report','');
INSERT INTO list_report_hotlinks VALUES(6,'total','invoke_entity','id','data_package_items/report','');
INSERT INTO list_report_hotlinks VALUES(7,'proposals','invoke_entity','id','data_package_proposals/report','');
INSERT INTO list_report_hotlinks VALUES(8,'description','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(9,'name','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(10,'data_doi','doi_link','value','','');
INSERT INTO list_report_hotlinks VALUES(11,'manuscript_doi','doi_link','value','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'share_path','href-folder','share_path','','valueCol','share_path','');
INSERT INTO detail_report_hotlinks VALUES(2,'web_path','literal_link','web_path','','valueCol','web_path','');
INSERT INTO detail_report_hotlinks VALUES(3,'biomaterial_count','detail-report','id','data_package_biomaterial/report','labelCol','biomaterial_count','');
INSERT INTO detail_report_hotlinks VALUES(4,'experiment_count','detail-report','id','data_package_experiments/report','labelCol','experiment_count','');
INSERT INTO detail_report_hotlinks VALUES(5,'+experiment_count','detail-report','id','data_package_experiment_plex_members/report','valueCol','experiment_count','');
INSERT INTO detail_report_hotlinks VALUES(6,'dataset_count','detail-report','id','data_package_dataset/report','labelCol','dataset_count','');
INSERT INTO detail_report_hotlinks VALUES(7,'+dataset_count','detail-report','id','data_package_dataset_files/report','valueCol','dataset_count','');
INSERT INTO detail_report_hotlinks VALUES(8,'analysis_job_count','detail-report','id','data_package_analysis_jobs/report','labelCol','analysis_job_count','');
INSERT INTO detail_report_hotlinks VALUES(9,'total_item_count','detail-report','id','data_package_items/report','labelCol','total_item_count','');
INSERT INTO detail_report_hotlinks VALUES(10,'prism_wiki','literal_link','prism_wiki','','valueCol','prism_wiki','');
INSERT INTO detail_report_hotlinks VALUES(11,'campaign_count','detail-report','id','data_package_campaigns/report','labelCol','campaign_count','');
INSERT INTO detail_report_hotlinks VALUES(12,'eus_proposal_count','detail-report','id','data_package_proposals/report','labelCol','eus_proposal_count','');
INSERT INTO detail_report_hotlinks VALUES(13,'eus_user_id','detail-report','eus_user_id','eus_users/show','labelCol','eus_user_id','');
INSERT INTO detail_report_hotlinks VALUES(14,'eus_proposal_id','detail-report','eus_proposal_id','eus_proposals/show','labelCol','eus_proposal_id','');
INSERT INTO detail_report_hotlinks VALUES(15,'data_doi','doi_link','data_doi','','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(16,'manuscript_doi','doi_link','manuscript_doi','','valueCol','','');
INSERT INTO detail_report_hotlinks VALUES(17,'owner','detail-report','owner','user/report/-/StartsWith__@','labelCol','owner','');
INSERT INTO detail_report_hotlinks VALUES(18,'requester','detail-report','requester','user/report/-/StartsWith__@','labelCol','requester','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','id','int','output','','add_update_data_package');
INSERT INTO sproc_args VALUES(2,'name','name','varchar','input','128','add_update_data_package');
INSERT INTO sproc_args VALUES(3,'package_type','packageType','varchar','input','128','add_update_data_package');
INSERT INTO sproc_args VALUES(4,'description','description','varchar','input','2048','add_update_data_package');
INSERT INTO sproc_args VALUES(5,'comment','comment','varchar','input','1024','add_update_data_package');
INSERT INTO sproc_args VALUES(6,'owner','owner','varchar','input','128','add_update_data_package');
INSERT INTO sproc_args VALUES(7,'requester','requester','varchar','input','128','add_update_data_package');
INSERT INTO sproc_args VALUES(8,'state','state','varchar','input','32','add_update_data_package');
INSERT INTO sproc_args VALUES(9,'team','team','varchar','input','64','add_update_data_package');
INSERT INTO sproc_args VALUES(10,'mass_tag_database','massTagDatabase','varchar','input','1024','add_update_data_package');
INSERT INTO sproc_args VALUES(11,'data_doi','dataDOI','varchar','input','255','add_update_data_package');
INSERT INTO sproc_args VALUES(12,'manuscript_doi','manuscriptDOI','varchar','input','255','add_update_data_package');
INSERT INTO sproc_args VALUES(13,'creation_params','creationParams','varchar','output','4096','add_update_data_package');
INSERT INTO sproc_args VALUES(14,'prismwiki_link','prismWikiLink','varchar','output','1024','add_update_data_package');
INSERT INTO sproc_args VALUES(15,'<local>','mode','varchar','input','12','add_update_data_package');
INSERT INTO sproc_args VALUES(16,'<local>','message','varchar','output','512','add_update_data_package');
INSERT INTO sproc_args VALUES(17,'<local>','callingUser','varchar','input','128','add_update_data_package');
INSERT INTO sproc_args VALUES(18,'packageID','packageID','int','input','','update_data_package_items');
INSERT INTO sproc_args VALUES(19,'itemType','itemType','varchar','input','128','update_data_package_items');
INSERT INTO sproc_args VALUES(20,'itemList','itemList','text','input','2147483647','update_data_package_items');
INSERT INTO sproc_args VALUES(21,'comment','comment','varchar','input','512','update_data_package_items');
INSERT INTO sproc_args VALUES(22,'<local>','mode','varchar','input','12','update_data_package_items');
INSERT INTO sproc_args VALUES(23,'removeParents','removeParents','tinyint','input','','update_data_package_items');
INSERT INTO sproc_args VALUES(24,'<local>','message','varchar','output','512','update_data_package_items');
INSERT INTO sproc_args VALUES(25,'<local>','callingUser','varchar','input','128','update_data_package_items');
COMMIT;
