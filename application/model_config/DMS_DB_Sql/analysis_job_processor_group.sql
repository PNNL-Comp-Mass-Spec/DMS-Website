﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Analysis_Job_Processor_Group_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Analysis_Job_Processor_Group_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Analysis_Job_Processor_Group_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'GroupName','Name','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'GroupDescription','Description','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(4,'GroupEnabled','Enabled','text','1','1','','','Y','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(5,'AvailableForGeneralProcessing','AvailableForGeneralProcessing','text','1','1','','','Y','trim|max_length[1]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'GroupEnabled','picker.replace','YNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'AvailableForGeneralProcessing','picker.replace','YNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_group_name','Group Name','32','','Group Name','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','analysis_job_processor_group/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Associated Jobs','invoke_entity','ID','analysis_job_processor_group_association/report','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Members','detail-report','ID','analysis_job_processor_group_membership/report','labelCol','members',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Associated Jobs','detail-report','ID','analysis_job_processor_group_association/report','labelCol','associated_jobs',NULL);
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(2,'GroupName','GroupName','varchar','input','64','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(3,'GroupDescription','GroupDescription','varchar','input','512','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(4,'GroupEnabled','GroupEnabled','char','input','1','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(5,'AvailableForGeneralProcessing','AvailableForGeneralProcessing','char','input','1','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(6,'<local>','mode','varchar','input','12','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(7,'<local>','message','varchar','output','512','AddUpdateAnalysisJobProcessorGroup');
INSERT INTO "sproc_args" VALUES(8,'<local>','callingUser','varchar','input','128','AddUpdateAnalysisJobProcessorGroup');
COMMIT;
