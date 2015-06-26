PRAGMA foreign_keys=OFF;
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
INSERT INTO "chooser_definitions" VALUES(3,'analysisJobPriPickList','default','select','{"1":"1", "2":"2", "3":"3", "4":"4"}');
INSERT INTO "chooser_definitions" VALUES(4,'analysisPredefJobPriPickList','default','select','{"1":"1", "2":"2", "3":"3", "4":"4", "5":"5", "6":"6", "7":"7", "8":"8", "9":"9"}');
INSERT INTO "chooser_definitions" VALUES(5,'containerTypePickList','default','select','{"Bag":"Bag", "Box":"Box"}');
INSERT INTO "chooser_definitions" VALUES(6,'datasetRatingPickList','default','select','{"Unreviewed":"Unreviewed", "Released":"Released", "Not Released":"Not Released", "Rerun (Good Data)":"Rerun (Good Data)", "Rerun (Superseded)":"Rerun (Superseded)"  }');
INSERT INTO "chooser_definitions" VALUES(7,'futureDatePickList','default','select','{"0":"Today", "-1":"1 day from now", "-2":"2 days from now", "-3":"3 days from now", "-5":"5 days from now", "-7":"7 days from now", "-14":"14 days from now", "-21":"21 days from now", "-30":"30 days from now", "-60":"60 days from now", "-90":"90 days from now", "-90":"120 days from now", "-180":"180 days from now"}');
INSERT INTO "chooser_definitions" VALUES(8,'instUsageJournalEMSLTypePickList','default','select','{"(R Remote":"(R Remote", "(O On site":"(O On site", "(B Broken":"(B Broken", "(A Available":"(A Available", "(C Capability Dev.":"(C Capability Dev.", "(U Unavailable":"(U Unavailable"}');
INSERT INTO "chooser_definitions" VALUES(9,'jobPropagationModePickList','default','select','{"Export":"Export", "No Export":"No Export"}');
INSERT INTO "chooser_definitions" VALUES(10,'longIntervalReasonPickList','default','select','{ "Staffing limitations, priorities directed elsewhere":"Staffing limitations, priorities directed elsewhere", "On hold pending scheduling":"On hold pending scheduling",  "End batch(es), prepare for next":"End batch(es), prepare for next",  "LC maintenance":"LC maintenance",  "MS maintenance":"MS maintenance",  "LC broken":"LC broken",  "MS broken":"MS broken",  "Troubleshooting LC issue":"Troubleshooting LC issue",  "Troubleshooting MS issue":"Troubleshooting MS issue",  "Capability development: Input required, description of work and user":"Capability development: Input required, description of work and user",  "Facilities: power failure or outage, instrument move, floor services":"Facilities: power failure or outage, instrument move, floor services" }');
INSERT INTO "chooser_definitions" VALUES(11,'lcCartComponentStatusPickList','default','select','{"Active":"Active", "Out of service":"Out of service", "Discarded":"Discarded"}');
INSERT INTO "chooser_definitions" VALUES(12,'logEntryPostedByPickList','default','select','{"Archive":"Archive", "ArchiveVerify":"ArchiveVerify", "Capture":"Capture", "Extraction":"Extraction", "Preparation":"Preparation", "RequestCaptureTask":"RequestCaptureTask", "Space":"Space"}');
INSERT INTO "chooser_definitions" VALUES(13,'logEntryTypePickList','default','select','{"Normal":"Normal", "Warning":"Warning", "Error":"Error"}');
INSERT INTO "chooser_definitions" VALUES(14,'postdigestIntStdPickList','default','select','{"none":"none", "PepChromeA":"PepChromeA"}');
INSERT INTO "chooser_definitions" VALUES(15,'predefCritNamePickList','default','select','{"Campaign Name":"Campaign Name", "Organism Name":"Organism Name", "Instrument Name":"Instrument Name", "Experiment Name":"Experiment Name", "ExpCommentContains":"ExpCommentContains", "Mouse Special Labels 1":"Mouse Special Labels 1", "Yeast Special Labels 1":"Yeast Special Labels 1", "Blood Serum Special Labels 1":"Blood Serum Special Labels 1", "Shewanella Special Labels 1":"Shewanella Special Labels 1", "deinococcus Special Labels 1":"deinococcus Special Labels 1", "deinococcus Special Labels 2":"deinococcus Special Labels 2", "Instrument Class":"Instrument Class"}');
INSERT INTO "chooser_definitions" VALUES(16,'predefCritValuePickList','default','select','{"MM without PEO or ICAT":"MM without PEO or ICAT", "MM with PEO":"MM with PEO", "MM with ICAT":"MM with ICAT", "Yeast without PEO or ICAT":"Yeast without PEO or ICAT", "Yeast with PEO":"Yeast with PEO", "Yeast with ICAT":"Yeast with ICAT", "Blood serum without PEO or ICAT":"Blood serum without PEO or ICAT", "Blood serum with PEO":"Blood serum with PEO", "Blood serum with ICAT":"Blood serum with ICAT", "Shewanella with N15":"Shewanella with N15", "deinococcus with N15":"deinococcus with N15", "deinococcus with PEO":"deinococcus with PEO"}');
INSERT INTO "chooser_definitions" VALUES(17,'predefEnablePickList','default','select','{"0":"0", "1":"1"}');
INSERT INTO "chooser_definitions" VALUES(18,'predefinedAnalysisPreviewReportType','default','select','{"Show Jobs":"Show jobs that would be created for dataset", "Show Rules":"Show rules that would be triggered by datset"}');
INSERT INTO "chooser_definitions" VALUES(19,'predigestIntStdPickList','default','select','{"none":"none", "mini-proteome":"mini-proteome"}');
INSERT INTO "chooser_definitions" VALUES(20,'prepLCRunGuardColPickList','default','select','{"Yes":"Yes (guard column was changed)", "No":"No (guard column was not changed)", "n/a":"N/A (guard column was not used)"}');
INSERT INTO "chooser_definitions" VALUES(21,'prepLCRunTypePickList','default','select','{"Fractionation":"Fractionation Run","Depletion":"Depletion Run", "Other":"Something else"}');
INSERT INTO "chooser_definitions" VALUES(22,'prevDatePickList','default','select','{"0":"Today", "1":"1 day ago", "2":"2 days ago", "3":"3 days ago", "5":"5 days ago", "7":"1 week ago", "14":"2 weeks ago", "21":"3 weeks ago", "30":"1 month ago", "60":"2 months ago", "90":"3 months ago", "180":"6 months ago", "365":"1 year ago", "730":"2 years ago", "-1":"Beginning of time"}');
INSERT INTO "chooser_definitions" VALUES(23,'rawDataTypePickList','default','sql','SELECT Raw_Data_Type_Name  AS val, Raw_Data_Type_Name AS ex FROM T_Instrument_Data_Type_Name');
INSERT INTO "chooser_definitions" VALUES(24,'samplePrepReqBiohazardPickList','default','select','{"BSL1":"BSL1", "BSL2":"BSL2"}');
INSERT INTO "chooser_definitions" VALUES(25,'samplePrepReqMethodPickList','default','select','{"Chloroform/methanol extraction":"Chloroform/methanol extraction", "Cysteinyl-peptide enrichment":"Cysteinyl-peptide enrichment", "Dilute for analysis":"Dilute for analysis", "FASP":"FASP", "GC-MS chemical derivatization":"GC-MS chemical derivatization", "Gel Electrophoresis":"Gel Electrophoresis", "Global Digest":"Global Digest", "High pH fractionation":"High pH fractionation", "HPLC Depletion (no Protease Inhibitor)":"HPLC Depletion (no Protease Inhibitor)", "HPLC Depletion (with Protease Inhibitor)":"HPLC Depletion (with Protease Inhibitor)", "HPLC SCX Fractionation":"HPLC SCX Fractionation", "iTRAQ labeling":"iTRAQ labeling", "Insoluble Digest":"Insoluble Digest", "N-glycopeptide enrichment":"N-glycopeptide enrichment", "O18 labeling":"O18 labeling", "Online 2D SCX fractionation":"Online 2D SCX fractionation", "Phosphopeptide enrichment":"Phosphopeptide enrichment", "Rapigest Digest":"Rapigest Digest", "Soluble Digest":"Soluble Digest", "SPE Cleanup":"SPE Cleanup", "TFE Digest":"TFE Digest", "Other":"Other"}');
INSERT INTO "chooser_definitions" VALUES(26,'samplePrepReqTypePickList','default','select','{"Cell pellet":"Cell pellet", "Protein solution":"Protein solution", "Peptides":"Peptides", "Serum":"Serum", "Blood":"Blood", "Tissue":"Tissue", "Bodily Fluid":"Bodily Fluid", "Plasma":"Plasma", "Other":"Other"}');
INSERT INTO "chooser_definitions" VALUES(27,'storageFunctionPickList','default','select','{"inbox":"inbox", "old-storage":"old-storage", "raw-storage":"raw-storage"}');
INSERT INTO "chooser_definitions" VALUES(28,'yesNoAsOneZeroPickList','default','select','{"1":"Yes", "0":"No"}');
INSERT INTO "chooser_definitions" VALUES(29,'yesNoPickList','default','select','{"Yes":"Yes", "No":"No"}');
INSERT INTO "chooser_definitions" VALUES(30,'EUSProposalStatePickList','default','sql','SELECT Cast(ID AS Varchar(32)) + '' - '' + Name as val, ID AS ex FROM T_EUS_Proposal_State_Name ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(31,'EUSUserSiteStatusPickList','default','sql','SELECT Cast(ID AS Varchar(32)) + '' - '' + Name as val, ID AS ex FROM T_EUS_Site_Status ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(32,'LCColumnPickList','default','sql','SELECT val, ex FROM V_LC_Column_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(33,'LCColumnStatePickList','default','sql','SELECT val, ex FROM V_LC_Column_State_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(34,'ReqRunInstrumentPicklist','default','sql','SELECT val, ex FROM V_Req_Run_Instrument_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(35,'ReqRunInstrumentPicklistEx','default','sql','SELECT val, ex FROM V_Req_Run_Instrument_Picklist_Ex ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(36,'analysisJobStatePickList','default','sql','SELECT AJS_name as val, '''' as ex FROM T_Analysis_State_Name ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(37,'analysisRequestPickList','default','sql','SELECT StateName AS val, '''' AS ex FROM T_Analysis_Job_Request_State WHERE StateName <> ''na'' ORDER BY StateName');
INSERT INTO "chooser_definitions" VALUES(38,'analysisToolPickList','default','sql','SELECT AJT_toolName as val, '''' as ex FROM T_Analysis_Tool WHERE (AJT_active > 0) ORDER BY AJT_toolName');
INSERT INTO "chooser_definitions" VALUES(39,'archiveFunctionPickList','default','sql','SELECT APF_Function AS val, '''' AS ex FROM T_Archive_Path_Function');
INSERT INTO "chooser_definitions" VALUES(40,'archiveStateName','default','sql','SELECT DASN_StateName AS val, DASN_StateName AS ex FROM T_DatasetArchiveStateName');
INSERT INTO "chooser_definitions" VALUES(41,'archiveUpdateName','default','sql','SELECT AUS_name AS val, AUS_name AS ex FROM T_Archive_Update_State_Name');
INSERT INTO "chooser_definitions" VALUES(42,'assignedProcessorPickList','default','sql','SELECT * FROM V_Analysis_Processor_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(43,'associatedProcessorGroupPickList','default','sql','SELECT Group_Name + '' ('' + CAST(ID AS varchar(12)) + '')'' AS val, Group_Name as ex FROM T_Analysis_Job_Processor_Group');
INSERT INTO "chooser_definitions" VALUES(44,'batchPriorityPickList','default','sql','SELECT ''Normal'' as val, '''' as ex union Select ''High'' as val, '''' as ex ');
INSERT INTO "chooser_definitions" VALUES(45,'captureMethodPickList','default','sql','SELECT val, '''' as ex FROM V_Capture_Method_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(46,'cellCultureTypePickList','default','sql','SELECT Name as val, '''' as ex FROM T_Cell_Culture_Type_Name ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(47,'datasetStatePickList','default','sql','SELECT DSS_name as val, '''' as ex FROM T_DatasetStateName ORDER BY Dataset_state_ID');
INSERT INTO "chooser_definitions" VALUES(48,'datasetTypePickList','default','sql','SELECT DST_Name + '' ... ['' + DST_Description + '']'' AS val, DST_Name AS ex FROM T_DatasetTypeName WHERE DST_Active = 1 ORDER BY DST_name');
INSERT INTO "chooser_definitions" VALUES(49,'dnaTabIDPickList','default','sql','SELECT Translation_Table_Name AS val, DNA_Translation_Table_ID AS ex FROM V_DNA_Translation_Tables ORDER BY DNA_Translation_Table_ID');
INSERT INTO "chooser_definitions" VALUES(50,'enzymePickList','default','sql','SELECT Enzyme_Name as val, '''' as ex FROM T_Enzymes WHERE Enzyme_ID > 0 ORDER BY Enzyme_Name');
INSERT INTO "chooser_definitions" VALUES(51,'eusUsageTypePickList','default','sql','SELECT [Name] AS val, [Name] AS ex FROM T_EUS_UsageType WHERE ID > 1 ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(52,'experimentUserPRNPickList','default','sql','SELECT Name AS val, [Payroll Num] AS ex FROM V_Experiment_User_Picklist ORDER BY [Name]');
INSERT INTO "chooser_definitions" VALUES(53,'filterSetPickList','default','sql','SELECT distinct Cast(filter_set_ID as varchar(11)) + '' - '' + Filter_Set_Name as val, filter_set_ID as ex FROM V_Filter_Sets ORDER by filter_set_ID');
INSERT INTO "chooser_definitions" VALUES(54,'instrumentClassPickList','default','sql','SELECT IN_class as val, '''' as ex FROM T_Instrument_Class ORDER BY IN_class ASC');
INSERT INTO "chooser_definitions" VALUES(56,'instrumentNameAdminPickList','default','sql','SELECT val, ex FROM V_Instrument_Admin_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(57,'instrumentNamePickList','default','sql','SELECT val, ex FROM V_Instrument_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(58,'instrumentNameExPickList','default','sql','SELECT val, ex FROM V_Instrument_Picklist_Ex ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(59,'instrumentOpsRolePickList','default','sql','SELECT val, '''' as ex FROM V_Instrument_OpsRole_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(60,'instrumentStatusPickList','default','sql','SELECT val, '''' as ex FROM V_Instrument_Status_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(61,'instrumentGroupPickList','default','sql','SELECT Instrument_Group As val, '''' As ex FROM V_Instrument_Group_PickList ORDER BY Instrument_Group');
INSERT INTO "chooser_definitions" VALUES(62,'internalStandardsPicklist','default','sql','SELECT val, ex FROM V_Internal_Standards_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(63,'labellingPickList','default','sql','SELECT Label as val, '''' as ex FROM T_Sample_Labelling ORDER BY Label');
INSERT INTO "chooser_definitions" VALUES(65,'lcCartPickList','default','sql','SELECT Cart_Name AS val, Cart_Name AS ex FROM T_LC_Cart WHERE Cart_State_ID = 2 ORDER BY Cart_Name');
INSERT INTO "chooser_definitions" VALUES(66,'lcCartStatePickList','default','sql','SELECT Name AS val, '''' AS ex FROM T_LC_Cart_State_Name WHERE (ID > 1) ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(67,'orgDefPickList','default','sql','SELECT OG_name as val, '''' as ex FROM T_Organisms ORDER BY OG_Name');
INSERT INTO "chooser_definitions" VALUES(68,'orgPickList','default','sql','SELECT OG_name as val, '''' as ex FROM T_Organisms WHERE (OG_Active > 0) ORDER BY OG_Name');
INSERT INTO "chooser_definitions" VALUES(69,'prepInstrumentPickList','default','sql','SELECT IN_name AS val, IN_name AS ex FROM T_Instrument_Name WHERE (IN_Group = ''PrepHPLC'') AND (IN_status IN (''active'', ''offline'')) ORDER BY IN_Name');
INSERT INTO "chooser_definitions" VALUES(70,'prepLCRunTabPickList','default','sql','SELECT val, '''' as ex FROM V_Helper_Prep_Lc_Run_Tab_List_Report ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(71,'protOptSeqDirPickList','default','sql','SELECT * FROM V_Protein_Options_Seq_Direction');
INSERT INTO "chooser_definitions" VALUES(73,'samplePrepUserPickList','default','sql','SELECT val, ex FROM V_Sample_Prep_Request_User_Picklist ORDER BY [val]');
INSERT INTO "chooser_definitions" VALUES(74,'sampleRequestStatePickList','default','sql','SELECT val, ex FROM V_Sample_Prep_Request_State_Picklist ORDER BY State_ID');
INSERT INTO "chooser_definitions" VALUES(76,'secsepPickList','default','sql','SELECT SS_name as val, '''' as ex FROM T_Secondary_Sep ORDER BY SS_Name');
INSERT INTO "chooser_definitions" VALUES(80,'userNamePickList','default','sql','SELECT Name + '' ('' + [Payroll Num] + '')'' AS val, '''' AS ex FROM V_Active_Users ORDER BY Name');
INSERT INTO "chooser_definitions" VALUES(81,'userOperationsPickList','default','sql','SELECT Operation as val, '''' as ex FROM T_User_Operations ORDER BY Operation');
INSERT INTO "chooser_definitions" VALUES(82,'userPRNPickList','default','sql','SELECT Name AS val, [Payroll Num] AS ex FROM V_Active_Users ORDER BY [Name]');
INSERT INTO "chooser_definitions" VALUES(83,'wellplatePickList','default','sql','SELECT val, ex FROM V_Wellplate_Picklist ORDER BY val');
INSERT INTO "chooser_definitions" VALUES(84,'dataPackageStatePickList','package','sql','SELECT Name AS val, Name AS ex FROM T_Data_Package_State');
INSERT INTO "chooser_definitions" VALUES(85,'dataPackageTeamPickList','package','sql','SELECT Team_Name AS val, Team_Name AS ex FROM T_Data_Package_Teams');
INSERT INTO "chooser_definitions" VALUES(86,'dataPackageTypePickList','package','sql','SELECT Name AS val, Name AS ex FROM T_Data_Package_Type');
INSERT INTO "chooser_definitions" VALUES(87,'captureMultiJobUpdateActionsPickList','default','select','{"Hold":"Hold", "Ignore":"Ignore", "Release":"Release", "Retry":"Retry failed job", "UpdateParameters":"Update Job Parameters from DMS"}');
INSERT INTO "chooser_definitions" VALUES(88,'itemTypePickList','default','select','{"Batch_ID":"Batch_ID","Requested_Run_ID":"Requested_Run_ID", "Dataset_Name":"Dataset_Name", "Dataset_ID":"Dataset_ID", "Experiment_Name":"Experiment_Name", "Experiment_ID":"Experiment_ID", "Data_Package_ID":"Data_Package_ID"}');
INSERT INTO "chooser_definitions" VALUES(89,'samplePrepRequestFacilityList','default','select','{"EMSL":"EMSL", "BSF":"BSF"}');
INSERT INTO "chooser_definitions" VALUES(90,'dataReleaseRestrictionsPicklist','default','sql','SELECT Name AS val, Name AS ex FROM T_Data_Release_Restrictions ORDER BY ID');
INSERT INTO "chooser_definitions" VALUES(91,'predefTriggerModePickList','default','select','{"0":"Normal", "1":"Before Disposition"}');
INSERT INTO "chooser_definitions" VALUES(92,'instrumentRuntimeReportOptions','default','select','{"Show All":"Show All", "No Intervals":"No Intervals", "Intervals Only":"Intervals Only", "Long Intervals":"Long Intervals"}');
INSERT INTO "chooser_definitions" VALUES(93,'percentagePicklist','default','select','{"10":"10", "20":"20",  "30":"30",  "40":"40",  "50":"50",  "60":"60",  "70":"70",  "80":"80",  "90":"90",  "100":"100"}');
INSERT INTO "chooser_definitions" VALUES(94,'longIntervalUsagePickList','default','select','{ "User[100%], Proposal[xxx], PropUser[xxx]":"User, Proposal, PropUser", "Broken[100%]":"Broken",  "Maintenance[100%]":"Maintenance", "OtherNotAvailable[100%]":"OtherNotAvailable",  "StaffNotAvailable[100%]":"StaffNotAvailable",  "CapDev[100%], Operator[xxx]":"CapDev, Operator",  "InstrumentAvailable[100%]":"InstrumentAvailable"  }');
INSERT INTO "chooser_definitions" VALUES(95,'instrumentRunUsagePicklist','default','sql','SELECT [Name] + '' ('' + Reporting + '')'' as val, [Name] as ex FROM V_Instrument_Tracked ORDER BY Val');
INSERT INTO "chooser_definitions" VALUES(96,'monthRunUsagePicklist','default','select','{ "01":"01", "02":"02", "03":"03", "04":"04", "05":"05", "06":"06", "07":"07", "08":"08", "09":"09", "10":"10", "11":"11", "12":"12" }');
INSERT INTO "chooser_definitions" VALUES(97,'macJobOptionsPicklist','default','select','{ "Include Protein Prophet":"Include Protein Prophet", "Fixed Effect":"Fixed Effect",  "Consolidation Factor":"Consolidation Factor",  "Protein Prophet":"Protein Prophet" }');
INSERT INTO "chooser_definitions" VALUES(98,'macJobTypePicklist','broker','sql','SELECT Script as val, Script as ex FROM T_Scripts WHERE (Enabled = ''Y'') AND (NOT (Parameters IS NULL))');
INSERT INTO "chooser_definitions" VALUES(99,'instrumentRunUsageFormatPicklist','default','select','{"report":"report", "details":"details", "rollup":"rollup", "check":"check"}');
INSERT INTO "chooser_definitions" VALUES(100,'operationsTaskState','default','select','{"New":"New", "Open":"Open", "In Progress":"In Progress", "Completed":"Completed",  "Not Implemented":"Not Implemented"}');
INSERT INTO "chooser_definitions" VALUES(101,'operationsTaskPriority','default','select','{"Normal":"Normal", "High":"High"}');
INSERT INTO "chooser_definitions" VALUES(102,'operationsTaskStaff','default','sql','SELECT Name as val, Name as ex FROM V_Operations_Task_Staff_Picklist order by Name');
INSERT INTO "chooser_definitions" VALUES(103,'separationGroupPickList','default','sql','SELECT Sep_Group AS val, '''' as ex FROM T_Separation_Group WHERE Active > 0 ORDER BY Sep_Group');
INSERT INTO "chooser_definitions" VALUES(104,'osmPackageStatePickList','package','sql','SELECT Name AS val, Name AS ex FROM T_OSM_Package_State');
INSERT INTO "chooser_definitions" VALUES(105,'osmPackageTypePickList','package','sql','SELECT Name AS val, Name AS ex FROM T_OSM_Package_Type');
INSERT INTO "chooser_definitions" VALUES(106,'experimentLabellingPickList','default','select','{"4plex":"4-Plex iTRAQ", "6plex":"6-Plex TMT", "8plex":"8-Plex iTRAQ" }');
INSERT INTO "chooser_definitions" VALUES(107,'psmJobTypePicklist','default','sql','SELECT Job_Type_Description AS val, Job_Type_Name AS ex FROM V_Default_PSM_Job_Types ORDER BY Job_Type_ID');
INSERT INTO "chooser_definitions" VALUES(108,'psmToolNamePicklist','default','sql','SELECT Description AS val, Tool_Name AS ex FROM V_Default_PSM_Job_Tools ORDER BY Tool_Name
');
INSERT INTO "chooser_definitions" VALUES(109,'amtDBPicklist','default','sql','SELECT DISTINCT MT_DB_Name AS val, MT_DB_Name AS ex FROM V_MTS_MT_DBs WHERE State_ID < 10');
INSERT INTO "chooser_definitions" VALUES(110,'usageTrackedInstruments','default','sql','SELECT Name + '' ('' + Reporting + '')   '' AS val, Name AS ex FROM V_Instrument_Tracked ORDER BY Reporting, Name');
INSERT INTO "chooser_definitions" VALUES(111,'multiDatasetRequestCommentTmpl','default','select','{ "Capability Development":"Description of work:|Est. desired start date:|Est. days required:|Production LC needed? (if yes, provide details):", "Triple Quad Usage":"Description of work:|Est. desired start date:|Est. days required:|Est. total samples:"  }
');
INSERT INTO "chooser_definitions" VALUES(112,'data_package_list','default','sql','SELECT CONVERT(VARCHAR(12), ID) + CHAR(32) +  Name AS val, ID AS ex FROM S_V_Data_Package_Export');
INSERT INTO "chooser_definitions" VALUES(113,'osm_package_list','default','sql','SELECT CONVERT(VARCHAR(12), ID) + CHAR(32) +  Name AS val, ID AS ex FROM S_V_OSM_Package_Export');
INSERT INTO "chooser_definitions" VALUES(114,'requested_run_batch_list','default','sql','SELECT CONVERT(VARCHAR(12), ID) + CHAR(32) +  Batch AS val, ID AS ex FROM T_Requested_Run_Batches');
INSERT INTO "chooser_definitions" VALUES(115,'yesNoNAPickList','default','select','{"Yes":"Yes", "No":"No", "NA":"NA"}');
INSERT INTO "chooser_definitions" VALUES(116,'instrumentConfigDescriptionPickList','default','select','{ "Transfer lenses check": "Transfer lenses check", "Transfer lenses cal": "Transfer lenses cal",  "Emult check": "Emult check",  "Mass cal": "Mass cal",  "Full Ion Trap cal": "Full Ion Trap cal",  "Full Instrument cal": "Full Instrument cal",  "Misc Instrument checks": "Misc Instrument checks",  "Cleaned source back to trap": "Cleaned source back to trap",  "Vendor service": "Vendor service",  "Magnet Inspection": "Magnet Inspection" }');
INSERT INTO "chooser_definitions" VALUES(117,'apeWorkflowPickList','default','select','{"default":"Default (1% FDR)", "5percent":"5% FDR"}');
INSERT INTO "chooser_definitions" VALUES(118,'instrumentNameRNAPickList','default','sql','SELECT Instrument  As val, '''' As ex FROM V_Instrument_Name_RNA_PickList ORDER BY Instrument');
INSERT INTO "chooser_definitions" VALUES(119,'rnaPrepReqMethodPickList','default','select','{"PreProcessing Cleanup":"PreProcessing Cleanup",
"PreProcessing RNA Extraction":"PreProcessing RNA Extraction",
"PreProcessing Gel/Bioanalyzer":"PreProcessing Gel/Bioanalyzer",
"Phenol/chloroform":"Phenol/chloroform",
"Trizol/Qiagen column":"Trizol/Qiagen column",
"Trizol/zymo column":"Trizol/zymo column",
"CTAB":"CTAB",
"Hot acid phenol":"Hot acid phenol"
}');
COMMIT;
