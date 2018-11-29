﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Experiment_Plex_Members');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Experiment_Plex_Members_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Experiment_Plex_Members_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Exp_ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('detail_report_sproc','AddUpdateExperimentPlexMembers');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Experiment_Plex_Members_TSV_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Exp_ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateExperimentPlexMembers');
INSERT INTO "general_params" VALUES('post_submission_detail_id','Exp_ID');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Exp_ID','Exp_ID','text-if-new','10','80','','','','trim|required|numeric');
INSERT INTO "form_fields" VALUES(2,'Experiment','Experiment','non-edit','40','80','','','','trim');
INSERT INTO "form_fields" VALUES(3,'Channel1_ExpID','Channel 1 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(4,'Channel2_ExpID','Channel 2 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(5,'Channel3_ExpID','Channel 3 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(6,'Channel4_ExpID','Channel 4 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(7,'Channel5_ExpID','Channel 5 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(8,'Channel6_ExpID','Channel 6 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(9,'Channel7_ExpID','Channel 7 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(10,'Channel8_ExpID','Channel 8 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(11,'Channel9_ExpID','Channel 9 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(12,'Channel10_ExpID','Channel 10 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(13,'Channel11_ExpID','Channel 11 Exp_ID','text','70','120','','','','trim');
INSERT INTO "form_fields" VALUES(14,'Channel1_Type','Channel 1 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(15,'Channel2_Type','Channel 2 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(16,'Channel3_Type','Channel 3 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(17,'Channel4_Type','Channel 4 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(18,'Channel5_Type','Channel 5 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(19,'Channel6_Type','Channel 6 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(20,'Channel7_Type','Channel 7 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(21,'Channel8_Type','Channel 8 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(22,'Channel9_Type','Channel 9 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(23,'Channel10_Type','Channel 10 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(24,'Channel11_Type','Channel 11 Type','text','10','10','','','','trim|alpha_numeric');
INSERT INTO "form_fields" VALUES(25,'Channel1_Comment','Channel 1 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(26,'Channel2_Comment','Channel 2 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(27,'Channel3_Comment','Channel 3 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(28,'Channel4_Comment','Channel 4 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(29,'Channel5_Comment','Channel 5 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(30,'Channel6_Comment','Channel 6 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(31,'Channel7_Comment','Channel 7 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(32,'Channel8_Comment','Channel 8 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(33,'Channel9_Comment','Channel 9 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(34,'Channel10_Comment','Channel 10 Comment','text','60','512','','','','trim');
INSERT INTO "form_fields" VALUES(35,'Channel11_Comment','Channel 11 Comment','text','60','512','','','','trim');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Plex_Exp_ID','invoke_entity','value','experiment_plex_members_tsv/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Exp_ID','invoke_entity','value','experimentid/show','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Comment','min_col_width','value','50','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Plex Members','tabular_link_list','Plex Members','experimentid/show','valueCol','dl_plex_members','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Exp_ID','detail-report','Exp_ID','experimentid/show','labelCol','dl_exp_id','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'+Exp_ID','detail-report','Exp_ID','experiment_plex_members/show/','valueCol','dl_experiment_plex_members','');
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
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_plex_exp_id','Plex Exp ID','6!','','Plex_Exp_ID','Equals','text','22','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_plex_experiment','Plex Experiment','30!','','Plex Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_exp_id','Channel Exp ID','6!','','Exp_ID','Equals','text','22','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_channel_experiment','Channel Experiment','30!','','Channel Experiment','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','15!','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_organism','Organism','15!','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_tissue','Tissue','10','','Tissue','ContainsText','text','128','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Exp_ID','plexExperimentId','int','input','','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(2,'Plex_Members','plexMembers','varchar','input','4000','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(3,'Channel1_ExpID','expIdChannel1','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(4,'Channel2_ExpID','expIdChannel2','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(5,'Channel3_ExpID','expIdChannel3','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(6,'Channel4_ExpID','expIdChannel4','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(7,'Channel5_ExpID','expIdChannel5','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(8,'Channel6_ExpID','expIdChannel6','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(9,'Channel7_ExpID','expIdChannel7','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(10,'Channel8_ExpID','expIdChannel8','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(11,'Channel9_ExpID','expIdChannel9','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(12,'Channel10_ExpID','expIdChannel10','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(13,'Channel11_ExpID','expIdChannel11','varchar','input','130','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(14,'Channel1_Type','channelType1','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(15,'Channel2_Type','channelType2','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(16,'Channel3_Type','channelType3','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(17,'Channel4_Type','channelType4','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(18,'Channel5_Type','channelType5','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(19,'Channel6_Type','channelType6','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(20,'Channel7_Type','channelType7','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(21,'Channel8_Type','channelType8','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(22,'Channel9_Type','channelType9','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(23,'Channel10_Type','channelType10','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(24,'Channel11_Type','channelType11','varchar','input','64','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(25,'Channel1_Comment','comment1','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(26,'Channel2_Comment','comment2','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(27,'Channel3_Comment','comment3','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(28,'Channel4_Comment','comment4','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(29,'Channel5_Comment','comment5','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(30,'Channel6_Comment','comment6','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(31,'Channel7_Comment','comment7','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(32,'Channel8_Comment','comment8','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(33,'Channel9_Comment','comment9','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(34,'Channel10_Comment','comment10','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(35,'Channel11_Comment','comment11','varchar','input','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(38,'<local>','mode','varchar','input','12','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(39,'<local>','message','varchar','output','512','AddUpdateExperimentPlexMembers');
INSERT INTO "sproc_args" VALUES(40,'<local>','callingUser','varchar','input','128','AddUpdateExperimentPlexMembers');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Exp_ID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Channel1_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Channel2_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'Channel3_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'Channel4_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'Channel5_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'Channel6_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'Channel7_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'Channel8_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'Channel9_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'Channel10_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(12,'Channel11_ExpID','list-report.helper','','helper_experimentid/report','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'Channel1_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(14,'Channel2_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(15,'Channel3_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(16,'Channel4_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(17,'Channel5_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(18,'Channel6_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(19,'Channel7_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(20,'Channel8_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(21,'Channel9_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(22,'Channel10_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(23,'Channel11_Type','picker.replace','experimentPlexChannelTypePickList','','',',','');
COMMIT;
