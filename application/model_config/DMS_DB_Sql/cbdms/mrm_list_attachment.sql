PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_MRM_List_Attachment_List_Report');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateAttachments');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_MRM_List_Attachment_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_cols','*, ''Download'' as Download');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_MRM_List_Attachment_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'AttachmentName','Name','text','80','128','','','','trim|max_length[128]');
INSERT INTO "form_fields" VALUES(2,'AttachmentDescription','Description','area','','','4','60','','trim|max_length[1024]');
INSERT INTO "form_fields" VALUES(3,'OwnerPRN','Owner','text','24','24','','','','trim|max_length[24]');
INSERT INTO "form_fields" VALUES(4,'Active','Active','text','8','8','','','Y','trim|max_length[8]');
INSERT INTO "form_fields" VALUES(5,'ID','ID','non-edit','','','','','0','trim');
INSERT INTO "form_fields" VALUES(6,'AttachmentType','Type','hidden','','','','','MRM Transition List','trim');
INSERT INTO "form_fields" VALUES(7,'FileName','FileName','text','80','128','','','','trim');
INSERT INTO "form_fields" VALUES(8,'Contents','File Contents','area','','','20','90','','trim|max_length[100000]');
CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );
INSERT INTO "form_field_options" VALUES(1,'OwnerPRN','default_function','GetUser()');
INSERT INTO "form_field_options" VALUES(2,'Contents','auto_format','None');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_id','ID','6','','ID','Equals','text','12','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','32','','Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_description','Description','32','','Description','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_owner','Owner','32','','Owner','ContainsText','text','128','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'Download','invoke_entity','ID','mrm_list_attachment/download','');
INSERT INTO "list_report_hotlinks" VALUES(2,'ID','invoke_entity','value','mrm_list_attachment/show/','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'ID','ID','int','output','','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(2,'AttachmentType','AttachmentType','varchar','input','24','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(3,'AttachmentName','AttachmentName','varchar','input','128','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(4,'AttachmentDescription','AttachmentDescription','varchar','input','1024','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(5,'OwnerPRN','OwnerPRN','varchar','input','24','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(6,'Active','Active','varchar','input','8','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(7,'Contents','Contents','text','input','2147483647','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(8,'FileName','FileName','varchar','input','128','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(9,'<local>','mode','varchar','input','12','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(10,'<local>','message','varchar','output','512','AddUpdateAttachments');
INSERT INTO "sproc_args" VALUES(11,'<local>','callingUser','varchar','input','128','AddUpdateAttachments');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Contents','markup','Contents','','valueCol','dl_contents',NULL);
COMMIT;
