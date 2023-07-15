﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_sproc','get_param_file_crosstab');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'analysis_tool_name','Analysis Tool','text','60','64','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(2,'parameter_file_filter','Parameter File Filter','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'show_valid_only','Show Valid Only','text','1','1','','','0','trim|required|max_length[1]');
INSERT INTO form_fields VALUES(4,'show_mod_symbol','Show Mod Symbol','text','1','1','','','0','trim|required|max_length[1]');
INSERT INTO form_fields VALUES(5,'show_mod_name','Show Mod Name','text','1','1','','','1','trim|required|max_length[1]');
INSERT INTO form_fields VALUES(6,'show_mod_mass','Show Mod Mass','text','1','1','','','1','trim|required|max_length[1]');
INSERT INTO form_fields VALUES(7,'use_mod_mass_alternative_name','Use Mod Mass Alternative Name','text','1','1','','','1','trim|required|max_length[1]');
INSERT INTO form_fields VALUES(8,'mass_mod_filter_text_column','Mass Mod Filter Text Column','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(9,'mass_mod_filter_text','Mass Mod Filter Text','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(10,'preview_sql','','hidden','','','','','0','trim');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'analysis_tool_name','picker.replace','analysisToolPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'show_mod_symbol','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'show_mod_name','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'show_mod_mass','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'use_mod_mass_alternative_name','picker.replace','yesNoAsOneZeroPickList','','',',','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'analysis_tool_name','analysisToolName','varchar','input','64','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(2,'parameter_file_filter','parameterFileFilter','varchar','input','128','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(3,'show_valid_only','showValidOnly','tinyint','input','','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(4,'show_mod_symbol','showModSymbol','tinyint','input','','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(5,'show_mod_name','showModName','tinyint','input','','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(6,'show_mod_mass','showModMass','tinyint','input','','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(7,'use_mod_mass_alternative_name','useModMassAlternativeName','tinyint','input','','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(8,'mass_mod_filter_text_column','massModFilterTextColumn','varchar','input','64','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(9,'mass_mod_filter_text','massModFilterText','varchar','input','64','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(10,'preview_sql','previewSql','tinyint','input','','get_param_file_crosstab');
INSERT INTO sproc_args VALUES(11,'<local>','message','varchar','output','512','get_param_file_crosstab');
COMMIT;
