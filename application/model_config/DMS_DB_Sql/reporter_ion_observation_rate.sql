﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Reporter_Ion_Observation_Rate_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','analysis_job/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Instrument','invoke_entity','value','instrument_operation_history/report','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Observation_Rate_Link','image_link','value','index.html','{"width":"400"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Intensity_Stats_Link','image_link','value','index.html','{"width":"400"}');
INSERT INTO "list_report_hotlinks" VALUES(6,'Channel1','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(7,'Channel2','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(8,'Channel3','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(9,'Channel4','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(10,'Channel5','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(11,'Channel6','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(12,'Channel7','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(13,'Channel8','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(14,'Channel9','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(15,'Channel10','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(16,'Channel11','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(17,'Channel12','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(18,'Channel13','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(19,'Channel14','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(20,'Channel15','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(21,'Channel16','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(22,'Channel1_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(23,'Channel2_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(24,'Channel3_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(25,'Channel4_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(26,'Channel5_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(27,'Channel6_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(28,'Channel7_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(29,'Channel8_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(30,'Channel9_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(31,'Channel10_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(32,'Channel11_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(33,'Channel12_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(34,'Channel13_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(35,'Channel14_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(36,'Channel15_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(37,'Channel16_All','format_commas','value','','{"Decimals":"2"}');
INSERT INTO "list_report_hotlinks" VALUES(38,'Param_File','invoke_entity','value','param_file/report/-/@/-','');
INSERT INTO "list_report_hotlinks" VALUES(39,'Reporter_Ion','invoke_entity','value','sample_label_reporter_ions/report/~','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_job','Job','6!','','Job','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','40!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_datasetId','Dataset_ID','6!','','Dataset_ID','Equals','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','32','','Instrument','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_reporterIon','Reporter_Ion','','','Reporter_Ion','StartsWithText','text','24','','');
COMMIT;
