﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Manager_Type_Report');
INSERT INTO "general_params" VALUES('list_report_data_cols','ID as [Sel], ID as [ID], Manager_Type as [Manager_Type], Manager_Count_Active as [Manager_Count_Active], Manager_Count_Inactive as [Manager_Count_Inactive]');
INSERT INTO "general_params" VALUES('my_db_group','manager_control');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Manager_Type');
INSERT INTO "general_params" VALUES('list_report_cmds','mc_enable_control_by_manager_type_cmds');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateSingleMgrTypeControlParam');
INSERT INTO "general_params" VALUES('list_report_cmds_url','mc_enable_control_by_manager_type/operation');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','ID','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','mc_enable_control_by_manager/report','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_manager_type','Manager Type','30!','','Manager_Type','ContainsText','text','50','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'paramName','paramName','varchar','input','32','UpdateSingleMgrTypeControlParam');
INSERT INTO "sproc_args" VALUES(2,'newValue','newValue','varchar','input','128','UpdateSingleMgrTypeControlParam');
INSERT INTO "sproc_args" VALUES(3,'managerTypeIDList','managerTypeIDList','varchar','input','2048','UpdateSingleMgrTypeControlParam');
INSERT INTO "sproc_args" VALUES(4,'<local>','callingUser','varchar','input','128','UpdateSingleMgrTypeControlParam');
COMMIT;
