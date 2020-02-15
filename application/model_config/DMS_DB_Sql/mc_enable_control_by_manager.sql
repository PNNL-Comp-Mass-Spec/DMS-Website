﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Manager_List_By_Type');
INSERT INTO "general_params" VALUES('list_report_data_cols','ID as Sel, ID, [Manager Name], [Manager Type], M_TypeID as TypeID, [Active], [State Last Changed], [Changed By], [Comment]');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Manager Name');
INSERT INTO "general_params" VALUES('my_db_group','manager_control');
INSERT INTO "general_params" VALUES('list_report_cmds','mc_enable_control_by_manager_cmds');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateSingleMgrControlParam');
INSERT INTO "general_params" VALUES('list_report_cmds_url','mc_enable_control_by_manager/operation');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','ID','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Manager Name','invoke_entity','value','mc_params/report','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Manager Type','invoke_entity','value','mc_enable_control_by_manager_type/report','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_m_typeid','M_TypeID','2!','','M_TypeID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_manager_name','Manager Name','10!','','Manager Name','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_manager_type','Manager Type','20!','','Manager Type','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_active','Active','6!','','Active','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_state_last_changed','State Last Changed','20','','State Last Changed','LaterThan','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_changed_by','Changed By','20','','Changed By','ContainsText','text','128','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'paramName','paramName','varchar','input','32','UpdateSingleMgrControlParam');
INSERT INTO "sproc_args" VALUES(2,'newValue','newValue','varchar','input','128','UpdateSingleMgrControlParam');
INSERT INTO "sproc_args" VALUES(3,'managerIDList','managerIDList','varchar','input','8000','UpdateSingleMgrControlParam');
INSERT INTO "sproc_args" VALUES(4,'<local>','callingUser','varchar','input','128','UpdateSingleMgrControlParam');
COMMIT;
