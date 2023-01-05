﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_instrument_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_instrument_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateInstrument');
INSERT INTO general_params VALUES('entry_page_data_table','v_instrument_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','0','trim');
INSERT INTO form_fields VALUES(2,'instrument_name','InstrumentName','non-edit','','','','','To add an instrument, use https://dms2.pnl.gov/new_instrument/create','trim');
INSERT INTO form_fields VALUES(3,'description','Description','area','50','255','4','60','','trim');
INSERT INTO form_fields VALUES(4,'instrument_class','InstrumentClass','text','32','32','','','LTQ_FT','trim');
INSERT INTO form_fields VALUES(5,'instrument_group','InstrumentGroup','text','32','32','','','Other','trim');
INSERT INTO form_fields VALUES(6,'room_number','RoomNumber','text','50','50','','','','trim');
INSERT INTO form_fields VALUES(7,'capture_method','CaptureMethod','text','10','10','','','fso','trim');
INSERT INTO form_fields VALUES(8,'status','Status','text','8','8','','','active','trim');
INSERT INTO form_fields VALUES(9,'usage','Usage','text','50','50','','','','trim');
INSERT INTO form_fields VALUES(10,'operations_role','OperationsRole','text','50','50','','','Production','trim');
INSERT INTO form_fields VALUES(11,'track_usage_when_inactive','Track Usage When Inactive','text','32','32','','','Y','trim|required|max_length[12]');
INSERT INTO form_fields VALUES(12,'scan_source_dir','Scan Source Directory','text','32','32','','','Y','trim|required|max_length[12]');
INSERT INTO form_fields VALUES(13,'percent_emsl_owned','Percent EMSL Owned','text','50','50','','','','trim');
INSERT INTO form_fields VALUES(14,'auto_define_storage_path','Auto Define Storage Path','text','32','32','','','Y','trim|required|max_length[12]');
INSERT INTO form_fields VALUES(15,'auto_sp_vol_name_client','Auto Storage VolNameClient','text','50','128','','','','trim');
INSERT INTO form_fields VALUES(16,'auto_sp_vol_name_server','Auto Storage VolNameServer','text','50','128','','','','trim');
INSERT INTO form_fields VALUES(17,'auto_sp_path_root','Auto Storage Path Root','text','50','128','','','','trim');
INSERT INTO form_fields VALUES(18,'auto_sp_url_domain','Auto Storage URL Domain','text','50','64','','','pnl.gov','trim');
INSERT INTO form_fields VALUES(19,'auto_sp_archive_server_name','Auto Storage Archive Server Name','text','50','64','','','','trim');
INSERT INTO form_fields VALUES(20,'auto_sp_archive_path_root','Auto Storage Archive Path Root','text','64','128','','','','trim');
INSERT INTO form_fields VALUES(21,'auto_sp_archive_share_path_root','Auto Storage Archive Share Path Root','text','64','128','','','','trim');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'instrument_class','picker.replace','instrumentClassPickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'capture_method','picker.replace','captureMethodPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'status','picker.replace','instrumentStatusPickList','','',',','');
INSERT INTO form_field_choosers VALUES(4,'operations_role','picker.replace','instrumentOpsRolePickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'instrument_group','picker.replace','instrumentGroupPickList','','',',','');
INSERT INTO form_field_choosers VALUES(6,'auto_define_storage_path','picker.replace','YNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(7,'scan_source_dir','picker.replace','YNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(8,'track_usage_when_inactive','picker.replace','YNPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','15!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_class','Class','32','','class','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_group','Group','32','','group','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_ops_role','Ops Role','32','','ops_role','ContainsText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_status','Status','32','','status','StartsWithText','text','64','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_assigned_storage','Assigned Storage','32','','assigned_storage','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_assigned_source','Assigned Source','32','','assigned_source','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','instrumentid/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'allowed_dataset_types','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(3,'group','invoke_entity','value','instrument_group/show/','');
INSERT INTO list_report_hotlinks VALUES(4,'class','invoke_entity','value','instrumentclass/show/','');
INSERT INTO list_report_hotlinks VALUES(5,'name','invoke_entity','value','helper_inst_source/view/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'id','instrumentID','int','output','','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(2,'instrument_name','instrumentName','varchar','input','24','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(3,'instrument_class','instrumentClass','varchar','input','32','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(4,'instrument_group','instrumentGroup','varchar','intput','64','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(5,'capture_method','captureMethod','varchar','input','10','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(6,'status','status','varchar','input','8','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(7,'room_number','roomNumber','varchar','input','50','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(8,'description','description','varchar','input','2550','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(9,'usage','usage','varchar','input','50','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(10,'operations_role','operationsRole','varchar','input','50','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(11,'track_usage_when_inactive','trackUsageWhenInactive','varchar','input','12','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(12,'scan_source_dir','scanSourceDir','varchar','input','32','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(13,'percent_emsl_owned','percentEMSLOwned','varchar','input','24','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(14,'<local>','mode','varchar','input','12','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(15,'<local>','message','varchar','output','512','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(16,'auto_define_storage_path','autoDefineStoragePath','varchar','input','32','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(17,'auto_sp_vol_name_client','autoSPVolNameClient','varchar','input','128','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(18,'auto_sp_vol_name_server','autoSPVolNameServer','varchar','input','128','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(19,'auto_sp_path_root','autoSPPathRoot','varchar','input','128','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(20,'auto_sp_url_domain','autoSPUrlDomain','varchar','input','64','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(21,'auto_sp_archive_server_name','autoSPArchiveServerName','varchar','input','64','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(22,'auto_sp_archive_path_root','autoSPArchivePathRoot','varchar','input','128','AddUpdateInstrument');
INSERT INTO sproc_args VALUES(23,'auto_sp_archive_share_path_root','autoSPArchiveSharePathRoot','varchar','input','128','AddUpdateInstrument');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'allowed_dataset_types','detail-report','instrument_group','instrument_allowed_dataset_type/report','labelCol','dl_instrument','');
INSERT INTO detail_report_hotlinks VALUES(2,'name','detail-report','name','helper_inst_source/view/','valueCol','dl_name_inst_source','');
INSERT INTO detail_report_hotlinks VALUES(3,'instrument_group','detail-report','instrument_group','instrument_group/show/','labelCol','dl_instrument_group','');
INSERT INTO detail_report_hotlinks VALUES(4,'assigned_archive_path','detail-report','name','archive_path/report','labelCol','dl_archive_path','');
INSERT INTO detail_report_hotlinks VALUES(5,'assigned_storage','detail-report','name','storage/report/-/~','valueCol','dl_assigned_storage','');
INSERT INTO detail_report_hotlinks VALUES(6,'archive_share_path','href-folder','archive_share_path','','labelCol','dl_archive_share_path','');
INSERT INTO detail_report_hotlinks VALUES(7,'class','detail-report','class','instrumentclass/show/','labelCol','dl_instrument_class','');
INSERT INTO detail_report_hotlinks VALUES(8,'+name','detail-report','name','instrument_config_history/report','labelCol','dl_name_config_history','');
INSERT INTO detail_report_hotlinks VALUES(9,'allocation_tag','detail-report','instrument_group','instrument_group/show/','labelCol','dl_allocation_tag','');
INSERT INTO detail_report_hotlinks VALUES(10,'id','detail-report','id','instrumentid/show/','labelCol','dl_instrumentid','');
INSERT INTO detail_report_hotlinks VALUES(11,'storage_path_id','detail-report','storage_path_id','storage/show/','valueCol','dl_storage_path_id','');
INSERT INTO detail_report_hotlinks VALUES(12,'source_path_id','detail-report','source_path_id','storage/show/','valueCol','dl_source_path_id','');
COMMIT;
