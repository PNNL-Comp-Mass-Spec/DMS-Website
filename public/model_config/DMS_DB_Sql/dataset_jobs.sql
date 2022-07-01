﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Dataset_ID, Job');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('list_report_data_cols','Dataset_ID, Dataset, Job, Pri, State, Tool, Campaign, Experiment, Instrument, "Param File", Settings_File, Organism, Tissue, "Job Organism", "Organism DB", "Protein Collection List", "Protein Options", Comment, Created, Started, Finished, Runtime, Progress, ETA_Minutes, "Job Request", "Results Folder", "Results Folder Path", Last_Affected, Rating');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_datasetid','Dataset ID','6!','','Dataset_ID','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_dataset','Dataset','60!','','Dataset','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_job','Job','6!','','Job','Equals','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_state','State','','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_tool','Tool','','','Tool','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_campaign','Campaign','','','Campaign','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_param_file','Param File','25!','','Param File','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_protein_collection_list','Protein Collection List','25!','','Protein Collection List','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_comment','Comment','','','Comment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_most_recent_weeks','Most recent weeks','3!','','Last_Affected','MostRecentWeeks','text','32','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_settings_file','Settings File','15!','','Settings_File','ContainsText','text','128','','');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_state','picker.replace','analysisJobStatePickList','','',',');
INSERT INTO "primary_filter_choosers" VALUES(2,'pf_tool','picker.replace','analysisToolPickList','','',',');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Job','invoke_entity','value','analysis_job/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Dataset_ID','invoke_entity','value','datasetid/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Job Request','invoke_entity','value','analysis_job_request/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','60','');
COMMIT;
