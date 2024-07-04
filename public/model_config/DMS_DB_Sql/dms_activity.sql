PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_event_log_24_hour_summary');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
COMMIT;
