﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_sort_col','tissue');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_sproc','report_tissue_usage_stats');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'start_date','startDate','varchar','input','24','report_tissue_usage_stats');
INSERT INTO sproc_args VALUES(2,'end_date','endDate','varchar','input','24','report_tissue_usage_stats');
INSERT INTO sproc_args VALUES(3,'organism_id_filter_list','organismIDFilterList','varchar','input','2000','report_tissue_usage_stats');
INSERT INTO sproc_args VALUES(4,'campaign_id_filter_list','campaignIDFilterList','varchar','input','2000','report_tissue_usage_stats');
INSERT INTO sproc_args VALUES(5,'instrument_filter_list','instrumentFilterList','varchar','input','2000','report_tissue_usage_stats');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'start_date','Starting Date','text','24','80','','','','trim');
INSERT INTO form_fields VALUES(2,'end_date','Ending Date','text','24','80','','','','trim');
INSERT INTO form_fields VALUES(3,'campaign_id_filter_list','Campaign ID List','text','24','2000','','','','trim');
INSERT INTO form_fields VALUES(4,'organism_id_filter_list','Organism ID List','text','24','2000','','','','trim');
INSERT INTO form_fields VALUES(5,'instrument_filter_list','Instrument Filter List','text','24','2000','','','','trim');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'experiments','invoke_entity','Tissue','experiment/report/-/-/-/-/@','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'start_date','default_function','PreviousNWeeks:10');
INSERT INTO form_field_options VALUES(2,'end_date','default_function','CurrentDate');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'instrument_filter_list','picker.append','instrumentNamePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'campaign_id_filter_list','picker.append','campaignIDPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'organism_id_filter_list','picker.append','organismIDPickList','','',',','');
COMMIT;
