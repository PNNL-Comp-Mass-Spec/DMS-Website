﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_material_locations_picklist');
INSERT INTO general_params VALUES('list_report_data_sort_col','location');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
INSERT INTO general_params VALUES('alternate_title_report','Choose Material Location');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_location','Location','15!','','location','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_freezer','Freezer','12!','','freezer','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_shelf','Shelf','6','','shelf','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_rack','Rack','6','','rack','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_row','Row','6','','row','ContainsText','text','80','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_col','Col','6','','col','ContainsText','text','80','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'location','update_opener','value','','');
COMMIT;
