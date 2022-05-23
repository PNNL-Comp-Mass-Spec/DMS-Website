﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('entry_sproc','AddNewInstrument');
INSERT INTO "general_params" VALUES('post_submission_link_tag','instrument');
INSERT INTO "general_params" VALUES('alternate_title_create','Create New Instrument');
INSERT INTO "general_params" VALUES('alternate_title_edit','Edit New Instrument');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'i_name','Instrument Name','text','24','24','','','VOrbi0x','trim|required|max_length[24]');
INSERT INTO "form_fields" VALUES(2,'i_class','Instrument Class','text','32','32','','','LTQ_FT','trim|required|max_length[32]');
INSERT INTO "form_fields" VALUES(3,'instrument_group','Instrument Group','text','50','64','','','Other','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(4,'i_method','Capture Method','text','10','10','','','secfso','trim|required|max_length[10]');
INSERT INTO "form_fields" VALUES(5,'i_room_num','Room Number','text','50','50','','','EMSL 14??','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(6,'i_description','Description','area','','','4','80','Description is required','trim|required|max_length[200]');
INSERT INTO "form_fields" VALUES(7,'usage','Usage','text','50','50','','','','trim');
INSERT INTO "form_fields" VALUES(8,'operations_role','Operations Role','text','50','50','','','Production','trim');
INSERT INTO "form_fields" VALUES(9,'percent_emsl_owned','Percent EMSL Owned','text','50','50','','','','trim');
INSERT INTO "form_fields" VALUES(10,'source_machine_name','Instrument Workstation Name','text','80','128','','','\\VOrbi0x.bionet\','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(11,'source_path','Instrument XFer Folder (UNC)','area','','','2','60','ProteomicsData\','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(12,'path','Storage Path','area','','','2','60','VOrbi0x\','trim|required|max_length[255]');
INSERT INTO "form_fields" VALUES(13,'vol_client','Storage Vol Client (remote)','text','80','128','','','\\proto-x\','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(14,'vol_server','Storage Vol Server (local)','text','80','128','','','F:\','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(15,'archive_path','Archive Path','text','50','50','','','/archive/dmsarch/VOrbi0x','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(16,'archive_server','Archive Server Name','text','50','50','','','agate.emsl.pnl.gov','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(17,'archive_note','Archive Note','text','50','50','','','VOrbi0x','trim|required|max_length[50]');
INSERT INTO "form_fields" VALUES(18,'auto_define_storage_path','Auto Define Storage Path','text','32','32','','','Y','trim|required|max_length[32]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'i_class','picker.replace','instrumentClassPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'i_method','picker.replace','captureMethodPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'operations_role','picker.replace','instrumentOpsRolePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'instrument_group','picker.replace','instrumentGroupPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'auto_define_storage_path','picker.replace','YNPickList','','',',','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'i_name','iName','varchar','input','24','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(2,'i_class','iClass','varchar','input','32','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(3,'i_method','iMethod','varchar','input','10','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(4,'i_room_num','iRoomNum','varchar','input','50','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(5,'i_description','iDescription','varchar','input','255','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(6,'source_machine_name','sourceMachineName','varchar','input','128','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(7,'source_path','sourcePath','varchar','input','255','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(8,'path','spPath','varchar','input','255','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(9,'vol_client','spVolClient','varchar','input','128','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(10,'vol_server','spVolServer','varchar','input','128','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(11,'archive_path','archivePath','varchar','input','50','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(12,'archive_server','archiveServer','varchar','input','50','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(13,'archive_note','archiveNote','varchar','input','50','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(14,'usage','Usage','varchar','input','50','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(15,'operations_role','OperationsRole','varchar','input','50','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(16,'instrument_group','InstrumentGroup','varchar','input','64','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(17,'percent_emsl_owned','PercentEMSLOwned','varchar','input','24','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(18,'<local>','message','varchar','output','512','AddNewInstrument');
INSERT INTO "sproc_args" VALUES(19,'auto_define_storage_path','AutoDefineStoragePath','varchar','input','32','AddNewInstrument');
COMMIT;
