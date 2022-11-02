﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Processor_Step_Tools_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('my_db_group','capture');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_processor_name','Processor_Name','6','','Processor_Name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_tool_name','Tool_Name','6','','Tool_Name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_priority','Priority','6','','Priority','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_enabled','Enabled','6','','Enabled','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Tool_Name','invoke_entity','value','capture_step_tools/show','');
INSERT INTO list_report_hotlinks VALUES(2,'Processor_Name','invoke_entity','value','capture_local_processors/show','');
COMMIT;
