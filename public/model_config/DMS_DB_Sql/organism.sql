﻿PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO general_params VALUES('list_report_data_table','v_organism_list_report');
INSERT INTO general_params VALUES('list_report_data_sort_dir','DESC');
INSERT INTO general_params VALUES('detail_report_data_table','v_organism_detail_report');
INSERT INTO general_params VALUES('detail_report_data_id_col','id');
INSERT INTO general_params VALUES('detail_report_data_id_type','integer');
INSERT INTO general_params VALUES('entry_sproc','add_update_organisms');
INSERT INTO general_params VALUES('entry_page_data_table','v_organism_entry');
INSERT INTO general_params VALUES('entry_page_data_id_col','id');
INSERT INTO general_params VALUES('post_submission_detail_id','id');
CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);
INSERT INTO form_fields VALUES(1,'id','ID','non-edit','','','','','','trim');
INSERT INTO form_fields VALUES(2,'organism','Name','text','50','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(3,'description','Description','area','','','2','60','','trim|max_length[512]');
INSERT INTO form_fields VALUES(4,'short_name','Short Name','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(5,'ncbi_taxonomy_id','NCBI_Taxonomy_ID','text','12','12','','','','trim');
INSERT INTO form_fields VALUES(6,'auto_define_taxonomy','Auto Define Taxonomy','text','12','12','','','Yes','trim');
INSERT INTO form_fields VALUES(7,'domain','Domain','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(8,'kingdom','Kingdom','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(9,'phylum','Phylum (Division)','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(10,'class','Class','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(11,'order','Order','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(12,'family','Family','text','60','64','','','','trim|max_length[64]');
INSERT INTO form_fields VALUES(13,'genus','Genus','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(14,'species','Species','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(15,'strain','Strain','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(17,'newt_id_list','NEWT_ID_List','area','','','2','60','','trim|max_length[255]');
INSERT INTO form_fields VALUES(22,'storage_location','Org. Storage Path','area','','','2','60','','trim|max_length[256]');
INSERT INTO form_fields VALUES(23,'default_protein_collection','Default Protein Collection','text','60','128','','','','trim|max_length[128]');
INSERT INTO form_fields VALUES(24,'active','Active','text','1','1','','','1','trim|max_length[1]');
CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);
INSERT INTO form_field_choosers VALUES(3,'active','picker.replace','yesNoAsOneZeroPickList','','',',','');
INSERT INTO form_field_choosers VALUES(5,'ncbi_taxonomy_id','list-report.helper','','helper_ncbi_taxonomy_id/report/','ncbi_taxonomy_id',',','');
INSERT INTO form_field_choosers VALUES(6,'auto_define_taxonomy','picker.replace','yesNoPickList','','',',','');
INSERT INTO form_field_choosers VALUES(7,'default_protein_collection','list-report.helper','','helper_protein_collection/report/','organism',',','');
INSERT INTO form_field_choosers VALUES(8,'newt_id_list','list-report.helper','','helper_newt_id/report','newt_id_list',',','');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO list_report_primary_filter VALUES(1,'pf_name','Name','30!','','name','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(2,'pf_description','Description','15!','','description','ContainsText','text','128','','');
INSERT INTO list_report_primary_filter VALUES(3,'pf_ID','ID','6!','','id','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(4,'pf_taxonomy_id','Tax_ID','6!','','ncbi_taxonomy_id','Equals','text','32','','');
INSERT INTO list_report_primary_filter VALUES(5,'pf_most_recent_weeks','Weeks since creation','4!','','created','MostRecentWeeks','text','32','','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO list_report_hotlinks VALUES(1,'id','invoke_entity','value','organism/show/','');
INSERT INTO list_report_hotlinks VALUES(2,'protein_collections','invoke_entity','name','protein_collection/report/-/~','');
INSERT INTO list_report_hotlinks VALUES(3,'ncbi_taxonomy','invoke_entity','value','ncbi_taxonomy/report/-/StartsWith__@','');
INSERT INTO list_report_hotlinks VALUES(4,'ncbi_synonyms','invoke_entity','ncbi_taxonomy_id','ncbi_taxonomy_altname/report/','');
INSERT INTO list_report_hotlinks VALUES(5,'ncbi_taxonomy_id','invoke_entity','value','ncbi_taxonomy/show/','');
INSERT INTO list_report_hotlinks VALUES(6,'legacy_fastas','invoke_entity','name','helper_organism_db/report/-/~','');
INSERT INTO list_report_hotlinks VALUES(7,'created','format_date','value','15','{"Format":"Y-m-d"}');
CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);
INSERT INTO sproc_args VALUES(1,'organism','orgName','varchar','input','128','add_update_organisms');
INSERT INTO sproc_args VALUES(2,'short_name','orgShortName','varchar','input','128','add_update_organisms');
INSERT INTO sproc_args VALUES(5,'storage_location','orgStorageLocation','varchar','input','256','add_update_organisms');
INSERT INTO sproc_args VALUES(6,'default_protein_collection','orgDBName','varchar','input','128','add_update_organisms');
INSERT INTO sproc_args VALUES(7,'description','orgDescription','varchar','input','512','add_update_organisms');
INSERT INTO sproc_args VALUES(8,'domain','orgDomain','varchar','input','64','add_update_organisms');
INSERT INTO sproc_args VALUES(9,'kingdom','orgKingdom','varchar','input','64','add_update_organisms');
INSERT INTO sproc_args VALUES(10,'phylum','orgPhylum','varchar','input','64','add_update_organisms');
INSERT INTO sproc_args VALUES(11,'class','orgClass','varchar','input','64','add_update_organisms');
INSERT INTO sproc_args VALUES(12,'order','orgOrder','varchar','input','64','add_update_organisms');
INSERT INTO sproc_args VALUES(13,'family','orgFamily','varchar','input','64','add_update_organisms');
INSERT INTO sproc_args VALUES(14,'genus','orgGenus','varchar','input','128','add_update_organisms');
INSERT INTO sproc_args VALUES(15,'species','orgSpecies','varchar','input','128','add_update_organisms');
INSERT INTO sproc_args VALUES(16,'strain','orgStrain','varchar','input','128','add_update_organisms');
INSERT INTO sproc_args VALUES(19,'active','orgActive','varchar','input','3','add_update_organisms');
INSERT INTO sproc_args VALUES(21,'newt_id_list','NEWTIDList','varchar','intput','255','add_update_organisms');
INSERT INTO sproc_args VALUES(22,'ncbi_taxonomy_id','NCBITaxonomyID','int','intput','12','add_update_organisms');
INSERT INTO sproc_args VALUES(23,'auto_define_taxonomy','AutoDefineTaxonomy','varchar','input','12','add_update_organisms');
INSERT INTO sproc_args VALUES(24,'id','id','int','output','','add_update_organisms');
INSERT INTO sproc_args VALUES(25,'<local>','mode','varchar','input','12','add_update_organisms');
INSERT INTO sproc_args VALUES(26,'<local>','message','varchar','output','512','add_update_organisms');
INSERT INTO sproc_args VALUES(27,'<local>','callingUser','varchar','input','128','add_update_organisms');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO detail_report_hotlinks VALUES(1,'protein_collections','detail-report','name','protein_collection/report/-/~','labelCol','protein_collections','');
INSERT INTO detail_report_hotlinks VALUES(4,'newt_id_list','link_list','newt_id_list','newt/show/','valueCol','newt_ID_List','');
INSERT INTO detail_report_hotlinks VALUES(5,'ncbi_taxonomy_id','detail-report','ncbi_taxonomy_id','ncbi_taxonomy/show/','valueCol','ncbi_taxonomy_detail _report','');
INSERT INTO detail_report_hotlinks VALUES(6,'+ncbi_taxonomy_id','detail-report','ncbi_taxonomy_id','ncbi_taxonomy/report/','labelCol','ncbi_taxonomy_list_report_id','');
INSERT INTO detail_report_hotlinks VALUES(7,'ncbi_taxonomy','detail-report','ncbi_taxonomy','ncbi_taxonomy/report/-/~','labelCol','ncbi_taxonomy_list_report_name','');
INSERT INTO detail_report_hotlinks VALUES(8,'ncbi_synonyms','detail-report','ncbi_taxonomy_id','ncbi_taxonomy_altname/report/','labelCol','ncbi_taxonomy_Synomys','');
INSERT INTO detail_report_hotlinks VALUES(10,'taxonomy_list','tabular_list','taxonomy_list','','valueCol','dl_taxonomy_list','');
INSERT INTO detail_report_hotlinks VALUES(11,'default_protein_collection','detail-report','default_protein_collection','protein_collection/report/~','valueCol','dl_default_protein_collection','');
INSERT INTO detail_report_hotlinks VALUES(12,'legacy_fasta_files','detail-report','name','helper_organism_db/report/-/~','labelCol','dl_legacy_fasta_files','');
INSERT INTO detail_report_hotlinks VALUES(13,'organism_storage_link','literal_link','organism_storage_link','','valueCol','dl_organism_storage_link','');
COMMIT;
