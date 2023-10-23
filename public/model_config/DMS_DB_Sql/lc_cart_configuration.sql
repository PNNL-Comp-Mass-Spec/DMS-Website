﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_LC_Cart_Configuration');
INSERT INTO general_params VALUES('list_report_data_table','v_lc_cart_configuration_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_lc_cart_configuration_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_page_data_table','v_lc_cart_configuration_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('entry_sproc','add_update_lc_cart_configuration');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','lc_cart_configuration/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'cart','invoke_entity','value','lc_cart/report/','');
INSERT INTO list_report_hotlinks VALUES(3,'dataset_usage','invoke_entity','config_name','dataset/report/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Cart_Config/MatchesText/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_cart_config','Cart Config','','','config_name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_cart','Cart','','','cart','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_pumps','Pumps','','','pumps','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_state','State','','','state','StartsWithText','text','12','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','id','int','input','','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(2,'config_name','configName','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(4,'description','description','varchar','input','512','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(5,'autosampler','autosampler','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(6,'custom_valve_config','customValveConfig','varchar','input','256','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(7,'pumps','pumps','varchar','input','256','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(8,'primary_injection_volume','primaryInjectionVolume','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(9,'primary_mobile_phases','primaryMobilePhases','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(10,'primary_trap_column','primaryTrapColumn','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(11,'primary_trap_flow_rate','primaryTrapFlowRate','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(12,'primary_trap_time','primaryTrapTime','varchar','input','32','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(13,'primary_trap_mobile_phase','primaryTrapMobilePhase','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(14,'primary_analytical_column','primaryAnalyticalColumn','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(15,'primary_column_temperature','primaryColumnTemperature','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(16,'primary_analytical_flow_rate','primaryAnalyticalFlowRate','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(17,'primary_gradient','primaryGradient','varchar','input','512','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(18,'mass_spec_start_delay','massSpecStartDelay','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(19,'upstream_injection_volume','upstreamInjectionVolume','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(20,'upstream_mobile_phases','upstreamMobilePhases','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(21,'upstream_trap_column','upstreamTrapColumn','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(22,'upstream_trap_flow_rate','upstreamTrapFlowRate','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(23,'upstream_analytical_column','upstreamAnalyticalColumn','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(24,'upstream_column_temperature','upstreamColumnTemperature','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(25,'upstream_analytical_flow_rate','upstreamAnalyticalFlowRate','varchar','input','64','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(26,'upstream_fractionation_profile','upstreamFractionationProfile','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(27,'upstream_fractionation_details','upstreamFractionationDetails','varchar','input','512','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(28,'cart_config_state','state','varchar','input','12','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(29,'entered_by','entryUser','varchar','input','128','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(30,'<local>','mode','varchar','input','12','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(31,'<local>','message','varchar','output','512','add_update_lc_cart_configuration');
INSERT INTO sproc_args VALUES(32,'<local>','callingUser','varchar','input','128','add_update_lc_cart_configuration');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'config_name','Config Name','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(4,'description','Description','area','','','2','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(5,'autosampler','Autosampler','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(6,'custom_valve_config','Custom Valve Config','text','70','256','','','','trim|max_length[256]');
INSERT INTO form_fields VALUES(7,'pumps','Pumps','text','70','256','','','','trim|max_length[256]');
INSERT INTO form_fields VALUES(8,'primary_injection_volume','Primary Injection Volume','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(9,'primary_mobile_phases','Primary Mobile Phases','area','','','2','70','','trim|max_length[128]');
INSERT INTO form_fields VALUES(13,'primary_trap_column','Primary Trap Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(14,'primary_trap_flow_rate','Primary Trap Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(15,'primary_trap_time','Primary Trap Time','text','50','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(16,'primary_trap_mobile_phase','Primary Trap Mobile Phase','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(17,'primary_analytical_column','Primary Analytical Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(18,'primary_column_temperature','Primary Column Temperature','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(19,'primary_analytical_flow_rate','Primary Analytical Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(20,'primary_gradient','Primary Gradient','area','','','10','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(21,'mass_spec_start_delay','Mass Spec Start Delay','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(22,'upstream_injection_volume','Upstream Injection Volume','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(23,'upstream_mobile_phases','Upstream Mobile Phases','area','','','2','70','','trim|max_length[128]');
INSERT INTO form_fields VALUES(24,'upstream_trap_column','Upstream Trap Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(25,'upstream_trap_flow_rate','Upstream Trap Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(26,'upstream_analytical_column','Upstream Analytical Column','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(27,'upstream_column_temperature','Upstream Column Temperature','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(28,'upstream_analytical_flow_rate','Upstream Analytical Flow Rate','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(29,'upstream_fractionation_profile','Upstream Fractionation Profile','text','70','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(30,'upstream_fractionation_details','Upstream Fractionation Details','area','','','10','70','','trim|max_length[512]');
INSERT INTO form_fields VALUES(31,'cart_config_state','State','text','15','12','','','Active','trim|max_length[12]');
INSERT INTO form_fields VALUES(32,'entered_by','Entry User','text','50','64','','','','trim|max_length[128]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'config_name','picker.prepend','lcCartPickList','','','_','');
INSERT INTO form_field_choosers VALUES(2,'cart_config_state','picker.replace','activeInactiveInvalidPickList','','','','');
INSERT INTO form_field_choosers VALUES(3,'entered_by','picker.replace','userUsernamePickList','','',',','');
CREATE TABLE IF NOT EXISTS "detail_report_hotlinks" (
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
INSERT INTO detail_report_hotlinks VALUES(1,'entered_by','detail-report','entered_by','user/report/-/~','labelCol','','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO detail_report_hotlinks VALUES(2,'updated_by','detail-report','updated_by','user/report/-/~','labelCol','','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO detail_report_hotlinks VALUES(7,'primary_gradient','monomarkup','primary_gradient','','valueCol','dl_primary_gradient','');
INSERT INTO detail_report_hotlinks VALUES(8,'upstream_fractionation_details','monomarkup','upstream_fractionation_details','','valueCol','dl_upstream_fractionation_Details','');
INSERT INTO detail_report_hotlinks VALUES(9,'description','markup','description','','valueCol','dl_description','');
INSERT INTO detail_report_hotlinks VALUES(10,'cart','detail-report','cart_id','lc_cart/show','labelCol','dl_cart','');
INSERT INTO detail_report_hotlinks VALUES(11,'dataset_usage','detail-report','config_name','dataset/report/-/-/-/-/-/-/-/-/-/-/-/sfx/AND/Cart_Config/MatchesText/','labelCol','dl_dataset_usage','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'description','auto_format','none');
INSERT INTO form_field_options VALUES(3,'primary_gradient','auto_format','none');
INSERT INTO form_field_options VALUES(4,'upstream_fractionation_details','auto_format','none');
INSERT INTO form_field_options VALUES(5,'description','section','Configuration');
INSERT INTO form_field_options VALUES(6,'primary_injection_volume','section','Primary Dimension');
INSERT INTO form_field_options VALUES(7,'upstream_injection_volume','section','Upstream Dimension');
INSERT INTO form_field_options VALUES(8,'cart_config_state','section','Miscellaneous');
COMMIT;
