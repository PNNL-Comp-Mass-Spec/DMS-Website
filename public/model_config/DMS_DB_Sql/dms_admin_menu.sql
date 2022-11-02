﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE home_menu_sections (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "section_name" TEXT,
    "section_header" TEXT,
    "section_number" TEXT,
    "section_comment" TEXT
);
INSERT INTO home_menu_sections VALUES(1,'Sample_Prep','Sample Prep','9','Common adminstration tasks for sample prep');
INSERT INTO home_menu_sections VALUES(2,'Report','Logs/Reports','1','...');
INSERT INTO home_menu_sections VALUES(3,'Capture','Datasets and Requested Runs','3','...');
INSERT INTO home_menu_sections VALUES(4,'Capture_Pipeline','Capture and Archive','6','...');
INSERT INTO home_menu_sections VALUES(5,'Instruments','Instruments and Storage','11','...');
INSERT INTO home_menu_sections VALUES(6,'Analysis','Data Analysis Jobs','2','...');
INSERT INTO home_menu_sections VALUES(7,'Analysis_Pipeline','Data Analysis Pipeline','5','...');
INSERT INTO home_menu_sections VALUES(8,'Miscelleneous','Miscelleneous','4','...');
INSERT INTO home_menu_sections VALUES(9,'EMSL','EMSL','7','EMSL Usage Reporting');
INSERT INTO home_menu_sections VALUES(10,'Configuration','Configuration DB','8','Config db');
INSERT INTO home_menu_sections VALUES(11,'MTS','MTS','10','Mass and Time Tag System');
CREATE TABLE home_menu_items (
    "id" INTEGER PRIMARY KEY,
    "section_name" TEXT,
    "page" TEXT,
    "link" TEXT,
    "label" TEXT
);
INSERT INTO home_menu_items VALUES(1,'Report','submenu','Activity Reports','');
INSERT INTO home_menu_items VALUES(5,'Report','production_instrument_stats/param','Display','production instrument statistics');
INSERT INTO home_menu_items VALUES(6,'Report','submenu','Display','daily status checks');
INSERT INTO home_menu_items VALUES(7,'Report','dataset_daily_check/report','Display','daily dataset check report');
INSERT INTO home_menu_items VALUES(8,'Report','main_log/report/Warning','Display','log - warnings');
INSERT INTO home_menu_items VALUES(9,'Report','archive_daily_check/report','Display','archive daily check report');
INSERT INTO home_menu_items VALUES(10,'Report','archive_daily_check_update/report','Display','archive update daily check report');
INSERT INTO home_menu_items VALUES(11,'Report','analysis_daily_check/report','Display','analysis job daily check report');
INSERT INTO home_menu_items VALUES(12,'Report','manager_daily_check/param/72','Display','managers daily check report');
INSERT INTO home_menu_items VALUES(13,'Report','dms_activity/report','Display','DMS activity report');
INSERT INTO home_menu_items VALUES(14,'Report','submenu','Event Log','');
INSERT INTO home_menu_items VALUES(15,'Report','event_log_dataset/report','Display','dataset event log');
INSERT INTO home_menu_items VALUES(16,'Report','event_log_analysis_job/report','Display','analysis job event log');
INSERT INTO home_menu_items VALUES(17,'Report','event_log_archive/report','Display','archive event log');
INSERT INTO home_menu_items VALUES(18,'Report','historic_log/report','Display','historic log');
INSERT INTO home_menu_items VALUES(19,'Analysis_Pipeline','submenu','Settings files and Scripts','');
INSERT INTO home_menu_items VALUES(20,'Analysis_Pipeline','settings_files/report','Display','list of settings files');
INSERT INTO home_menu_items VALUES(21,'Analysis_Pipeline','pipeline_script/report','Display','list of analysis job scripts');
INSERT INTO home_menu_items VALUES(22,'Analysis_Pipeline','submenu','Jobs and Steps (Recent Jobs)','');
INSERT INTO home_menu_items VALUES(23,'Analysis_Pipeline','pipeline_jobs/report','Display','list of analysis jobs');
INSERT INTO home_menu_items VALUES(24,'Analysis_Pipeline','pipeline_job_steps/report','Display','list of analysis job steps');
INSERT INTO home_menu_items VALUES(25,'Analysis_Pipeline','pipeline_jobs/create','Create','new pipeline DB job');
INSERT INTO home_menu_items VALUES(26,'Analysis_Pipeline','submenu','Jobs and Steps (Full History)','');
INSERT INTO home_menu_items VALUES(27,'Analysis_Pipeline','pipeline_jobs_history/report','Display','list of analysis jobs');
INSERT INTO home_menu_items VALUES(28,'Analysis_Pipeline','pipeline_job_steps_history/report','Display','list of analysis job steps');
INSERT INTO home_menu_items VALUES(29,'Analysis_Pipeline','submenu','Processors and Step Tools','');
INSERT INTO home_menu_items VALUES(30,'Analysis_Pipeline','pipeline_local_processors/report','Display','list of local processors');
INSERT INTO home_menu_items VALUES(31,'Analysis_Pipeline','pipeline_step_tools/report','Display','list of step tools');
INSERT INTO home_menu_items VALUES(32,'Analysis_Pipeline','pipeline_processor_tool_crosstab/report','Display','processor tool crosstab report');
INSERT INTO home_menu_items VALUES(33,'Analysis_Pipeline','pipeline_processor_step_tools/report','Display','processor step tools report');
INSERT INTO home_menu_items VALUES(37,'Analysis','submenu','Jobs and Param Files','');
INSERT INTO home_menu_items VALUES(38,'Analysis','get_paramfile_crosstab/param','Display','get paramfile crosstab');
INSERT INTO home_menu_items VALUES(39,'Analysis','update_analysis_jobs/create','Update','multiple analysis jobs');
INSERT INTO home_menu_items VALUES(40,'Analysis','analysis_batch/report','Display','list of analysis batches');
INSERT INTO home_menu_items VALUES(41,'Analysis','analysis_group/create','Create','new analysis job group');
INSERT INTO home_menu_items VALUES(45,'Analysis','analysis_tools/report','Display','list of analysis job tools (in DMS, not pipeline DB)');
INSERT INTO home_menu_items VALUES(46,'Analysis','submenu','Processor Groups','');
INSERT INTO home_menu_items VALUES(47,'Analysis','analysis_job_processor_group/report','Display','analysis job processor group');
INSERT INTO home_menu_items VALUES(48,'Analysis','analysis_job_processor_group/create','Create','analysis job processor group');
INSERT INTO home_menu_items VALUES(49,'Analysis','submenu','Processors','');
INSERT INTO home_menu_items VALUES(50,'Analysis','analysis_job_processors/report','Display','analysis job processors');
INSERT INTO home_menu_items VALUES(51,'Analysis','analysis_job_processors/create','Create','analysis job processors');
INSERT INTO home_menu_items VALUES(52,'Analysis','submenu','Predefines','');
INSERT INTO home_menu_items VALUES(53,'Analysis','submenu','Report','');
INSERT INTO home_menu_items VALUES(54,'Analysis','predefined_analysis/report/-','Display','predefined analysis');
INSERT INTO home_menu_items VALUES(55,'Analysis','predefined_analysis_disabled/report','Display','predefined analysis disabled');
INSERT INTO home_menu_items VALUES(56,'Analysis','predefined_analysis/create','Create','predefined analysis');
INSERT INTO home_menu_items VALUES(57,'Analysis','submenu','Preview','');
INSERT INTO home_menu_items VALUES(58,'Analysis','predefined_analysis_preview/param','Display','predefined analysis preview');
INSERT INTO home_menu_items VALUES(59,'Analysis','predefined_analysis_preview_mds/param','Display','predefined analysis preview mds');
INSERT INTO home_menu_items VALUES(60,'Analysis','predefined_analysis_datasets/param','Display','predefined analysis datasets');
INSERT INTO home_menu_items VALUES(61,'Analysis','submenu','Scheduling Rules','');
INSERT INTO home_menu_items VALUES(62,'Analysis','predefined_analysis_scheduling_rules/report','Display','predefined analysis scheduling rules');
INSERT INTO home_menu_items VALUES(63,'Analysis','predefined_analysis_scheduling_rules/create','Create','predefined analysis scheduling rules');
INSERT INTO home_menu_items VALUES(64,'Capture_Pipeline','submenu','Capture','');
INSERT INTO home_menu_items VALUES(65,'Capture_Pipeline','capture_script/report','Display','capture script');
INSERT INTO home_menu_items VALUES(66,'Capture_Pipeline','capture_jobs/report','Display','capture jobs');
INSERT INTO home_menu_items VALUES(67,'Capture_Pipeline','capture_job_steps/report','Display','capture job steps');
INSERT INTO home_menu_items VALUES(68,'Capture_Pipeline','capture_local_processors/report','Display','capture local processors');
INSERT INTO home_menu_items VALUES(69,'Capture_Pipeline','capture_step_tools/report','Display','capture step tools');
INSERT INTO home_menu_items VALUES(70,'Capture_Pipeline','capture_processor_tool_crosstab/report','Display','capture processor tool crosstab');
INSERT INTO home_menu_items VALUES(71,'Capture_Pipeline','capture_processor_step_tools/report','Display','capture processor step tools');
INSERT INTO home_menu_items VALUES(72,'Capture','submenu','Dataset','');
INSERT INTO home_menu_items VALUES(75,'Capture','dataset_redisposition/create','Create','dataset redisposition (recycle request)');
INSERT INTO home_menu_items VALUES(76,'Capture','submenu','Dataset Info','');
INSERT INTO home_menu_items VALUES(77,'Capture','dataset_info/report','Display','dataset info report');
INSERT INTO home_menu_items VALUES(78,'Capture','dataset_scans/report','Display','dataset scan report');
INSERT INTO home_menu_items VALUES(79,'Capture','submenu','Requested Run Factors and Blocking','');
INSERT INTO home_menu_items VALUES(80,'Capture','requested_run_factors/param','Define','requested run factors');
INSERT INTO home_menu_items VALUES(81,'Capture','custom_factors/report','Search','requested run factors');
INSERT INTO home_menu_items VALUES(82,'Capture','requested_run_batch_order/report','Display','requested run blocks');
INSERT INTO home_menu_items VALUES(83,'Capture','requested_run_batch_blocking/param','Define','requested run blocks (multiple factors)');
INSERT INTO home_menu_items VALUES(85,'Capture','batch_tracking/report/-/-/-/-','Display','requested run batch tracking report');
INSERT INTO home_menu_items VALUES(86,'Capture','requested_run_admin/report','Administer','requested runs');
INSERT INTO home_menu_items VALUES(87,'Capture_Pipeline','submenu','Archive','');
INSERT INTO home_menu_items VALUES(88,'Capture_Pipeline','archive/report','Display','archive');
INSERT INTO home_menu_items VALUES(89,'Capture_Pipeline','archive_assigned_storage/report','Display','archive assigned storage');
INSERT INTO home_menu_items VALUES(90,'Capture_Pipeline','archive_path/report','Display','archive path');
INSERT INTO home_menu_items VALUES(91,'Capture_Pipeline','update_archive/create','Create','update archive');
INSERT INTO home_menu_items VALUES(92,'Capture_Pipeline','archive/search','Search','archive');
INSERT INTO home_menu_items VALUES(93,'Instruments','submenu','Instruments','');
INSERT INTO home_menu_items VALUES(94,'Instruments','instrument/report','Display','instrument');
INSERT INTO home_menu_items VALUES(95,'Instruments','new_instrument/create','Create','new instrument');
INSERT INTO home_menu_items VALUES(96,'Instruments','submenu','Instrument Class','');
INSERT INTO home_menu_items VALUES(97,'Instruments','instrumentclass/report','Display','instrumentclass');
INSERT INTO home_menu_items VALUES(98,'Instruments','new_instrumentclass/create','Create','new instrumentclass');
INSERT INTO home_menu_items VALUES(99,'Instruments','submenu','File Storage','');
INSERT INTO home_menu_items VALUES(100,'Instruments','storage/report','Display','storage');
INSERT INTO home_menu_items VALUES(101,'Instruments','storage/create','Create','storage');
INSERT INTO home_menu_items VALUES(103,'Miscelleneous','submenu','Organisms','');
INSERT INTO home_menu_items VALUES(104,'Miscelleneous','organism/report','Display','organism');
INSERT INTO home_menu_items VALUES(105,'Miscelleneous','organism/create','Create','organism');
INSERT INTO home_menu_items VALUES(106,'Miscelleneous','submenu','Users','');
INSERT INTO home_menu_items VALUES(107,'Miscelleneous','user/report','Display','user');
INSERT INTO home_menu_items VALUES(108,'Miscelleneous','user/create','Create','user');
INSERT INTO home_menu_items VALUES(109,'Miscelleneous','user_operation/report','Display','user operation');
INSERT INTO home_menu_items VALUES(110,'Miscelleneous','submenu','Internal Standards','');
INSERT INTO home_menu_items VALUES(111,'Miscelleneous','internal_standards/report','Display','internal standards');
INSERT INTO home_menu_items VALUES(112,'Miscelleneous','submenu','Metadata Dump','');
INSERT INTO home_menu_items VALUES(113,'Miscelleneous','dump_metadata_for_multiple_experiments/param','Display','dump metadata for multiple experiments');
INSERT INTO home_menu_items VALUES(114,'Miscelleneous','dump_metadata_for_multiple_datasets/param','Display','dump metadata for multiple datasets');
INSERT INTO home_menu_items VALUES(115,'Miscelleneous','submenu','Aux Info Definition','');
INSERT INTO home_menu_items VALUES(116,'Miscelleneous','aux_info_def/report/Biomaterial','Display','Biomaterial aux info def');
INSERT INTO home_menu_items VALUES(117,'Miscelleneous','aux_info_def/report/Experiment','Display','Experiment aux info def');
INSERT INTO home_menu_items VALUES(118,'Miscelleneous','aux_info_def/report/Dataset','Display','Dataset aux info def');
INSERT INTO home_menu_items VALUES(119,'Miscelleneous','aux_info_def/report/SamplePrepRequest','Display','Sample Prep aux info def');
INSERT INTO home_menu_items VALUES(120,'EMSL','submenu','EUS Proposals',' ');
INSERT INTO home_menu_items VALUES(121,'EMSL','eus_proposals/report/-','Display','EUS proposals');
INSERT INTO home_menu_items VALUES(124,'EMSL','eus_proposals/create','Create','EUS proposals');
INSERT INTO home_menu_items VALUES(125,'EMSL','submenu','EUS Users',' ');
INSERT INTO home_menu_items VALUES(126,'EMSL','eus_users/report','Display','EUS users');
INSERT INTO home_menu_items VALUES(127,'EMSL','eus_users/create','Create','EUS users');
INSERT INTO home_menu_items VALUES(128,'EMSL','submenu','EUS Usage',' ');
INSERT INTO home_menu_items VALUES(129,'EMSL','user_proposal_dataset/search','Find...','user proposal dataset');
INSERT INTO home_menu_items VALUES(130,'Configuration','submenu','Page Family','');
INSERT INTO home_menu_items VALUES(131,'Configuration','config_db/page_families','Display','Page Family Database List');
INSERT INTO home_menu_items VALUES(132,'Configuration','data/lr_menu','Display','list of custom (ad-hoc) list reports');
INSERT INTO home_menu_items VALUES(133,'Configuration','submenu','DMS Menus','');
INSERT INTO home_menu_items VALUES(134,'Configuration','config_db/edit_table/dms_menu.db/menu_def','Edit','side menu items');
INSERT INTO home_menu_items VALUES(136,'Configuration','config_db/edit_table/dms_menu.db/nav_def','Edit','nav bar items');
INSERT INTO home_menu_items VALUES(137,'Configuration','submenu','Home Page Menu Sections','');
INSERT INTO home_menu_items VALUES(138,'Configuration','config_db/edit_table/dms_menu.db/home_menu_sections','Edit','menu sections');
INSERT INTO home_menu_items VALUES(139,'Configuration','config_db/edit_table/dms_menu.db/home_menu_items','Edit','menu items');
INSERT INTO home_menu_items VALUES(140,'Configuration','submenu','Admin Page Menu Sections','');
INSERT INTO home_menu_items VALUES(141,'Configuration','config_db/edit_table/dms_admin_menu.db/home_menu_sections','Edit','menu sections');
INSERT INTO home_menu_items VALUES(142,'Configuration','config_db/edit_table/dms_admin_menu.db/home_menu_items','Edit','menu items');
INSERT INTO home_menu_items VALUES(143,'Configuration','submenu','Drop-down Choosers','');
INSERT INTO home_menu_items VALUES(144,'Configuration','chooser/get_chooser_list','Display','list of all drop-down style choosers');
INSERT INTO home_menu_items VALUES(145,'Configuration','config_db/edit_table/dms_chooser.db/chooser_definitions','Edit','drop-down chooser definitions config db');
INSERT INTO home_menu_items VALUES(146,'Configuration','submenu','Definitions for Restricted Actions','');
INSERT INTO home_menu_items VALUES(147,'Configuration','config_db/edit_table/master_authorization.db/restricted_actions','Edit','master_authorization restricted_actions config db');
INSERT INTO home_menu_items VALUES(148,'Configuration','submenu','Config DB System Parameters','');
INSERT INTO home_menu_items VALUES(149,'Configuration','config_db/edit_table/master_config_db.db/table_def_description','Edit','descriptions of standard config db tables');
INSERT INTO home_menu_items VALUES(150,'Configuration','config_db/edit_table/master_config_db.db/table_def_sql','Edit','SQL that creates standard config db tables');
INSERT INTO home_menu_items VALUES(151,'Configuration','config_db/edit_table/master_config_db.db/table_edit_col_defs','Edit','column definitions for standard config db tables');
INSERT INTO home_menu_items VALUES(152,'Sample_Prep','submenu','Prep Requests','');
INSERT INTO home_menu_items VALUES(153,'Sample_Prep','sample_prep_request_active/report','Display','a list of all active sample prep requests');
INSERT INTO home_menu_items VALUES(154,'Sample_Prep','sample_prep_request/create','Create','a new sample prep request');
INSERT INTO home_menu_items VALUES(155,'Sample_Prep','sample_prep_request_assignment/report','Assign','staff to requests (admin function)');
INSERT INTO home_menu_items VALUES(156,'Sample_Prep','submenu','Material Storage','');
INSERT INTO home_menu_items VALUES(157,'Sample_Prep','freezer/tree','Manage','freezer contents and status (tree)');
INSERT INTO home_menu_items VALUES(158,'Sample_Prep','material_move_container/report/-/-/-','Move','containers between locations');
INSERT INTO home_menu_items VALUES(159,'Sample_Prep','material_move_items/report/-/-/-','Move','material (Biomaterial, Experiments) between containers');
INSERT INTO home_menu_items VALUES(160,'Sample_Prep','submenu','Prep LC','');
INSERT INTO home_menu_items VALUES(161,'Sample_Prep','prep_lc_run/report','Display','a list of all prep LC runs');
INSERT INTO home_menu_items VALUES(162,'Sample_Prep','prep_lc_run/create','Create','a new prep LC run');
INSERT INTO home_menu_items VALUES(163,'Sample_Prep','prep_lc_column/report/-/-/-/-/NoMatch__Retired','Display','a list of all prep LC columns');
INSERT INTO home_menu_items VALUES(164,'Sample_Prep','prep_lc_column/create','Create','a new prep LC column');
INSERT INTO home_menu_items VALUES(165,'Sample_Prep','submenu','Spreadsheet','');
INSERT INTO home_menu_items VALUES(166,'Sample_Prep','upload/main','Load','sample prep requests from spreadsheet');
INSERT INTO home_menu_items VALUES(167,'Capture_Pipeline','capture_daily_check/report','Display','pending capture jobs');
INSERT INTO home_menu_items VALUES(169,'Capture_Pipeline','capture_log/report','Display','capture log');
INSERT INTO home_menu_items VALUES(170,'MTS','submenu','Databases','');
INSERT INTO home_menu_items VALUES(171,'MTS','mts_pt_dbs/report','Display','Peptide databases');
INSERT INTO home_menu_items VALUES(172,'MTS','mts_mt_dbs/report','Display','AMT Tag databases');
INSERT INTO home_menu_items VALUES(173,'MTS','submenu','Results','');
INSERT INTO home_menu_items VALUES(174,'MTS','mts_pm_results/report','Display','peak matching results');
INSERT INTO home_menu_items VALUES(176,'Miscelleneous','submenu','Notification','');
INSERT INTO home_menu_items VALUES(177,'Miscelleneous','notification/report','Display','list of user notifications according to research team membership');
INSERT INTO home_menu_items VALUES(178,'Miscelleneous','notification_event/report','Display','list of notification events');
INSERT INTO home_menu_items VALUES(179,'Sample_Prep','submenu','Instrument Maint. Notes','');
INSERT INTO home_menu_items VALUES(180,'Sample_Prep','instrument_config_history/report/PrepHPLC','Display','a list of all sample prep maintenance notes');
INSERT INTO home_menu_items VALUES(181,'Sample_Prep','instrument_config_history/create','Create','a new sample prep maintenance notes');
INSERT INTO home_menu_items VALUES(182,'Sample_Prep','submenu','Operations Task Queue','');
INSERT INTO home_menu_items VALUES(183,'Sample_Prep','operations_tasks/report','Display','a list of all operations tasks');
INSERT INTO home_menu_items VALUES(184,'Sample_Prep','operations_tasks/create','Create','a new operations task');
INSERT INTO home_menu_items VALUES(185,'Sample_Prep','submenu','OSM Package','');
INSERT INTO home_menu_items VALUES(186,'Sample_Prep','osm_package/report','Display','a list of all OSM Packages');
INSERT INTO home_menu_items VALUES(187,'Sample_Prep','osm_package/create','Create','a new OSM Package');
COMMIT;
