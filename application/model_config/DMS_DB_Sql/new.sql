PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_@@@');
INSERT INTO "general_params" VALUES('list_report_data_table','V_@@@_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_@@@_Detail_Report');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_@@@_Entry');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdate@@@');
INSERT INTO "general_params" VALUES('operations_sproc','Update@@@');
COMMIT;
