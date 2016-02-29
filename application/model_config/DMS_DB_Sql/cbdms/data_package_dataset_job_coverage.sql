PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_sproc','GetPackageDatasetJobToolCrosstab');
INSERT INTO "general_params" VALUES('my_db_group','package');
INSERT INTO "general_params" VALUES('list_report_autoload','yes');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Jobs','invoke_multi_col','Jobs','data_package_job_coverage/report','{"#ID":0, "Dataset":0}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'DataPackageID','DataPackageID','int','input','','GetPackageDatasetJobToolCrosstab');
INSERT INTO "sproc_args" VALUES(2,'<local>','message','varchar','output','512','GetPackageDatasetJobToolCrosstab');
INSERT INTO "sproc_args" VALUES(3,'<local>','callingUser','varchar','input','128','GetPackageDatasetJobToolCrosstab');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'DataPackageID',' Data Package ID','text','12','12','','','','trim|max_length[12]');
COMMIT;
