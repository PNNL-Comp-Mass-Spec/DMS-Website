﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Config_History_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Instrument_Config_History_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateInstrumentConfigHistory');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Instrument_Config_History_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO "general_params" VALUES('post_submission_detail_id','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Instrument','Instrument','text','24','24','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(3,'DateOfChange','Date Of Change','text','24','24','','','','trim|required|max_length[24]|valid_date');
INSERT INTO "form_fields" VALUES(4,'PostedBy','Posted By','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'Description','Description','text','60','128','','','General configuration note','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(6,'Note','Note','area','','','25','80','','trim|max_length[2147483647]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'DateOfChange','default_function','CurrentDate');
INSERT INTO "form_field_options" VALUES(2,'Note','auto_format','None');
INSERT INTO "form_field_options" VALUES(3,'PostedBy','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Instrument','picker.replace','instrumentNameExPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'PostedBy','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Description','picker.replace','instrumentConfigDescriptionPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'DateOfChange','picker.prevDate','futureDatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Description','60','','Description','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_note','Note','60','','#NoteFull','ContainsText','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_instrument','picker.replace','instrumentNameExPickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','instrument_config_history/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Note','markup','value','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(2,'Instrument','Instrument','varchar','input','24','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(3,'DateOfChange','DateOfChange','varchar','input','24','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(4,'PostedBy','PostedBy','varchar','input','64','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(5,'Description','Description','varchar','input','128','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(6,'Note','Note','text','input','2147483647','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(7,'<local>','mode','varchar','input','12','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(8,'<local>','message','varchar','output','512','AddUpdateInstrumentConfigHistory');
INSERT INTO "sproc_args" VALUES(9,'<local>','callingUser','varchar','input','128','AddUpdateInstrumentConfigHistory');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Note','markup','Note','','valueCol','dl_note',NULL);
COMMIT;
