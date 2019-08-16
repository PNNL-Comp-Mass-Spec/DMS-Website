﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_RNA_Prep_Request_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_RNA_Prep_Request_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateRNAPrepRequest');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_RNA_Prep_Request_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('post_submission_detail_id','ID');
INSERT INTO "general_params" VALUES('entry_submission_cmds','sample_prep_cmds');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO "general_params" VALUES('base_table','T_Sample_Prep_Request');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','ID','rna_prep_request/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'WP','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'WP State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_requestname','RequestName','32','','RequestName','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_organism','Organism','32','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_WP','WP','32','','WP','ContainsText','text','50','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , "options" text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Experiments','detail-report','ID','sample_prep_request_experiments/report','labelCol','experiments','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Updates','detail-report','ID','sample_prep_request_updates/report','labelCol','updates','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Instrument Name','detail-report','Instrument Name','instrument/show/','valueCol','dl_instrumentName','');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Dataset Type','detail-report','Instrument Name','helper_instrument_dataset_type/report','valueCol','dl_DatasetType','');
INSERT INTO "detail_report_hotlinks" VALUES(8,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show','valueCol','dl_EUS_Proposal','');
INSERT INTO "detail_report_hotlinks" VALUES(9,'Work Package Number','detail-report','Work Package Number','charge_code/show','labelCol','dl_Work_Package','');
INSERT INTO "detail_report_hotlinks" VALUES(10,'Wiki Page Link','literal_link','Wiki Page Link','','valueCol','dl_wiki_page_link','');
INSERT INTO "detail_report_hotlinks" VALUES(11,'Material Containers Item Count','detail-report','ID','sample_prep_request_items/report/material_container','labelCol','dl_material_containers_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(12,'Experiment Group Item Count','detail-report','ID','sample_prep_request_items/report/experiment_group','labelCol','dl_experiment_group_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(13,'Experiment Item Count','detail-report','ID','sample_prep_request_items/report/experiment','labelCol','dl_experiment_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(14,'HPLC Runs Item Count','detail-report','ID','sample_prep_request_items/report/prep_lc_run','labelCol','dl_hplc_runs_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(15,'Biomaterial Item Count','detail-report','ID','sample_prep_request_items/report/biomaterial','labelCol','dl_biomaterial_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(16,'Total_Item_Count','detail-report','ID','sample_prep_request_items/report/-','labelCol','dl_total_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(17,'Requested Run Item Count','detail-report','ID','sample_prep_request_items/report/requested_run','labelCol','dl_requested_run_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(18,'Dataset Item Count','detail-report','ID','sample_prep_request_items/report/dataset','labelCol','dl_dataset_item_count','');
INSERT INTO "detail_report_hotlinks" VALUES(19,'Work Package State','color_label','#WPActivationState','','valueCol','dl_Work_Package_State','{"3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'RequestName','RequestName','varchar','input','128','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(2,'RequesterPRN','RequesterPRN','varchar','input','32','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(3,'Reason','Reason','varchar','input','512','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(5,'Organism','Organism','varchar','input','128','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(6,'BiohazardLevel','BiohazardLevel','varchar','input','12','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(7,'Campaign','Campaign','varchar','input','128','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(8,'NumberofSamples','NumberofSamples','int','input','','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(9,'SampleNameList','SampleNameList','varchar','input','1500','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(10,'SampleType','SampleType','varchar','input','128','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(11,'PrepMethod','PrepMethod','varchar','input','512','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(12,'SampleNamingConvention','SampleNamingConvention','varchar','input','128','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(13,'EstimatedCompletion','EstimatedCompletion','varchar','input','32','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(14,'WorkPackageNumber','WorkPackageNumber','varchar','input','64','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(16,'eusProposalID','eusProposalID','varchar','input','10','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(17,'eusUsageType','eusUsageType','varchar','input','50','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(18,'eusUserID','eusUserID','int','input','','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(19,'InstrumentName','InstrumentName','varchar','input','128','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(20,'DatasetType','DatasetType','varchar','input','50','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(21,'InstrumentAnalysisSpecifications','InstrumentAnalysisSpecifications','varchar','input','512','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(22,'State','State','varchar','input','32','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(23,'ID','ID','int','output','','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(25,'<local>','mode','varchar','input','12','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(26,'<local>','message','varchar','output','512','AddUpdateRNAPrepRequest');
INSERT INTO "sproc_args" VALUES(27,'<local>','callingUser','varchar','input','128','AddUpdateRNAPrepRequest');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'RequestName','Request Name','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(3,'RequesterPRN','Requester PRN','text','32','32','','','','trim|max_length[32]|required');
INSERT INTO "form_fields" VALUES(4,'Campaign','Campaign','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(5,'Reason','Reason For Experiment','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(7,'Organism','Organism','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(9,'BiohazardLevel','Biohazard Level','text','12','12','','','BSL1','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(10,'NumberofSamples','Number of Samples','text','4','4','','','1','trim|max_length[4]');
INSERT INTO "form_fields" VALUES(11,'SampleNameList','Sample Name List','area','','','4','60','','trim|max_length[1500]');
INSERT INTO "form_fields" VALUES(12,'SampleType','Sample Type','text','60','128','','','Cell pellet','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(13,'PrepMethod','Prep Method','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(14,'InstrumentName','Instrument Name','text','24','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(15,'DatasetType','Dataset Type','text','24','50','','','','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(16,'InstrumentAnalysisSpecifications','Instrument Analysis Specifications','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(17,'SampleNamingConvention','Sample Group Naming Prefix','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(18,'WorkPackageNumber','Work Package Number','text','15','64','','','','trim|max_length[64]|required');
INSERT INTO "form_fields" VALUES(20,'eusUsageType','EMSL Usage Type','text','50','50','','','','trim|required|max_length[50]|not_contain[(unknown)]');
INSERT INTO "form_fields" VALUES(21,'eusProposalID','EMSL Proposal ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(22,'eusUserID','EMSL User ID','text','10','10','','','','trim|max_length[10]|numeric');
INSERT INTO "form_fields" VALUES(23,'EstimatedCompletion','EstimatedCompletion','text','32','32','','','','trim|max_length[32]|valid_date');
INSERT INTO "form_fields" VALUES(24,'State','State','text','32','32','','','Pending Approval','trim|max_length[32]|required');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'RequesterPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'BiohazardLevel','picker.replace','samplePrepReqBiohazardPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'Campaign','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'InstrumentName','picker.replace','instrumentNameRNAPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'DatasetType','list-report.helper','','data/lr/ad_hoc_query/helper_inst_name_dstype/report','InstrumentName',',','');
INSERT INTO "form_field_choosers" VALUES(8,'SampleType','picker.replace','samplePrepReqTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(9,'PrepMethod','picker.append','rnaPrepReqMethodPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(10,'eusUsageType','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'eusUserID','list-report.helper','','helper_eus_user/report','eusProposalID',',','Select User...');
INSERT INTO "form_field_choosers" VALUES(12,'State','picker.replace','sampleRequestStatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(13,'eusProposalID','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(14,'eusProposalID','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO "form_field_choosers" VALUES(15,'WorkPackageNumber','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO "form_field_choosers" VALUES(16,'EstimatedCompletion','picker.prevDate','futureDatePickList','','',',','');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'ID','section','Basic Information');
INSERT INTO "form_field_options" VALUES(2,'RequesterPRN','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(3,'Organism','section','Biomaterial Information');
INSERT INTO "form_field_options" VALUES(4,'InstrumentName','section','Instrument Run Information');
INSERT INTO "form_field_options" VALUES(5,'NumberofSamples','section','Preparation Information');
INSERT INTO "form_field_options" VALUES(6,'SampleNamingConvention','section','Project Tracking Information');
INSERT INTO "form_field_options" VALUES(7,'EstimatedCompletion','section','Assignment and Scheduling');
INSERT INTO "form_field_options" VALUES(8,'State','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration,DMS_Sample_Preparation');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Convert Request to Experiment','copy_from','','experiment','Go to experiment entry page and copy information from this sample prep request.','');
COMMIT;
