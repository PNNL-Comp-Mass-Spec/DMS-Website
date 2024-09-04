﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Material_Freezers');
INSERT INTO general_params VALUES('list_report_data_table','v_freezer_list_report');
INSERT INTO general_params VALUES('detail_report_data_table','v_freezer_list_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','freezer');
INSERT INTO general_params VALUES('detail_report_data_id_type','string');
INSERT INTO general_params VALUES('list_report_data_sort_col','freezer');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_Freezer','Freezer','32','','freezer','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_Comment','Comment','32','','comment','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'freezer','invoke_entity','freezer','freezers/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'freezer_tag','invoke_entity','freezer','material_location/report/-/~@','');
INSERT INTO list_report_hotlinks VALUES(3,'containers','invoke_entity','freezer_tag','material_container/report/-/StartsWith__@','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO detail_report_hotlinks VALUES(1,'freezer','detail-report','freezer','material_location/report/-/~@','labelCol','dl_freezer_locations','');
INSERT INTO detail_report_hotlinks VALUES(2,'containers','detail-report','freezer_tag','material_container/report/-/StartsWith__@','labelCol','dl_freezer_containers','');
COMMIT;
