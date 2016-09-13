PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE general_params ( "name" text, "value" text );
INSERT INTO "general_params" VALUES('list_report_data_table','V_Unimod_List_Report');
INSERT INTO "general_params" VALUES('detail_report_data_table','V_Unimod_Detail_Report');
INSERT INTO "general_params" VALUES('detail_report_data_id_col','Unimod_ID');
INSERT INTO "general_params" VALUES('my_db_group','ontology');
CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );
INSERT INTO "list_report_primary_filter" VALUES(2,'pf_name','Name','20','','Name','ContainsText','text','512','','');
INSERT INTO "list_report_primary_filter" VALUES(3,'pf_DMS_Name','DMS_Name','20','','DMS_Name','ContainsText','text','128','','');
INSERT INTO "list_report_primary_filter" VALUES(4,'pf_full_name','Full_Name','20','','Full_Name','ContainsText','text','1020','','');
INSERT INTO "list_report_primary_filter" VALUES(5,'pf_monomass_min','Min_Mass','20','','MonoMass','GreaterThanOrEqualTo','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(6,'pf_monomass_max','Max_Mass','20','','MonoMass','LessThanOrEqualTo','text','20','','');
INSERT INTO "list_report_primary_filter" VALUES(7,'pf_composition','Composition','40','','Composition','ContainsText','text','512','','');
CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text , options text);
INSERT INTO "detail_report_hotlinks" VALUES(1,'Name','detail-report','Name','unimod/report/','valueCol','dl_Name',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(2,'Monoisotopic_Mass','detail-report','Monoisotopic_Mass','unimod/report/-/-/-/-/@/@','valueCol','dl_monomass',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(3,'URL','literal_link','URL','url/show','valueCol','dl_url',NULL);
INSERT INTO "detail_report_hotlinks" VALUES(4,'DMS_Name','detail-report','DMS_Name','mass_correction_factors/report/-/~','valueCol','dl_dms_name','');
INSERT INTO "detail_report_hotlinks" VALUES(5,'Mass_Correction_ID','detail-report','Mass_Correction_ID','param_file_mass_mods/report/-/@','valueCol','dl_mass_correction_id','');
CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );
INSERT INTO "list_report_hotlinks" VALUES(1,'ID','invoke_entity','value','unimod/show/','');
INSERT INTO "list_report_hotlinks" VALUES(2,'DMS_Name','invoke_entity','value','mass_correction_factors/report/-/~','');
COMMIT;
