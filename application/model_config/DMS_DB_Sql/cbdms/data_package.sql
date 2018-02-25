PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Data_Package_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateDataPackage');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Data_Package_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateDataPackageItems');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('post_submission_detail_id','ID');
INSERT INTO "general_params" VALUES('detail_report_cmds','data_package_cmds');
INSERT INTO "general_params" VALUES('list_report_data_cols','[ID],[Name],[Description],[Owner],[Team],[State],[Package Type],[Requester],[Total],[Jobs],[Datasets],[Experiments],[Biomaterial],[Last Modified],[Created]');
INSERT INTO "general_params" VALUES('detail_report_data_cols','ID,Name,[Package Type],Description,Comment,Owner,Requester,Team,Created,[Last Modified],State,[Package File Folder],[Share Path],[Web Path],[AMT Tag Database],[Biomaterial Item Count],[Experiment Item Count],[EUS Proposals Count],[Dataset Item Count],[Analysis Job Item Count],[Campaign Count],[Total Item Count] ');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'Name','Name','text','128','128','','','','trim|max_length[128]|os_filename|min_length[8]');
INSERT INTO "form_fields" VALUES(3,'PackageType','Package Type','text','50','128','','','General','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(4,'Description','Description','area','','','4','70','','trim|max_length[2048]');
INSERT INTO "form_fields" VALUES(5,'Comment','Comment','area','','','4','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(6,'Owner','Owner','text','40','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(7,'Requester','Requester','text','40','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(8,'State','State','text','32','32','','','Active','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(9,'Team','Team','text','50','64','','','Public','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(10,'MassTagDatabase','AMT Tag Database','area','','','4','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(11,'PRISMWikiLink','PRISMWiki Link','text','128','1024','','','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(12,'creationParams','creationParams','hidden','','','','','','trim');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'PRISMWikiLink','hide','add');
INSERT INTO "form_field_options" VALUES(2,'Requester','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(3,'Owner','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'PackageType','picker.replace','dataPackageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Owner','autocomplete.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Requester','autocomplete.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'Team','picker.replace','dataPackageTeamPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'State','picker.replace','dataPackageStatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'Owner','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'Requester','picker.replace','userPRNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','6','','Name','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_ID','ID','6','','ID','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','6','','Description','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_state','State','6','','State','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_type','Type','6','','Package Type','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_owner','Owner','6','','Owner','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','data_package/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Jobs','invoke_entity','ID','data_package_analysis_jobs/report','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Datasets','invoke_entity','ID','data_package_dataset/report','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Experiments','invoke_entity','ID','data_package_experiments/report','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Biomaterial','invoke_entity','ID','data_package_biomaterial/report','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Total','invoke_entity','ID','data_package_items/report','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Proposals','invoke_entity','ID','data_package_proposals/report','');
INSERT INTO "list_report_hotlinks" VALUES(8,'Description','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(9,'Name','min_col_width','value','60','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Share Path','href-folder','Share Path','','valueCol','share_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Web Path','literal_link','Web Path','','valueCol','web_path',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Biomaterial Item Count','detail-report','ID','data_package_biomaterial/report','labelCol','biomaterial_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Experiment Item Count','detail-report','ID','data_package_experiments/report','labelCol','experiment_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'Dataset Item Count','detail-report','ID','data_package_dataset/report','labelCol','dataset_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Analysis Job Item Count','detail-report','ID','data_package_analysis_jobs/report','labelCol','analysis_job_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'Total Item Count','detail-report','ID','data_package_items/report','labelCol','total_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(8,'PRISM Wiki','literal_link','PRISM Wiki','','valueCol','prism_wiki',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(9,'MyEMSL URL','masked_link','MyEMSL URL','','valueCol','myemsl_url','{"Label":"Show files in MyEMSL"}');
INSERT INTO "detail_report_hotlinks" VALUES(10,'EUS Proposals Count','detail-report','ID','data_package_proposals/report','labelCol','eus_proposals_count','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Campaign Count','detail-report','ID','data_package_campaigns/report','labelCol','campaign_count','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(2,'Name','Name','varchar','input','128','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(3,'PackageType','PackageType','varchar','input','128','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(4,'Description','Description','varchar','input','2048','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(5,'Comment','Comment','varchar','input','1024','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(6,'Owner','Owner','varchar','input','128','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(7,'Requester','Requester','varchar','input','128','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(8,'State','State','varchar','input','32','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(9,'Team','Team','varchar','input','64','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(10,'MassTagDatabase','MassTagDatabase','varchar','input','1024','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(11,'PRISMWikiLink','PRISMWikiLink','varchar','output','1024','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(12,'creationParams','creationParams','varchar','output','4096','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(13,'<local>','mode','varchar','input','12','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(14,'<local>','message','varchar','output','512','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(15,'<local>','callingUser','varchar','input','128','AddUpdateDataPackage');
INSERT INTO "sproc_args" VALUES(16,'packageID','packageID','int','input','','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(17,'itemType','itemType','varchar','input','128','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(18,'itemList','itemList','text','input','2147483647','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(19,'comment','comment','varchar','input','512','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(20,'<local>','mode','varchar','input','12','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(21,'removeParents','removeParents','tinyint','input','','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(22,'<local>','message','varchar','output','512','UpdateDataPackageItems');
INSERT INTO "sproc_args" VALUES(23,'<local>','callingUser','varchar','input','128','UpdateDataPackageItems');
COMMIT;
