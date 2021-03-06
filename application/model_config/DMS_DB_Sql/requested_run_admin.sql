﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Requested_Run_Admin_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_cols','Request as Sel, Name, Campaign, Experiment, Dataset, Instrument, [Inst. Group], Type, [Separation Group], Origin, Request, Status, Requester, WPN, WP_State, [Days In Queue], [Queue State], [Queued Instrument], [Queue Date], Pri, Batch, [Block], [Run Order], [Dataset Comment], [Request Name Code], #DaysInQueue, #WPActivationState');
INSERT INTO "general_params" VALUES('list_report_cmds','requested_run_admin_cmds');
INSERT INTO "general_params" VALUES('list_report_cmds_url','requested_run_admin/operation');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateRequestedRunAdmin');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Request');
INSERT INTO "general_params" VALUES('updatewp_sproc','UpdateRequestedRunWP');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_name','Name','35!','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_request','RequestID','12','','Request','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_status','Status','20','','Status','StartsWithText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_batch','Batch','20','','Batch','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_campaign','Campaign','30!','','Campaign','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_queue_state','Queue State','20','','Queue State','StartsWithText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_queued_instrument','Queued Instrument','20','','Queued Instrument','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_requestNameCode','Code','20!','','Request Name Code','StartsWithText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Request','invoke_entity','value','requested_run/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Campaign','invoke_entity','value','campaign/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Experiment','invoke_entity','value','experiment/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Sel','CHECKBOX','value','','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(7,'WP_State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(8,'WPN','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(9,'Batch','invoke_entity','value','requested_run_batch/show','');
INSERT INTO "list_report_hotlinks" VALUES(10,'+Sel','no_export','value','','');
INSERT INTO "list_report_hotlinks" VALUES(11,'#DaysInQueue','no_export','value','','');
INSERT INTO "list_report_hotlinks" VALUES(12,'#WPActivationState','no_export','value','','');
INSERT INTO "list_report_hotlinks" VALUES(13,'Pri','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(14,'Batch','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(15,'Block','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(16,'Run Order','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(17,'+Days In Queue','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(18,'+Request','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(19,'Status','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(20,'+WP_State','export_align','value','','{"Align":"Center"}');
INSERT INTO "list_report_hotlinks" VALUES(21,'Queue State','export_align','value','','{"Align":"Center"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'requestList','requestList','text','input','2147483647','UpdateRequestedRunAdmin');
INSERT INTO "sproc_args" VALUES(2,'<local>','mode','varchar','input','32','UpdateRequestedRunAdmin');
INSERT INTO "sproc_args" VALUES(3,'<local>','message','varchar','output','512','UpdateRequestedRunAdmin');
INSERT INTO "sproc_args" VALUES(4,'<local>','callingUser','varchar','input','128','UpdateRequestedRunAdmin');
INSERT INTO "sproc_args" VALUES(5,'OldWorkPackage','OldWorkPackage','varchar','input','50','UpdateRequestedRunWP');
INSERT INTO "sproc_args" VALUES(6,'NewWorkPackage','NewWorkPackage','varchar','input','50','UpdateRequestedRunWP');
INSERT INTO "sproc_args" VALUES(7,'RequestedIdList','RequestedIdList','varchar','input','2147483647','UpdateRequestedRunWP');
INSERT INTO "sproc_args" VALUES(8,'<local>','message','varchar','output','512','UpdateRequestedRunWP');
INSERT INTO "sproc_args" VALUES(9,'<local>','callingUser','varchar','input','128','UpdateRequestedRunWP');
INSERT INTO "sproc_args" VALUES(10,'InfoOnly','InfoOnly','tinyint','input','','UpdateRequestedRunWP');
COMMIT;
