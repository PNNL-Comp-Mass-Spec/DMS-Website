﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_helper_charge_code');
INSERT INTO general_params VALUES('list_report_helper_multiple_selection','no');
INSERT INTO general_params VALUES('list_report_data_sort_col','sort_key');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'charge_code','update_opener','value','','');
INSERT INTO list_report_hotlinks VALUES(2,'state','color_label','activation_state','',replace('{"0":"clr_30","1":"clr_45","2":"clr_60",\n"3":"clr_120","4":"clr_120","5":"clr_120"}','\n',char(10)));
INSERT INTO list_report_hotlinks VALUES(3,'activation_state','no_display','value','','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_charge_code','Charge_Code','20','','charge_code','ContainsText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_wbs','WBS','20','','wbs','ContainsText','text','60','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_title','Title','20','','title','ContainsText','text','30','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_subaccount','SubAccount','20','','sub_account','ContainsText','text','60','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_owner_name','Owner','20','','owner_name','ContainsText','text','50','','');
COMMIT;
