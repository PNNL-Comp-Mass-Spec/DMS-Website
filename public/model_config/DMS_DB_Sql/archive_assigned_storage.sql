PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Assigned_Archive_Storage');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
COMMIT;
