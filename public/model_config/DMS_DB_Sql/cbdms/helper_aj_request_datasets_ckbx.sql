﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_autoload','1');
INSERT INTO "general_params" VALUES('list_report_sproc','FindMatchingDatasetsForJobRequest');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'requestID','Request','text','4','4','','','','trim|max_length[4]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Dataset','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Jobs','invoke_entity','Dataset','analysis_job/report/-/-/-/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Dataset','update_opener','Dataset','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'requestID','requestID','int','input','','FindMatchingDatasetsForJobRequest');
INSERT INTO "sproc_args" VALUES(2,'<local>','message','varchar','output','512','FindMatchingDatasetsForJobRequest');
COMMIT;