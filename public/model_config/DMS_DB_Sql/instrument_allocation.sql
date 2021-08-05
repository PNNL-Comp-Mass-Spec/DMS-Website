﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Allocation_List_Report');
INSERT INTO "general_params" VALUES('base_table','T_Instrument_Allocation');
INSERT INTO "general_params" VALUES('list_report_cmds','instrument_allocation_cmds');
INSERT INTO "general_params" VALUES('operations_sproc','UpdateInstrumentUsageAllocationsXML');
INSERT INTO "general_params" VALUES('list_report_cmds_url','instrument_allocation/operation');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Instrument_Allocation_Detail_Report');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Instrument_Allocation_Detail_Entry');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','FY_Proposal');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','FY_Proposal');
INSERT INTO "general_params" VALUES('entry_sproc','UpdateInstrumentUsageAllocations');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_fiscal_year','Fiscal Year','20','','Fiscal_Year','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_proposal_id','Proposal ID','20','','Proposal_ID','ContainsText','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Proposal_ID','invoke_entity','#FY_Proposal','instrument_allocation/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'parameterList','parameterList','text','input','2147483647','UpdateInstrumentUsageAllocationsXML');
INSERT INTO "sproc_args" VALUES(2,'<local>','message','varchar','output','512','UpdateInstrumentUsageAllocationsXML');
INSERT INTO "sproc_args" VALUES(3,'<local>','callingUser','varchar','input','128','UpdateInstrumentUsageAllocationsXML');
INSERT INTO "sproc_args" VALUES(4,'infoOnly','infoOnly','tinyint','input','','UpdateInstrumentUsageAllocationsXML');
INSERT INTO "sproc_args" VALUES(5,'FY_Proposal','FYProposal','varchar','input','64','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(6,'Fiscal_Year','FiscalYear','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(7,'Proposal_ID','ProposalID','varchar','input','128','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(8,'FT','FT','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(9,'FTComment','FTComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(10,'IMS','IMS','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(11,'IMSComment','IMSComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(12,'ORB','ORB','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(13,'ORBComment','ORBComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(14,'EXA','EXA','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(15,'EXAComment','EXAComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(16,'LTQ','LTQ','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(17,'LTQComment','LTQComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(18,'GC','GC','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(19,'GCComment','GCComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(20,'QQQ','QQQ','varchar','input','24','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(21,'QQQComment','QQQComment','varchar','input','256','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(22,'<local>','mode','varchar','input','12','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(23,'<local>','message','varchar','output','512','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(24,'<local>','callingUser','varchar','input','128','UpdateInstrumentUsageAllocations');
INSERT INTO "sproc_args" VALUES(25,'infoOnly','infoOnly','tinyint','input','','UpdateInstrumentUsageAllocations');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'FY_Proposal','FY_Proposal','non-edit','64','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'Fiscal_Year','Fiscal Year','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(3,'Proposal_ID','Proposal ID','text','64','64','','','','trim');
INSERT INTO "form_fields" VALUES(4,'FT','FT Hours','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(5,'FTComment','FT Comment','text','64','256','','','','trim');
INSERT INTO "form_fields" VALUES(6,'IMS','IMS Hours','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(7,'IMSComment','IMS Comment','text','64','256','','','','trim');
INSERT INTO "form_fields" VALUES(8,'ORB','Orbitrap Hours','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(9,'ORBComment','Orbi Comment','text','64','256','','','','trim');
INSERT INTO "form_fields" VALUES(10,'EXA','Exactive Hours','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(11,'EXAComment','Exactive Comment','text','64','256','','','','trim');
INSERT INTO "form_fields" VALUES(12,'LTQ','LTQ Hours','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(13,'LTQComment','LTQ Comment','text','64','256','','','','trim');
INSERT INTO "form_fields" VALUES(14,'GC','GC Hours','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(15,'GCComment','GC Comment','text','64','256','','','','trim');
INSERT INTO "form_fields" VALUES(16,'QQQ','QQQ','text','24','','','','','trim');
INSERT INTO "form_fields" VALUES(17,'QQQComment','QQQ Comment','text','64','256','','','','trim');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Fiscal Year','detail-report','Fiscal Year','instrument_allocation/report/','labelCol','dl_Fiscal_Year',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Proposal_ID','detail-report','Proposal_ID','eus_proposals/show/','labelCol','dl_Proposal',NULL);
COMMIT;