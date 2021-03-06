﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('instrument_allocation_data_sproc','GetInstrumentUsageAllocationsForGrid');
INSERT INTO "general_params" VALUES('requested_run_data_sproc','GetRequestedRunsForGrid');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'itemList','itemList','text','input','2147483647','GetInstrumentUsageAllocationsForGrid');
INSERT INTO "sproc_args" VALUES(2,'fiscalYear','fiscalYear','varchar','input','256','GetInstrumentUsageAllocationsForGrid');
INSERT INTO "sproc_args" VALUES(3,'<local>','message','varchar','output','512','GetInstrumentUsageAllocationsForGrid');
INSERT INTO "sproc_args" VALUES(4,'itemList','itemList','text','input','2147483647','GetRequestedRunsForGrid');
INSERT INTO "sproc_args" VALUES(5,'<local>','message','varchar','output','512','GetRequestedRunsForGrid');
COMMIT;
