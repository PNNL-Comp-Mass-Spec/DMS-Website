﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_analysis_job_processor_group_membership_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_col','id');
INSERT INTO general_params VALUES('list_report_data_sort_dir','ASC');
INSERT INTO general_params VALUES('list_report_cmds','analysis_job_processor_group_membership_cmds');
INSERT INTO general_params VALUES('list_report_cmds_url','/analysis_job_processor_group_membership/operation');
INSERT INTO general_params VALUES('list_report_data_cols',''''' AS Sel, *');
INSERT INTO general_params VALUES('operations_sproc','update_analysis_job_processor_group_membership');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_groupid','Group_ID','10','','group_id','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_name','Name','10','','name','ContainsText','text','64','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'sel','CHECKBOX','name','','');
INSERT INTO list_report_hotlinks VALUES(2,'group_id','no_display','value','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'processorNameList','processorNameList','varchar','input','6000','update_analysis_job_processor_group_membership');
INSERT INTO sproc_args VALUES(2,'processorGroupID','processorGroupID','varchar','input','32','update_analysis_job_processor_group_membership');
INSERT INTO sproc_args VALUES(3,'newValue','newValue','varchar','input','64','update_analysis_job_processor_group_membership');
INSERT INTO sproc_args VALUES(4,'<local>','mode','varchar','input','32','update_analysis_job_processor_group_membership');
INSERT INTO sproc_args VALUES(5,'<local>','message','varchar','output','512','update_analysis_job_processor_group_membership');
INSERT INTO sproc_args VALUES(6,'<local>','callingUser','varchar','input','128','update_analysis_job_processor_group_membership');
COMMIT;
