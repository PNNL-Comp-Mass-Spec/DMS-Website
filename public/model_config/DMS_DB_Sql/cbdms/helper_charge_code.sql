PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Helper_Charge_Code');
INSERT INTO "general_params" VALUES('list_report_helper_multiple_selection','no');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','SortKey');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','ASC');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Charge_Code','update_opener','value','','');
INSERT INTO "list_report_hotlinks" VALUES(2,'State','color_label','#activation_state','','{"0":"clr_30","1":"clr_45","2":"clr_60",
"3":"clr_120","4":"clr_120","5":"clr_120"}');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_charge_code','Charge_Code','20','','Charge_Code','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_wbs','WBS','20','','WBS','ContainsText','text','60','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_title','Title','20','','Title','ContainsText','text','30','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_subaccount','SubAccount','20','','SubAccount','ContainsText','text','60','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_owner_name','Owner','20','','Owner_Name','ContainsText','text','50','','');
COMMIT;
