﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Capture_Step_Tools_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Capture_Step_Tools_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Name');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateStepTools');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Capture_Step_Tools_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Name');
INSERT INTO "general_params" VALUES('my_db_group','capture');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Name',' Name','text-if-new','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(2,'Description',' Description','area','','','4','70','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(3,'BionetRequired',' Bionet Required','text','1','1','','','N','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(4,'OnlyOnStorageServer',' Only On Storage Server','text','1','1','','','N','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(5,'InstrumentCapacityLimited',' Instrument Capacity Limited','text','1','1','','','N','trim|max_length[1]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'BionetRequired','picker.replace','YNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'OnlyOnStorageServer','picker.replace','YNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'InstrumentCapacityLimited','picker.replace','YNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','6','','Name','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_type','Type','6','','Type','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','6','','Description','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Name','invoke_entity','value','capture_step_tools/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Name','Name','varchar','input','64','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(2,'Description','Description','varchar','input','512','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(3,'BionetRequired','BionetRequired','char','input','1','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(4,'OnlyOnStorageServer','OnlyOnStorageServer','char','input','1','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(5,'InstrumentCapacityLimited','InstrumentCapacityLimited','char','input','1','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(6,'<local>','mode','varchar','input','12','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(7,'<local>','message','varchar','output','512','AddUpdateStepTools');
INSERT INTO "sproc_args" VALUES(8,'<local>','callingUser','varchar','input','128','AddUpdateStepTools');
COMMIT;
