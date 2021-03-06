﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Sample_Prep_Request_Planning_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','#Assigned_SortKey, Days In Queue');
INSERT INTO "general_params" VALUES('list_report_disable_sort_persist','false');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_assigned','Assigned','32','','Assigned','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_requestname','Request Name','32','','RequestName','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_WP','WP','32','','WP','ContainsText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','ID','sample_prep_request/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'WP','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'WP State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Campaign','invoke_entity','value','campaign/show','');
INSERT INTO "list_report_hotlinks" VALUES(6,'#DaysInQueue','no_export','value','','');
INSERT INTO "list_report_hotlinks" VALUES(7,'#WPActivationState','no_export','value','','');
INSERT INTO "list_report_hotlinks" VALUES(8,'+ID','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(9,'Num Samples','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(10,'MS Runs TBG','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(11,'+Days In Queue','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(12,'WP','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(13,'WP State','export_align','value','','{"Align":"Center"}');
COMMIT;
