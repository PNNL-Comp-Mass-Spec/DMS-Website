﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE home_menu_sections (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "section_name" TEXT,
    "section_header" TEXT,
    "section_number" TEXT,
    "section_comment" TEXT
);
INSERT INTO home_menu_sections VALUES(2,'Report','Logs/Reports','1','...');
INSERT INTO home_menu_sections VALUES(3,'Capture','Datasets and Requested Runs','3','...');
INSERT INTO home_menu_sections VALUES(4,'Capture_Pipeline','Capture and Archive','6','...');
INSERT INTO home_menu_sections VALUES(5,'Instruments','Instruments and Storage','11','...');
INSERT INTO home_menu_sections VALUES(6,'Analysis','Data Analysis Jobs','2','...');
INSERT INTO home_menu_sections VALUES(7,'Analysis_Pipeline','Data Analysis Pipeline','5','...');
INSERT INTO home_menu_sections VALUES(8,'Miscelleneous','Miscelleneous','4','...');
INSERT INTO home_menu_sections VALUES(10,'Configuration','Configuration DB','8','Config db');
CREATE TABLE home_menu_items (
    "id" INTEGER PRIMARY KEY,
    "section_name" TEXT,
    "page" TEXT,
    "link" TEXT,
    "label" TEXT
);
INSERT INTO home_menu_items VALUES(1,'Report','submenu','Activity Reports','');
INSERT INTO home_menu_items VALUES(2,'Report','production_instrument_stats/param','Display','production instrument statistics');
INSERT INTO home_menu_items VALUES(3,'Report','submenu','Display','daily status checks');
INSERT INTO home_menu_items VALUES(4,'Report','dataset_daily_check/report','Display','daily dataset check report');
INSERT INTO home_menu_items VALUES(5,'Report','main_log/report/Warning','Display','log - warnings');
INSERT INTO home_menu_items VALUES(6,'Report','archive_daily_check/report','Display','archive daily check report');
INSERT INTO home_menu_items VALUES(7,'Report','archive_daily_check_update/report','Display','archive update daily check report');
INSERT INTO home_menu_items VALUES(8,'Report','analysis_daily_check/report','Display','analysis job daily check report');
INSERT INTO home_menu_items VALUES(10,'Report','dms_activity/report','Display','DMS activity report');
INSERT INTO home_menu_items VALUES(11,'Report','submenu','Event Log','');
INSERT INTO home_menu_items VALUES(12,'Report','event_log_dataset/report','Display','dataset event log');
INSERT INTO home_menu_items VALUES(13,'Report','event_log_analysis_job/report','Display','analysis job event log');
INSERT INTO home_menu_items VALUES(14,'Report','event_log_archive/report','Display','archive event log');
INSERT INTO home_menu_items VALUES(16,'Analysis_Pipeline','submenu','Settings files and Scripts','');
INSERT INTO home_menu_items VALUES(17,'Analysis_Pipeline','settings_files/report','Display','list of settings files');
INSERT INTO home_menu_items VALUES(18,'Analysis_Pipeline','pipeline_script/report','Display','list of analysis job scripts');
INSERT INTO home_menu_items VALUES(19,'Analysis_Pipeline','submenu','Jobs and Steps (Recent Jobs)','');
INSERT INTO home_menu_items VALUES(20,'Analysis_Pipeline','pipeline_jobs/report','Display','list of analysis jobs');
INSERT INTO home_menu_items VALUES(21,'Analysis_Pipeline','pipeline_job_steps/report','Display','list of analysis job steps');
INSERT INTO home_menu_items VALUES(22,'Analysis_Pipeline','pipeline_jobs/create','Create','new pipeline DB job');
INSERT INTO home_menu_items VALUES(23,'Analysis_Pipeline','submenu','Jobs and Steps (Full History)','');
INSERT INTO home_menu_items VALUES(24,'Analysis_Pipeline','pipeline_jobs_history/report','Display','list of analysis jobs');
INSERT INTO home_menu_items VALUES(25,'Analysis_Pipeline','pipeline_job_steps_history/report','Display','list of analysis job steps');
INSERT INTO home_menu_items VALUES(26,'Analysis_Pipeline','submenu','Processors and Step Tools','');
INSERT INTO home_menu_items VALUES(27,'Analysis_Pipeline','pipeline_local_processors/report','Display','list of local processors');
INSERT INTO home_menu_items VALUES(28,'Analysis_Pipeline','pipeline_step_tools/report','Display','list of step tools');
INSERT INTO home_menu_items VALUES(29,'Analysis_Pipeline','pipeline_processor_tool_crosstab/report','Display','processor tool crosstab report');
INSERT INTO home_menu_items VALUES(30,'Analysis_Pipeline','pipeline_processor_step_tools/report','Display','processor step tools report');
INSERT INTO home_menu_items VALUES(31,'Analysis','submenu','Jobs and Param Files','');
INSERT INTO home_menu_items VALUES(32,'Analysis','get_paramfile_crosstab/param','Display','get paramfile crosstab');
INSERT INTO home_menu_items VALUES(33,'Analysis','update_analysis_jobs/create','Update','multiple analysis jobs');
INSERT INTO home_menu_items VALUES(34,'Analysis','analysis_batch/report','Display','list of analysis batches');
INSERT INTO home_menu_items VALUES(35,'Analysis','analysis_group/create','Create','new analysis job group');
INSERT INTO home_menu_items VALUES(36,'Analysis','analysis_tools/report','Display','list of analysis job tools (in DMS, not pipeline DB)');
INSERT INTO home_menu_items VALUES(37,'Analysis','submenu','Processor Groups','');
INSERT INTO home_menu_items VALUES(38,'Analysis','analysis_job_processor_group/report','Display','analysis job processor group');
INSERT INTO home_menu_items VALUES(39,'Analysis','analysis_job_processor_group/create','Create','analysis job processor group');
INSERT INTO home_menu_items VALUES(40,'Analysis','submenu','Processors','');
INSERT INTO home_menu_items VALUES(41,'Analysis','analysis_job_processors/report','Display','analysis job processors');
INSERT INTO home_menu_items VALUES(42,'Analysis','analysis_job_processors/create','Create','analysis job processors');
INSERT INTO home_menu_items VALUES(43,'Analysis','submenu','Predefines','');
INSERT INTO home_menu_items VALUES(44,'Analysis','submenu','Report','');
INSERT INTO home_menu_items VALUES(45,'Analysis','predefined_analysis/report/-','Display','predefined analysis');
INSERT INTO home_menu_items VALUES(46,'Analysis','predefined_analysis_disabled/report','Display','predefined analysis disabled');
INSERT INTO home_menu_items VALUES(47,'Analysis','predefined_analysis/create','Create','predefined analysis');
INSERT INTO home_menu_items VALUES(48,'Analysis','submenu','Preview','');
INSERT INTO home_menu_items VALUES(49,'Analysis','predefined_analysis_jobs_preview/param','Display','predefined analysis job preview');
INSERT INTO home_menu_items VALUES(50,'Analysis','predefined_analysis_preview_mds/param','Display','predefined analysis job preview (mds)');
INSERT INTO home_menu_items VALUES(51,'Analysis','predefined_analysis_datasets/param','Display','predefined analysis datasets');
INSERT INTO home_menu_items VALUES(52,'Analysis','predefined_analysis_rules_preview/param','Display','predefined analysis rules preview');
INSERT INTO home_menu_items VALUES(53,'Analysis','submenu','Scheduling Rules','');
INSERT INTO home_menu_items VALUES(54,'Analysis','predefined_analysis_scheduling_rules/report','Display','predefined analysis scheduling rules');
INSERT INTO home_menu_items VALUES(55,'Analysis','predefined_analysis_scheduling_rules/create','Create','predefined analysis scheduling rules');
INSERT INTO home_menu_items VALUES(56,'Capture_Pipeline','submenu','Capture','');
INSERT INTO home_menu_items VALUES(57,'Capture_Pipeline','capture_script/report','Display','capture script');
INSERT INTO home_menu_items VALUES(58,'Capture_Pipeline','capture_jobs/report','Display','capture jobs');
INSERT INTO home_menu_items VALUES(59,'Capture_Pipeline','capture_job_steps/report','Display','capture job steps');
INSERT INTO home_menu_items VALUES(60,'Capture_Pipeline','capture_local_processors/report','Display','capture local processors');
INSERT INTO home_menu_items VALUES(61,'Capture_Pipeline','capture_step_tools/report','Display','capture step tools');
INSERT INTO home_menu_items VALUES(62,'Capture_Pipeline','capture_processor_tool_crosstab/report','Display','capture processor tool crosstab');
INSERT INTO home_menu_items VALUES(63,'Capture_Pipeline','capture_processor_step_tools/report','Display','capture processor step tools');
INSERT INTO home_menu_items VALUES(64,'Capture','submenu','Dataset','');
INSERT INTO home_menu_items VALUES(65,'Capture','dataset_redisposition/create','Create','dataset redisposition (recycle request)');
INSERT INTO home_menu_items VALUES(66,'Capture','submenu','Dataset Info','');
INSERT INTO home_menu_items VALUES(67,'Capture','dataset_info/report','Display','dataset info report');
INSERT INTO home_menu_items VALUES(68,'Capture','dataset_scans/report','Display','dataset scan report');
INSERT INTO home_menu_items VALUES(69,'Capture','submenu','Requested Run Factors and Blocking','');
INSERT INTO home_menu_items VALUES(70,'Capture','requested_run_factors/param','Define','requested run factors');
INSERT INTO home_menu_items VALUES(71,'Capture','custom_factors/report','Search','requested run factors');
INSERT INTO home_menu_items VALUES(72,'Capture','requested_run_batch_order/report','Display','requested run blocks');
INSERT INTO home_menu_items VALUES(73,'Capture','requested_run_batch_blocking/param','Define','requested run blocks (multiple factors)');
INSERT INTO home_menu_items VALUES(74,'Capture','batch_tracking/report/-/-/-/-','Display','requested run batch tracking report');
INSERT INTO home_menu_items VALUES(75,'Capture','requested_run_admin/report','Administer','requested runs');
INSERT INTO home_menu_items VALUES(76,'Capture_Pipeline','submenu','Archive','');
INSERT INTO home_menu_items VALUES(77,'Capture_Pipeline','archive/report','Display','archive');
INSERT INTO home_menu_items VALUES(78,'Capture_Pipeline','archive_assigned_storage/report','Display','archive assigned storage');
INSERT INTO home_menu_items VALUES(79,'Capture_Pipeline','archive_path/report','Display','archive path');
INSERT INTO home_menu_items VALUES(80,'Capture_Pipeline','update_archive/create','Create','update archive');
INSERT INTO home_menu_items VALUES(81,'Capture_Pipeline','archive/search','Search','archive');
INSERT INTO home_menu_items VALUES(82,'Instruments','submenu','Instruments','');
INSERT INTO home_menu_items VALUES(83,'Instruments','instrument/report','Display','instrument');
INSERT INTO home_menu_items VALUES(84,'Instruments','new_instrument/create','Create','new instrument');
INSERT INTO home_menu_items VALUES(85,'Instruments','submenu','Instrument Class','');
INSERT INTO home_menu_items VALUES(86,'Instruments','instrument_class/report','Display','instrument class');
INSERT INTO home_menu_items VALUES(87,'Instruments','new_instrument_class/create','Create','new instrument class');
INSERT INTO home_menu_items VALUES(88,'Instruments','submenu','File Storage','');
INSERT INTO home_menu_items VALUES(89,'Instruments','storage/report','Display','storage');
INSERT INTO home_menu_items VALUES(90,'Instruments','storage/create','Create','storage');
INSERT INTO home_menu_items VALUES(91,'Miscelleneous','submenu','Organisms','');
INSERT INTO home_menu_items VALUES(92,'Miscelleneous','organism/report','Display','organism');
INSERT INTO home_menu_items VALUES(93,'Miscelleneous','organism/create','Create','organism');
INSERT INTO home_menu_items VALUES(94,'Miscelleneous','submenu','Users','');
INSERT INTO home_menu_items VALUES(95,'Miscelleneous','user/report','Display','user');
INSERT INTO home_menu_items VALUES(96,'Miscelleneous','user/create','Create','user');
INSERT INTO home_menu_items VALUES(97,'Miscelleneous','user_operation/report','Display','user operation');
INSERT INTO home_menu_items VALUES(98,'Miscelleneous','submenu','Internal Standards','');
INSERT INTO home_menu_items VALUES(99,'Miscelleneous','internal_standards/report','Display','internal standards');
INSERT INTO home_menu_items VALUES(116,'Configuration','submenu','Page Family','');
INSERT INTO home_menu_items VALUES(117,'Configuration','config_db/page_families','Display','Page Family Database List');
INSERT INTO home_menu_items VALUES(118,'Configuration','data/lr_menu','Display','list of custom (ad-hoc) list reports');
INSERT INTO home_menu_items VALUES(119,'Configuration','submenu','DMS Menus','');
INSERT INTO home_menu_items VALUES(120,'Configuration','config_db/edit_table/dms_menu.db/menu_def','Edit','side menu items');
INSERT INTO home_menu_items VALUES(121,'Configuration','config_db/edit_table/dms_menu.db/nav_def','Edit','nav bar items');
INSERT INTO home_menu_items VALUES(122,'Configuration','submenu','Home Page Menu Sections','');
INSERT INTO home_menu_items VALUES(123,'Configuration','config_db/edit_table/dms_menu.db/home_menu_sections','Edit','menu sections');
INSERT INTO home_menu_items VALUES(124,'Configuration','config_db/edit_table/dms_menu.db/home_menu_items','Edit','menu items');
INSERT INTO home_menu_items VALUES(125,'Configuration','submenu','Admin Page Menu Sections','');
INSERT INTO home_menu_items VALUES(126,'Configuration','config_db/edit_table/dms_admin_menu.db/home_menu_sections','Edit','menu sections');
INSERT INTO home_menu_items VALUES(127,'Configuration','config_db/edit_table/dms_admin_menu.db/home_menu_items','Edit','menu items');
INSERT INTO home_menu_items VALUES(128,'Configuration','submenu','Drop-down Choosers','');
INSERT INTO home_menu_items VALUES(129,'Configuration','chooser/get_chooser_list','Display','list of all drop-down style choosers');
INSERT INTO home_menu_items VALUES(130,'Configuration','config_db/edit_table/dms_chooser.db/chooser_definitions','Edit','drop-down chooser definitions config db');
INSERT INTO home_menu_items VALUES(131,'Configuration','submenu','Definitions for Restricted Actions','');
INSERT INTO home_menu_items VALUES(132,'Configuration','config_db/edit_table/master_authorization.db/restricted_actions','Edit','master_authorization restricted_actions config db');
INSERT INTO home_menu_items VALUES(133,'Configuration','submenu','Config DB System Parameters','');
INSERT INTO home_menu_items VALUES(134,'Configuration','config_db/edit_table/master_config_db.db/table_def_description','Edit','descriptions of standard config db tables');
INSERT INTO home_menu_items VALUES(135,'Configuration','config_db/edit_table/master_config_db.db/table_def_sql','Edit','SQL that creates standard config db tables');
INSERT INTO home_menu_items VALUES(136,'Configuration','config_db/edit_table/master_config_db.db/table_edit_col_defs','Edit','column definitions for standard config db tables');
INSERT INTO home_menu_items VALUES(152,'Capture_Pipeline','capture_daily_check/report','Display','pending capture jobs');
INSERT INTO home_menu_items VALUES(153,'Capture_Pipeline','capture_log/report','Display','capture log');
COMMIT;
