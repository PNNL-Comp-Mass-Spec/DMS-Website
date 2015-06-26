PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('entry_sproc','UpdateMultipleCaptureJobs');
INSERT INTO "general_params" VALUES('alternate_title_create','Update Multiple Capture Jobs');
INSERT INTO "general_params" VALUES('my_db_group','capture');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'JobList','JobList','varchar','input','6000','UpdateMultipleCaptureJobs');
INSERT INTO "sproc_args" VALUES(2,'action','action','varchar','input','32','UpdateMultipleCaptureJobs');
INSERT INTO "sproc_args" VALUES(3,'<local>','mode','varchar','input','12','UpdateMultipleCaptureJobs');
INSERT INTO "sproc_args" VALUES(4,'<local>','message','varchar','output','512','UpdateMultipleCaptureJobs');
INSERT INTO "sproc_args" VALUES(5,'<local>','callingUser','varchar','input','128','UpdateMultipleCaptureJobs');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'JobList','Capture Jobs','area','','','15','70','','trim|max_length[6000]|NoBlank');
INSERT INTO "form_fields" VALUES(2,'action','Action','text','32','32','','','','trim|max_length[32]|NoBlank');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'update','override','Update','','add');
INSERT INTO "entry_commands" VALUES(2,'preview','cmd','Validate changes','Validate by performing update process without making any changes to database','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'JobList','list-report.helper','┬á','helper_capture_job_steps/report/-/-/-/-','',',','Job steps (all)...');
INSERT INTO "form_field_choosers" VALUES(2,'JobList','list-report.helper','┬á','helper_capture_job_steps/report/-/-/Failed/Ignore','',',','Job steps (failed)...');
INSERT INTO "form_field_choosers" VALUES(3,'action','picker.replace','captureMultiJobUpdateActionsPickList','┬á','',',','┬á');
COMMIT;
