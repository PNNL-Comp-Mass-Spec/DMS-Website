﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Material_Items_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Material_Items_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Container, Item');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_container','Container','6','','Container','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_location','Location','6','','Location','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_item','Item','6','','Item','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_item_type','Item Type','6','','Item_Type','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_container_status','Container Status','6','','Container Status','StartsWithText','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_prep_request_id','Prep Request','6','','Prep Request','Equals','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Item','select_case','Item_Type','','{"Biomaterial":"cell_culture","Experiment":"experiment","RefCompound":"reference_compound/report"}');
INSERT INTO "list_report_hotlinks" VALUES(2,'Container','invoke_entity','value','material_container/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Location','invoke_entity','value','material_location/report/~@','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Prep Request','invoke_entity','value','sample_prep_request/show/','');
COMMIT;
