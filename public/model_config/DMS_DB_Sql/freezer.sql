﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('operations_sproc','update_material_locations');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'locationList','locationList','text','input','2147483647','update_material_locations');
INSERT INTO sproc_args VALUES(2,'<local>','message','varchar','output','512','update_material_locations');
INSERT INTO sproc_args VALUES(3,'<local>','callingUser','varchar','input','128','update_material_locations');
COMMIT;
