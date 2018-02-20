PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Material_Containers_Picklist');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Container');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','yes');
INSERT INTO "general_params" VALUES('alternate_title_report','Choose Material Container');
INSERT INTO "general_params" VALUES('list_report_data_cols','Container AS Sel, *');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Sel','CHECKBOX','Container','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Container','update_opener','Container','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_container','Container','10','','Container','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_type','Type','10','','Type','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_status','Status','10','','Status','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_comment','Comment','10','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_location','Location','15!','','Location','ContainsText','text','128','','');
COMMIT;
