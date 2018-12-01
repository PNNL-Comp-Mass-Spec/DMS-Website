﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_sproc','ReportProductionStats');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Start_Date','Starting Date','text','24','80','','','','trim');
INSERT INTO "form_fields" VALUES(2,'End_Date','Ending Date','text','24','80','','','','trim');
INSERT INTO "form_fields" VALUES(3,'Production_Only','Production Only','text','24','1','','','0','trim');
INSERT INTO "form_fields" VALUES(4,'Campaign_ID_Filter_List','Campaign ID List','text','24','2000','','','','trim');
INSERT INTO "form_fields" VALUES(5,'EUS_Usage_Filter_List','EUS Usage List','text','24','2000','','','','trim');
INSERT INTO "form_fields" VALUES(6,'Instrument_Filter_List','Instrument Filter List','text','24','2000','','','','trim');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Start_Date','default_function','PreviousNWeeks:10');
INSERT INTO "form_field_options" VALUES(2,'End_Date','default_function','CurrentDate');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Start_Date','startDate','varchar','input','24','ReportProductionStats');
INSERT INTO "sproc_args" VALUES(2,'End_Date','endDate','varchar','input','24','ReportProductionStats');
INSERT INTO "sproc_args" VALUES(3,'Production_Only','productionOnly','tinyint','input','','ReportProductionStats');
INSERT INTO "sproc_args" VALUES(4,'Campaign_ID_Filter_List','CampaignIDFilterList','varchar','input','2000','ReportProductionStats');
INSERT INTO "sproc_args" VALUES(5,'EUS_Usage_Filter_List','EUSUsageFilterList','varchar','input','2000','ReportProductionStats');
INSERT INTO "sproc_args" VALUES(6,'Instrument_Filter_List','InstrumentFilterList','varchar','input','2000','ReportProductionStats');
INSERT INTO "sproc_args" VALUES(7,'<local>','message','varchar','output','256','ReportProductionStats');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Total Datasets','invoke_entity','Instrument','dataset/report/-/-/-','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Study Specific Datasets','column_tooltip','value','[Total Datasets] - [Blank Datasets] - [QC Datasets] - [Bad Datasets]','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Study Specific Datasets per day','column_tooltip','value','Study specific datasets per day','');
INSERT INTO "list_report_hotlinks" VALUES(4,'EMSL-Funded Study Specific Datasets','column_tooltip','value','[EMSL-Funded Total Datasets] - [EF Blanks] - [EF QCs] - [EF Bad]','');
INSERT INTO "list_report_hotlinks" VALUES(5,'EF Study Specific Datasets per day','column_tooltip','value','EMSL-Funded study specific datasets per day','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Total AcqTimeDays','column_tooltip','value','Total number of days the instrument was acquiring data','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Study Specific AcqTimeDays','column_tooltip','value','[Total_AcqTimeDays] - [BadBlankQC_AcqTimeDays]','');
INSERT INTO "list_report_hotlinks" VALUES(8,'EF Total AcqTimeDays','column_tooltip','value','Total number of days the instrument was acquiring EMSL-funded data','');
INSERT INTO "list_report_hotlinks" VALUES(9,'EF Study Specific AcqTimeDays','column_tooltip','value','[EMSL-Funded Total AcqTimeDays] - [EMSL-Funded BadBlankQC AcqTimeDays]','');
INSERT INTO "list_report_hotlinks" VALUES(10,'Hours AcqTime per Day','column_tooltip','value','Total_AcqTimeDays / HoursInRange','');
INSERT INTO "list_report_hotlinks" VALUES(11,'% Inst EMSL Owned','column_tooltip','value','Percent of the instrument owned by EMSL','');
INSERT INTO "list_report_hotlinks" VALUES(12,'EF Total Datasets','column_tooltip','value','Total number of EMSL-funded datasets (good and bad)','');
INSERT INTO "list_report_hotlinks" VALUES(13,'EF Datasets per day','column_tooltip','value','Number of EMSL-funded datasets per day','');
INSERT INTO "list_report_hotlinks" VALUES(14,'% Blank Datasets','column_tooltip','value','Percent of all datasets that were blank','');
INSERT INTO "list_report_hotlinks" VALUES(15,'% QC Datasets','column_tooltip','value','Percent of all datasets that were QC (but not bad)','');
INSERT INTO "list_report_hotlinks" VALUES(16,'% Bad Datasets','column_tooltip','value','Percent of all datasets that were Bad (but not blank)','');
INSERT INTO "list_report_hotlinks" VALUES(17,'% Study Specific Datasets','column_tooltip','value','[Study Specific Datasets] / [Total datasets]','');
INSERT INTO "list_report_hotlinks" VALUES(18,'% EF Study Specific Datasets','column_tooltip','value','[EMSL-Funded Study Specific] / [Total datasets]','');
INSERT INTO "list_report_hotlinks" VALUES(19,'% EF Study Specific by AcqTime','column_tooltip','value','[EMSL-Funded Total AcqTimeDays] / [Total AcqTimeDays]','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Instrument_Filter_List','picker.append','instrumentNamePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Production_Only','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'EUS_Usage_Filter_List','picker.append','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'Campaign_ID_Filter_List','picker.append','campaignIDPickList','','',',','');
COMMIT;
