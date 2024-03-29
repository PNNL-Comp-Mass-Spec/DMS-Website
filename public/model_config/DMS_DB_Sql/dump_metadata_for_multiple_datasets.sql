﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_sproc','dump_metadata_for_multiple_datasets');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'dataset_list','Dataset List','area','','','10','60','','trim|max_length[7000]');
INSERT INTO form_fields VALUES(2,'options','Options','area','','','2','60','(There are no options presently available.)','trim|max_length[256]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'dataset_list','list-report.helper','','helper_dataset_ckbx/report','',',','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'dataset_list','dataset_List','varchar','input','7000','dump_metadata_for_multiple_datasets');
INSERT INTO sproc_args VALUES(2,'options','Options','varchar','input','256','dump_metadata_for_multiple_datasets');
INSERT INTO sproc_args VALUES(3,'<local>','message','varchar','output','512','dump_metadata_for_multiple_datasets');
COMMIT;
