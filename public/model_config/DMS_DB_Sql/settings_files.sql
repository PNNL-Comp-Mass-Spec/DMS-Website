﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_settings_files_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_settings_files_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','add_update_settings_file');
INSERT INTO general_params VALUES('entry_page_data_table','v_settings_files_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'analysis_tool','Analysis Tool','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(3,'file_name','FileName','area','','','1','80','','trim|max_length[255]');
INSERT INTO form_fields VALUES(4,'description','Description','area','','','4','80','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(5,'active','Active','text','6','1','','','1','trim|max_length[1]');
INSERT INTO form_fields VALUES(6,'auto_centroid','Msgfplus Auto Centroid File	','area','','','1','80','','trim|max_length[255]');
INSERT INTO form_fields VALUES(7,'hms_auto_supersede','HMS Auto Supersede File','area','','','1','80','','trim|max_length[255]');
INSERT INTO form_fields VALUES(8,'contents','Contents','area','','','18','80',replace(replace('<section name="AAA" tool="">\r\n	<item key="xxx" value=""></item>\r\n	<item key="yyy" value=""></item>\r\n</section>\r\n<section name="BBB" tool="">\r\n	<item key="xxx" value=""></item>\r\n	<item key="yyy" value=""></item>\r\n</section>','\r',char(13)),'\n',char(10)),'trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'contents','auto_format','xml');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'analysis_tool','picker.replace','analysisToolPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'active','picker.replace','yesNoAsOneZeroPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_analysis_tool','Analysis Tool','25!','','analysis_tool','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_file_name','File Name','35!','','file_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_description','Description','25!','','description','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','settings_files/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'description','min_col_width','value','200','');
INSERT INTO list_report_hotlinks VALUES(3,'analysis_tool','invoke_entity','value','analysis_job/report/-/-/~@/-/-/-/-/-/52/-','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','settingsFileID','int','output','','add_update_settings_file');
INSERT INTO sproc_args VALUES(2,'analysis_tool','analysisTool','varchar','input','64','add_update_settings_file');
INSERT INTO sproc_args VALUES(3,'file_name','fileName','varchar','input','255','add_update_settings_file');
INSERT INTO sproc_args VALUES(4,'description','description','varchar','input','1024','add_update_settings_file');
INSERT INTO sproc_args VALUES(5,'active','active','tinyint','input','','add_update_settings_file');
INSERT INTO sproc_args VALUES(6,'contents','contents','text','input','2147483647','add_update_settings_file');
INSERT INTO sproc_args VALUES(7,'hms_auto_supersede','hmsAutoSupersede','varchar','input','255','add_update_settings_file');
INSERT INTO sproc_args VALUES(8,'auto_centroid','msgfPlusAutoCentroid','varchar','input','255','add_update_settings_file');
INSERT INTO sproc_args VALUES(9,'<local>','mode','varchar','input','12','add_update_settings_file');
INSERT INTO sproc_args VALUES(10,'<local>','message','varchar','output','512','add_update_settings_file');
INSERT INTO sproc_args VALUES(11,'<local>','callingUser','varchar','input','128','add_update_settings_file');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'job_usage_count','detail-report','file_name','analysis_job/report/-/-/-/-/-/-/-/-/-/~','labelCol','dl_Jobs','');
COMMIT;
