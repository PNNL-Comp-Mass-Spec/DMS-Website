﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Predefined_Analysis_Scheduling_Rules_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Evaluation Order');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Predefined_Analysis_Scheduling_Rules_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Predefined_Analysis_Scheduling_Rules_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'evaluationOrder','Evaluation Order','text','2','2','','','','trim');
INSERT INTO "form_fields" VALUES(3,'instrumentClass','Instrument Class','text','32','32','','','','trim');
INSERT INTO "form_fields" VALUES(4,'instrumentName','Instrument Name','text','60','64','','','','trim');
INSERT INTO "form_fields" VALUES(5,'datasetName','Dataset Name','text','60','128','','','','trim');
INSERT INTO "form_fields" VALUES(6,'analysisToolName','Analysis Tool Name','text','60','64','','','','trim');
INSERT INTO "form_fields" VALUES(7,'priority','Priority','text','4','4','','','3','trim');
INSERT INTO "form_fields" VALUES(8,'processorGroup','Processor Group','text','60','64','','','','trim');
INSERT INTO "form_fields" VALUES(9,'enabled','Enabled','text','1','1','','','1','trim');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'instrumentClass','picker.replace','instrumentClassPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'instrumentName','picker.replace','instrumentNameAdminPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'datasetName','list-report.helper','','helper_dataset/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'analysisToolName','picker.replace','analysisToolPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'priority','picker.replace','analysisJobPriPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'processorGroup','picker.replace','associatedProcessorGroupPickList','','',',','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','predefined_analysis_scheduling_rules/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'evaluationOrder','evaluationOrder','smallint','input','','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(2,'instrumentClass','instrumentClass','varchar','input','32','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(3,'instrumentName','instrumentName','varchar','input','64','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(4,'datasetName','datasetName','varchar','input','128','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(5,'analysisToolName','analysisToolName','varchar','input','64','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(6,'priority','priority','int','input','','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(7,'processorGroup','processorGroup','varchar','input','64','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(8,'enabled','enabled','tinyint','input','','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(9,'ID','ID','int','output','','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(10,'<local>','mode','varchar','input','12','AddUpdatePredefinedAnalysisSchedulingRules');
INSERT INTO "sproc_args" VALUES(11,'<local>','message','varchar','output','512','AddUpdatePredefinedAnalysisSchedulingRules');
COMMIT;