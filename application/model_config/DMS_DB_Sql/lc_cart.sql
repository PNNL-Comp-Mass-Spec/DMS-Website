﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Cart_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_LC_Cart_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateLCCart');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_LC_Cart_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('post_submission_detail_id','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'CartName','Cart Name','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(3,'CartDescription','Description','area','','','4','60','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(4,'CartState','State','text','50','50','','','','trim|max_length[50]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'CartState','picker.replace','lcCartStatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_cart_name','Cart Name','6','','Cart Name','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Description','20','','Description','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_id','ID','6','','ID','Equals','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_state','State','','','State','ContainsText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','lc_cart/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'State','min_col_width','value','20','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Created','format_date','value','20','{"Format":"Y-m-d"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateLCCart');
INSERT INTO "sproc_args" VALUES(2,'CartName','CartName','varchar','input','128','AddUpdateLCCart');
INSERT INTO "sproc_args" VALUES(3,'CartDescription','CartDescription','varchar','input','1024','AddUpdateLCCart');
INSERT INTO "sproc_args" VALUES(4,'CartState','CartState','varchar','input','50','AddUpdateLCCart');
INSERT INTO "sproc_args" VALUES(5,'<local>','mode','varchar','input','12','AddUpdateLCCart');
INSERT INTO "sproc_args" VALUES(6,'<local>','message','varchar','output','512','AddUpdateLCCart');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Configuration Count','detail-report','Cart Name','lc_cart_configuration/report/-/~','labelCol','dl_ConfigurationCount','');
COMMIT;
