﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_sproc','GetDatasetStatsByCampaign');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Campaign','invoke_entity','value','campaign/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'Work Package','invoke_entity','value','requested_run/report/-/-/-/-/-/-/-/-/@','');
INSERT INTO list_report_hotlinks VALUES(3,'Request Min','invoke_entity','value','requested_run/show/','');
INSERT INTO list_report_hotlinks VALUES(4,'Request Max','invoke_entity','value','requested_run/show/','');
INSERT INTO list_report_hotlinks VALUES(5,'Instrument','invoke_entity','value','requested_run/report/-/-/-/-/-/-/@','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'most_recent_weeks','mostRecentWeeks','int','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(2,'start_date','startDate','datetime','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(3,'end_date','endDate','datetime','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(4,'include_instrument','includeInstrument','tinyint','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(5,'exclude_qcand_blank_without_wp','excludeQCAndBlankWithoutWP','tinyint','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(6,'exclude_all_qcand_blank','excludeAllQCAndBlank','tinyint','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(7,'campaign_name_filter','campaignNameFilter','varchar','input','128','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(8,'campaign_name_exclude','campaignNameExclude','varchar','input','128','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(9,'instrument_building','instrumentBuilding','varchar','input','64','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(10,'preview_sql','previewSql','tinyint','input','','GetDatasetStatsByCampaign');
INSERT INTO sproc_args VALUES(11,'message','message','varchar','output','512','GetDatasetStatsByCampaign');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'campaign_name_filter','Campaign Name Filter','text','24','128','','','EMSL','trim|max_length[128]');
INSERT INTO form_fields VALUES(2,'campaign_name_exclude','Campaign Exclusion Filter','text','24','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'instrument_building','Instrument Building','text','10','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(4,'most_recent_weeks','Most Recent Weeks','text','10','','','','16','trim|Numeric');
INSERT INTO form_fields VALUES(5,'start_date','Start Date','text','10','64','','','','trim|Date');
INSERT INTO form_fields VALUES(6,'end_date','End Date','text','10','64','','','','trim|Date');
INSERT INTO form_fields VALUES(7,'include_instrument','Include Instrument?','text','10','32','','','','trim|Numeric');
INSERT INTO form_fields VALUES(8,'exclude_qcand_blank_without_wp','Exclude QC/Blank Without WP','text','10','32','','','1','trim|Numeric');
INSERT INTO form_fields VALUES(9,'exclude_all_qcand_blank','Exclude All QC/Blank','text','10','32','','','0','trim|Numeric');
INSERT INTO form_fields VALUES(10,'preview_sql','Preview SQL','hidden','','','','','','trim');
INSERT INTO form_fields VALUES(11,'message','Message','hidden','','','','','','trim');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'start_date','picker.prevDate','','','',',','Start Date');
INSERT INTO form_field_choosers VALUES(2,'end_date','picker.prevDate','','','',',','End Date');
INSERT INTO form_field_choosers VALUES(3,'include_instrument','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'campaign_name_filter','list-report.helper','	','helper_campaign/report/Active/','campaign_name_filter',',','');
INSERT INTO form_field_choosers VALUES(5,'campaign_name_exclude','list-report.helper','','helper_campaign/report/Active/','campaign_name_filter',',','');
INSERT INTO form_field_choosers VALUES(6,'exclude_qcand_blank_without_wp','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(7,'exclude_all_qcand_blank','picker.replace','yesNoAsOneZeroPickList','','',',','');
COMMIT;
