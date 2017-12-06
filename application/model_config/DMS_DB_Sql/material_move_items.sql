PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Material_Items_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Material_Items_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('list_report_cmds','material_move_items_cmds');
INSERT INTO "general_params" VALUES('list_report_cmds_url','/material_move_items/operation');
INSERT INTO "general_params" VALUES('list_report_data_cols','Item, ID, '''' AS Sel, Item_Type, Container, Type, Location, #I_ID');
INSERT INTO "general_params" VALUES('list_report_data_order_by','Item');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateMaterialItems');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_container','Container','6','','Container','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_location','Location','15!','','Location','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_item','Item','6','','Item','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_item_type','Item_Type','6','','Item_Type','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Item','select_case','Item_Type','','{"Biomaterial":"cell_culture","Experiment":"experiment","RefCompound":"reference_compound/report"}');
INSERT INTO "list_report_hotlinks" VALUES(2,'Sel','CHECKBOX','#I_ID','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'<local>','mode','varchar','input','32','UpdateMaterialItems');
INSERT INTO "sproc_args" VALUES(2,'itemList','itemList','varchar','input','4096','UpdateMaterialItems');
INSERT INTO "sproc_args" VALUES(3,'itemType','itemType','varchar','input','128','UpdateMaterialItems');
INSERT INTO "sproc_args" VALUES(4,'newValue','newValue','varchar','input','128','UpdateMaterialItems');
INSERT INTO "sproc_args" VALUES(5,'comment','comment','varchar','input','512','UpdateMaterialItems');
INSERT INTO "sproc_args" VALUES(6,'<local>','message','varchar','output','512','UpdateMaterialItems');
INSERT INTO "sproc_args" VALUES(7,'<local>','callingUser','varchar','input','128','UpdateMaterialItems');
COMMIT;
