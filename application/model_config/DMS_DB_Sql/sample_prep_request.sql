PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Sample_Prep_Request_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Sample_Prep_Request_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateSamplePrepRequest');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Sample_Prep_Request_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('post_submission_detail_id','ID');
INSERT INTO "general_params" VALUES('entry_submission_cmds','sample_prep_cmds');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO "general_params" VALUES('base_table','T_Sample_Prep_Request');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'RequestName','Request Name','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(3,'RequesterPRN','Requester PRN','text','32','32','','','','trim|max_length[32]|required');
INSERT INTO "form_fields" VALUES(4,'Campaign','Campaign','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(5,'Reason','Reason For Experiment','area-nocopy','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(7,'MaterialContainerList','Material Container List','area-nocopy','','','2','70','','trim|max_length[2048]');
INSERT INTO "form_fields" VALUES(8,'Organism','Organism','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(10,'Tissue','Plant/Animal Tissue','text','60','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(11,'BiohazardLevel','Biohazard Level','text','12','12','','','BSL1','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(12,'NumberofSamples','Number of Samples','text-nocopy','4','4','','','1','trim|max_length[4]');
INSERT INTO "form_fields" VALUES(13,'BlockAndRandomizeSamples','Block And Randomize Samples','text','3','3','','','','trim|required|max_length[3]');
INSERT INTO "form_fields" VALUES(14,'SampleNameList','Source Sample Names','area','','','4','60','','trim|max_length[1500]');
INSERT INTO "form_fields" VALUES(15,'SampleType','Sample Type','text','60','128','','','Cell pellet','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(16,'PrepMethod','Prep Method','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(20,'Comment','Comment','area-nocopy','','','8','80','','trim|max_length[2048]');
INSERT INTO "form_fields" VALUES(21,'EstimatedMSRuns','MS Runs To Be Generated','text','16','16','','','','trim|required|max_length[16]');
INSERT INTO "form_fields" VALUES(23,'InstrumentGroup','Instrument Group','text','24','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(24,'DatasetType','Dataset Type','text','24','50','','','','trim|max_length[50]');
INSERT INTO "form_fields" VALUES(25,'SeparationGroup','Separation Group','text','24','128','','','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(26,'InstrumentAnalysisSpecifications','Instrument Analysis Specifications','area','','','3','60','','trim|max_length[512]');
INSERT INTO "form_fields" VALUES(28,'BlockAndRandomizeRuns','Block And Randomize Runs','text','3','3','','','','trim|required|max_length[3]');
INSERT INTO "form_fields" VALUES(29,'SampleNamingConvention','Sample Group Naming Prefix','text','60','128','','','','trim|max_length[128]|required');
INSERT INTO "form_fields" VALUES(30,'WorkPackageNumber','Work Package Number','text','15','64','','','','trim|max_length[64]|required');
INSERT INTO "form_fields" VALUES(32,'eusUsageType','EMSL Usage Type','text','50','50','','','','trim|required|max_length[50]|not_contain[(unknown)]');
INSERT INTO "form_fields" VALUES(33,'eusProposalID','EMSL Proposal ID','text','10','10','','','','trim|max_length[10]');
INSERT INTO "form_fields" VALUES(34,'eusUserID','EMSL User ID','text','10','10','','','','trim|max_length[10]|numeric');
INSERT INTO "form_fields" VALUES(35,'RequestedPersonnel','Requested Personnel','area','','','2','60','','trim|required|max_length[256]');
INSERT INTO "form_fields" VALUES(36,'AssignedPersonnel','Assigned Personnel','area','','','2','60','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(37,'EstimatedCompletion','EstimatedCompletion','text','32','32','','','','trim|max_length[32]|valid_date');
INSERT INTO "form_fields" VALUES(39,'Priority','Priority','text','12','12','','','Normal','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(40,'ReasonForHighPriority','Reason For High Priority','area-nocopy','','','4','70','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(42,'State','State','text','32','32','','','New','trim|max_length[32]|required');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'ID','section','Basic Information');
INSERT INTO "form_field_options" VALUES(2,'RequesterPRN','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(3,'MaterialContainerList','section','Biomaterial Information');
INSERT INTO "form_field_options" VALUES(4,'EstimatedMSRuns','section','Instrument Run Information');
INSERT INTO "form_field_options" VALUES(5,'NumberofSamples','section','Preparation Information');
INSERT INTO "form_field_options" VALUES(7,'SampleNamingConvention','section','Project Tracking Information');
INSERT INTO "form_field_options" VALUES(9,'RequestedPersonnel','section','Assignment and Scheduling');
INSERT INTO "form_field_options" VALUES(10,'State','permission','DMS_Infrastructure_Administration,DMS_Ops_Administration,DMS_Sample_Preparation');
INSERT INTO "form_field_options" VALUES(11,'Comment','auto_format','none');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'RequesterPRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Organism','list-report.helper','','helper_organism/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'Tissue','list-report.helper','','helper_tissue/report','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'BiohazardLevel','picker.replace','samplePrepReqBiohazardPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'Campaign','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'InstrumentGroup','picker.replace','samplePrepInstrumentGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(8,'DatasetType','list-report.helper','','data/lr/ad_hoc_query/helper_inst_group_dstype/report','InstrumentGroup',',','');
INSERT INTO "form_field_choosers" VALUES(10,'SampleType','picker.replace','samplePrepReqTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(11,'PrepMethod','picker.append','samplePrepReqMethodPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(15,'eusUsageType','picker.replace','eusUsageTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(16,'eusUserID','list-report.helper','','helper_eus_user/report','eusProposalID',',','Select User...');
INSERT INTO "form_field_choosers" VALUES(17,'RequestedPersonnel','picker.append','samplePrepUserPickList','','','; ','');
INSERT INTO "form_field_choosers" VALUES(18,'AssignedPersonnel','picker.append','samplePrepUserPickList','','','; ','');
INSERT INTO "form_field_choosers" VALUES(19,'EstimatedCompletion','picker.prevDate','futureDatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(20,'State','picker.replace','sampleRequestStatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(21,'eusProposalID','list-report.helper','','helper_eus_proposal/report','',',','Select Proposal...');
INSERT INTO "form_field_choosers" VALUES(22,'eusProposalID','list-report.helper','','helper_eus_proposal_ex/report','',',','Select Proposal (by dataset)...');
INSERT INTO "form_field_choosers" VALUES(23,'SeparationGroup','picker.replace','samplePrepSeparationGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(25,'BlockAndRandomizeRuns','picker.replace','yesNoPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(26,'BlockAndRandomizeSamples','picker.replace','yesNoNAPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(27,'Priority','picker.replace','operationsTaskPriority','','',',','');
INSERT INTO "form_field_choosers" VALUES(28,'WorkPackageNumber','list-report.helper','','helper_charge_code/report','',',','');
INSERT INTO "form_field_choosers" VALUES(29,'MaterialContainerList','list-report.helper','','helper_material_container_ckbx/report','',',','Select Container...');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_state','State','32','','State','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_requestname','RequestName','32','','RequestName','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_requester','Requester','32','','Requester','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_organism','Organism','32','','Organism','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_campaign','Campaign','20','','Campaign','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_WP','WP','32','','WP','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_Container','Container','32','','Containers','ContainsText','text','50','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','ID','sample_prep_request/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Days In Queue','color_label','#DaysInQueue','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(3,'WP','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(4,'WP State','color_label','#WPActivationState','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120","10":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Reason','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(6,'Comment','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(7,'Inst. Analysis','min_col_width','value','60','');
INSERT INTO "list_report_hotlinks" VALUES(8,'EUS Proposal','invoke_entity','value','eus_proposals/show','');
INSERT INTO "list_report_hotlinks" VALUES(9,'Tissue','invoke_entity','value','tissue/report/~','');
INSERT INTO "list_report_hotlinks" VALUES(10,'Organism','invoke_entity','value','organism/report/~','');
INSERT INTO "list_report_hotlinks" VALUES(11,'Campaign','invoke_entity','value','campaign/show','');
INSERT INTO "list_report_hotlinks" VALUES(12,'Containers','min_col_width','value','35','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO "detail_report_commands" VALUES(1,'Convert Request to Experiment','copy_from','','experiment','Go to experiment entry page and copy information from this sample prep request.','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Experiments','detail-report','ID','sample_prep_request_experiments/report','labelCol','experiments',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Updates','detail-report','ID','sample_prep_request_updates/report','labelCol','updates',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'Instrument Group','detail-report','Instrument Group','instrument_group/show','valueCol','dl_instrumentGroup',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(7,'Dataset Type','detail-report','Instrument Group','instrument_allowed_dataset_type/report','valueCol','dl_DatasetType',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(8,'EUS Proposal','detail-report','EUS Proposal','eus_proposals/show','valueCol','dl_EUS_Proposal',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(9,'Work Package Number','detail-report','Work Package Number','charge_code/show','labelCol','dl_Work_Package',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(10,'Wiki Page Link','literal_link','Wiki Page Link','','valueCol','dl_wiki_page_link',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(12,'Material Containers Item Count','detail-report','ID','sample_prep_request_items/report/material_container','labelCol','dl_material_containers_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(14,'Experiment Group Item Count','detail-report','ID','sample_prep_request_items/report/experiment_group','labelCol','dl_experiment_group_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(15,'Experiment Item Count','detail-report','ID','sample_prep_request_items/report/experiment','labelCol','dl_experiment_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(16,'HPLC Runs Item Count','detail-report','ID','sample_prep_request_items/report/prep_lc_run','labelCol','dl_hplc_runs_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(17,'Biomaterial Item Count','detail-report','ID','sample_prep_request_items/report/biomaterial','labelCol','dl_biomaterial_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(18,'Total_Item_Count','detail-report','ID','sample_prep_request_items/report/-','labelCol','dl_total_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(19,'Requested Run Item Count','detail-report','ID','sample_prep_request_items/report/requested_run','labelCol','dl_requested_run_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(20,'Dataset Item Count','detail-report','ID','sample_prep_request_items/report/dataset','labelCol','dl_dataset_item_count',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(21,'Work Package State','color_label','#WPActivationState','','valueCol','dl_Work_Package_State','{"3":"clr_90","4":"clr_120",
"5":"clr_120","10":"clr_120"}');
INSERT INTO "detail_report_hotlinks" VALUES(22,'Material Containers','link_list','Material Containers','material_container/show','valueCol','dl_material_containers','');
INSERT INTO "detail_report_hotlinks" VALUES(23,'Requester','detail-report','Requester','user/report/-/~','labelCol','dl_researcher','{"RemoveRegEx":" [(].*[)]"}');
INSERT INTO "detail_report_hotlinks" VALUES(24,'Comment','markup','Comment','','valueCol','dl_Comment','');
INSERT INTO "detail_report_hotlinks" VALUES(25,'Plant/Animal Tissue','detail-report','Plant/Animal Tissue','tissue/report/~','valueCol','dl_tissue','');
INSERT INTO "detail_report_hotlinks" VALUES(26,'Organism','detail-report','Organism','organism/report/~','valueCol','dl_organism','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'RequestName','RequestName','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(2,'RequesterPRN','RequesterPRN','varchar','input','32','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(3,'Reason','Reason','varchar','input','512','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(5,'MaterialContainerList','MaterialContainerList','varchar','input','2048','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(6,'Organism','Organism','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(7,'BiohazardLevel','BiohazardLevel','varchar','input','12','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(8,'Campaign','Campaign','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(9,'NumberofSamples','NumberofSamples','int','input','','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(10,'SampleNameList','SampleNameList','varchar','input','1500','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(11,'SampleType','SampleType','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(12,'PrepMethod','PrepMethod','varchar','input','512','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(15,'SampleNamingConvention','SampleNamingConvention','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(16,'AssignedPersonnel','AssignedPersonnel','varchar','input','256','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(17,'RequestedPersonnel','RequestedPersonnel','varchar','input','256','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(18,'EstimatedCompletion','EstimatedCompletion','varchar','input','32','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(19,'EstimatedMSRuns','EstimatedMSRuns','varchar','input','16','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(20,'WorkPackageNumber','WorkPackageNumber','varchar','input','64','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(22,'eusProposalID','eusProposalID','varchar','input','10','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(23,'eusUsageType','eusUsageType','varchar','input','50','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(24,'eusUserID','eusUserID','int','input','','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(27,'InstrumentGroup','InstrumentGroup','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(28,'DatasetType','DatasetType','varchar','input','50','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(29,'InstrumentAnalysisSpecifications','InstrumentAnalysisSpecifications','varchar','input','512','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(31,'Comment','Comment','varchar','input','2048','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(32,'Priority','Priority','varchar','input','12','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(33,'State','State','varchar','input','32','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(35,'ID','ID','int','output','','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(36,'SeparationGroup','SeparationGroup','varchar','input','256','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(37,'BlockAndRandomizeSamples','BlockAndRandomizeSamples','char','input','3','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(38,'BlockAndRandomizeRuns','BlockAndRandomizeRuns','char','input','3','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(40,'ReasonForHighPriority','ReasonForHighPriority','varchar','input','1024','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(42,'Tissue','Tissue','varchar','input','128','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(43,'<local>','mode','varchar','input','12','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(44,'<local>','message','varchar','output','512','AddUpdateSamplePrepRequest');
INSERT INTO "sproc_args" VALUES(45,'<local>','callingUser','varchar','input','128','AddUpdateSamplePrepRequest');
COMMIT;
