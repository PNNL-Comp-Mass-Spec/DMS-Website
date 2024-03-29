﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('instrument_allocation_data_sproc','get_instrument_usage_allocations_for_grid');
INSERT INTO general_params VALUES('requested_run_data_sproc','get_requested_runs_for_grid');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'itemList','itemList','text','input','2147483647','get_instrument_usage_allocations_for_grid');
INSERT INTO sproc_args VALUES(2,'fiscalYear','fiscalYear','varchar','input','256','get_instrument_usage_allocations_for_grid');
INSERT INTO sproc_args VALUES(3,'<local>','message','varchar','output','512','get_instrument_usage_allocations_for_grid');
INSERT INTO sproc_args VALUES(4,'itemList','itemList','text','input','2147483647','get_requested_runs_for_grid');
INSERT INTO sproc_args VALUES(5,'<local>','message','varchar','output','512','get_requested_runs_for_grid');
COMMIT;
