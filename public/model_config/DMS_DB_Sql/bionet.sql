﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('base_table','T_Bionet_Hosts');
INSERT INTO general_params VALUES('list_report_data_table','V_Bionet_List_Report');
INSERT INTO general_params VALUES('detail_report_data_table','V_Bionet_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Host');
INSERT INTO general_params VALUES('entry_page_data_table','T_Bionet_Hosts');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateBionetHost');
INSERT INTO general_params VALUES('entry_page_data_id_col','Host');
INSERT INTO general_params VALUES('post_submission_detail_id','Host');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Host','invoke_entity','Host','bionet/show','');
INSERT INTO list_report_hotlinks VALUES(2,'Instruments','link_list','Instruments','helper_inst_source/view','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_host','Host','20','','Host','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_ip','IP','20','','IP','ContainsText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_alias','Alias','20','','Alias','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_tag','Tag','20','','Tag','ContainsText','text','20','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_instruments','Instruments','20','','Instruments','ContainsText','text','64','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Instruments','link_table','Instruments','instrument/report','valueCol','dl_Instruments',NULL);
INSERT INTO detail_report_hotlinks VALUES(4,'Instrument Datasets','link_table','Instrument Datasets','helper_inst_source/view/','valueCol','dl_InstrumentDatasets',NULL);
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'Host','Host','text-if-new','50','64','','','','trim');
INSERT INTO form_fields VALUES(2,'IP','IP','text','15','15','','','','trim|max_length[15]');
INSERT INTO form_fields VALUES(3,'Alias','Alias','text','50','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(4,'Tag','Tag','text','24','24','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(5,'Instruments','Instruments','area','','','2','40','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(6,'Comment','Comment','area','','','4','70','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(7,'Active','Active','text','12','12','','','','trim|max_length[12]');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'Host','host','varchar','input','64','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(2,'IP','ip','varchar','input','15','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(3,'Alias','alias','varchar','input','64','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(4,'Tag','tag','varchar','input','24','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(5,'Instruments','instruments','varchar','input','1024','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(6,'Comment','comment','varchar','intput','1024','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(7,'Active','active','tinyint','input','','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(8,'<local>','mode','varchar','input','12','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(9,'<local>','message','varchar','output','512','AddUpdateBionetHost');
INSERT INTO sproc_args VALUES(10,'<local>','callingUser','varchar','input','128','AddUpdateBionetHost');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'Active','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'Instruments','picker.append','instrumentNameAdminPickList','','',',','');
COMMIT;
