﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Column_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_LC_Column_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Column Name');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateLCColumn');
INSERT INTO "general_params" VALUES('entry_page_data_table','v_lc_column_entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','lc_column');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Created');
INSERT INTO "general_params" VALUES('post_submission_detail_id','lc_column');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'lc_column','Column Name','text-if-new','60','128','','','','trim|max_length[60]|name_space');
INSERT INTO "form_fields" VALUES(2,'packing_mfg','Packing Mfg','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'packing_type','Packing Type','text','30','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'particle_size','Particle Size','text','20','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'particle_type','Particle Type','text','20','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(6,'column_inner_dia','Column Inner Dia','text','20','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(7,'column_outer_dia','Column Outer Dia','text','20','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(8,'column_length','Length','text','20','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(9,'column_state','State','text','30','64','','','Active','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(10,'operator_prn','Operator (PRN)','text','30','50','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(11,'comment','Comment','area','','','4','60','','trim|max_length[60]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'operator_prn','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'column_state','hide','add');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'column_state','picker.replace','LCColumnStatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'operator_prn','picker.replace','userPRNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_column_name','Column Name','6','','Column Name','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_comment','Comment','20','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_state','State','12','','State','ContainsText','text','24','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Column Name','invoke_entity','value','lc_column/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Created','format_date','value','15','{"Format":"Y-m-d"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'lc_column','columnNumber','varchar','output','128','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(2,'packing_mfg','packingMfg','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(3,'packing_type','packingType','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(4,'particle_size','particleSize','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(5,'particle_type','particleType','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(6,'column_inner_dia','columnInnerDia','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(7,'column_outer_dia','columnOuterDia','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(8,'column_length','length','varchar','input','64','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(9,'column_state','state','varchar','input','32','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(10,'operator_prn','operator_prn','varchar','input','50','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(11,'comment','comment','varchar','input','244','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(12,'<local>','mode','varchar','input','12','AddUpdateLCColumn');
INSERT INTO "sproc_args" VALUES(13,'<local>','message','varchar','output','512','AddUpdateLCColumn');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Column Name','detail-report','Column Name','lc_column/report/','labelCol','dl_column_name','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'+Column Name','detail-report','Column Name','lc_column_dataset/report/~','valueCol','dl_column_name2','');
COMMIT;
