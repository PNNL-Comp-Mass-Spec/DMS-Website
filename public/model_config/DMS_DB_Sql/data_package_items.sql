﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Data_Package_All_Items_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('list_report_cmds','data_package_cmds');
INSERT INTO "general_params" VALUES('list_report_cmds_url','/data_package_items/operation');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateDataPackageItemsXML');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID, Item_Type, Item');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','5!','','ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_item_type','Item_Type','32','','Item_Type','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_item','Item','25!','','Item','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_parent_entity','Parent_Entity','32','','Parent_Entity','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','value','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','data_package/show','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'paramListXML','paramListXML','text','input','2147483647','UpdateDataPackageItemsXML');
INSERT INTO "sproc_args" VALUES(2,'comment','comment','varchar','input','512','UpdateDataPackageItemsXML');
INSERT INTO "sproc_args" VALUES(3,'<local>','mode','varchar','input','12','UpdateDataPackageItemsXML');
INSERT INTO "sproc_args" VALUES(4,'removeParents','removeParents','tinyint','input','','UpdateDataPackageItemsXML');
INSERT INTO "sproc_args" VALUES(5,'<local>','message','varchar','output','512','UpdateDataPackageItemsXML');
INSERT INTO "sproc_args" VALUES(6,'<local>','callingUser','varchar','input','128','UpdateDataPackageItemsXML');
COMMIT;
