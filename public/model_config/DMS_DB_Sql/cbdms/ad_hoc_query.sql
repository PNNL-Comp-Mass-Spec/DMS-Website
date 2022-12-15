PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
CREATE TABLE utility_queries (

  id        integer PRIMARY KEY,

  name      text,

  label     text,

  db        text,

  "table"   text,

  columns   text,

  sorting   text,

  filters   text,

  hotlinks  text

);
INSERT INTO utility_queries VALUES(1,'users','Test Case 1','','T_Users','U_PRN, U_Name','','{"U_PRN":"CTx", "U_Name":"CTx"}','');
INSERT INTO utility_queries VALUES(3,'target_jobs','Test Case 2','package','V_DMS_Data_Package_Aggregation_Jobs','*','','{"Data_Package_ID":"EQn"}','');
INSERT INTO utility_queries VALUES(4,'dsj','Test Case 3','package','dbo.CheckDataPackageDatasetJobCoverage(id, tool, mode)','*','','{"id":"Rp", "tool":"Rp", "mode":"Rp"}','');
INSERT INTO utility_queries VALUES(5,'job_parm','Test Case 4','broker','dbo.GetJobParameters(job)','*','','{"job":"Rp"}','');
INSERT INTO utility_queries VALUES(6,'helper_experiment_ckbx','Experiment Chooser','','V_Experiment_List_Report','Experiment AS Sel, Experiment, Created, Researcher, Organism, Comment, Reason','{"col":"Created", "dir":"DESC"}','{"Experiment":"CTx", "Researcher":"CTx",  "Organism":"CTx",  "Reason":"CTx"}','{"Sel":{"LinkType":"CHECKBOX","WhichArg":"Experiment"},"Experiment":{"LinkType":"update_opener"}, "Comment":{"LinkType":"min_col_width", "Target":"120"}}');
INSERT INTO utility_queries VALUES(7,'campaign','Campaigns','','V_Campaign_List_Report_2','*','','{"Campaign":"CTx", "State":"CTx",  "Description":"CTx"}','{"Campaign":{"LinkType":"invoke_entity","Target":"campaign\/show\/"}}');
INSERT INTO utility_queries VALUES(8,'ron_ds1','Ron Moore Special Dataset','','V_Dataset_List_Report_2','ID, Dataset,  Instrument,  Comment,  Rating,  [Dataset Type],  [Acq Start],  [Separation Type]','{"col":"Created", "dir":"DESC"}','{"Dataset":"CTx", "ID":"Equals", "State":"CTx", "Comment":"CTx", "Rating":"CTx", "Separation Type":"CTx", "Acq Start":"LTd"}','');
INSERT INTO utility_queries VALUES(9,'ron_req1','Ron Moore Special Requested Run','','V_Requested_Run_List_Report_2','Request, Name,  Batch,  Instrument,  Requester,  Created,  Comment,  Type,  [Separation Type]','{"col":"Request", "dir":"DESC"}','{"Request":"CTx", "Name":"CTx", "Batch":"EQn", "Instrument":"CTx", "Requester":"CTx", "Created":"LTd", "Comment":"CTx", "Type":"CTx", "Separation Type":"CTx"}','{"Comment":{"LinkType":"min_col_width", "Target":"40"}}');
INSERT INTO utility_queries VALUES(10,'aux_info','Aux Info Details','','V_Aux_Info_Definition_with_ID','*,  dbo.GetAuxInfoAllowedValues(Item_ID) AS Allowed_Values','{"col":"Target", "dir":"ASC"}','{"Target":"CTx", "Category":"CTx",  "Subcategory":"CTx",  "Item":"CTx"}','');
INSERT INTO utility_queries VALUES(11,'database_objects','DMS Database Objects','','V_Database_Objects','*','{"col":"Modified", "dir":"DESC"}','{"Name":"CTx", "Type":"CTx"}','');
INSERT INTO utility_queries VALUES(12,'helper_inst_group_dstype','Instrument Group Dataset Type Chooser','','V_Instrument_Group_Allowed_Dataset_Type','*','{"col":"Dataset Count", "dir":"DESC"}','{"Instrument Group":"MTx"}','{"Dataset Type":{"LinkType":"update_opener"}}');
INSERT INTO utility_queries VALUES(13,'instrument_run_info','Instrument Run Information','','dbo.GetInstrumentRunDatasets(mostRecentWeeks, instrument)','*','','{"mostRecentWeeks":"Rp", "instrument":"Rp"}','');
INSERT INTO utility_queries VALUES(14,'lcms_requested_run','Requested Run List For LCMS Cart','','V_Requested_Run_Active_Export','*','{"col":"Request", "dir":"ASC"}','','');
INSERT INTO utility_queries VALUES(15,'factors_for_jobs','Factors For Jobs','','V_Custom_Factors_For_Job_List_Report','*','','{"Dataset":"CTx", "Factor":"CTx", "Value":"CTx", "Tool":"CTx", "Experiment":"CTx", "Campaign":"CTx" }','');
INSERT INTO utility_queries VALUES(16,'protein_collection','Protein Collections','','V_Protein_Collection_Name','[Organism Name], Name, Description, Entries, Type, ID, [Usage Last 12 Months], [Usage All Years]','{"col":"Name", "dir":"ASC"}','{"Organism Name":"MTx", "Name":"CTx", "Description":"CTx", "Type":"CTx"}','{"Organism Name":{"LinkType":"invoke_entity","Target":"organism\/report\/"}}');
INSERT INTO utility_queries VALUES(17,'capture_operations','Capture Job Operations','capture','V_Job_Steps WHERE State IN (4, 6)','*','','','');
INSERT INTO utility_queries VALUES(18,'job_operations','Analysis Job Operations','broker','V_Job_Steps2 WHERE State IN (4, 6)','*','{"col":"Tool", "dir":"ASC"}','','');
INSERT INTO utility_queries VALUES(19,'emsl_instrument_allocation','EMSL Instrument Allocation','','T_EMSL_Instrument_Allocation','*','{"col":"FY", "dir":"DESC"}','{"FY":"CTx", "Ext_Display_Name":"CTx"}','');
INSERT INTO utility_queries VALUES(20,'emsl_instruments','EMSL Instruments','','T_EMSL_Instruments','*','','{ "EUS_Display_Name":"CTx", "EUS_Instrument_Name":"CTx", "EUS_Available_Hours":"CTx", "Local_Category_Name":"CTx", "Local_Instrument_Name":"CTx" }','');
INSERT INTO utility_queries VALUES(21,'emsl_allocated_usage_by_category','EMSL Allocated Usage By Category','','V_EMSL_Allocated_Usage_By_Category','*','{"col":"FY", "dir":"DESC"}','{ "FY":"CTx", "Proposal_ID":"CTx", "Category":"CTx" }','');
INSERT INTO utility_queries VALUES(22,'emsl_actual_usage_by_category','EMSL Actual Usage By Category','','V_EMSL_Actual_Usage_By_Category','*','{"col":"FY", "dir":"DESC"}','{ "FY":"CTx", "Proposal_ID":"CTx", "Category":"CTx" }','');
INSERT INTO utility_queries VALUES(23,'ron_plan1','Ron Moore Run Planning Std Datasets','','V_Run_Planning_Report_StdSep','*','{"col":"Request Name Code", "dir":"ASC"}','{ "Inst Group":"CTx", "Batch or Experiment":"CTx", "Requester":"CTx", "Comment":"CTx"}',replace('{"Min Request":  {"LinkType":"invoke_entity",                               "Target":"requested_run\/show\/"},\n "Run Count":    {"LinkType":"invoke_entity","WhichArg":"Request Name Code","Target":"requested_run\/report\/-\/-\/~active\/-\/-\/~"}\n}','\n',char(10)));
INSERT INTO utility_queries VALUES(24,'dms_emsl_inst','DMS To EMSL Instrument Mapping','',replace('T_Instrument_Name TIN \nINNER JOIN T_EMSL_DMS_Instrument_Mapping TEDIM ON TIN.Instrument_ID = TEDIM.DMS_Instrument_ID \nINNER JOIN T_EMSL_Instruments TEI ON TEDIM.EUS_Instrument_ID = TEI.EUS_Instrument_ID','\n',char(10)),replace('TIN.IN_name as DMS_Instrument_Name,\nTEI.EUS_Instrument_Name, \nTEI.EUS_Display_Name, \nTEI.Local_Category_Name','\n',char(10)),'',replace('{ "DMS_Instrument_Name":"CTx",\n"EUS_Instrument_Name":"CTx", "EUS_Display_Name":"CTx", "Local_Category_Name":"CTx" }','\n',char(10)),'');
INSERT INTO utility_queries VALUES(25,'instrument_tracked','EMSL To DMS Instrument','','V_Instrument_Tracked','*','','','');
INSERT INTO utility_queries VALUES(26,'run_planning_1','Run Planning','','V_Run_Planning_Report_1','*',NULL,NULL,NULL);
INSERT INTO utility_queries VALUES(27,'osm_package_requests','OSM Package Requested Runs','','(SELECT Item_ID AS id, OSM_Package_ID FROM S_V_OSM_Package_Items_Export WHERE  Item_Type = ''Requested_Runs'') TX','*','{"col":"id", "dir":"ASC"}','{"OSM_Package_ID":"EQn"}','');
INSERT INTO utility_queries VALUES(28,'osm_package_datasets','OSM Package Datasets','','(SELECT Item AS id, OSM_Package_ID FROM S_V_OSM_Package_Items_Export WHERE  Item_Type = ''Requested_Runs'') TX','*','{"col":"id", "dir":"ASC"}','{"OSM_Package_ID":"EQn"}','');
INSERT INTO utility_queries VALUES(29,'data_package_datasets','Data Package Datasets','','S_V_Data_Package_Datasets_Export','Dataset as id','{"col":"id", "dir":"ASC"}','{"Data_Package_ID":"EQn"}','');
INSERT INTO utility_queries VALUES(30,'batch_requests','Batch Requests','','T_Requested_Run','ID as id','{"col":"id", "dir":"ASC"}','{"RDS_BatchID":"EQn"}','');
INSERT INTO utility_queries VALUES(31,'data_package_list','Data Package List','package','(SELECT label, value FROM V_Data_Package_Picklist) TX','*','','{"label":"CTx"}','');
INSERT INTO utility_queries VALUES(32,'osm_package_list','OSM Package List','package','(SELECT label, value FROM V_OSM_Package_Picklist) TX','*','','{"label":"CTx"}','');
INSERT INTO utility_queries VALUES(33,'requested_run_batch_list','Requested Run Batch List','','(SELECT ID_with_Batch As label, ID AS value FROM V_Requested_Run_Batch_PickList) TX','*','','{"label":"CTx"}','');
INSERT INTO utility_queries VALUES(34,'helper_inst_name_dstype','Instrument Dataset Type Chooser','','V_Instrument_Allowed_Dataset_Type','*','{"col":"Dataset Count", "dir":"DESC"}','{"Instrument":"MTx"}','{"Dataset Type":{"LinkType":"update_opener"}}');
COMMIT;
