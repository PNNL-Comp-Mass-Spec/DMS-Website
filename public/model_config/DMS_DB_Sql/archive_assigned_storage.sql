﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_assigned_archive_storage');
INSERT INTO general_params VALUES('list_report_data_sort_dir','desc');
INSERT INTO general_params VALUES('list_report_data_sort_col','archive_path_id');
COMMIT;
