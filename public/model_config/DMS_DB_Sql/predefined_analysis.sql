﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Predefined_Analysis_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Predefined_Analysis_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdatePredefinedAnalysis');
INSERT INTO "general_params" VALUES('entry_page_data_table','v_predefined_analysis_entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','id');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Instrument Class, Level, Seq., ID');
INSERT INTO "general_params" VALUES('post_submission_detail_id','id');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'id','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'level','Level','text','4','4','','','','trim');
INSERT INTO "form_fields" VALUES(3,'sequence','Sequence','text','4','4','','','','trim');
INSERT INTO "form_fields" VALUES(4,'instrument_class_criteria','Instrument Class Criteria','text','32','32','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(5,'next_level','Next Level','text','4','4','','','','trim');
INSERT INTO "form_fields" VALUES(6,'trigger_before_disposition','Trigger Mode','text','4','32','','','0','trim');
INSERT INTO "form_fields" VALUES(7,'propagation_mode','Export Mode','text','24','24','','','Export','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(8,'campaign_name_criteria','Campaign Criteria','text','60','128','','','','trim');
INSERT INTO "form_fields" VALUES(9,'campaign_excl_criteria','Campaign Exclusion Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(10,'experiment_name_criteria','Experiment Criteria','text','60','128','','','','trim');
INSERT INTO "form_fields" VALUES(11,'experiment_excl_criteria','Experiment Exclusion Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(12,'instrument_name_criteria','Instrument Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'instrument_excl_criteria','Instrument Exclusion Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(14,'organism_name_criteria','Organism Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(15,'dataset_name_criteria','Dataset Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(16,'dataset_excl_criteria','Dataset Exclusion Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(17,'dataset_type_criteria','Dataset Type Criteria','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(18,'labelling_incl_criteria','Experiment Labelling Criteria','text','60','64','','','','trim');
INSERT INTO "form_fields" VALUES(19,'labelling_excl_criteria','Experiment Labelling Exclusion Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(20,'exp_comment_criteria','Experiment Comment Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(21,'separation_type_criteria','Separation Type Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(22,'analysis_tool_name','Analysis Tool','text','60','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(23,'parm_file_name','Parm File','area','','','2','60','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(24,'settings_file_name','Settings File','area','','','2','60','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(25,'organism_name','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(26,'organism_dbname','Organism DB File','text','60','128','','','na','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(27,'prot_coll_name_list','Protein Collection List','area','','','3','60','na','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(28,'prot_coll_options_list','Protein Options List','area','','','2','60','seq_direction=forward','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(29,'special_processing','Special Processing','area','','','3','110','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(30,'priority','Priority','text','4','4','','','3','trim|default_value[3]|required|max_length[2]|numeric');
INSERT INTO "form_fields" VALUES(31,'enabled','Enabled','text','1','1','','','1','trim|default_value[1]|required|max_length[2]');
INSERT INTO "form_fields" VALUES(32,'description','Description','area','','','2','60','','trim');
INSERT INTO "form_fields" VALUES(33,'creator','Creator','text','50','50','','','','trim|required');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'id','section','Basic Information');
INSERT INTO "form_field_options" VALUES(2,'campaign_name_criteria','section','Evaluation Criteria');
INSERT INTO "form_field_options" VALUES(3,'analysis_tool_name','section','Job Presets');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'instrument_class_criteria','picker.replace','instrumentClassPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'campaign_name_criteria','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'campaign_excl_criteria','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'experiment_name_criteria','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'experiment_excl_criteria','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'instrument_name_criteria','picker.replace','instrumentNamePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'organism_name_criteria','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'labelling_incl_criteria','picker.replace','labellingPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'analysis_tool_name','picker.replace','analysisToolPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'parm_file_name','list-report.helper','','helper_aj_param_file/report','analysis_tool_name',',','');
INSERT INTO "form_field_choosers" VALUES(11,'settings_file_name','list-report.helper','','helper_aj_settings_file/report/~','analysis_tool_name',',','');
INSERT INTO "form_field_choosers" VALUES(12,'organism_name','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'prot_coll_name_list','list-report.helper','','helper_protein_collection/report','organism_name',',','');
INSERT INTO "form_field_choosers" VALUES(14,'prot_coll_options_list','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(15,'priority','picker.replace','analysisPredefJobPriPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(16,'enabled','picker.replace','predefEnablePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(17,'creator','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(18,'dataset_type_criteria','picker.replace','datasetTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(19,'trigger_before_disposition','picker.replace','predefTriggerModePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(20,'propagation_mode','picker.replace','jobPropagationModePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument_class','Instrument Class','6','','Instrument Class','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_analysis_tool','Analysis Tool','6','','Analysis Tool','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_campaign','Campaign','20!','','Campaign Crit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','6','','Instrument Crit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_parm_file','Param File','45!','','Parm File','ContainsText','text','256','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_experiment','Experiment','25!','','Experiment Crit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_dataset','Dataset','20!','','DatasetCrit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_enabled','Enabled','2!','','Enabled','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_level','Level','2!','','Level','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_sequence_start','Seq Start','3!','','Seq.','GreaterThanOrEqualTo','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_sequence_end','Seq End','3!','','Seq.','LessThanOrEqualTo','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','predefined_analysis/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Last_Affected','format_date','value','','{"Format":"Y-m-d"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'Description','min_col_width','value','30','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Show datasets that satisy this rule...','call','param','predefined_analysis_datasets','Show existing datasets that meet criteria.','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'level','level','int','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(2,'sequence','sequence','varchar','input','12','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(3,'instrument_class_criteria','instrumentClassCriteria','varchar','input','32','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(4,'campaign_name_criteria','campaignNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(5,'experiment_name_criteria','experimentNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(6,'instrument_name_criteria','instrumentNameCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(7,'instrument_excl_criteria','instrumentExclCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(8,'organism_name_criteria','organismNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(9,'dataset_name_criteria','datasetNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(10,'exp_comment_criteria','expCommentCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(11,'labelling_incl_criteria','labellingInclCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(12,'labelling_excl_criteria','labellingExclCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(13,'analysis_tool_name','analysisToolName','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(14,'parm_file_name','parmFileName','varchar','input','255','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(15,'settings_file_name','settingsFileName','varchar','input','255','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(16,'organism_name','organismName','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(17,'organism_dbname','organismDBName','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(18,'prot_coll_name_list','protCollNameList','varchar','input','512','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(19,'prot_coll_options_list','protCollOptionsList','varchar','input','256','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(20,'priority','priority','int','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(21,'enabled','enabled','tinyint','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(22,'description','description','varchar','input','512','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(23,'creator','creator','varchar','input','50','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(24,'next_level','nextLevel','varchar','input','12','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(25,'id','ID','int','output','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(26,'<local>','mode','varchar','input','12','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(27,'<local>','message','varchar','output','512','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(28,'separation_type_criteria','separationTypeCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(29,'campaign_excl_criteria','campaignExclCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(30,'experiment_excl_criteria','experimentExclCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(31,'dataset_excl_criteria','datasetExclCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(32,'dataset_type_criteria','datasetTypeCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(33,'trigger_before_disposition','TriggerBeforeDisposition','tinyint','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(34,'propagation_mode','PropagationMode','varchar','input','24','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(35,'special_processing','specialProcessing','varchar','input','512','AddUpdatePredefinedAnalysis');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Settings File Name','detail-report','Settings File Name','settings_files/report/-/~','labelCol','settings_file',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Instrument Class Criteria','detail-report','Instrument Class Criteria','instrumentclass/show','labelCol','instrument_class',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'ID','detail-report','ID','predefined_analysis_datasets/param','labelCol','preview_datasets_matching_predefine','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Dataset Criteria','detail-report','Dataset Criteria','predefined_analysis_preview/param','labelCol','preview_predefines_by_dataset','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Parmfile Name','detail-report','Parmfile Name','param_file/report/-/~','labelCol','parameter_file','');
CREATE TABLE utility_queries ( id integer PRIMARY KEY, name text, label text, db text, "table" text, columns text, sorting text, filters text, hotlinks text );
INSERT INTO "utility_queries" VALUES(1,'queue','Datasets Predefined Analysis Queue','','V_Predefined_Analysis_Scheduling_Queue_List_Report','*','{"col":"Item", "dir":"DESC"}','{"Dataset":"CTx", "ID":"Equals", "State":"CTx", "Message":"CTx", "User":"CTx", "Entered":"LTd"}','{"Dataset":{"LinkType":"invoke_entity","Target":"dataset\/show\/"},"Message":{"LinkType":"min_col_width", "Target":"40"}}');
COMMIT;
