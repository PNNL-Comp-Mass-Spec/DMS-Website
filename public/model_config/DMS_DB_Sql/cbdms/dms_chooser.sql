﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE 'chooser_definitions' (

    "id" INTEGER PRIMARY KEY,

  name   text,

  db     text,

  type   text,

  value  text

);
INSERT INTO "chooser_definitions" VALUES(1,'YNPickList','default','select','{"N":"N", "Y":"Y"}');
INSERT INTO "chooser_definitions" VALUES(2,'activeInactivePickList','default','select','{"Active":"Active", "Inactive":"Inactive"}');
INSERT INTO "chooser_definitions" VALUES(3,'activeInactiveInvalidPickList','default','select','{"Active":"Active", "Inactive":"Inactive", "Invalid":"Invalid"}');
INSERT INTO "chooser_definitions" VALUES(4,'analysisJobPriPickList','default','select','{"1":"1", "2":"2", "3":"3", "4":"4", "5":"5"}');
INSERT INTO "chooser_definitions" VALUES(5,'analysisPredefJobPriPickList','default','select','{"1":"1", "2":"2", "3":"3", "4":"4", "5":"5", "6":"6", "7":"7", "8":"8", "9":"9"}');
INSERT INTO "chooser_definitions" VALUES(6,'containerTypePickList','default','select','{"Bag":"Bag", "Box":"Box", "Wellplate":"Wellplate"}');
INSERT INTO "chooser_definitions" VALUES(7,'datasetRatingPickList','default','select','{"Unreviewed":"Unreviewed", "Released":"Released", "Not Released":"Not Released", "Rerun (Good Data)":"Rerun (Good Data)", "Rerun (Superseded)":"Rerun (Superseded)"  }');
INSERT INTO "chooser_definitions" VALUES(8,'futureDatePickList','default','select','{"0":"Today", "-1":"1 day from now", "-2":"2 days from now", "-3":"3 days from now", "-5":"5 days from now", "-7":"7 days from now", "-14":"14 days from now", "-21":"21 days from now", "-30":"30 days from now", "-60":"60 days from now", "-90":"90 days from now", "-90":"120 days from now", "-180":"180 days from now"}');
INSERT INTO "chooser_definitions" VALUES(9,'instUsageJournalEMSLTypePickList','default','select','{"(R Remote":"(R Remote", "(O On site":"(O On site", "(B Broken":"(B Broken", "(A Available":"(A Available", "(C Capability Dev.":"(C Capability Dev.", "(U Unavailable":"(U Unavailable"}');
INSERT INTO "chooser_definitions" VALUES(10,'jobPropagationModePickList','default','select','{"Export":"Export", "No Export":"No Export"}');
INSERT INTO "chooser_definitions" VALUES(11,'longIntervalReasonPickList','default','select','{ "Staffing limitations, priorities directed elsewhere":"Staffing limitations, priorities directed elsewhere", "On hold pending scheduling":"On hold pending scheduling",  "End batch(es), prepare for next":"End batch(es), prepare for next",  "LC maintenance":"LC maintenance",  "MS maintenance":"MS maintenance",  "LC broken":"LC broken",  "MS broken":"MS broken",  "Troubleshooting LC issue":"Troubleshooting LC issue",  "Troubleshooting MS issue":"Troubleshooting MS issue",  "Capability development: Input required, description of work and user":"Capability development: Input required, description of work and user",  "Facilities: power failure or outage, instrument move, floor services":"Facilities: power failure or outage, instrument move, floor services" }');
INSERT INTO "chooser_definitions" VALUES(12,'lcCartComponentStatusPickList','default','select','{"Active":"Active", "Out of service":"Out of service", "Discarded":"Discarded"}');
INSERT INTO "chooser_definitions" VALUES(13,'logEntryPostedByPickList','default','select','{"Archive":"Archive", "ArchiveVerify":"ArchiveVerify", "Capture":"Capture", "Extraction":"Extraction", "Preparation":"Preparation", "RequestCaptureTask":"RequestCaptureTask", "Space":"Space"}');
INSERT INTO "chooser_definitions" VALUES(14,'logEntryTypePickList','default','select','{"Normal":"Normal", "Warning":"Warning", "Error":"Error"}');
INSERT INTO "chooser_definitions" VALUES(15,'postdigestIntStdPickList','default','select','{"none":"none", "PepChromeA":"PepChromeA"}');
INSERT INTO "chooser_definitions" VALUES(16,'predefCritNamePickList','default','select','{"Campaign Name":"Campaign Name", "Organism Name":"Organism Name", "Instrument Name":"Instrument Name", "Experiment Name":"Experiment Name", "ExpCommentContains":"ExpCommentContains", "Mouse Special Labels 1":"Mouse Special Labels 1", "Yeast Special Labels 1":"Yeast Special Labels 1", "Blood Serum Special Labels 1":"Blood Serum Special Labels 1", "Shewanella Special Labels 1":"Shewanella Special Labels 1", "deinococcus Special Labels 1":"deinococcus Special Labels 1", "deinococcus Special Labels 2":"deinococcus Special Labels 2", "Instrument Class":"Instrument Class"}');
INSERT INTO "chooser_definitions" VALUES(17,'predefCritValuePickList','default','select','{"MM without PEO or ICAT":"MM without PEO or ICAT", "MM with PEO":"MM with PEO", "MM with ICAT":"MM with ICAT", "Yeast without PEO or ICAT":"Yeast without PEO or ICAT", "Yeast with PEO":"Yeast with PEO", "Yeast with ICAT":"Yeast with ICAT", "Blood serum without PEO or ICAT":"Blood serum without PEO or ICAT", "Blood serum with PEO":"Blood serum with PEO", "Blood serum with ICAT":"Blood serum with ICAT", "Shewanella with N15":"Shewanella with N15", "deinococcus with N15":"deinococcus with N15", "deinococcus with PEO":"deinococcus with PEO"}');
INSERT INTO "chooser_definitions" VALUES(18,'predefEnablePickList','default','select','{"0":"0", "1":"1"}');
INSERT INTO "chooser_definitions" VALUES(19,'predefinedAnalysisPreviewReportType','default','select','{"Show Jobs":"Show jobs that would be created for dataset", "Show Rules":"Show rules that would be triggered by datset"}');
INSERT INTO "chooser_definitions" VALUES(20,'predigestIntStdPickList','default','select','{"none":"none", "mini-proteome":"mini-proteome"}');
INSERT INTO "chooser_definitions" VALUES(21,'prepLCRunGuardColPickList','default','select','{"Yes":"Yes (guard column was changed)", "No":"No (guard column was not changed)", "n/a":"N/A (guard column was not used)"}');
INSERT INTO "chooser_definitions" VALUES(22,'prepLCRunTypePickList','default','select','{"Fractionation":"Fractionation Run","Depletion":"Depletion Run", "Other":"Something else"}');
INSERT INTO "chooser_definitions" VALUES(23,'prevDatePickList','default','select','{"0":"Today", "1":"1 day ago", "2":"2 days ago", "3":"3 days ago", "5":"5 days ago", "7":"1 week ago", "14":"2 weeks ago", "21":"3 weeks ago", "30":"1 month ago", "60":"2 months ago", "90":"3 months ago", "180":"6 months ago", "365":"1 year ago", "730":"2 years ago", "-1":"Beginning of time"}');
INSERT INTO "chooser_definitions" VALUES(24,'rawDataTypePickList','default','sql','SELECT Name AS val, Name AS ex FROM V_Instrument_Data_Type_Name_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(25,'samplePrepReqBiohazardPickList','default','select','{"BSL1":"BSL1", "BSL2":"BSL2"}');
INSERT INTO "chooser_definitions" VALUES(26,'samplePrepReqMethodPickList','default','select','{"Bond Elut PPL (ICR) ":"Bond Elut PPL (ICR)", "Cysteinyl-peptide enrichment":"Cysteinyl-peptide enrichment", "Dilute for analysis":"Dilute for analysis", "FASP":"FASP", "GC-MS chemical derivatization":"GC-MS chemical derivatization", "Gel Electrophoresis":"Gel Electrophoresis", "Global Digest":"Global Digest", "High pH fractionation":"High pH fractionation", "HPLC Depletion":"HPLC Depletion", "iTRAQ labeling":"iTRAQ labeling", "MALDI":"MALDI", "Modified Folch (ICR)":"Modified Folch (ICR)", "MPLEx":"MPLEx", "N-glycopeptide enrichment":"N-glycopeptide enrichment", "Online 2D SCX fractionation":"Online 2D SCX fractionation", "Phosphopeptide enrichment":"Phosphopeptide enrichment", "Sequential Extraction (ICR)":"Sequential Extraction (ICR)", "Solvent Extraction":"Solvent Extraction", "SPE Cleanup":"SPE Cleanup", "TFE Digest":"TFE Digest", "TMT Labeling":"TMT Labeling", "Water/NaOH (ICR)":"Water/NaOH (ICR)", "Other":"Other"}');
INSERT INTO "chooser_definitions" VALUES(27,'samplePrepReqTypePickList','default','select','{"Blood":"Blood", "Bodily Fluid":"Bodily Fluid", "Cell pellet":"Cell pellet", "Peptides":"Peptides", "Plasma":"Plasma", "Protein solution":"Protein solution", "Serum":"Serum", "Soil":"Soil", "Tissue":"Tissue", "Other":"Other"}');
INSERT INTO "chooser_definitions" VALUES(28,'storageFunctionPickList','default','select','{"inbox":"inbox", "old-storage":"old-storage", "raw-storage":"raw-storage"}');
INSERT INTO "chooser_definitions" VALUES(29,'yesNoAsOneZeroPickList','default','select','{"1":"Yes", "0":"No"}');
INSERT INTO "chooser_definitions" VALUES(30,'yesNoPickList','default','select','{"Yes":"Yes", "No":"No"}');
INSERT INTO "chooser_definitions" VALUES(31,'EUSProposalStatePickList','default','sql','SELECT ID_with_Name as val, ID AS ex FROM V_EUS_Project_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(32,'EUSUserSiteStatusPickList','default','sql','SELECT ID_with_Name as val, ID AS ex FROM V_EUS_Site_Status_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(33,'LCColumnPickList','default','sql','SELECT val, ex FROM V_LC_Column_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(34,'LCColumnStatePickList','default','sql','SELECT val, ex FROM V_LC_Column_State_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(35,'ReqRunInstrumentPicklist','default','sql','SELECT val, ex FROM V_Req_Run_Instrument_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(36,'ReqRunInstrumentPicklistEx','default','sql','SELECT val, ex FROM V_Req_Run_Instrument_Picklist_Ex ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(37,'analysisJobStatePickList','default','sql','SELECT Name as val, '''' as ex FROM V_Analysis_Job_State_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(38,'analysisRequestPickList','default','sql','SELECT Name AS val, '''' AS ex FROM V_Analysis_Job_Request_State_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(39,'analysisToolPickList','default','sql','SELECT Name as val, '''' as ex FROM V_Analysis_Tool_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(40,'archiveFunctionPickList','default','sql','SELECT Name AS val, '''' AS ex FROM V_Archive_Path_Function_Picklist Order By Name');
INSERT INTO "chooser_definitions" VALUES(41,'archiveStateName','default','sql','SELECT Name AS val, Name AS ex FROM V_Dataset_Archive_State_Name_Picklist');
INSERT INTO "chooser_definitions" VALUES(42,'archiveUpdateName','default','sql','SELECT Name AS val, Name AS ex FROM V_Archive_Update_State_Name_Picklist');
INSERT INTO "chooser_definitions" VALUES(43,'assignedProcessorPickList','default','sql','SELECT val, ex FROM V_Analysis_Processor_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(44,'associatedProcessorGroupPickList','default','sql','SELECT Name_With_ID AS val, Group_Name as ex FROM V_Analysis_Job_Processor_Group_Picklist');
INSERT INTO "chooser_definitions" VALUES(45,'batchPriorityPickList','default','sql','SELECT ''Normal'' as val, '''' as ex union Select ''High'' as val, '''' as ex ');
INSERT INTO "chooser_definitions" VALUES(46,'captureMethodPickList','default','sql','SELECT val, '''' as ex FROM V_Capture_Method_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(47,'biomaterialTypePickList','default','sql','SELECT Name as val, '''' as ex FROM V_Biomaterial_Type_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(48,'datasetStatePickList','default','sql','SELECT Name as val, '''' as ex FROM V_Dataset_State_Name_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(49,'datasetTypePickList','default','sql','SELECT Name_with_Description AS val, Name AS ex FROM V_Dataset_Type_Name_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(51,'enzymePickList','default','sql','SELECT Name as val, '''' as ex FROM V_Enzyme_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(52,'eusUsageTypePickList','default','sql','SELECT Description AS val, Name AS ex FROM V_EUS_Usage_Type_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(54,'filterSetPickList','default','sql','SELECT distinct Cast(filter_set_ID as varchar(11)) + '' - '' + Filter_Set_Name as val, filter_set_ID as ex FROM V_Filter_Sets ORDER by filter_set_ID');
INSERT INTO "chooser_definitions" VALUES(55,'instrumentClassPickList','default','sql','SELECT Name as val, '''' as ex FROM V_Instrument_Class_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(57,'instrumentNameAdminPickList','default','sql','SELECT val, ex FROM V_Instrument_Admin_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(58,'instrumentNamePickList','default','sql','SELECT val, ex FROM V_Instrument_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(60,'instrumentOpsRolePickList','default','sql','SELECT val, '''' as ex FROM V_Instrument_OpsRole_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(61,'instrumentStatusPickList','default','sql','SELECT val, '''' as ex FROM V_Instrument_Status_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(62,'instrumentGroupPickList','default','sql','SELECT Instrument_Group As val, '''' As ex FROM V_Instrument_Group_PickList ORDER BY Instrument_Group');
INSERT INTO "chooser_definitions" VALUES(63,'samplePrepInstrumentGroupPickList','default','sql','SELECT Instrument_Group_and_Instruments as val, Instrument_Group As ex FROM V_Instrument_Group_PickList WHERE Sample_Prep_Visible > 0 ORDER BY Instrument_Group');
INSERT INTO "chooser_definitions" VALUES(64,'requestedRunInstrumentGroupPickList','default','sql','SELECT Instrument_Group_and_Instruments as val, Instrument_Group As ex FROM V_Instrument_Group_PickList WHERE Requested_Run_Visible > 0 ORDER BY Instrument_Group');
INSERT INTO "chooser_definitions" VALUES(65,'internalStandardsPicklist','default','sql','SELECT val, ex FROM V_Internal_Standards_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(66,'labellingPickList','default','sql','SELECT Label as val, '''' As ex FROM V_Sample_Labelling_Picklist ORDER BY Label');
INSERT INTO "chooser_definitions" VALUES(68,'lcCartPickList','default','sql','SELECT Name AS val, Name AS ex FROM V_LC_Cart_Picklist ORDER BY Name ');
INSERT INTO "chooser_definitions" VALUES(69,'lcCartConfigPickList','default','sql','SELECT Name AS val, Name AS ex FROM V_LC_Cart_Configuration_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(70,'lcCartStatePickList','default','sql','SELECT Name AS val, '''' AS ex FROM V_LC_Cart_State_Name_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(71,'orgDefPickList','default','sql','SELECT Name as val, '''' as ex FROM V_Organism_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(72,'orgPickList','default','sql','SELECT Name as val, '''' as ex FROM V_Organism_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(73,'prepInstrumentPickList','default','sql','SELECT Name AS val, Name AS ex FROM V_Prep_Instrument_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(74,'prepLCRunTabPickList','default','sql','SELECT val, '''' as ex FROM V_Helper_Prep_Lc_Run_Tab_List_Report ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(75,'protOptSeqDirPickList','default','sql','SELECT val, ex FROM V_Protein_Options_Seq_Direction');
INSERT INTO "chooser_definitions" VALUES(77,'samplePrepUserPickList','default','sql','SELECT val, ex FROM V_Sample_Prep_Request_User_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(78,'sampleRequestStatePickList','default','sql','SELECT val, ex FROM V_Sample_Prep_Request_State_Picklist ORDER BY State_ID');
INSERT INTO "chooser_definitions" VALUES(80,'samplePrepEusUsageTypePickList','default','sql','SELECT Description AS val, Name AS ex FROM V_EUS_Usage_Type_Picklist WHERE Enabled_Prep_Request > 0 ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(81,'secsepPickList','default','sql','SELECT Name as val, '''' as ex FROM V_Secondary_Sep_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(83,'experimentUserPRNPickList','default','sql','SELECT Name AS val, Username AS ex FROM V_Experiment_User_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(84,'instrumentUserPRNPickList','default','sql','SELECT Name AS val, Username AS ex FROM V_Active_Instrument_Users ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(85,'userNamePickList','default','sql','SELECT Name_with_Username AS val, '''' AS ex FROM V_Active_Users ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(86,'userOperationsPickList','default','sql','SELECT Name As val, '''' as ex FROM V_User_Operation_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(87,'userPRNPickList','default','sql','SELECT Name AS val, Username AS ex FROM V_Active_Users ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(88,'wellplatePickList','default','sql','SELECT val, ex FROM V_Wellplate_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(89,'dataPackageStatePickList','package','sql','SELECT Name AS val, Name AS ex FROM V_Data_Package_State_Picklist');
INSERT INTO "chooser_definitions" VALUES(90,'dataPackageTeamPickList','package','sql','SELECT Team_Name AS val, Team_Name AS ex FROM V_Data_Package_Teams_Picklist');
INSERT INTO "chooser_definitions" VALUES(91,'dataPackageTypePickList','package','sql','SELECT Name AS val, Name AS ex FROM V_Data_Package_Type_Picklist');
INSERT INTO "chooser_definitions" VALUES(92,'captureMultiJobUpdateActionsPickList','default','select','{"Hold":"Hold", "Ignore":"Ignore", "Release":"Release", "Retry":"Retry failed job", "UpdateParameters":"Update Job Parameters from DMS"}');
INSERT INTO "chooser_definitions" VALUES(93,'itemTypePickList','default','select','{"Batch_ID":"Batch_ID","Requested_Run_ID":"Requested_Run_ID", "Dataset_Name":"Dataset_Name", "Dataset_ID":"Dataset_ID", "Experiment_Name":"Experiment_Name", "Experiment_ID":"Experiment_ID", "Data_Package_ID":"Data_Package_ID"}');
INSERT INTO "chooser_definitions" VALUES(94,'samplePrepRequestFacilityList','default','select','{"EMSL":"EMSL", "BSF":"BSF"}');
INSERT INTO "chooser_definitions" VALUES(95,'dataReleaseRestrictionsPicklist','default','sql','SELECT Name AS val, Name AS ex FROM V_Data_Release_Restriction_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(96,'predefTriggerModePickList','default','select','{"0":"Normal", "1":"Before Disposition"}');
INSERT INTO "chooser_definitions" VALUES(97,'instrumentRuntimeReportOptions','default','select','{"Show All":"Show All", "No Intervals":"No Intervals", "Intervals Only":"Intervals Only", "Long Intervals":"Long Intervals"}');
INSERT INTO "chooser_definitions" VALUES(98,'percentagePicklist','default','select','{"10":"10", "20":"20",  "30":"30",  "40":"40",  "50":"50",  "60":"60",  "70":"70",  "80":"80",  "90":"90",  "100":"100"}');
INSERT INTO "chooser_definitions" VALUES(99,'longIntervalUsagePickList','default','select','{ "UserOnsite[100%], Proposal[xxx], PropUser[xxx]":"UserOnsite, Proposal, PropUser",  "UserRemote[100%], Proposal[xxx], PropUser[xxx]":"UserRemote, Proposal, PropUser", "Broken[100%]":"Broken",  "Maintenance[100%]":"Maintenance", "OtherNotAvailable[100%]":"OtherNotAvailable",  "StaffNotAvailable[100%]":"StaffNotAvailable",  "CapDev[100%], Operator[xxx]":"CapDev, Operator",  "InstrumentAvailable[100%]":"InstrumentAvailable" }');
INSERT INTO "chooser_definitions" VALUES(100,'instrumentRunUsagePicklist','default','sql','SELECT Name_with_Reporting As val, Name as ex FROM V_Instrument_Tracked ORDER BY Name_with_Reporting ');
INSERT INTO "chooser_definitions" VALUES(101,'monthRunUsagePicklist','default','select','{ "01":"01", "02":"02", "03":"03", "04":"04", "05":"05", "06":"06", "07":"07", "08":"08", "09":"09", "10":"10", "11":"11", "12":"12" }');
INSERT INTO "chooser_definitions" VALUES(102,'macJobOptionsPicklist','default','select','{ "Include Protein Prophet":"Include Protein Prophet", "Fixed Effect":"Fixed Effect",  "Consolidation Factor":"Consolidation Factor",  "Protein Prophet":"Protein Prophet" }');
INSERT INTO "chooser_definitions" VALUES(103,'macJobTypePicklist','broker','sql','SELECT Script as val, Script as ex FROM V_MAC_Job_Type_Picklist ORDER BY Script');
INSERT INTO "chooser_definitions" VALUES(104,'instrumentRunUsageFormatPicklist','default','select','{"report":"report", "details":"details", "rollup":"rollup", "check":"check"}');
INSERT INTO "chooser_definitions" VALUES(105,'operationsTaskState','default','select','{"New":"New", "Open":"Open", "In Progress":"In Progress", "Completed":"Completed",  "Not Implemented":"Not Implemented"}');
INSERT INTO "chooser_definitions" VALUES(106,'operationsTaskPriority','default','select','{"Normal":"Normal", "High":"High"}');
INSERT INTO "chooser_definitions" VALUES(107,'operationsTaskStaff','default','sql','SELECT Name as val, Name as ex FROM V_Operations_Task_Staff_Picklist order by Name');
INSERT INTO "chooser_definitions" VALUES(108,'separationGroupPickList','default','sql','SELECT Sep_Group AS val, '''' as ex FROM V_Separation_Group_PickList ORDER BY Sep_Group');
INSERT INTO "chooser_definitions" VALUES(109,'samplePrepSeparationGroupPickList','default','sql','SELECT Sep_Group AS val, '''' as ex FROM V_Separation_Group_PickList WHERE Sample_Prep_Visible > 0 ORDER BY Sep_Group');
INSERT INTO "chooser_definitions" VALUES(110,'osmPackageStatePickList','package','sql','SELECT Name AS val, Name AS ex FROM V_OSM_Package_State_Picklist');
INSERT INTO "chooser_definitions" VALUES(111,'osmPackageTypePickList','package','sql','SELECT Name AS val, Name AS ex FROM V_OSM_Package_Type_Picklist');
INSERT INTO "chooser_definitions" VALUES(113,'psmJobTypePicklist','default','sql','SELECT Job_Type_Description AS val, Job_Type_Name AS ex FROM V_Default_PSM_Job_Types ORDER BY Job_Type_ID');
INSERT INTO "chooser_definitions" VALUES(114,'psmToolNamePicklist','default','sql','SELECT Description AS val, Tool_Name AS ex FROM V_Default_PSM_Job_Tools ORDER BY Tool_Name');
INSERT INTO "chooser_definitions" VALUES(115,'amtDBPicklist','default','sql','SELECT DISTINCT MT_DB_Name AS val, MT_DB_Name AS ex FROM V_MTS_MT_DBs WHERE State_ID < 10');
INSERT INTO "chooser_definitions" VALUES(116,'usageTrackedInstruments','default','sql','SELECT Name_with_Reporting AS val, Name AS ex FROM V_Instrument_Tracked ORDER BY Reporting, Name');
INSERT INTO "chooser_definitions" VALUES(117,'multiDatasetRequestCommentTmpl','default','select','{ "Capability Development":"Description of work:|Est. desired start date:|Est. days required:|Production LC needed? (if yes, provide details):", "Triple Quad Usage":"Analysis Type:|Est. desired start date:|Est. days required:|Est. total samples:|Project deadline (date):", "RapidFire":"Cartridge: |Ionization: |Experiment Group: https://dms2.pnl.gov/experiment_group/show/0000|Number of Runs: |Additional Info:" }');
INSERT INTO "chooser_definitions" VALUES(118,'data_package_list','default','sql','SELECT Id_with_Name AS val, ID AS ex FROM V_Data_Package_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(119,'osm_package_list','default','sql','SELECT Id_with_Name AS val, ID AS ex FROM V_OSM_Package_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(120,'requested_run_batch_list','default','sql','SELECT ID_with_Batch AS val, ID AS ex FROM V_Requested_Run_Batch_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(121,'yesNoNAPickList','default','select','{"Yes":"Yes", "No":"No", "NA":"NA"}');
INSERT INTO "chooser_definitions" VALUES(122,'instrumentConfigDescriptionPickList','default','select','{ "Cleaned source": "Cleaned source", "Cleaned source back to trap": "Cleaned source back to trap", "Deep clean": "Deep clean", "FT Mass cal": "FT Mass cal", "Full Instrument cal": "Full Instrument cal", "General configuration note": "General configuration note", "Liquid Nitrogen Fill....": "Liquid Nitrogen Fill....", "Mass cal": "Mass cal", "Mass cal and checks - Negative mode": "Mass cal and checks - Negative mode", "Mass cal and checks - Positive mode": "Mass cal and checks - Positive mode", "Misc Instrument checks": "Misc Instrument checks" }');
INSERT INTO "chooser_definitions" VALUES(123,'apeWorkflowPickList','default','select','{"default":"Default (1% FDR)", "5percent":"5% FDR"}');
INSERT INTO "chooser_definitions" VALUES(124,'instrumentNameRNAPickList','default','sql','SELECT Instrument  As val, '''' As ex FROM V_Instrument_Name_RNA_PickList ORDER BY Instrument');
INSERT INTO "chooser_definitions" VALUES(125,'rnaPrepReqMethodPickList','default','select','{"PreProcessing Cleanup":"PreProcessing Cleanup",
"PreProcessing RNA Extraction":"PreProcessing RNA Extraction",
"PreProcessing Gel/Bioanalyzer":"PreProcessing Gel/Bioanalyzer",
"Phenol/chloroform":"Phenol/chloroform",
"Trizol/Qiagen column":"Trizol/Qiagen column",
"Trizol/zymo column":"Trizol/zymo column",
"CTAB":"CTAB",
"Hot acid phenol":"Hot acid phenol"
}');
INSERT INTO "chooser_definitions" VALUES(126,'paramFileTypePickList','default','sql','SELECT Param_File_Type_Ex as val, Param_File_Type as ex FROM V_Param_File_Type_PickList order by Param_File_Type');
INSERT INTO "chooser_definitions" VALUES(127,'campaignIDPickList','default','sql','SELECT Campaign as val, ID as ex FROM V_Campaign_List_Report_2 WHERE State = ''Active'' ORDER BY campaign');
INSERT INTO "chooser_definitions" VALUES(128,'compoundTypePickList','default','sql','SELECT Name AS val, Name AS ex FROM V_Reference_Compound_Type_Name_Picklist ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(129,'experimentPlexChannelTypePickList','default','select','{"Sample":"Sample", "Reference":"Reference", "Boost":"Boost", "Empty":"Empty"}');
INSERT INTO "chooser_definitions" VALUES(130,'userStatusPickList','default','sql','SELECT Description as val, Status AS ex FROM V_User_Status_Picklist ORDER BY Status');
INSERT INTO "chooser_definitions" VALUES(131,'organismIDPickList','default','sql','SELECT Name as val, ID as ex FROM V_Organism_List_Report ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(132,'sampleTypePickList','default','sql','SELECT Name as val, Name as ex FROM V_Secondary_Sep_Sample_Type_Picklist ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(133,'separationGroupNoFractionsPickList','default','sql','SELECT Sep_Group AS val, '''' as ex FROM V_Separation_Group_PickList WHERE Fraction_Count = 0 ORDER BY Sep_Group');
INSERT INTO "chooser_definitions" VALUES(134,'campaignEusUsageTypePickList','default','sql','SELECT Description AS val, Name AS ex FROM V_EUS_Usage_Type_Picklist WHERE Enabled_Campaign > 0 ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(135,'dataAnalysisTypePickList','default','select','{"Proteomics":"Proteomics", "Metabolomics":"Metabolomics", "Lipidomics":"Lipidomics"}');
INSERT INTO "chooser_definitions" VALUES(136,'dataAnalysisRequestStatePickList','default','sql','SELECT val, ex FROM V_Data_Analysis_Request_State_Picklist ORDER BY State_ID');
INSERT INTO "chooser_definitions" VALUES(137,'dataAnalysisRequestUserPickList','default','sql','SELECT val, ex FROM V_Data_Analysis_Request_User_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(138,'labLocationPickList','default','sql','SELECT Lab_Name as val, Lab_Name  as ex FROM V_Lab_Locations ORDER BY Sort_Weight, Lab_Name');
INSERT INTO "chooser_definitions" VALUES(139,'operationTaskTypePickList','default','sql','SELECT Task_Type_Name as val, Task_Type_Name  as ex FROM V_Operations_Task_Types ORDER BY Task_Type_Name');
INSERT INTO "chooser_definitions" VALUES(140,'emslInstrumentUsagePickList','default','sql','SELECT Description AS val, Name AS ex FROM V_EMSL_Instrument_Usage_Type_Picklist ORDER BY Name');
COMMIT;
