﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Experiment_Plex_Members');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Experiment_Plex_Summary_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Experiment_Plex_Members_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Exp_ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('detail_report_sproc','AddUpdateExperimentPlexMembers');
INSERT INTO "general_params" VALUES('entry_page_data_table','v_experiment_plex_members_entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','exp_id');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateExperimentPlexMembers');
INSERT INTO "general_params" VALUES('post_submission_detail_id','exp_id');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','Plex_Exp_ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','Desc');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Plex_Exp_ID','invoke_entity','value','experiment_plex_members/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Channels','invoke_entity','Plex_Exp_ID','experiment_plex_members_tsv/report/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Request','invoke_entity','value','sample_prep_request/show','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_plex_exp_id','Plex Exp ID','6!','','Plex_Exp_ID','Equals','text','22','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_plex_experiment','Plex Experiment','30!','','Plex Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','15!','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_organism','Organism','15!','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_tissue','Tissue','10','','Tissue','ContainsText','text','128','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Plex Members','tabular_link_list','Plex Members','experimentid/show','valueCol','dl_plex_members','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Exp_ID','detail-report','Exp_ID','experimentid/show','valueCol','dl_exp_id','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'+Exp_ID','detail-report','Exp_ID','experiment_plex_members_tsv/show/','labelCol','dl_experiment_plex_members','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Request','detail-report','Request','sample_prep_request/show','labelCol','request','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Datasets','detail-report','Experiment','dataset/report/-/-/-/-/~','labelCol','datasets','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Factors','detail-report','Experiment','custom_factors/report/-/-/-/~','labelCol','dl_custom_factors','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'Container','detail-report','Container','material_items/report/~','labelCol','dl_Container','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'+Container','detail-report','Container','material_move_items/report/~','valueCol','dl_container_move','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'Location','detail-report','Location','material_items/report/-/','labelCol','dl_location','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'+Location','detail-report','Location','material_move_items/report/-/','valueCol','dl_location_move','');
INSERT INTO "detail_report_hotlinks" VALUES(12,'Organism','detail-report','Organism','organism/report/~','labelCol','dl_organism','');
INSERT INTO "detail_report_hotlinks" VALUES(13,'Researcher','detail-report','Researcher','user/report/-/~','labelCol','dl_researcher','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(14,'Plant/Animal Tissue','detail-report','Plant/Animal Tissue','tissue/report/~','valueCol','dl_tissue','');
INSERT INTO "detail_report_hotlinks" VALUES(15,'Labelling','detail-report','Labelling','sample_label_reporter_ions/report/~','valueCol','dl_labelling','');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'exp_id','Exp_ID','text-if-new','10','80','','','','trim|required|numeric');
INSERT INTO "form_fields" VALUES(2,'experiment','Experiment','non-edit|text-nocopy','40','80','','','','trim');
INSERT INTO "form_fields" VALUES(3,'plex_members','Plex Members','area','','','13','100','Tag, Exp_ID_or_Name, Channel Type, Comment<br>126, 00000, Sample, <br>127N, 00000, Sample, <br>127C, 00000, Sample, <br>128N, 00000, Sample, <br>128C, 00000, Sample, <br>129N, 00000, Sample, <br>129C, 00000, Sample, <br>130N, 00000, Sample, <br>130C, 00000, Sample, <br>131N, 00000, Sample, <br>131C, 00000, Reference, ','trim');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'exp_id','list-report.helper','','helper_experimentid/report','',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'plex_members','auto_format','none');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'exp_id','plexExperimentIdOrName','varchar','output','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(2,'plex_members','plexMembers','varchar','input','4000','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(3,'<local>','mode','varchar','input','12','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(4,'<local>','message','varchar','output','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(5,'<local>','callingUser','varchar','input','128','AddUpdateExperimentPlexMembers');
CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );
INSERT INTO "entry_commands" VALUES(1,'preview','cmd','Preview','Validate items in the Plex Member textbox and preview changes that would be made.','');
COMMIT;
