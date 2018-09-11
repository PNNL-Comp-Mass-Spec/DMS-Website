PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Settings_Files_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Settings_Files_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateSettingsFile');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Settings_Files_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim|max_length[4]');
INSERT INTO "form_fields" VALUES(2,'AnalysisTool','Analysis Tool','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'FileName','FileName','area','','','1','80','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(4,'Description','Description','area','','','4','80','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(5,'Active','Active','text','1','1','','','1','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(6,'AutoCentroid','AutoCentroid','area','','','1','80','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(7,'HMS_AutoSupersede','HMS_AutoSupersede','area','','','1','80','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(8,'Contents','Contents','area','','','18','80','<section name="AAA" tool="">

	<item key="xxx" value=""></item>

	<item key="yyy" value=""></item>

</section>

<section name="BBB" tool="">

	<item key="xxx" value=""></item>

	<item key="yyy" value=""></item>

</section>','trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Contents','auto_format','xml');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'AnalysisTool','picker.replace','analysisToolPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_analysis_tool','Analysis Tool','20','','Analysis Tool','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_file_name','File Name','35!','','File Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','25!','','Description','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','settings_files/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Description','min_col_width','value','200','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Analysis Tool','invoke_entity','value','analysis_job/report/-/-/~@/-/-/-/-/-/52/-','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','input','','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(2,'AnalysisTool','AnalysisTool','varchar','input','64','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(3,'FileName','FileName','varchar','input','255','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(4,'Description','Description','varchar','input','1024','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(5,'Active','Active','tinyint','input','','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(6,'Contents','Contents','text','input','2147483647','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(7,'HMS_AutoSupersede','HMSAutoSupersede','varchar','input','255','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(8,'AutoCentroid','MSGFPlusAutoCentroid','varchar','input','255','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(9,'<local>','mode','varchar','input','12','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(10,'<local>','message','varchar','output','512','AddUpdateSettingsFile');
INSERT INTO "sproc_args" VALUES(11,'<local>','callingUser','varchar','input','128','AddUpdateSettingsFile');
COMMIT;
