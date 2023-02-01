﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_experiment_list_report_2');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_experiment_detail_report_ex');
INSERT INTO general_params VALUES('detail_report_data_id_col','experiment');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateExperiment');
INSERT INTO general_params VALUES('entry_page_data_table','v_experiment_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','experiment');
INSERT INTO general_params VALUES('detail_report_aux_info_target','Experiment');
INSERT INTO general_params VALUES('operations_sproc','DoMaterialItemOperation');
INSERT INTO general_params VALUES('post_submission_detail_id','experiment');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO general_params VALUES('list_report_data_cols','id, experiment, researcher, organism, reason, comment, concentration, created, campaign, biomaterial_list, enzyme, notebook, labelling, request, alkylated, tissue');
INSERT INTO general_params VALUES('detail_report_data_cols','experiment, researcher, reason_for_experiment, comment, created, sample_concentration, digestion_enzyme, lab_notebook, campaign, biomaterial_list, labelling, alkylated, request, experiment_groups, datasets, most_recent_dataset, factors, plant_or_animal_tissue, tissue_id, experiment_files, experiment_group_files, id, material_status, last_used, barcode');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','Experiment ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'experiment','Experiment Name','text','40','80','','','','trim|required|max_length[50]|alpha_dash|min_length[6]');
INSERT INTO form_fields VALUES(3,'campaign','Campaign Name','text','40','80','','','','trim|required|max_length[64]|not_contain[Placeholder]');
INSERT INTO form_fields VALUES(4,'researcher_prn','Researcher','text','40','80','','','','trim|required|max_length[50]');
INSERT INTO form_fields VALUES(5,'organism_name','Organism Name','text','40','80','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(6,'reason','Reason for Experiment','area','','','4','50','','trim|required|max_length[500]');
INSERT INTO form_fields VALUES(7,'tissue','Plant/Animal Tissue','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(8,'biomaterial_list','Biomaterial (Cell Culture)','area','','','2','40','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(9,'reference_compound_list','Reference Compounds','area','','','3','60','','trim|max_length[2048]');
INSERT INTO form_fields VALUES(10,'sample_concentration','Smpl. Concentration','text','40','80','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(11,'enzyme_name','Digestion Enzyme','text','40','50','','','Trypsin','trim|required|max_length[50]');
INSERT INTO form_fields VALUES(12,'lab_notebook_ref','Lab Notebook','text','40','80','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(13,'labelling','Labelling','text','40','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(14,'comment','Comment','area','','','4','50','','trim|max_length[500]');
INSERT INTO form_fields VALUES(15,'internal_standard','Predigest Int Std','text','50','50','','','none','trim|max_length[50]');
INSERT INTO form_fields VALUES(16,'postdigest_int_std','Postdigest Int Std','text','50','50','','','none','trim|max_length[50]');
INSERT INTO form_fields VALUES(17,'alkylation','Alkylation','text','1','1','','','N','trim|max_length[1]');
INSERT INTO form_fields VALUES(18,'sample_prep_request','Sample Prep Request','text','4','4','','','0','trim');
INSERT INTO form_fields VALUES(19,'container','Container','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(20,'wellplate','Wellplate','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(21,'well','Well Number','text','8','8','','','','trim|max_length[8]');
INSERT INTO form_fields VALUES(22,'barcode','Barcode','text','40','64','','','','trim|max_length[64]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO form_field_options VALUES(1,'researcher_prn','default_function','GetUser()');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'experiment','list-report.helper','','helper_experiment/report','experiment',',','');
INSERT INTO form_field_choosers VALUES(2,'campaign','list-report.helper','','helper_campaign/report/Active/','campaign',',','');
INSERT INTO form_field_choosers VALUES(3,'researcher_prn','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'organism_name','picker.replace','orgPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'biomaterial_list','list-report.helper','','helper_biomaterial/report','biomaterial_list',';','');
INSERT INTO form_field_choosers VALUES(6,'reference_compound_list','list-report.helper','','helper_reference_compound/report/-/-/-/-/-/','reference_compound_list',';','');
INSERT INTO form_field_choosers VALUES(7,'enzyme_name','picker.replace','enzymePickList','','',',','');
INSERT INTO form_field_choosers VALUES(8,'labelling','picker.replace','labellingPickList','','',',','');
INSERT INTO form_field_choosers VALUES(9,'internal_standard','list-report.helper','','helper_internal_standards_predigest/report','',',','');
INSERT INTO form_field_choosers VALUES(10,'postdigest_int_std','list-report.helper','','helper_internal_standards_postdigest/report','',',','');
INSERT INTO form_field_choosers VALUES(11,'container','list-report.helper','','helper_material_container/report','',',','');
INSERT INTO form_field_choosers VALUES(12,'alkylation','picker.replace','YNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(13,'sample_prep_request','list-report.helper','','helper_sample_prep/report/-/-/~','organism_name',',','');
INSERT INTO form_field_choosers VALUES(14,'tissue','list-report.helper','','helper_tissue/report','tissue',',','');
INSERT INTO form_field_choosers VALUES(15,'wellplate','picker.replace','wellplatePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_experiment','Experiment','30!','','experiment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_campaign','Campaign','15!','','campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_id','ID','6!','','id','Equals','text','22','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_organism','Organism','15!','','organism','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_tissue','Tissue','10','','tissue','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_reason','Reason','10','','reason','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_comment','Comment','10','','comment','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'experiment','invoke_entity','value','experiment/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'request','invoke_entity','value','sample_prep_request/show','');
INSERT INTO list_report_hotlinks VALUES(3,'reason','min_col_width','value','45','');
INSERT INTO list_report_hotlinks VALUES(4,'comment','min_col_width','value','45','');
INSERT INTO list_report_hotlinks VALUES(5,'location','min_col_width','value','20','');
INSERT INTO list_report_hotlinks VALUES(6,'tissue','invoke_entity','value','tissue/report/~','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Retire Experiment','cmd_op','retire_experiment','experiment','Make this experiment inactive and remove it from the current container','Are you sure that you want to retire this experiment? Container/location information will be removed.');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'campaign','detail-report','campaign','campaign/show','labelCol','campaign','');
INSERT INTO detail_report_hotlinks VALUES(2,'experiment_groups','link_list','experiment_groups','experiment_group/show','valueCol','experiment_groups','{"HideLinkIfValueMatch":"(none)"}');
INSERT INTO detail_report_hotlinks VALUES(3,'request','detail-report','request','sample_prep_request/show','labelCol','request','');
INSERT INTO detail_report_hotlinks VALUES(4,'datasets','detail-report','experiment','dataset/report/-/-/-/-/~','labelCol','datasets','');
INSERT INTO detail_report_hotlinks VALUES(5,'biomaterial_list','link_list','biomaterial_list','biomaterial/show','valueCol','dl_biomaterial','');
INSERT INTO detail_report_hotlinks VALUES(6,'reference_compounds','link_list','reference_compounds','reference_compound/report/-/-/-/-/@','valueCol','dl_reference_compounds','');
INSERT INTO detail_report_hotlinks VALUES(7,'factors','detail-report','experiment','custom_factors/report/-/-/-/~','labelCol','dl_custom_factors','');
INSERT INTO detail_report_hotlinks VALUES(8,'experiment_files','detail-report','experiment','file_attachment/report/-/experiment/','labelCol','dl_experiment_files','');
INSERT INTO detail_report_hotlinks VALUES(9,'experiment_group_files','detail-report','id','experiment_file_attachment/report','labelCol','dl_experiment_group_files','');
INSERT INTO detail_report_hotlinks VALUES(10,'container','detail-report','container','material_items/report/~','labelCol','dl_Container','');
INSERT INTO detail_report_hotlinks VALUES(11,'+container','detail-report','container','material_move_items/report/~','valueCol','dl_container_move','');
INSERT INTO detail_report_hotlinks VALUES(12,'location','detail-report','location','material_items/report/-/','labelCol','dl_location','');
INSERT INTO detail_report_hotlinks VALUES(13,'+location','detail-report','location','material_move_items/report/-/','valueCol','dl_location_move','');
INSERT INTO detail_report_hotlinks VALUES(14,'organism','detail-report','organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO detail_report_hotlinks VALUES(15,'researcher','detail-report','researcher','user/report/-/~','labelCol','dl_researcher','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO detail_report_hotlinks VALUES(16,'plant_or_animal_tissue','detail-report','plant_or_animal_tissue','tissue/report/~','valueCol','dl_tissue','');
INSERT INTO detail_report_hotlinks VALUES(17,'labelling','detail-report','labelling','sample_label_reporter_ions/report/~','valueCol','dl_labelling_reporter_ions','{"HideLinkIfValueMatch":"none"}');
INSERT INTO detail_report_hotlinks VALUES(18,'+labelling','detail-report','id','experiment_plex_members/report/','labelCol','dl_labelling_plex_id','{"HideLinkIfValueMatch":"none"}');
INSERT INTO detail_report_hotlinks VALUES(19,'experiment','detail-report','id','experimentid/show','labelCol','dl_experiment_id','');
INSERT INTO detail_report_hotlinks VALUES(20,'id','detail-report','id','experimentid/show','labelCol','dl_experiment_id_from_name','');
INSERT INTO detail_report_hotlinks VALUES(21,'digestion_enzyme','detail-report','digestion_enzyme','enzymes/report/-/~','labelCol','dl_enzyme','');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO external_sources VALUES(1,'sample_prep_request','experiment','ColName','sample_group_naming_prefix');
INSERT INTO external_sources VALUES(2,'sample_prep_request','campaign','ColName','campaign');
INSERT INTO external_sources VALUES(3,'sample_prep_request','organism_name','ColName','organism');
INSERT INTO external_sources VALUES(4,'sample_prep_request','reason','ColName','reason');
INSERT INTO external_sources VALUES(6,'sample_prep_request','enzyme_name','Literal','Trypsin');
INSERT INTO external_sources VALUES(7,'sample_prep_request','comment','ColName','comment');
INSERT INTO external_sources VALUES(10,'sample_prep_request','sample_prep_request','ColName','id');
INSERT INTO external_sources VALUES(11,'sample_prep_request','tissue','ColName','plant_or_animal_tissue');
INSERT INTO external_sources VALUES(12,'rna_prep_request','experiment','ColName','sample_group_naming_prefix');
INSERT INTO external_sources VALUES(13,'rna_prep_request','campaign','ColName','campaign');
INSERT INTO external_sources VALUES(14,'rna_prep_request','organism_name','ColName','organism');
INSERT INTO external_sources VALUES(15,'rna_prep_request','reason','ColName','reason');
INSERT INTO external_sources VALUES(16,'rna_prep_request','biomaterial_list','Literal','');
INSERT INTO external_sources VALUES(17,'rna_prep_request','enzyme_name','Literal','Trypsin');
INSERT INTO external_sources VALUES(19,'rna_prep_request','sample_prep_request','ColName','id');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','name','varchar','input','128','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(2,'<local>','mode','varchar','input','32','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(3,'<local>','message','varchar','output','512','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(4,'<local>','callingUser','varchar','input','128','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(5,'id','experimentId','int','output','','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(6,'experiment','experimentName','varchar','input','50','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(7,'campaign','campaignName','varchar','input','64','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(8,'researcher_prn','researcherPRN','varchar','input','50','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(9,'organism_name','organismName','varchar','input','128','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(10,'reason','reason','varchar','input','500','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(11,'comment','comment','varchar','input','500','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(12,'sample_concentration','sampleConcentration','varchar','input','32','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(13,'enzyme_name','enzymeName','varchar','input','50','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(14,'lab_notebook_ref','labNotebookRef','varchar','input','128','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(15,'labelling','labelling','varchar','input','64','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(16,'biomaterial_list','biomaterialList','varchar','input','2048','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(17,'reference_compound_list','referenceCompoundList','varchar','input','2048','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(18,'sample_prep_request','samplePrepRequest','int','input','','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(19,'internal_standard','internalStandard','varchar','input','50','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(20,'postdigest_int_std','postdigestIntStd','varchar','input','50','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(21,'wellplate','wellplateName','varchar','input','64','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(22,'well','wellNum','varchar','input','8','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(23,'alkylation','alkylation','varchar','input','1','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(24,'<local>','mode','varchar','input','12','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(25,'<local>','message','varchar','output','512','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(26,'container','container','varchar','input','128','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(27,'barcode','barcode','varchar','input','64','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(28,'tissue','tissue','varchar','intput','128','AddUpdateExperiment');
INSERT INTO sproc_args VALUES(29,'<local>','callingUser','varchar','input','128','AddUpdateExperiment');
CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );
INSERT INTO primary_filter_choosers VALUES(1,'pf_organism','picker.replace','orgPickList','','',',');
COMMIT;
