﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_EMSL_Instrument_Usage_Report');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Usage_Report_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Instrument_Usage_Report_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Seq');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Instrument_Usage_Report_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Seq');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateInstrumentUsageReport');
INSERT INTO "general_params" VALUES('list_report_cmds','instrument_usage_report_cmds');
INSERT INTO "general_params" VALUES('list_report_cmds_url','instrument_usage_report/operation');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateInstrumentUsageReport');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_year','Year','20','','Year','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_month','Month','20','','Month','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','64','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Seq','Seq','int','input','','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(2,'EMSLInstID','EMSLInstID','int','input','','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(3,'Instrument','Instrument','varchar','input','64','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(4,'Type','Type','varchar','input','128','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(5,'Start','Start','varchar','input','32','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(6,'Minutes','Minutes','int','input','','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(7,'Year','Year','int','input','','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(8,'Month','Month','int','input','','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(9,'ID','ID','int','input','','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(10,'Proposal','Proposal','varchar','input','32','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(11,'Usage','Usage','varchar','input','32','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(12,'Users','Users','varchar','input','1024','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(13,'Operator','Operator','varchar','input','64','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(14,'Comment','Comment','varchar','input','4096','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(15,'<local>','mode','varchar','input','12','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(16,'<local>','message','varchar','output','512','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(17,'<local>','callingUser','varchar','input','128','AddUpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(18,'factorList','factorList','text','input','2147483647','UpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(19,'operation','operation','varchar','input','32','UpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(20,'year','year','varchar','input','12','UpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(21,'month','month','varchar','input','12','UpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(22,'instrument','instrument','varchar','input','128','UpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(23,'<local>','message','varchar','output','512','UpdateInstrumentUsageReport');
INSERT INTO "sproc_args" VALUES(24,'<local>','callingUser','varchar','input','128','UpdateInstrumentUsageReport');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Seq',' Seq','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'EMSLInstID',' EMSLInst ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(3,'Instrument',' Instrument','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(4,'Type',' Type','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(5,'Start',' Start','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(6,'Minutes',' Minutes','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(7,'Year',' Year','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(8,'Month',' Month','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(9,'ID',' ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(10,'Proposal',' Proposal','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(11,'Usage',' Usage','text','32','32','','','','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(12,'Users',' Users','area','','','4','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(13,'Operator',' Operator','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(14,'Comment',' Comment','area','','','4','70','','trim|max_length[4096]');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Seq','invoke_entity','value','instrument_usage_report/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','datasetid/show','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'ID','detail-report','ID','datasetid/show','valueCol','dl_dataset_id','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Instrument','detail-report','Instrument','instrument/show/','valueCol','dl_instrument','');
COMMIT;
