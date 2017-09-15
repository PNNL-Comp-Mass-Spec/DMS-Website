PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Predefined_Analysis_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Predefined_Analysis_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdatePredefinedAnalysis');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Predefined_Analysis_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_order_by','[Instrument Class], Level, [Seq.], ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'level','Level','text','4','4','','','','trim');
INSERT INTO "form_fields" VALUES(3,'sequence','Sequence','text','4','4','','','','trim');
INSERT INTO "form_fields" VALUES(4,'instrumentClassCriteria','Instrument Class Criteria','text','32','32','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(5,'nextLevel','Next Level','text','4','4','','','','trim');
INSERT INTO "form_fields" VALUES(6,'TriggerBeforeDisposition','Trigger Mode','text','4','32','','','0','trim');
INSERT INTO "form_fields" VALUES(7,'PropagationMode','Export Mode','text','24','24','','','Export','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(8,'campaignNameCriteria','Campaign Criteria','text','60','128','','','','trim');
INSERT INTO "form_fields" VALUES(9,'campaignExclCriteria','Campaign Exclusion Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(10,'experimentNameCriteria','Experiment Criteria','text','60','128','','','','trim');
INSERT INTO "form_fields" VALUES(11,'experimentExclCriteria','Experiment Exclusion Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(12,'instrumentNameCriteria','Instrument Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'instrumentExclCriteria','Instrument Exclusion Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(14,'organismNameCriteria','Organism Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(15,'datasetNameCriteria','Dataset Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(16,'datasetExclCriteria','Dataset Exclusion Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(17,'datasetTypeCriteria','Dataset Type Criteria','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(18,'labellingInclCriteria','Experiment Labelling Criteria','text','60','64','','','','trim');
INSERT INTO "form_fields" VALUES(19,'labellingExclCriteria','Experiment Labelling Exclusion Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(20,'expCommentCriteria','Experiment Comment Criteria','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(21,'separationTypeCriteria','Separation Type Criteria','text','60','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(22,'analysisToolName','Analysis Tool','text','60','64','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(23,'parmFileName','Parm File','area','','','2','60','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(24,'settingsFileName','Settings File','area','','','2','60','','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(25,'organismName','Organism','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(26,'organismDBName','Organism DB File','text','60','128','','','na','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(27,'protCollNameList','Protein Collection List','area','','','3','60','na','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(28,'protCollOptionsList','Protein Options List','area','','','2','60','seq_direction=forward','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(29,'specialProcessing','Special Processing','area','','','3','110','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(30,'priority','Priority','text','4','4','','','3','trim|default_value[3]|required|max_length[2]|numeric');
INSERT INTO "form_fields" VALUES(31,'enabled','Enabled','text','1','1','','','1','trim|default_value[1]|required|max_length[2]');
INSERT INTO "form_fields" VALUES(32,'description','Description','area','','','2','60','','trim');
INSERT INTO "form_fields" VALUES(33,'creator','Creator','text','50','50','','','','trim|required');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'ID','section','Basic Information');
INSERT INTO "form_field_options" VALUES(2,'campaignNameCriteria','section','Evaluation Criteria');
INSERT INTO "form_field_options" VALUES(3,'analysisToolName','section','Job Presets');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'instrumentClassCriteria','picker.replace','instrumentClassPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'campaignNameCriteria','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'campaignExclCriteria','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'experimentNameCriteria','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'experimentExclCriteria','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'instrumentNameCriteria','picker.replace','instrumentNamePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'organismNameCriteria','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'labellingInclCriteria','picker.replace','labellingPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'analysisToolName','picker.replace','analysisToolPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'parmFileName','list-report.helper','','helper_aj_param_file/report','analysisToolName',',','');
INSERT INTO "form_field_choosers" VALUES(11,'settingsFileName','list-report.helper','','helper_aj_settings_file/report/~','analysisToolName',',','');
INSERT INTO "form_field_choosers" VALUES(12,'organismName','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'protCollNameList','list-report.helper','','helper_protein_collection/report','organismName',',','');
INSERT INTO "form_field_choosers" VALUES(14,'protCollOptionsList','picker.replace','protOptSeqDirPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(15,'priority','picker.replace','analysisPredefJobPriPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(16,'enabled','picker.replace','predefEnablePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(17,'creator','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(18,'datasetTypeCriteria','picker.replace','datasetTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(19,'TriggerBeforeDisposition','picker.replace','predefTriggerModePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(20,'PropagationMode','picker.replace','jobPropagationModePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument_class','Instrument Class','6','','Instrument Class','ContainsText','text','80','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_analysis_tool','Analysis Tool','6','','Analysis Tool','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_campaign','Campaign','6','','Campaign Crit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_instrument','Instrument','6','','Instrument Crit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_parm_file','Param File','6','','Parm File','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_experiment','Experiment','6','','Experiment Crit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_dataset','Dataset','','','DatasetCrit.','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(8,'pf_enabled','Enabled','2','','Enabled','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(9,'pf_level','Level','2','','Level','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(10,'pf_sequence_start','Seq Start','3','','Seq.','GreaterThanOrEqualTo','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(11,'pf_sequence_end','Seq End','3','','Seq.','LessThanOrEqualTo','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','predefined_analysis/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Last_Affected','format_date','value','','{"Format":"Y-m-d"}');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Show datasets that satisy this rule...','call','param','predefined_analysis_datasets','Show existing datasets that meet criteria.','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'level','level','int','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(2,'sequence','sequence','varchar','input','12','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(3,'instrumentClassCriteria','instrumentClassCriteria','varchar','input','32','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(4,'campaignNameCriteria','campaignNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(5,'experimentNameCriteria','experimentNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(6,'instrumentNameCriteria','instrumentNameCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(7,'instrumentExclCriteria','instrumentExclCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(8,'organismNameCriteria','organismNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(9,'datasetNameCriteria','datasetNameCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(10,'expCommentCriteria','expCommentCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(11,'labellingInclCriteria','labellingInclCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(12,'labellingExclCriteria','labellingExclCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(13,'analysisToolName','analysisToolName','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(14,'parmFileName','parmFileName','varchar','input','255','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(15,'settingsFileName','settingsFileName','varchar','input','255','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(16,'organismName','organismName','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(17,'organismDBName','organismDBName','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(18,'protCollNameList','protCollNameList','varchar','input','512','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(19,'protCollOptionsList','protCollOptionsList','varchar','input','256','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(20,'priority','priority','int','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(21,'enabled','enabled','tinyint','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(22,'description','description','varchar','input','512','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(23,'creator','creator','varchar','input','50','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(24,'nextLevel','nextLevel','varchar','input','12','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(25,'ID','ID','int','output','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(26,'<local>','mode','varchar','input','12','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(27,'<local>','message','varchar','output','512','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(28,'separationTypeCriteria','separationTypeCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(29,'campaignExclCriteria','campaignExclCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(30,'experimentExclCriteria','experimentExclCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(31,'datasetExclCriteria','datasetExclCriteria','varchar','input','128','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(32,'datasetTypeCriteria','datasetTypeCriteria','varchar','input','64','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(33,'TriggerBeforeDisposition','TriggerBeforeDisposition','tinyint','input','','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(34,'PropagationMode','PropagationMode','varchar','input','24','AddUpdatePredefinedAnalysis');
INSERT INTO "sproc_args" VALUES(35,'specialProcessing','specialProcessing','varchar','input','512','AddUpdatePredefinedAnalysis');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Settings File Name','detail-report','Settings File Name','settings_files/report/-/~','labelCol','settings_file',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Instrument Class Criteria','detail-report','Instrument Class Criteria','instrumentclass/show','labelCol','instrument_class',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'ID','detail-report','ID','predefined_analysis_datasets/param','labelCol','preview_datasets_matching_predefine','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Dataset Criteria','detail-report','Dataset Criteria','predefined_analysis_preview/param','labelCol','preview_predefines_by_dataset','');
CREATE TABLE utility_queries ( id integer PRIMARY KEY, name text, label text, db text, "table" text, columns text, sorting text, filters text, hotlinks text );
INSERT INTO "utility_queries" VALUES(1,'queue','Datasets Predefined Analysis Queue','','V_Predefined_Analysis_Scheduling_Queue_List_Report','*','{"col":"Item", "dir":"DESC"}','{"Dataset":"CTx", "ID":"Equals", "State":"CTx", "Message":"CTx", "User":"CTx", "Entered":"LTd"}','{"Dataset":{"LinkType":"invoke_entity","Target":"dataset\/show\/"},"Message":{"LinkType":"min_col_width", "Target":"40"}}');
COMMIT;
