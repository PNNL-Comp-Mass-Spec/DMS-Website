﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Material_Containers_List_Report');
INSERT INTO general_params VALUES('list_report_data_sort_col','Created');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','V_Material_Containers_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Container');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateMaterialContainer');
INSERT INTO general_params VALUES('entry_page_data_table','v_material_containers_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','container');
INSERT INTO general_params VALUES('list_report_data_cols','*');
INSERT INTO general_params VALUES('operations_sproc','DoMaterialContainerOperation');
INSERT INTO general_params VALUES('post_submission_detail_id','container');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'container','Container','non-edit','','','','','(generate name)','trim|max_length[128]');
INSERT INTO form_fields VALUES(2,'type','Type','text','32','32','','','Box','trim|max_length[32]');
INSERT INTO form_fields VALUES(3,'location','Location','text','24','24','','','','trim|max_length[24]');
INSERT INTO form_fields VALUES(4,'researcher','Researcher','text','50','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(5,'comment','Comment','area','','','4','60','','trim|max_length[1024]');
INSERT INTO form_fields VALUES(6,'barcode','Barcode','text','32','32','','','','trim|max_length[32]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'type','picker.replace','containerTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'location','list-report.helper','','helper_material_location/report','',',','');
INSERT INTO form_field_choosers VALUES(3,'researcher','picker.replace','userNamePickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_container','Container','10','','Container','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_location','Location','15!','','Location','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_type','Type','4','','Type','ContainsText','text','10','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_comment','Comment','15','','Comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_status','Status','4','','Status','StartsWithText','text','10','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_campaigns','Campaigns','15!','','Campaigns','ContainsText','text','1024','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Container','invoke_entity','value','material_container/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'Items','invoke_entity','Container','material_items/report/~@','');
INSERT INTO list_report_hotlinks VALUES(3,'Action','invoke_entity','Container','biomaterial/create/init/-/-/-/-/-/-/-/','');
INSERT INTO list_report_hotlinks VALUES(4,'Location','invoke_entity','Location','material_location/report/~@','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Retire this container','cmd_op','retire_container','material_container','Make this container inactive and move it to null location','Are you sure that you want to retire this container?');
INSERT INTO detail_report_commands VALUES(2,'Retire this container (and its contents)','cmd_op','retire_container_and_contents','material_container','Make this container inactive and move it to null location, and also the material items it contains','Are you sure that you want to retire this container and all its contents?');
INSERT INTO detail_report_commands VALUES(3,'Unretire this container (make active)','cmd_op','unretire_container','material_container','Make this container active again','Are you sure that you want to make this container active again?');
INSERT INTO detail_report_commands VALUES(4,'New Biomaterial','call','create/init/-/-/-/-/-/-/-','biomaterial','Create new biomaterial item in this container','');
INSERT INTO detail_report_commands VALUES(5,'New Experiment','call','create/init/-/-/-/-/-/-/-/-/-/-/-/-/-/-','experiment','Create new experiment  in this container','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Items','detail-report','Container','material_items/report/~','labelCol','dh_items','');
INSERT INTO detail_report_hotlinks VALUES(2,'+Items','detail-report','Container','material_move_items/report/~','valueCol','dh_items_move','');
INSERT INTO detail_report_hotlinks VALUES(3,'Location','detail-report','Location','material_location/report/~','labelCol','dh_Location','');
INSERT INTO detail_report_hotlinks VALUES(4,'+Location','detail-report','Location','material_move_container/report/-/~','valueCol','dh_Location_move','');
INSERT INTO detail_report_hotlinks VALUES(5,'Container','detail-report','Container','material_move_container/report/~','valueCol','dh_Container','');
INSERT INTO detail_report_hotlinks VALUES(6,'Freezer','detail-report','Freezer','freezers/report/','labelCol','dh_Freezer','');
INSERT INTO detail_report_hotlinks VALUES(7,'Status','detail-report','Container','material_log/report/-/~','labelCol','dh_log1','');
INSERT INTO detail_report_hotlinks VALUES(8,'+Status','detail-report','Container','material_log/report/-/-/-/~','valueCol','dh_log2','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(9,'ID','name','varchar','input','128','DoMaterialContainerOperation');
INSERT INTO sproc_args VALUES(10,'<local>','mode','varchar','input','32','DoMaterialContainerOperation');
INSERT INTO sproc_args VALUES(11,'<local>','message','varchar','output','512','DoMaterialContainerOperation');
INSERT INTO sproc_args VALUES(12,'<local>','callingUser','varchar','input','128','DoMaterialContainerOperation');
INSERT INTO sproc_args VALUES(13,'container','Container','varchar','output','128','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(14,'type','Type','varchar','input','32','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(15,'location','Location','varchar','input','24','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(16,'comment','Comment','varchar','input','1024','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(17,'barcode','Barcode','varchar','input','32','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(18,'researcher','Researcher','varchar','input','128','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(19,'<local>','mode','varchar','input','12','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(20,'<local>','message','varchar','output','512','AddUpdateMaterialContainer');
INSERT INTO sproc_args VALUES(21,'<local>','callingUser','varchar','input','128','AddUpdateMaterialContainer');
COMMIT;
