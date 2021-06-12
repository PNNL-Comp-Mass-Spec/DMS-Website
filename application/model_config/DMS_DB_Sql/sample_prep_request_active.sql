﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Sample_Prep_Request_Active_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_requestname','RequestName','32','','RequestName','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_organism','Organism','32','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_WP','WP','32','','Work Package','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_requested_personnel','Req. Personnel','32','','RequestedPersonnel','ContainsText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_assigned_personnel','Assigned Personnel','32','','AssignedPersonnel','ContainsText','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','ID','sample_prep_request/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'Work Package','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'WP State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'EUS Proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Comment','min_col_width','value','50','');
COMMIT;
