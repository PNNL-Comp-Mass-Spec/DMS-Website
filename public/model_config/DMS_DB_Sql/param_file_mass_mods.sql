﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Param_File_Mass_Mods');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Param_File_Mass_Mods_List_Report');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_param_file_id','Param File ID','','','Param_File_ID','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_mass_correction_id','Mod ID','','','Mod_ID','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_mod_type','Mod Type','','','Mod_Type','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_residue','Residue','','','Residue','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_residue_desc','Residue_Desc','','','Residue_Desc','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_local_symbol','Symbol','','','Symbol','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_mass_min','Min Mass','','','Mono_Mass','GreaterThanOrEqualTo','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_mass_max','Max Mass','','','Mono_Mass','LessThanOrEqualTo','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_param_file_name','Param File','50!','','Param_File_Name','ContainsText','text','256','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_primary_tool','Tool','','','Primary_Tool','ContainsText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Original_Source_Name','invoke_entity','value','unimod/report','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Mass_Correction_Tag','invoke_entity','value','mass_correction_factors/report/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Param_File_ID','invoke_entity','value','param_file/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Mod_ID','invoke_entity','value','param_file_mass_mods/report/-/@','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Param_File_Description','min_col_width','value','90','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Residue_Desc','invoke_entity','value','residue/report/-/~@/-/-','');
COMMIT;
