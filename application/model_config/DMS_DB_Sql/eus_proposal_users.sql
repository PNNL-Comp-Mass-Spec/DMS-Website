PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_EUS_Proposal_Users_List_Report');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_eus_proposal_id','EUS Proposal ID','20','','EUS Proposal ID','ContainsText','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_eus_person_id','EUS Person ID','20','','EUS Person ID','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_name','Name','20','','Name','ContainsText','text','50','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_site_status','Site Status','20','','Site Status','ContainsText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'EUS Person ID','invoke_entity','value','eus_users/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'EUS Proposal ID','invoke_entity','value','/eus_proposals/show','');
COMMIT;
