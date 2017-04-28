PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Instrument_Operation_History');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Operation_History_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Instrument_Operation_History_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Instrument_Operation_History_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateInstrumentOperationHistory');
INSERT INTO "general_params" VALUES('list_report_cmds','instrument_operation_history_cmds');
INSERT INTO "general_params" VALUES('operations_sproc','bogus');
INSERT INTO "general_params" VALUES('alternate_title_create','Create New Instrument Operation History Note');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','input','','AddUpdateInstrumentOperationHistory');
INSERT INTO "sproc_args" VALUES(2,'Instrument','Instrument','varchar','input','24','AddUpdateInstrumentOperationHistory');
INSERT INTO "sproc_args" VALUES(3,'postedBy','postedBy','varchar','input','64','AddUpdateInstrumentOperationHistory');
INSERT INTO "sproc_args" VALUES(4,'Note','Note','text','input','2147483647','AddUpdateInstrumentOperationHistory');
INSERT INTO "sproc_args" VALUES(5,'<local>','mode','varchar','input','12','AddUpdateInstrumentOperationHistory');
INSERT INTO "sproc_args" VALUES(6,'<local>','message','varchar','output','512','AddUpdateInstrumentOperationHistory');
INSERT INTO "sproc_args" VALUES(7,'<local>','callingUser','varchar','input','128','AddUpdateInstrumentOperationHistory');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID',' ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'Instrument',' Instrument','text','24','24','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(3,'postedBy','Posted By','text','50','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'Note',' Note','area','','','20','100','','trim|required');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','instrument_operation_history/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Note','markup','value','','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Instrument','picker.replace','instrumentNamePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'postedBy','picker.replace','userPRNPickList','','',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Note','auto_format','None');
INSERT INTO "form_field_options" VALUES(2,'postedBy','default_function','GetUser()');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_note','Note','20','','Note','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_enteredby','EnteredBy','20','','EnteredBy','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_entered','Entered','20','','Entered','LaterThan','text','20','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Note','markup','Note','','valueCol','dl_note',NULL);
COMMIT;
