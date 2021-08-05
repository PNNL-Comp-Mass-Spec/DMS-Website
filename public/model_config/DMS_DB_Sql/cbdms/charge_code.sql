﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Charge_Code_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Charge_Code_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Charge_Code');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','SortKey');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Charge_Code','invoke_entity','value','charge_code/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Usage_RequestedRun','invoke_entity','Charge_Code','requested_run/report/-/-/-/-/-/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(3,'Usage_SamplePrep','invoke_entity','Charge_Code','sample_prep_request/report/-/-/-/-/-/~','');
INSERT INTO "list_report_hotlinks" VALUES(4,'State','color_label','#Activation_State','','{"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_90","4":"clr_120","5":"clr_120"}');
INSERT INTO "list_report_hotlinks" VALUES(5,'Owner_PRN','invoke_entity','value','user/report/','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_charge_code','Code','20','','Charge_Code','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_wbs','WBS','20','','WBS','ContainsText','text','60','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_title','Title','20','','Title','ContainsText','text','30','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_subaccount','SubAcct','20','','SubAccount','ContainsText','text','60','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_owner_name','Owner','20','','Owner_Name','ContainsText','text','50','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'SubAccount','detail-report','SubAccount','charge_code/report/-/-/-/~','labelCol','dl_SubAccount',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'WBS','detail-report','WBS','charge_code/report/-/~','labelCol','dl_WBS',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'Title','detail-report','Title','charge_code/report/-/-/~','labelCol','dl_Title',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'Usage_RequestedRun','detail-report','Charge_Code','requested_run/report/-/-/-/-/-/-/-/-/~','labelCol','dl_Usage_RequestedRun',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(5,'Usage_SamplePrep','detail-report','Charge_Code','sample_prep_request/report/-/-/-/-/-/~','labelCol','dl_Usage_SamplePrep',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(6,'State','color_label','#WPActivationState','','valueCol','dl_State','{"3":"clr_90","4":"clr_120", "5":"clr_120","10":"clr_120"}');
INSERT INTO "detail_report_hotlinks" VALUES(7,'Owner_PRN','detail-report','Owner_PRN','user/report/','labelCol','dl_PRN','');
COMMIT;