PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Experiment_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Experiment_Detail_Report_Ex');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Experiment');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateExperiment');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Experiment_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','experimentNum');
INSERT INTO "general_params" VALUES('detail_report_aux_info_target','Experiment');
INSERT INTO "general_params" VALUES('operations_sproc','DoMaterialItemOperation');
INSERT INTO "general_params" VALUES('post_submission_detail_id','experimentNum');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO "general_params" VALUES('list_report_data_cols','[ID],[Experiment],[Researcher],[Organism],[Reason],[Comment],[Concentration],[Created],[Campaign],[Cell Cultures] AS Biomaterial,[Enzyme],[Notebook],[Labelling],[Request],[Alkylated],[Tissue]');
INSERT INTO "general_params" VALUES('detail_report_data_cols','Experiment,Researcher,Comment,Created,[Sample Concentration],[Digestion Enzyme],[Lab Notebook],Campaign,[Cell Cultures],Labelling,Alkylated,Request,[Experiment Groups],Datasets,[Most Recent Dataset],Factors,[Plant/Animal Tissue],[Tissue ID],[Experiment Files],[Experiment Group Files],ID,[Material Status],[Last Used],Barcode');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'experimentNum','Experiment Name','text-if-new','40','80','','','','trim|required|max_length[50]|alpha_dash|min_length[6]');
INSERT INTO "form_fields" VALUES(2,'campaignNum','Campaign Name','text','40','80','','','','trim|required|max_length[64]|not_contain[Placeholder]');
INSERT INTO "form_fields" VALUES(3,'researcherPRN','Researcher (PRN)','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(4,'organismName','Organism Name','text','40','80','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(5,'reason','Reason for Experiment','area','','','4','50','','trim|required|max_length[500]');
INSERT INTO "form_fields" VALUES(6,'tissue','Plant/Animal Tissue','text','50','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(7,'cellCultureList','Cell Culture','area','','','2','40','','trim|max_length[2048]');
INSERT INTO "form_fields" VALUES(8,'referenceCompoundList','Reference Compounds','area','','','3','60','','trim|max_length[2048]');
INSERT INTO "form_fields" VALUES(9,'sampleConcentration','Smpl. Concentration','text','40','80','','','','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(10,'enzymeName','Digestion Enzyme','text','40','50','','','Trypsin','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(11,'labNotebookRef','Lab Notebook','text','40','80','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(12,'labelling','Labelling','text','40','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'comment','Comment','area','','','4','50','','trim|max_length[500]');
INSERT INTO "form_fields" VALUES(14,'internalStandard','Predigest Int Std','text','50','50','','','none','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(15,'postdigestIntStd','Postdigest Int Std','text','50','50','','','none','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(16,'alkylation','Alkylation','text','1','1','','','N','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(17,'samplePrepRequest','Sample Prep Request','text','4','4','','','0','trim');
INSERT INTO "form_fields" VALUES(18,'container','Container','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(19,'wellplateNum','Wellplate Number','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(20,'wellNum','Well Number','text','8','8','','','','trim|max_length[8]');
INSERT INTO "form_fields" VALUES(21,'barcode','Barcode','text','40','64','','','','trim|max_length[64]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'researcherPRN','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'experimentNum','list-report.helper','','helper_experiment/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'campaignNum','list-report.helper','','helper_campaign/report','campaignNum',',','');
INSERT INTO "form_field_choosers" VALUES(3,'researcherPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'organismName','picker.replace','orgPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'cellCultureList','list-report.helper','','helper_cell_culture/report','cellCultureList',';','');
INSERT INTO "form_field_choosers" VALUES(6,'referenceCompoundList','list-report.helper','','helper_reference_compound/report/-/-/-/-/-/','referenceCompoundList',';','');
INSERT INTO "form_field_choosers" VALUES(7,'enzymeName','picker.replace','enzymePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'labelling','picker.replace','labellingPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'internalStandard','list-report.helper','','helper_internal_standards_predigest/report','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'postdigestIntStd','list-report.helper','','helper_internal_standards_postdigest/report','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'container','list-report.helper','','helper_material_container/report','',',','');
INSERT INTO "form_field_choosers" VALUES(12,'alkylation','picker.replace','YNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'samplePrepRequest','list-report.helper','','helper_sample_prep/report/-/-/~','organismName',',','');
INSERT INTO "form_field_choosers" VALUES(14,'tissue','list-report.helper','','helper_tissue/report','tissue',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_experiment','Experiment','30!','','Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_campaign','Campaign','15!','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_id','ID','6!','','ID','Equals','text','22','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_organism','Organism','15!','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_tissue','Tissue','10','','Tissue','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Experiment','invoke_entity','value','experiment/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Request','invoke_entity','value','sample_prep_request/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Reason','min_col_width','value','45','');
INSERT INTO "list_report_hotlinks" VALUES(4,'Comment','min_col_width','value','45','');
INSERT INTO "list_report_hotlinks" VALUES(5,'Location','min_col_width','value','20','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Tissue','invoke_entity','value','tissue/report/~','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Retire Experiment','cmd_op','retire_experiment','experiment','Make this experiment inactive and remove it from the current container','Are you sure that you want to retire this experiment? Container/location information will be removed.');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Experiment Groups','link_list','Experiment Groups','experiment_group/show','valueCol','experiment_groups','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'Request','detail-report','Request','sample_prep_request/show','labelCol','request','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Datasets','detail-report','Experiment','dataset/report/-/-/-/-/~','labelCol','datasets','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Cell Cultures','link_list','Cell Cultures','cell_culture/show','valueCol','dl_cell_cultures','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Reference Compounds','link_list','Reference Compounds','reference_compound/report/-/-/-/-/@','valueCol','dl_reference_compounds','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Factors','detail-report','Experiment','custom_factors/report/-/-/-/~','labelCol','dl_custom_factors','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Experiment Files','detail-report','Experiment','file_attachment/report/-/experiment/','labelCol','dl_experiment_files','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Experiment Group Files','detail-report','ID','experiment_file_attachment/report','labelCol','dl_experiment_group_files','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'Container','detail-report','Container','material_items/report/~','labelCol','dl_Container','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'+Container','detail-report','Container','material_move_items/report/~','valueCol','dl_container_move','');
INSERT INTO "detail_report_hotlinks" VALUES(12,'Location','detail-report','Location','material_items/report/-/','labelCol','dl_location','');
INSERT INTO "detail_report_hotlinks" VALUES(13,'+Location','detail-report','Location','material_move_items/report/-/','valueCol','dl_location_move','');
INSERT INTO "detail_report_hotlinks" VALUES(14,'Organism','detail-report','Organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO "detail_report_hotlinks" VALUES(15,'Researcher','detail-report','Researcher','user/report/-/~','labelCol','dl_researcher','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(16,'Plant/Animal Tissue','detail-report','Plant/Animal Tissue','tissue/report/~','valueCol','dl_tissue','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO "external_sources" VALUES(1,'sample_prep_request','experimentNum','ColName','Sample Group Naming Prefix');
INSERT INTO "external_sources" VALUES(2,'sample_prep_request','campaignNum','ColName','Campaign');
INSERT INTO "external_sources" VALUES(3,'sample_prep_request','organismName','ColName','Organism');
INSERT INTO "external_sources" VALUES(4,'sample_prep_request','reason','ColName','Reason');
INSERT INTO "external_sources" VALUES(6,'sample_prep_request','enzymeName','Literal','Trypsin');
INSERT INTO "external_sources" VALUES(7,'sample_prep_request','comment','ColName','Comment');
INSERT INTO "external_sources" VALUES(10,'sample_prep_request','samplePrepRequest','ColName','ID');
INSERT INTO "external_sources" VALUES(11,'sample_prep_request','tissue','ColName','Plant/Animal Tissue');
INSERT INTO "external_sources" VALUES(12,'rna_prep_request','experimentNum','ColName','Sample Group Naming Prefix');
INSERT INTO "external_sources" VALUES(13,'rna_prep_request','campaignNum','ColName','Campaign');
INSERT INTO "external_sources" VALUES(14,'rna_prep_request','organismName','ColName','Organism');
INSERT INTO "external_sources" VALUES(15,'rna_prep_request','reason','ColName','Reason');
INSERT INTO "external_sources" VALUES(16,'rna_prep_request','cellCultureList','ColName','Biomaterial List');
INSERT INTO "external_sources" VALUES(17,'rna_prep_request','enzymeName','Literal','Trypsin');
INSERT INTO "external_sources" VALUES(19,'rna_prep_request','samplePrepRequest','ColName','ID');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','name','varchar','input','128','DoMaterialItemOperation');
INSERT INTO "sproc_args" VALUES(2,'<local>','mode','varchar','input','32','DoMaterialItemOperation');
INSERT INTO "sproc_args" VALUES(3,'<local>','message','varchar','output','512','DoMaterialItemOperation');
INSERT INTO "sproc_args" VALUES(4,'<local>','callingUser','varchar','input','128','DoMaterialItemOperation');
INSERT INTO "sproc_args" VALUES(5,'experimentNum','experimentNum','varchar','input','50','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(6,'campaignNum','campaignNum','varchar','input','64','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(7,'researcherPRN','researcherPRN','varchar','input','50','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(8,'organismName','organismName','varchar','input','50','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(9,'reason','reason','varchar','input','500','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(10,'comment','comment','varchar','input','500','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(11,'sampleConcentration','sampleConcentration','varchar','input','32','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(12,'enzymeName','enzymeName','varchar','input','50','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(13,'labNotebookRef','labNotebookRef','varchar','input','64','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(14,'labelling','labelling','varchar','input','64','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(15,'cellCultureList','cellCultureList','varchar','input','512','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(16,'referenceCompoundList','referenceCompoundList','varchar','input','512','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(17,'samplePrepRequest','samplePrepRequest','int','input','','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(18,'internalStandard','internalStandard','varchar','input','50','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(19,'postdigestIntStd','postdigestIntStd','varchar','input','50','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(20,'wellplateNum','wellplateNum','varchar','input','64','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(21,'wellNum','wellNum','varchar','input','8','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(22,'alkylation','alkylation','varchar','input','1','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(23,'<local>','mode','varchar','input','12','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(24,'<local>','message','varchar','output','512','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(25,'container','container','varchar','input','128','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(26,'barcode','barcode','varchar','input','64','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(27,'tissue','tissue','varchar','intput','128','AddUpdateExperiment');
INSERT INTO "sproc_args" VALUES(28,'<local>','callingUser','varchar','input','128','AddUpdateExperiment');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO "primary_filter_choosers" VALUES(1,'pf_organism','picker.replace','orgPickList','','',',');
COMMIT;
