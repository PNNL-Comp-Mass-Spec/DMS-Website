﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('list_report_sproc','predefined_analysis_jobs_mds_proc');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'dataset_list','Datasets','area','','','4','60','QC_05_3_f_22Jan07_Phoenix_06-11-19, core_BO215_050601B_06Jun05_Agilent_0305-12s, ShewFed089_LTQFT2_23Feb06_Griffin_06-01-09','trim|required|max_length[3500]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'dataset_list','list-report.helper','','helper_dataset_ckbx/report/-/-/-/-/-/52','',',','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Dataset','invoke_entity','value','dataset/show','');
INSERT INTO list_report_hotlinks VALUES(2,'Jobs','invoke_entity','Dataset','analysis_job/report/-/-/-/~','');
INSERT INTO list_report_hotlinks VALUES(3,'Job','row_to_url','Dataset','analysis_group/create/predefined_analysis_preview/post','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'dataset_list','datasetList','varchar','input','3500','predefined_analysis_jobs_mds_proc');
INSERT INTO sproc_args VALUES(2,'<local>','message','varchar','output','512','predefined_analysis_jobs_mds_proc');
COMMIT;
