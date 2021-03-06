﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Param_Files');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Param_File_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Param_File_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Param_File_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Param_File_ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateParamFile');
INSERT INTO "general_params" VALUES('post_submission_detail_id','Param_File_ID');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Param_File_ID','invoke_entity','value','param_file/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Job_Usage_Count','invoke_entity','Param_File_Name','analysis_job/report/-/-/-/-/-/~','');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Param_File_ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Param_File_Name','Name','text','80','255','','','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(3,'Param_File_Description','Description','area','','','4','80','','trim|required|max_length[1024]');
INSERT INTO "form_fields" VALUES(4,'Param_File_Type','Type','text','32','32','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(5,'Valid','Valid','text','8','8','','','','trim|required|numeric');
INSERT INTO "form_fields" VALUES(6,'MassMods','Mass Mods','area','','','10','80','','trim');
INSERT INTO "form_fields" VALUES(7,'ReplaceMassMods','Replace Existing Mass Mods','text','8','8','','','0','trim|numeric');
INSERT INTO "form_fields" VALUES(8,'ValidateUnimod','Validate Unimod Names','text','8','8','','','1','trim|numeric');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Param_File_ID','paramFileID','int','output','','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(2,'Param_File_Name','paramFileName','varchar','input','255','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(3,'Param_File_Description','paramFileDesc','varchar','input','1024','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(4,'Param_File_Type','paramFileType','varchar','input','50','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(5,'Valid','paramfileValid','int','input','','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(6,'MassMods','paramfileMassMods','varchar','input','4000','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(7,'ReplaceMassMods','replaceExistingMassMods','int','','','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(8,'ValidateUnimod','validateUnimod','int','','','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(9,'<local>','mode','varchar','input','12','AddUpdateParamFile');
INSERT INTO "sproc_args" VALUES(10,'<local>','message','varchar','output','512','AddUpdateParamFile');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Job Usage Count','detail-report','Name','analysis_job/report/-/-/-/-/-/~@','labelCol','dl_job_usage_count','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Primary Tool','detail-report','Primary Tool','analysis_job/report/-/-/@','labelCol','dl_primary_tool',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Mass_Mods','detail-report','ID','param_file_mass_mods/report/@','labelCol','dl_mass_mods',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'+Primary Tool','detail-report','Primary Tool','analysis_tools/report','valueCol','dl_primary_tool_2',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'+Mass_Mods','monomarkup','Mass_Mods','','valueCol','dl_mass_mods_markup',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'MaxQuant_Mods','tabular_link_list','MaxQuant_Mods','maxquant_mods/report/@/-','valueCol','dl_maxquant_mods','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_param_file_id','ID','5!','','Param_File_ID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_param_file_name','Name','50!','','Param_File_Name','ContainsText','text','255','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_param_file_type','Type','20','','Param_File_Type','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_param_file_description','Description','20!','','Param_File_Description','ContainsText','text','1024','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_valid','Valid','5!','','Valid','Equals','text','20','','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Param_File_Type','picker.replace','paramFileTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Valid','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'ReplaceMassMods','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'ValidateUnimod','picker.replace','yesNoAsOneZeroPickList','','',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'MassMods','auto_format','none');
COMMIT;
