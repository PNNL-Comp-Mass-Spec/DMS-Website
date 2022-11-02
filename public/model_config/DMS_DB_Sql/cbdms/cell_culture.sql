﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','V_Cell_Culture_List_Report_2');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','V_Cell_Culture_Detail_Report');
INSERT INTO general_params VALUES('detail_report_data_id_col','Name');
INSERT INTO general_params VALUES('entry_sproc','AddUpdateCellCulture');
INSERT INTO general_params VALUES('entry_page_data_table','v_cell_culture_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','name');
INSERT INTO general_params VALUES('detail_report_aux_info_target','Biomaterial');
INSERT INTO general_params VALUES('operations_sproc','DoMaterialItemOperation');
INSERT INTO general_params VALUES('post_submission_detail_id','name');
INSERT INTO general_params VALUES('alternate_title_report','Biomaterial (Cell Culture) Report');
INSERT INTO general_params VALUES('alternate_title_search','Biomaterial (Cell Culture) Search');
INSERT INTO general_params VALUES('alternate_title_show','Biomaterial (Cell Culture) Detail Report');
INSERT INTO general_params VALUES('alternate_title_create','Create New Biomaterial (Cell Culture)');
INSERT INTO general_params VALUES('alternate_title_edit','Edit Biomaterial (Cell Culture)');
INSERT INTO general_params VALUES('alternate_title_param','Biomaterial (Cell Culture)');
INSERT INTO general_params VALUES('alternate_title_export','Biomaterial (Cell Culture)');
INSERT INTO general_params VALUES('detail_report_cmds','file_attachment_cmds');
INSERT INTO general_params VALUES('list_report_data_cols','ID, Name, Source, Contact, Type, Created, PI, Comment, Campaign, Organisms, [Material Status]');
INSERT INTO general_params VALUES('detail_report_data_cols','Name, Supplier, [Contact (usually PNNL Staff)], Type, Comment, Campaign, ID, Organism_List');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'name','Name','text-if-new','60','80','','','','trim|required|max_length[64]|alpha_dash|min_length[8]');
INSERT INTO form_fields VALUES(2,'culture_type_name','Type','text','60','80','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(3,'source_name','Supplier','text','60','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(4,'contact_prn','Contact (usually PNNL Staff)','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(5,'reason','Reason for creation','area','','','4','50','','trim|required|max_length[500]');
INSERT INTO form_fields VALUES(6,'campaign','Campaign','text','60','80','','','','trim|required|max_length[64]');
INSERT INTO form_fields VALUES(7,'pi_prn','Principle Investigator (PRN)','text','60','80','','','','trim|required|max_length[32]');
INSERT INTO form_fields VALUES(8,'container','Container','text','60','128','','','','trim|required|max_length[128]');
INSERT INTO form_fields VALUES(9,'comment','Comment','area','','','4','50','','trim|max_length[255]');
INSERT INTO form_fields VALUES(10,'organism_list','Organism List','area','','','6','50','','trim');
INSERT INTO form_fields VALUES(11,'mutation','Mutation','text','60','80','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(12,'plasmid','Plasmid','text','60','80','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(13,'cell_line','Cell Line','text','60','80','','','','trim|max_length[64]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(1,'culture_type_name','picker.replace','biomaterialTypePickList','','',',','');
INSERT INTO form_field_choosers VALUES(2,'contact_prn','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(3,'campaign','list-report.helper','','helper_campaign/report/Active/','campaign',',','');
INSERT INTO form_field_choosers VALUES(4,'pi_prn','picker.replace','userPRNPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'container','list-report.helper','','helper_material_container/report','',',','');
INSERT INTO form_field_choosers VALUES(7,'organism_list','picker.append','orgPickList','','',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','20!','','Name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_campaign','Campaign','20!','','Campaign','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_source','Source','32','','Source','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_contact','Contact','32','','Contact','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_pi','PI','32','','PI','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(6,'pf_comment','Comment','32','','Comment','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(7,'pf_organism','Organism(s)','30!','','Organisms','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(8,'pf_id','ID','20','','ID','Equals','text','24','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'Name','invoke_entity','value','cell_culture/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'Reason','min_col_width','value','60','');
INSERT INTO list_report_hotlinks VALUES(3,'Comment','min_col_width','value','60','');
CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );
INSERT INTO detail_report_commands VALUES(1,'Retire Biomaterial','cmd_op','retire_biomaterial','cell_culture','Make this biomaterial inactive and move it to null container','Are you sure that you want to retire this biomaterial?');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'Campaign','detail-report','Campaign','campaign/show','labelCol','campaign',NULL);
INSERT INTO detail_report_hotlinks VALUES(2,'Container','detail-report','Container','material_items/report/~','labelCol','dl_container','');
INSERT INTO detail_report_hotlinks VALUES(3,'+Container','detail-report','Container','material_move_items/report/~','valueCol','dl_container_move','');
INSERT INTO detail_report_hotlinks VALUES(4,'Location','detail-report','Location','material_items/report/-/','labelCol','dl_location','');
INSERT INTO detail_report_hotlinks VALUES(5,'+Location','detail-report','Location','material_move_items/report/-/','valueCol','dl_location_move','');
INSERT INTO detail_report_hotlinks VALUES(6,'Organism_List','link_table','Organism_List','organism/report/~@','valueCol','dl_organism_list','');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'name','cellCultureName','varchar','input','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(2,'source_name','sourceName','varchar','input','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(3,'contact_prn','contactPRN','varchar','input','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(4,'pi_prn','piPRN','varchar','input','32','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(5,'culture_type_name','cultureType','varchar','input','32','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(6,'reason','reason','varchar','input','500','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(7,'comment','comment','varchar','input','500','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(8,'campaign','campaignNum','varchar','input','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(9,'<local>','mode','varchar','input','12','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(10,'<local>','message','varchar','output','512','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(11,'container','container','varchar','input','128','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(12,'organism_list','organismList','varchar','input','8000','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(13,'mutation','mutation','varchar','input','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(14,'plasmid','plasmid','varchar','input','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(15,'cell_line','cellLine','varchar','varchar','64','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(16,'ID','name','varchar','input','128','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(17,'<local>','callingUser','varchar','input','128','AddUpdateCellCulture');
INSERT INTO sproc_args VALUES(18,'<local>','mode','varchar','input','32','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(19,'<local>','message','varchar','output','512','DoMaterialItemOperation');
INSERT INTO sproc_args VALUES(20,'<local>','callingUser','varchar','input','128','DoMaterialItemOperation');
CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );
INSERT INTO external_sources VALUES(1,'sample_submission','campaign','ColName','Campaign');
INSERT INTO external_sources VALUES(2,'sample_submission','container','ColName','Container List');
INSERT INTO external_sources VALUES(3,'sample_submission','contact_prn','ColName','Received By');
INSERT INTO external_sources VALUES(4,'sample_submission','reason','ColName','Description');
COMMIT;
