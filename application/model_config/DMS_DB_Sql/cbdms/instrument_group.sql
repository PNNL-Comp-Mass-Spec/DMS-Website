PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Instrument_Group');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Group_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Instrument_Group_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Instrument_Group');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Instrument_Group_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','InstrumentGroup');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateInstrumentGroup');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Instrument_Group','invoke_entity','value','instrument_group/show/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument_group','Instrument_Group','20','','Instrument_Group','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_allowed_dataset_types','Allowed_Dataset_Types','20','','Allowed_Dataset_Types','ContainsText','text','4000','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_active','Active','20','','Active','Equals','text','20','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'InstrumentGroup','InstrumentGroup','varchar','input','64','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(2,'Usage','Usage','varchar','input','64','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(3,'Comment','Comment','varchar','input','512','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(4,'Active','Active','tinyint','input','','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(5,'Sample_Prep_Visible','SamplePrepVisible','tinyint','input','','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(6,'Allocation_Tag','AllocationTag','varchar','intput','24','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(7,'DefaultDatasetTypeName','DefaultDatasetTypeName','varchar','input','50','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(8,'<local>','mode','varchar','input','12','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(9,'<local>','message','varchar','output','512','AddUpdateInstrumentGroup');
INSERT INTO "sproc_args" VALUES(10,'<local>','callingUser','varchar','input','128','AddUpdateInstrumentGroup');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'InstrumentGroup','Instrument Group','text-if-new','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(2,'Usage','Usage','text','64','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'Comment','Comment','area','64','512','2','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(4,'Active','Active','text','12','12','','','','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(5,'Sample_Prep_Visible','Sample Prep Visible','text','12','12','','','','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(6,'Allocation_Tag','Allocation Tag','text','12','24','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(7,'DefaultDatasetTypeName','Default Dataset Type','text','20','64','','','','trim|max_length[64]');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Allowed_Dataset_Types','detail-report','Instrument_Group','instrument_allowed_dataset_type/report','labelCol','dl_dataset_type','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Instruments','detail-report','Instrument_Group','instrument/report/-/-/','labelCol','dl_instrument_list','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'+Instruments','link_list','Instruments','instrument/report','valueCol','dl_instrument_name','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'DefaultDatasetTypeName','picker.replace','datasetTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Active','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Sample_Prep_Visible','picker.replace','yesNoAsOneZeroPickList','','',',','');
COMMIT;
