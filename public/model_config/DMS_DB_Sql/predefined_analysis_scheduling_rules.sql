﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_predefined_analysis_scheduling_rules_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','evaluation_order');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('detail_report_data_table','v_predefined_analysis_scheduling_rules_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','add_update_predefined_analysis_scheduling_rules');
INSERT INTO general_params VALUES('entry_page_data_table','v_predefined_analysis_scheduling_rules_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','','trim|default_value[0]');
INSERT INTO form_fields VALUES(2,'evaluation_order','Evaluation Order','text','2','2','','','','trim');
INSERT INTO form_fields VALUES(3,'instrument_class','Instrument Class','text','32','32','','','','trim');
INSERT INTO form_fields VALUES(4,'instrument_name','Instrument Name','text','60','64','','','','trim');
INSERT INTO form_fields VALUES(5,'dataset_name','Dataset Name','text','60','128','','','','trim');
INSERT INTO form_fields VALUES(6,'analysis_tool_name','Analysis Tool Name','text','60','64','','','','trim');
INSERT INTO form_fields VALUES(7,'priority','Priority','text','4','4','','','3','trim');
INSERT INTO form_fields VALUES(8,'processor_group','Processor Group','text','60','64','','','','trim');
INSERT INTO form_fields VALUES(9,'enabled','Enabled','text','1','1','','','1','trim');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'instrument_class','picker.replace','instrumentClassPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'instrument_name','picker.replace','instrumentNameAdminPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'dataset_name','list-report.helper','','helper_dataset/report','',',','');
INSERT INTO form_field_choosers VALUES(4,'analysis_tool_name','picker.replace','analysisToolPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'priority','picker.replace','analysisJobPriPickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'processor_group','picker.replace','associatedProcessorGroupPickList','','',',','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','predefined_analysis_scheduling_rules/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'evaluation_order','evaluationOrder','smallint','input','','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(2,'instrument_class','instrumentClass','varchar','input','32','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(3,'instrument_name','instrumentName','varchar','input','64','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(4,'dataset_name','datasetName','varchar','input','128','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(5,'analysis_tool_name','analysisToolName','varchar','input','64','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(6,'priority','priority','int','input','','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(7,'processor_group','processorGroup','varchar','input','64','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(8,'enabled','enabled','tinyint','input','','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(9,'id','id','int','output','','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(10,'<local>','mode','varchar','input','12','add_update_predefined_analysis_scheduling_rules');
INSERT INTO sproc_args VALUES(11,'<local>','message','varchar','output','512','add_update_predefined_analysis_scheduling_rules');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_id','ID','','','id','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_evaluation_order','Evaluation Order','','','evaluation_order','Equals','text','','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_class','Class','','','instrument_class','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_dataset','Dataset','','','dataset','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_tool','Tool','','','analysis_tool','ContainsText','text','','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_enabled','Enabled','','','enabled','Equals','text','','','');
COMMIT;
