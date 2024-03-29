﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_material_containers_picklist');
INSERT INTO general_params VALUES('list_report_data_sort_col','sort_key');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
INSERT INTO general_params VALUES('alternate_title_report','Choose Material Container');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_container','Container','10','','container','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_type','Type','10','','type','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_status','Status','10','','status','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_comment','Comment','10','','comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_location','Location','20!','','location','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'container','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(2,'sort_key','no_display','value','','');
COMMIT;
