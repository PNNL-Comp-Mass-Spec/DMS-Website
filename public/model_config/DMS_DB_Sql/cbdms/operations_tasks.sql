﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Operations_Tasks');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Operations_Tasks_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Operations_Tasks_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Operations_Tasks_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateOperationsTasks');
INSERT INTO "general_params" VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','operations_tasks/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Days_In_Queue','color_label','#Age_Bracket','','{"30":"clr_30","60":"clr_60","90":"clr_90","120":"clr_120"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(2,'Tab','Tab','varchar','input','64','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(3,'Requestor','Requestor','varchar','input','64','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(4,'RequestedPersonal','RequestedPersonal','varchar','input','256','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(5,'AssignedPersonal','AssignedPersonal','varchar','input','256','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(6,'Description','Description','varchar','input','5132','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(7,'Comments','Comments','varchar','input','2147483647','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(8,'WorkPackage','WorkPackage','varchar','input','32','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(9,'Status','Status','varchar','input','32','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(10,'Priority','Priority','varchar','input','32','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(11,'HoursSpent','HoursSpent','varchar','input','12','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(12,'<local>','mode','varchar','input','12','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(13,'<local>','message','varchar','output','512','AddUpdateOperationsTasks');
INSERT INTO "sproc_args" VALUES(14,'<local>','callingUser','varchar','input','128','AddUpdateOperationsTasks');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'ID',' ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(2,'Tab',' Tab','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(3,'Description',' Description','area','','','4','70','','trim|max_length[5132]');
INSERT INTO "form_fields" VALUES(4,'Requestor',' Requestor','text','50','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'RequestedPersonal',' Requested Personal','area','','','4','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(6,'AssignedPersonal',' Assigned Personal','area','','','4','70','','trim|max_length[256]');
INSERT INTO "form_fields" VALUES(7,'Comments',' Comments','area','','','4','70','','trim|max_length[2147483647]');
INSERT INTO "form_fields" VALUES(8,'HoursSpent',' Hours Spent','text','12','12','','','','trim|max_length[12]');
INSERT INTO "form_fields" VALUES(9,'Status',' Status','text','32','32','','','New','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(10,'Priority',' Priority','text','32','32','','','Normal','trim|max_length[32]');
INSERT INTO "form_fields" VALUES(11,'WorkPackage',' Work Package','text','32','32','','','','trim|max_length[32]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'Requestor','default_function','GetUser()');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_tab','Tab','20','','Tab','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_description','Description','20','','Description','ContainsText','text','5132','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_comments','Comments','20','','Comments','ContainsText','text','2147483647','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_status','Status','20','','Status','ContainsText','text','32','','');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Status','picker.replace','operationsTaskState','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Priority','picker.replace','operationsTaskPriority','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'RequestedPersonal','picker.replace','operationsTaskStaff','','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'AssignedPersonal','picker.replace','operationsTaskStaff','','',',','');
COMMIT;