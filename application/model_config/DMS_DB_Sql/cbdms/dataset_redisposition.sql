﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('entry_sproc','UpdateDatasetDispositionsByName');
INSERT INTO "general_params" VALUES('alternate_title_create','Redisposition Datasets');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'datasetList','Dataset List','area','','','4','60','','trim|max_length[6000]');
INSERT INTO "form_fields" VALUES(2,'rating','Rating','text','60','64','','','Not Released','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'recycleRequest','Recycle Request','text','32','32','','','Yes','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(4,'comment','Comment','area','','','3','60','This dataset was redispositioned.','trim|max_length[512]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'datasetList','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/4','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'rating','picker.replace','datasetRatingPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'recycleRequest','picker.replace','yesNoPickList','','',',','');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'update','override','Update','','add');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'datasetList','datasetList','varchar','input','6000','UpdateDatasetDispositionsByName');
INSERT INTO "sproc_args" VALUES(2,'rating','rating','varchar','input','64','UpdateDatasetDispositionsByName');
INSERT INTO "sproc_args" VALUES(3,'comment','comment','varchar','input','512','UpdateDatasetDispositionsByName');
INSERT INTO "sproc_args" VALUES(4,'recycleRequest','recycleRequest','varchar','input','32','UpdateDatasetDispositionsByName');
INSERT INTO "sproc_args" VALUES(5,'<local>','mode','varchar','input','12','UpdateDatasetDispositionsByName');
INSERT INTO "sproc_args" VALUES(6,'<local>','message','varchar','output','512','UpdateDatasetDispositionsByName');
INSERT INTO "sproc_args" VALUES(7,'<local>','callingUser','varchar','input','128','UpdateDatasetDispositionsByName');
COMMIT;
