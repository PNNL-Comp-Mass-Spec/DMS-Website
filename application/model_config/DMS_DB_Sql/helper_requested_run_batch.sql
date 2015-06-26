PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Requested_Run_Batch_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_cols','[ID], [Name], [Requests], [Req. Priority], [Instrument], [Description], [Owner], [Created], [Comment]');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','update_opener','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','20','','ID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','20','','Name','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','20','','Description','ContainsText','text','256','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_comment','Comment','20','','Comment','ContainsText','text','512','','');
COMMIT;
