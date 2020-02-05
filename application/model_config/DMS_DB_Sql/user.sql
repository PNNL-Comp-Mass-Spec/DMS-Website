﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_User_List_Report_2');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_User_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Username');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateUser');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_User_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Username');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Username','Username','text-if-new','20','50','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(2,'HanfordIDNum','Hanford ID','text','20','50','','','','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(3,'LastNameFirstName','Last Name, First Name','text','50','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(4,'EntryNote','Entry Note','non-edit','','','','','Last Name/First Name, Email, and Payroll are auto-updated when "User Update" = Y','');
INSERT INTO "form_fields" VALUES(5,'Email','Email','text','50','64','','','','trim');
INSERT INTO "form_fields" VALUES(6,'Payroll','Payroll','text','20','10','','','','trim');
INSERT INTO "form_fields" VALUES(7,'UserStatus','User Status','text','24','24','','','Active','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(8,'UserUpdate','User Update','text','1','1','','','Y','trim|max_length[1]');
INSERT INTO "form_fields" VALUES(9,'OperationsList','Operations List','area','','','4','60','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(10,'Comment','Comment','area','','','2','60','','trim|max_length[512]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'UserStatus','picker.replace','userStatusPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'UserUpdate','picker.replace','YNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'OperationsList','picker.append','userOperationsPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_username','Username','7!','','Username','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','20!','','Name','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_status','Status','6!','','Status','StartsWithText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_hanford_id','Hanford ID','8!','','Hanford ID','ContainsText','text','64','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_operations_list','Operations','15!','','Operations List','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_eus_ID','EUS ID','6!','','EUS_ID','Equals','text','12','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Username','invoke_entity','value','user/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'EUS_ID','invoke_entity','value','eus_users/report/','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Created_DMS','format_date','value','','{"Format":"Y-m-d"}');
INSERT INTO "list_report_hotlinks" VALUES(4,'Operations List','link_list','value','user_operation/report','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Username','Username','varchar','input','50','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(2,'HanfordIDNum','HanfordIDNum','varchar','input','50','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(3,'Payroll','Payroll','varchar','input','32','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(4,'LastNameFirstName','LastNameFirstName','varchar','input','128','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(5,'Email','Email','varchar','intput','64','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(6,'UserStatus','UserStatus','varchar','input','24','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(7,'UserUpdate','UserUpdate','varchar','input','1','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(8,'OperationsList','OperationsList','varchar','input','1024','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(9,'Comment','Comment','varchar','input','512','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(10,'<local>','mode','varchar','input','12','AddUpdateUser');
INSERT INTO "sproc_args" VALUES(11,'<local>','message','varchar','output','512','AddUpdateUser');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Operations List','link_list','Operations List','user_operation/report/','valueCol','dl_Operations_List','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'EUS_Person_ID','detail-report','EUS_Person_ID','eus_users/report/','valueCol','dl_EUS_Person_ID',NULL);
COMMIT;
