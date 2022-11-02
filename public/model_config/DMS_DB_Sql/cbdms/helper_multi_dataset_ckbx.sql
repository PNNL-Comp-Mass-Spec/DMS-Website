﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Archive_List_Report_2');
INSERT INTO general_params VALUES('list_report_data_sort_col','Dataset');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_data_cols','ID, ''x'' AS Sel, Dataset, Instrument, State, Update, [State Last Affected], [Update State Last Affected], [Archive Path], [Archive Server], [Storage Server]');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_update','Update','32','','Update','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_state','picker.replace','datasetStatePickList','','',',');
INSERT INTO primary_filter_choosers VALUES(2,'pf_update','picker.replace','archiveUpdateName','','',',');
INSERT INTO primary_filter_choosers VALUES(3,'pf_instrument','picker.replace','instrumentNamePickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Sel','CHECKBOX','Dataset','','');
INSERT INTO list_report_hotlinks VALUES(2,'Dataset','update_opener','value','','');
COMMIT;
