﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','t_reporter_ion_observation_rate_addnl');
INSERT INTO general_params VALUES('list_report_data_table','v_reporter_ion_observation_rate_tmtpro_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','dataset_id, job');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'job','invoke_entity','value','analysis_job/show','');
INSERT INTO list_report_hotlinks VALUES(2,'dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(3,'instrument','invoke_entity','value','instrument_operation_history/report','');
INSERT INTO list_report_hotlinks VALUES(4,'observation_rate_link','image_link','value','index.html','{"width":"400"}');
INSERT INTO list_report_hotlinks VALUES(5,'intensity_stats_link','image_link','value','index.html','{"width":"400"}');
INSERT INTO list_report_hotlinks VALUES(6,'channel1','invoke_entity','dataset','reporter_ion_observation_rate/report/~','');
INSERT INTO list_report_hotlinks VALUES(7,'+channel1','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(8,'channel2','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(9,'channel3','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(10,'channel4','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(11,'channel5','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(12,'channel6','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(13,'channel7','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(14,'channel8','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(15,'channel9','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(16,'channel10','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(17,'channel11','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(18,'channel12','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(19,'channel13','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(20,'channel14','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(21,'channel15','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(22,'channel16','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(23,'channel17','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(24,'channel18','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(25,'channel19','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(26,'channel20','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(27,'channel21','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(28,'channel22','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(29,'channel23','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(30,'channel24','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(31,'channel25','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(32,'channel26','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(33,'channel27','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(34,'channel28','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(35,'channel29','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(36,'channel20','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(37,'channel31','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(38,'channel32','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(39,'channel33','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(40,'channel34','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(41,'channel35','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(42,'channel1_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(43,'channel2_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(44,'channel3_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(45,'channel4_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(46,'channel5_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(47,'channel6_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(48,'channel7_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(49,'channel8_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(50,'channel9_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(51,'channel10_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(52,'channel11_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(53,'channel12_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(54,'channel13_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(55,'channel14_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(56,'channel15_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(57,'channel16_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(58,'channel17_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(59,'channel18_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(60,'channel19_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(61,'channel20_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(62,'channel21_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(63,'channel22_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(64,'channel23_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(65,'channel24_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(66,'channel25_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(67,'channel26_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(68,'channel27_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(69,'channel28_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(70,'channel29_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(71,'channel30_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(72,'channel31_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(73,'channel32_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(74,'channel33_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(75,'channel34_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(76,'channel35_intensity','format_commas','value','','{"Decimals":"2"}');
INSERT INTO list_report_hotlinks VALUES(77,'param_file','invoke_entity','value','param_file/report/-/@/-','');
INSERT INTO list_report_hotlinks VALUES(78,'reporter_ion','invoke_entity','value','sample_label_reporter_ions/report/~','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_dataset','Dataset','50!','','dataset','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_datasetId','Dataset ID','8!','','dataset_id','Equals','text','24','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_instrument','Instrument','20!','','instrument','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_reporterIon','Reporter Ion','','','reporter_ion','StartsWithText','text','24','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_batch','Batch','6!','','batch','Equals','text','24','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_job','Job','8!','','job','Equals','text','24','','');
COMMIT;