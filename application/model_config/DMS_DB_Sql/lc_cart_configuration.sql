PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_LC_Cart_Configuration');
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Cart_Configuration_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_LC_Cart_Configuration_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_LC_Cart_Configuration_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateLCCartConfiguration');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','lc_cart_configuration/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Cart_Name','invoke_entity','value','lc_cart/report/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_cart','Cart','','','Cart_Name','ContainsText','text','128','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','input','','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(2,'Cart','cartName','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(3,'Pumps','pumps','varchar','input','256','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(4,'Columns','columns','varchar','input','256','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(5,'Traps','traps','varchar','input','256','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(6,'Mobile_Phase','mobilePhase','varchar','input','256','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(7,'Injection','injection','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(8,'Gradient','gradient','varchar','input','512','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(9,'Comment','comment','varchar','input','1024','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(10,'<local>','mode','varchar','input','12','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(11,'<local>','message','varchar','output','512','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(12,'<local>','callingUser','varchar','input','128','AddUpdateLCCartConfiguration');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Cart','Cart','text','50','64','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(3,'Pumps','Pumps','area','','','5','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(4,'Columns','Columns','area','','','5','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(5,'Traps','Traps','area','','','5','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(6,'Mobile_Phase','Mobile Phase','area','','','5','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(7,'Injection','Injection','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(8,'Gradient','Gradient','area','','','10','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(9,'Comment','Comment','area','','','5','70','','trim|max_length[1024]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Cart','picker.replace','lcCartPickList','','',',','');
CREATE TABLE "detail_report_hotlinks" (
	`idx`	INTEGER,
	`name`	text,
	`LinkType`	text,
	`WhichArg`	text,
	`Target`	text,
	`Placement`	text,
	`id`	text,
	`options`	TEXT,
	PRIMARY KEY(`idx`)
);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Entered_By','detail-report','Entered_By','user/report/-/~','labelCol','','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Updated_By','detail-report','Updated_By','user/report/-/~','labelCol','','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(3,'Pumps','markup','Pumps','','valueCol','dl_pumps','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Columns','markup','Columns','','valueCol','dl_columns','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Traps','markup','Traps','','valueCol','dl_traps','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Mobile_Phase','markup','Mobile_Phase','','valueCol','dl_mobilephase','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Gradient','monomarkup','Gradient','','valueCol','dl_gradient','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Comment','markup','Comment','','valueCol','dl_comment','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Pumps','auto_format','none');
INSERT INTO "form_field_options" VALUES(2,'Columns','auto_format','none');
INSERT INTO "form_field_options" VALUES(3,'Traps','auto_format','none');
INSERT INTO "form_field_options" VALUES(4,'Mobile_Phase','auto_format','none');
INSERT INTO "form_field_options" VALUES(5,'Gradient','auto_format','none');
INSERT INTO "form_field_options" VALUES(6,'Comment','auto_format','none');
COMMIT;
