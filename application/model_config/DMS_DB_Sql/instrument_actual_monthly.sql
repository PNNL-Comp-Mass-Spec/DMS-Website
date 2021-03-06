﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Actual_Montly_List_Report');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_year','Year','20','','Year','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_month','Month','20','','Month','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_proposal_id','Proposal_ID','20','','Proposal_ID','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_title','Title','20','','Title','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_status','Status','20','','Status','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Proposal_ID','invoke_entity','Proposal_ID','eus_proposals/show','');
COMMIT;
