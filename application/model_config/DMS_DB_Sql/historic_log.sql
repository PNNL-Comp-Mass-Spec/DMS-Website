﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','DMSHistoricLog1.dbo.V_Historic_Log_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
COMMIT;
