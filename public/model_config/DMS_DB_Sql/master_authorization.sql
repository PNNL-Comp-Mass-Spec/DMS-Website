﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE 'restricted_actions' ("id" INTEGER PRIMARY KEY AUTOINCREMENT, "page_family" TEXT, "action" TEXT, "required_permisions" TEXT );
INSERT INTO "restricted_actions" VALUES(1,'analysis_group','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(2,'analysis_job','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Analysis_Job_Administration');
INSERT INTO "restricted_actions" VALUES(3,'analysis_job','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(4,'analysis_job_processor_group','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(5,'analysis_job_processor_group_association','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(6,'analysis_job_processor_group_membership','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(7,'analysis_job_processors','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(8,'analysis_job_request','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(9,'analysis_job_request','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(10,'analysis_job_request_psm','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(11,'analysis_job_request_psm','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(12,'archive','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(13,'archive_path','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(14,'aux_info_def','operation','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(15,'bionet','enter','DMS_Infrastructure_Administration, DMS_Instrument_Operation, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(16,'campaign','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(17,'capture_jobs','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(18,'capture_multi_job_update','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(19,'capture_script','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(20,'capture_step_tools','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(21,'cell_culture','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(22,'cell_culture','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(23,'data_package','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(24,'data_package','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(25,'data_package_items','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(26,'data_package_job_coverage','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(27,'dataset','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation, DMS_Dataset_Operation');
INSERT INTO "restricted_actions" VALUES(28,'dataset','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(29,'dataset_disposition','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation, DMS_Dataset_Operation');
INSERT INTO "restricted_actions" VALUES(30,'dataset_disposition_lite','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation, DMS_Dataset_Operation');
INSERT INTO "restricted_actions" VALUES(31,'dataset_redisposition','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation, DMS_Dataset_Operation');
INSERT INTO "restricted_actions" VALUES(32,'datasetid','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(33,'delete_dataset','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(34,'eus_proposal_users_operation','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(35,'eus_proposals','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(36,'eus_proposals_operation','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(37,'eus_users','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(38,'eus_users_operation','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(39,'experiment','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(40,'experiment','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(41,'experiment_fraction','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(42,'experiment_group','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(43,'file_attachment','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(44,'file_attachment','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(45,'freezer','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation');
INSERT INTO "restricted_actions" VALUES(46,'instrument','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(47,'instrument_allocation','enter','DMS_Infrastructure_Administration, DMS_Instrument_Tracking');
INSERT INTO "restricted_actions" VALUES(48,'instrument_allocation','operation','DMS_Infrastructure_Administration, DMS_Instrument_Tracking');
INSERT INTO "restricted_actions" VALUES(49,'instrument_allowed_dataset_type','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(50,'instrument_config_history','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(51,'instrument_group','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(52,'instrument_operation_history','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(53,'instrument_operation_history','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(54,'instrument_usage_report','operation','DMS_Infrastructure_Administration, DMS_Instrument_Tracking');
INSERT INTO "restricted_actions" VALUES(55,'instrumentclass','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(56,'instrumentid','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(57,'lc_cart','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(58,'lc_cart_config_history','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(59,'lc_cart_configuration','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(60,'lc_cart_loading','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(61,'lc_cart_request_loading','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(62,'lc_cart_settings_history','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(63,'lc_cart_version','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(64,'lc_column','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(65,'mac_jobs','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(66,'material_container','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(67,'material_container','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(68,'material_location','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(69,'material_move_container','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(70,'material_move_items','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(71,'mc_enable_control_by_manager','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(72,'mc_enable_control_by_manager_type','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(73,'mrm_list_attachment','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(75,'new_instrument','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(76,'new_instrumentclass','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(77,'operations_tasks','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(78,'organism','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(79,'osm_package','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation');
INSERT INTO "restricted_actions" VALUES(80,'param_file','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(81,'pipeline_jobs','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Analysis_Job_Administration, DMS_User');
INSERT INTO "restricted_actions" VALUES(83,'pipeline_script','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(84,'pipeline_step_tools','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(85,'predefined_analysis','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(86,'predefined_analysis_scheduling_rules','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(87,'prep_lc_capture_job','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(88,'prep_lc_column','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(89,'prep_lc_run','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(90,'requested_run','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(91,'requested_run','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(92,'requested_run_admin','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(93,'requested_run_batch','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation, DMS_User');
INSERT INTO "restricted_actions" VALUES(94,'requested_run_batch','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation, DMS_User');
INSERT INTO "restricted_actions" VALUES(95,'requested_run_batch_blocking','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation, DMS_User');
INSERT INTO "restricted_actions" VALUES(96,'requested_run_factors','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(97,'requested_run_group','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(98,'rna_prep_request','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(99,'run_assignment','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(100,'run_interval','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation, DMS_Dataset_Operation');
INSERT INTO "restricted_actions" VALUES(101,'run_op_logs','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(103,'sample_prep_request','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(104,'sample_prep_request_assignment','operation','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Sample_Preparation');
INSERT INTO "restricted_actions" VALUES(105,'sample_submission','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(106,'sample_submission','operation','DMS_User');
INSERT INTO "restricted_actions" VALUES(107,'separation_group','enter','DMS_Infrastructure_Administration, DMS_Instrument_Operation');
INSERT INTO "restricted_actions" VALUES(108,'settings_files','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration');
INSERT INTO "restricted_actions" VALUES(109,'storage','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(110,'table_loader','operation','DMS_Infrastructure_Administration, DMS_Instrument_Operation, DMS_Sample_Preparation');
INSERT INTO "restricted_actions" VALUES(111,'tracking_dataset','enter','DMS_Infrastructure_Administration, DMS_Instrument_Tracking');
INSERT INTO "restricted_actions" VALUES(112,'update_analysis_jobs','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Analysis_Job_Administration');
INSERT INTO "restricted_actions" VALUES(113,'update_archive','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(114,'update_datasets','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation, DMS_Dataset_Operation');
INSERT INTO "restricted_actions" VALUES(115,'user','enter','DMS_Infrastructure_Administration');
INSERT INTO "restricted_actions" VALUES(116,'wellplate','enter','DMS_User');
INSERT INTO "restricted_actions" VALUES(117,'separation_type','enter','DMS_Infrastructure_Administration, DMS_Ops_Administration, DMS_Instrument_Operation');
DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('restricted_actions',117);
COMMIT;
