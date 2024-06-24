﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_processor_step_tools_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','processor_name, tool_name');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('my_db_group','broker');
INSERT INTO general_params VALUES('detail_report_data_table','v_processor_step_tools_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','processor_name');
INSERT INTO general_params VALUES('detail_report_data_id_type','string');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_processor_name','Processor','6','','processor_name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_tool_name','Tool','6','','tool_name','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_priority','Priority','6','','priority','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_enabled','Enabled','6','','enabled','Equals','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_machine','Machine','6','','machine','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_group_name','Group','6','','group_name','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'tool_name','invoke_entity','value','pipeline_step_tools/show','');
INSERT INTO list_report_hotlinks VALUES(2,'processor_name','invoke_entity','processor_name','pipeline_processor_step_tools/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'tool_name','detail-report','tool_name','pipeline_processor_step_tools/report/-/@','valueCol','dl_tool_name','');
INSERT INTO detail_report_hotlinks VALUES(2,'machine','detail-report','machine','pipeline_processor_step_tools/report/-/-/-/-/@','valueCol','dl_machine','');
INSERT INTO detail_report_hotlinks VALUES(3,'group_name','detail-report','group_name','pipeline_processor_step_tools/report/-/-/-/-/-/@','valueCol','dl_group_name','');
COMMIT;
