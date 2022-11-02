﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateInstrumentClass');
INSERT INTO general_params VALUES('entry_page_data_table','v_instrument_class_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','instrument_class');
INSERT INTO general_params VALUES('alternate_title_create','Create New Instrument Class');
INSERT INTO general_params VALUES('alternate_title_edit','Edit New Instrument Class');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'instrument_class','Instrument Class','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(2,'is_purgable','Is Purgable','text','1','1','','','','trim|max_length[1]');
INSERT INTO form_fields VALUES(3,'raw_data_type','Raw Data Type','text','32','32','','','','trim|max_length[32]');
INSERT INTO form_fields VALUES(4,'requires_preparation','Requires Preparation','text','1','1','','','','trim|max_length[1]');
INSERT INTO form_fields VALUES(5,'params','Params','area','','','4','70','','trim|max_length[2147483647]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'is_purgable','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'raw_data_type','picker.replace','rawDataTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'requires_preparation','picker.replace','yesNoAsOneZeroPickList','','',',','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'instrument_class','InstrumentClass','varchar','input','32','AddUpdateInstrumentClass');
INSERT INTO sproc_args VALUES(2,'is_purgable','isPurgable','varchar','input','1','AddUpdateInstrumentClass');
INSERT INTO sproc_args VALUES(3,'raw_data_type','rawDataType','varchar','input','32','AddUpdateInstrumentClass');
INSERT INTO sproc_args VALUES(4,'requires_preparation','requiresPreparation','varchar','input','1','AddUpdateInstrumentClass');
INSERT INTO sproc_args VALUES(5,'params','Params','text','input','2147483647','AddUpdateInstrumentClass');
INSERT INTO sproc_args VALUES(6,'<local>','mode','varchar','input','12','AddUpdateInstrumentClass');
INSERT INTO sproc_args VALUES(7,'<local>','message','varchar','output','512','AddUpdateInstrumentClass');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'params','auto_format','xml');
COMMIT;
