﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Param_File_Mass_Mods');
INSERT INTO general_params VALUES('list_report_data_table','v_param_file_mass_mods_list_report');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_param_file_id','Param File ID','','','param_file_id','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_mass_correction_id','Mod ID','','','mod_id','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_mod_type','Mod Type','','','mod_type','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_residue','Residue','','','residue','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_residue_desc','Residue_Desc','','','residue_desc','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_local_symbol','Symbol','','','symbol','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_mass_min','Min Mass','','','mono_mass','GreaterThanOrEqualTo','text','','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_mass_max','Max Mass','','','mono_mass','LessThanOrEqualTo','text','','','');
INSERT INTO list_report_primary_filter VALUES(9,'pf_param_file_name','Param File','50!','','param_file_name','ContainsText','text','256','','');
INSERT INTO list_report_primary_filter VALUES(10,'pf_primary_tool','Tool','','','primary_tool','ContainsText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'original_source_name','invoke_entity','value','unimod/report','');
INSERT INTO list_report_hotlinks VALUES(2,'mass_correction_tag','invoke_entity','value','mass_correction_factors/report/-/~','');
INSERT INTO list_report_hotlinks VALUES(3,'param_file_id','invoke_entity','value','param_file/show','');
INSERT INTO list_report_hotlinks VALUES(4,'mod_id','invoke_entity','value','param_file_mass_mods/report/-/@','');
INSERT INTO list_report_hotlinks VALUES(5,'param_file_description','min_col_width','value','90','');
INSERT INTO list_report_hotlinks VALUES(6,'residue_desc','invoke_entity','value','residue/report/-/~@/-/-','');
COMMIT;
