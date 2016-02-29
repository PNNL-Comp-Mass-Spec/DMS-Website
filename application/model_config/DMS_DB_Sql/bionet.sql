PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Bionet_Hosts');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Bionet_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Bionet_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Host');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Host','invoke_entity','Host','bionet/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_host','Host','20','','Host','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_ip','IP','20','','IP','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_alias','Alias','20','','Alias','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instruments','Instruments','20','','Instruments','ContainsText','text','64','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Instruments','link_table','Instruments','instrument/report','valueCol','dl_Instruments');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Instrument Datasets','link_table','Instrument Datasets','helper_inst_source/view/','valueCol','dl_InstrumentDatasets');
COMMIT;
