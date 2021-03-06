﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','V_Param_Value');
INSERT INTO "general_params" VALUES('my_db_group','manager_control');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Param_Value');
INSERT INTO "general_params" VALUES('list_report_data_cols','Mgr_Name as [Mgr_Name], Param_Name as [Param_Name], Entry_ID as [Entry_ID], Type_ID as [Type_ID], Value as [Value], Mgr_ID as [Mgr_ID], Comment as [Comment], Last_Affected as [Last_Affected], Entered_By as [Entered_By], Mgr_Type_ID as [Mgr_Type_ID]');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_m_name','Mgr_Name','20','','Mgr_Name','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_paramname','Param_Name','20','','Param_Name','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_value','Value','20','','Value','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_comment','Comment','20','','Comment','ContainsText','text','255','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_last_affected','Last_Affected','20','','Last_Affected','LaterThan','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_entered_by','Entered_By','20','','Entered_By','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Mgr_Name','invoke_entity','value','pipeline_processor_step_tools/show','');
COMMIT;
