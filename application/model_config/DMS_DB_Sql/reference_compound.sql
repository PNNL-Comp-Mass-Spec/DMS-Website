PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('base_table','T_Reference_Compound');
INSERT INTO "general_params" VALUES('list_report_data_table','V_Reference_Compound_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Reference_Compound_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Compound_ID');
INSERT INTO "general_params" VALUES('detail_report_data_id_type','integer');
INSERT INTO "general_params" VALUES('entry_page_data_table','V_Reference_Compound_Entry');
INSERT INTO "general_params" VALUES('entry_page_data_id_col','Compound_ID');
INSERT INTO "general_params" VALUES('entry_sproc','AddUpdateReferenceCompound');
INSERT INTO "general_params" VALUES('list_report_data_sort_col','ID');
INSERT INTO "general_params" VALUES('list_report_data_sort_dir','DESC');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(1,'pf_Name','Name','','','Name','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_PubChemID','PubChem ID','','','PubChem CID','Equals','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_Supplier','Supplier','','','Supplier','ContainsText','text','','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_Description','Description','','','Description','ContainsText','text','','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','reference_compound/show','');
INSERT INTO "list_report_hotlinks" VALUES(2,'Description','min_col_width','value','60','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text, "options" text );
INSERT INTO "detail_report_hotlinks" VALUES(1,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign','');
INSERT INTO "detail_report_hotlinks" VALUES(2,'Container','detail-report','Container','material_items/report/~','labelCol','dl_container','');
INSERT INTO "detail_report_hotlinks" VALUES(3,'+Container','detail-report','Container','material_move_items/report/~','valueCol','dl_container_move','');
INSERT INTO "detail_report_hotlinks" VALUES(4,'Location','detail-report','Location','material_items/report/-/','labelCol','dl_location','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'+Location','detail-report','Location','material_move_items/report/-/','valueCol','dl_location_move','');
INSERT INTO "detail_report_hotlinks" VALUES(6,'Organism','detail-report','Organism','organism/report/~','labelCol','','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO "sproc_args" VALUES(1,'Compound_ID','compoundID','int','input','12','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(2,'Compound_Name','compoundName','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(3,'Description','description','varchar','input','500','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(4,'Compound_Type_Name','compoundTypeName','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(5,'Organism_Name','organismName','varchar','input','128','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(6,'PubChem_CID','pubChemID','varchar','input','12','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(7,'Campaign','campaignName','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(8,'Container','containerName','varchar','input','128','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(9,'Wellplate_Name','wellplateName','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(10,'Well_Number','wellNumber','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(11,'Contact_PRN','contactPRN','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(12,'Supplier','supplier','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(13,'Product_ID','productId','varchar','input','128','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(14,'Purchase_Date','purchaseDate','varchar','input','30','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(15,'Purity','purity','varchar','input','64','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(16,'Purchase_Quantity','purchaseQuantity','varchar','input','128','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(17,'Mass','mass','varchar','input','30','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(18,'Modifications','modifications','varchar','input','500','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(19,'Active','active','varchar','input','3','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(20,'<local>','mode','varchar','input','12','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(21,'<local>','message','varchar','output','512','AddUpdateReferenceCompound');
INSERT INTO "sproc_args" VALUES(22,'<local>','callingUser','varchar','input','128','AddUpdateReferenceCompound');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO "form_fields" VALUES(1,'Compound_ID','Compound_ID','non-edit','','','','','','trim');
INSERT INTO "form_fields" VALUES(2,'Compound_Name','Compound Name','text','60','80','','','','trim|required|max_length[128]|name_space|min_length[6]');
INSERT INTO "form_fields" VALUES(3,'Description','Description','area','','','4','50','','trim|required|max_length[512]');
INSERT INTO "form_fields" VALUES(4,'Compound_Type_Name','Compound Type','text','60','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(5,'Organism_Name','Organism','text','60','80','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(6,'PubChem_CID','PubChem CID','text','20','80','','','','trim|numeric');
INSERT INTO "form_fields" VALUES(7,'Contact_PRN','Contact (usually PNNL Staff)','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(8,'Campaign','Campaign','text','60','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(9,'Container','Container','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(10,'Wellplate_Name','Wellplate','text','60','80','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(11,'Well_Number','Well Number','text','60','80','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(12,'Supplier','Supplier','text','60','80','','','','trim|required|max_length[64]');
INSERT INTO "form_fields" VALUES(13,'Product_ID','Product ID (Catalog #)','text','30','128','','','','trim|required|max_length[128]');
INSERT INTO "form_fields" VALUES(14,'Purchase_Date','Purchase Date','text','30','30','','','','trim|max_length[30]');
INSERT INTO "form_fields" VALUES(15,'Purity','Purity','text','30','64','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(16,'Purchase_Quantity','Purchase Quantity','text','30','128','','','','trim|max_length[64]');
INSERT INTO "form_fields" VALUES(17,'Mass','Mass','text','30','30','','','','trim|max_length[30]');
INSERT INTO "form_fields" VALUES(18,'Modifications','Modifications','area','','','4','50','','trim|max_length[500]');
INSERT INTO "form_fields" VALUES(19,'Active','Active','text','30','30','','','Yes','trim|max_length[3]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO "form_field_choosers" VALUES(1,'Contact_PRN','picker.replace','userPRNPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(2,'Campaign','list-report.helper','','helper_campaign/report','',',','');
INSERT INTO "form_field_choosers" VALUES(3,'Container','list-report.helper','','helper_material_container/report','',',','');
INSERT INTO "form_field_choosers" VALUES(4,'Purchase_Date','picker.prevDate','futureDatePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(5,'Active','picker.replace','yesNoPickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(6,'Compound_Type_Name','picker.replace','compoundTypePickList','','',',','');
INSERT INTO "form_field_choosers" VALUES(7,'Organism_Name','list-report.helper','','helper_organism/report','',',','');
COMMIT;
