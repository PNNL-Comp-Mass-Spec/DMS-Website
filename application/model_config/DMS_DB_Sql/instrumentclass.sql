﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Instrument_Class_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Instrument_Class_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Instrument Class');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateInstrumentClass');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Instrument_Class_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','InstrumentClass');
INSERT INTO "general_params" VALUES('entry_block_new','True');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'InstrumentClass','Instrument Class','non-edit','32','32','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(2,'IsPurgable','Is Purgable','text','1','1','','','','trim|required|max_length[1]');
INSERT INTO "form_fields" VALUES(3,'RawDataType','Raw Data Type','text','32','32','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(4,'RequiresPreparation','Requires Preparation','text','1','1','','','','trim|required|max_length[1]');
INSERT INTO "form_fields" VALUES(5,'Comment','Comment','area','','','3','70','','trim|max_length[255]');
INSERT INTO "form_fields" VALUES(6,'Params','Params','area','','','18','70','<sections><section name="DatasetQC"><item key="SaveTICAndBPIPlots" value="True"/><item key="SaveLCMS2DPlots" value="True"/><item key="ComputeOverallQualityScores" value="True"/><item key="CreateDatasetInfoFile" value="True"/><item key="LCMS2DPlotMZResolution" value="0.4"/><item key="LCMS2DPlotMaxPointsToPlot" value="500000"/><item key="LCMS2DPlotMinPointsPerSpectrum" value="2"/><item key="LCMS2DPlotMinIntensity" value="0"/><item key="LCMS2DOverviewPlotDivisor" value="10"/></section></sections>','trim|max_length[2147483647]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'IsPurgable','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'RawDataType','picker.replace','rawDataTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'RequiresPreparation','picker.replace','yesNoAsOneZeroPickList','','',',','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Instrument Class','invoke_entity','value','instrumentclass/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'InstrumentClass','instrumentClass','varchar','input','32','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(2,'IsPurgable','isPurgable','varchar','input','1','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(3,'RawDataType','rawDataType','varchar','input','32','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(4,'RequiresPreparation','requiresPreparation','varchar','input','1','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(6,'Params','params','text','input','2147483647','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(7,'Comment','comment','varchar','input','255','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(8,'<local>','mode','varchar','input','12','AddUpdateInstrumentClass');
INSERT INTO "sproc_args" VALUES(9,'<local>','message','varchar','output','512','AddUpdateInstrumentClass');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Params','auto_format','xml');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_class','Class','10','','Instrument Class','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_raw_data_type','Raw Data Type','10','','Raw Data Type','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_comment','Comment','10','','Comment','ContainsText','text','80','','');
COMMIT;
