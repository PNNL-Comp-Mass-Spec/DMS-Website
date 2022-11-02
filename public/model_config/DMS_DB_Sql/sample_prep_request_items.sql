﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Sample_Prep_Request_Items');
INSERT INTO general_params VALUES('list_report_data_table','V_Sample_Prep_Request_Items_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','ID');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_item_type','Item Type','20','','Item Type','StartsWithText','text','512','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_id','ID','20','','ID','Equals','text','20','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_item_name','Item Name','20','','Item Name','ContainsText','text','2048','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_item_id','Item ID','20','','Item ID','Equals','text','512','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Item Type','select_case','Item Type','#link','{"dataset":"dataset", "experiment":"experiment",  "experiment_group":"experiment_group",  "material_container":"material_container",  "prep_lc_run":"prep_lc_run",  "requested_run":"requested_run", "biomaterial":"biomaterial"}');
INSERT INTO list_report_hotlinks VALUES(2,'ID','invoke_entity','value','sample_prep_request/show','');
COMMIT;
