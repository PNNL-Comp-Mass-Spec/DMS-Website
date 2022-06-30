﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Sample_Prep_Request_Assignment');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_cmds','sample_prep_request_assignment_cmds');
INSERT INTO "general_params" VALUES('list_report_cmds_url','/sample_prep_request_assignment/operation');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateSampleRequestAssignments');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','32','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','ID','','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'ID','invoke_entity','ID','sample_prep_request/show/','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Prep Method','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Comment','min_col_width','value','75','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Reason','min_col_width','value','75','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'<local>','mode','varchar','input','32','UpdateSampleRequestAssignments');
INSERT INTO "sproc_args" VALUES(2,'newValue','newValue','varchar','input','512','UpdateSampleRequestAssignments');
INSERT INTO "sproc_args" VALUES(3,'reqIDList','reqIDList','varchar','input','2048','UpdateSampleRequestAssignments');
COMMIT;
