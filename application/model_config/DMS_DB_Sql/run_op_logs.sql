PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Ops_Logs_List_Report');
INSERT INTO "general_params" VALUES('operations_sproc','Junk_GRK_Test');
INSERT INTO "general_params" VALUES('update_sproc','UpdateRunOpLog');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_instrument','Instrument','20','','Instrument','ContainsText','text','24','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_year','Year','20','','Year','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_month','Month','20','','Month','Equals','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_day','Day','20','','Day','Equals','text','20','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','ID','run_interval/edit','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Log','select_case','Type','','{ "Operation":"instrument_operation_history", "Configuration":"instrument_config_history" }');
INSERT INTO "list_report_hotlinks" VALUES(4,'Request','row_to_json','Request','run_op_logs/call/operations_sproc','{ "rowAction":"omicron.update", "fields":"Usage, Proposal, EMSL User" }');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'id_fld','id_fld','varchar','input','32','Junk_GRK_Test');
INSERT INTO "sproc_args" VALUES(2,'usage_fld','usage_fld','varchar','input','128','Junk_GRK_Test');
INSERT INTO "sproc_args" VALUES(3,'proposal_fld','proposal_fld','varchar','input','256','Junk_GRK_Test');
INSERT INTO "sproc_args" VALUES(4,'emsl_user_fld','emsl_user_fld','varchar','input','256','Junk_GRK_Test');
INSERT INTO "sproc_args" VALUES(5,'<local>','message','varchar','output','512','Junk_GRK_Test');
INSERT INTO "sproc_args" VALUES(6,'<local>','callingUser','varchar','input','128','Junk_GRK_Test');
INSERT INTO "sproc_args" VALUES(7,'changes','changes','text','input','2147483647','UpdateRunOpLog');
INSERT INTO "sproc_args" VALUES(8,'<local>','message','varchar','output','512','UpdateRunOpLog');
INSERT INTO "sproc_args" VALUES(9,'<local>','callingUser','varchar','input','128','UpdateRunOpLog');
COMMIT;
