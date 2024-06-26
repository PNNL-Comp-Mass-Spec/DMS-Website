﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_sproc','report_dataset_instrument_runtime');
INSERT INTO general_params VALUES('list_report_data_sort_col','seq');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_cmds','dataset_instrument_runtime_cmds');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'start_date','startDate','varchar','input','24','report_dataset_instrument_runtime');
INSERT INTO sproc_args VALUES(2,'end_date','endDate','varchar','input','24','report_dataset_instrument_runtime');
INSERT INTO sproc_args VALUES(3,'instrument_name','instrumentName','varchar','input','64','report_dataset_instrument_runtime');
INSERT INTO sproc_args VALUES(4,'report_options','reportOptions','varchar','input','64','report_dataset_instrument_runtime');
INSERT INTO sproc_args VALUES(5,'<local>','message','varchar','output','256','report_dataset_instrument_runtime');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'start_date','Start Date','text','24','24','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(2,'end_date','End Date','text','24','24','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(3,'instrument_name','Instrument Name','text','50','64','','','Exact01','trim|max_length[64]');
INSERT INTO form_fields VALUES(4,'report_options','Report Options','text','50','64','','','Show All','trim|max_length[64]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'start_date','default_function','PreviousNWeeks:4');
INSERT INTO form_field_options VALUES(2,'end_date','default_function','CurrentDate');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'instrument_name','picker.replace','instrumentNamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'report_options','picker.replace','instrumentRuntimeReportOptions','','',',','');
COMMIT;
