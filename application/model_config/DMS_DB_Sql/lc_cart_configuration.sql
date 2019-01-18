﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_LC_Cart_Configuration');
INSERT INTO "general_params" VALUES('list_report_data_table','V_LC_Cart_Configuration_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_LC_Cart_Configuration_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_LC_Cart_Configuration_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateLCCartConfiguration');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','lc_cart_configuration/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Cart_Name','invoke_entity','value','lc_cart/report/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Dataset Usage','invoke_entity','Config Name','dataset/report/-/-/-/-/-/-/-/-/-/sfx/AND/Cart%20Config/MatchesText/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_cart_config','Cart Config','','','Config Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_cart','Cart','','','Cart','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_pumps','Pumps','','','Pumps','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_state','State','','','State','StartsWithText','text','12','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','input','','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(2,'Config_Name','configName','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(4,'Description','description','varchar','input','512','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(5,'Autosampler','autosampler','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(6,'Custom_Valve_Config','customValveConfig','varchar','input','256','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(7,'Pumps','pumps','varchar','input','256','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(8,'Primary_Injection_Volume','primaryInjectionVolume','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(9,'Primary_Mobile_Phases','primaryMobilePhases','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(10,'Primary_Trap_Column','primaryTrapColumn','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(11,'Primary_Trap_Flow_Rate','primaryTrapFlowRate','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(12,'Primary_Trap_Time','primaryTrapTime','varchar','input','32','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(13,'Primary_Trap_Mobile_Phase','primaryTrapMobilePhase','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(14,'Primary_Analytical_Column','primaryAnalyticalColumn','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(15,'Primary_Column_Temperature','primaryColumnTemperature','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(16,'Primary_Analytical_Flow_Rate','primaryAnalyticalFlowRate','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(17,'Primary_Gradient','primaryGradient','varchar','input','512','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(18,'Mass_Spec_Start_Delay','massSpecStartDelay','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(19,'Upstream_Injection_Volume','upstreamInjectionVolume','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(20,'Upstream_Mobile_Phases','upstreamMobilePhases','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(21,'Upstream_Trap_Column','upstreamTrapColumn','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(22,'Upstream_Trap_Flow_Rate','upstreamTrapFlowRate','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(23,'Upstream_Analytical_Column','upstreamAnalyticalColumn','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(24,'Upstream_Column_Temperature','upstreamColumnTemperature','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(25,'Upstream_Analytical_Flow_Rate','upstreamAnalyticalFlowRate','varchar','input','64','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(26,'Upstream_Fractionation_Profile','upstreamFractionationProfile','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(27,'Upstream_Fractionation_Details','upstreamFractionationDetails','varchar','input','512','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(28,'Cart_Config_State','state','varchar','input','12','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(29,'Entered_By','entryUser','varchar','input','128','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(30,'<local>','mode','varchar','input','12','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(31,'<local>','message','varchar','output','512','AddUpdateLCCartConfiguration');
INSERT INTO "sproc_args" VALUES(32,'<local>','callingUser','varchar','input','128','AddUpdateLCCartConfiguration');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Config_Name','Config Name','text','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(4,'Description','Description','area','','','2','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(5,'Autosampler','Autosampler','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(6,'Custom_Valve_Config','Custom Valve Config','text','70','256','','','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(7,'Pumps','Pumps','text','70','256','','','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(8,'Primary_Injection_Volume','Primary Injection Volume','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(9,'Primary_Mobile_Phases','Primary Mobile Phases','area','','','2','70','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(13,'Primary_Trap_Column','Primary Trap Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(14,'Primary_Trap_Flow_Rate','Primary Trap Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(15,'Primary_Trap_Time','Primary Trap Time','text','50','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(16,'Primary_Trap_Mobile_Phase','Primary Trap Mobile Phase','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(17,'Primary_Analytical_Column','Primary Analytical Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(18,'Primary_Column_Temperature','Primary Column Temperature','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(19,'Primary_Analytical_Flow_Rate','Primary Analytical Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(20,'Primary_Gradient','Primary Gradient','area','','','10','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(21,'Mass_Spec_Start_Delay','Mass Spec Start Delay','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(22,'Upstream_Injection_Volume','Upstream Injection Volume','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(23,'Upstream_Mobile_Phases','Upstream Mobile Phases','area','','','2','70','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(24,'Upstream_Trap_Column','Upstream Trap Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(25,'Upstream_Trap_Flow_Rate','Upstream Trap Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(26,'Upstream_Analytical_Column','Upstream Analytical Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(27,'Upstream_Column_Temperature','Upstream Column Temperature','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(28,'Upstream_Analytical_Flow_Rate','Upstream Analytical Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(29,'Upstream_Fractionation_Profile','Upstream Fractionation Profile','text','70','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(30,'Upstream_Fractionation_Details','Upstream Fractionation Details','area','','','10','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(31,'Cart_Config_State','State','text','15','12','','','Active','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(32,'Entered_By','Entry User','text','50','64','','','','trim|max_length[128]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Config_Name','picker.prepend','lcCartPickList','','','_','');
INSERT INTO "form_field_choosers" VALUES(2,'Cart_Config_State','picker.replace','activeInactiveInvalidPickList','','','','');
INSERT INTO "form_field_choosers" VALUES(3,'Entered_By','picker.replace','userPRNPickList','','',',','');
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
INSERT INTO "detail_report_hotlinks" VALUES(7,'Primary Gradient','monomarkup','Primary Gradient','','valueCol','dl_primary_gradient','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Upstream Fractionation Details','monomarkup','Upstream Fractionation Details','','valueCol','dl_upstream_fractionation_Details','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Description','markup','Description','','valueCol','dl_description','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'Cart','detail-report','Cart_ID','lc_cart/show','labelCol','dl_cart','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Dataset Usage','detail-report','Config_Name','dataset/report/-/-/-/-/-/-/-/-/-/sfx/AND/Cart%20Config/MatchesText/','labelCol','dl_dataset_usage','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Description','auto_format','none');
INSERT INTO "form_field_options" VALUES(3,'Primary_Gradient','auto_format','none');
INSERT INTO "form_field_options" VALUES(4,'Upstream_Fractionation_Details','auto_format','none');
INSERT INTO "form_field_options" VALUES(5,'Description','section','Configuration');
INSERT INTO "form_field_options" VALUES(6,'Primary_Injection_Volume','section','Primary Dimension');
INSERT INTO "form_field_options" VALUES(7,'Upstream_Injection_Volume','section','Upstream Dimension');
INSERT INTO "form_field_options" VALUES(8,'Cart_Config_State','section','Miscellaneous');
COMMIT;
