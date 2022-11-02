PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE loadable_entities (

 id INTEGER PRIMARY KEY,
"entity_type" text,
"config_source" text,
"aux_info_target" text,
"existence_check_sql" text,
"key" text
);
INSERT INTO loadable_entities VALUES(1,'EXPERIMENT','experiment','Experiment','SELECT Exp_ID AS ID FROM T_Experiments WHERE Experiment_Num = KEY','name');
INSERT INTO loadable_entities VALUES(2,'REQUESTED RUN','requested_run','','SELECT ID FROM T_Requested_Run WHERE RDS_Name = KEY','ID');
INSERT INTO loadable_entities VALUES(3,'BIOMATERIAL','biomaterial','Biomaterial','SELECT CC_ID AS ID FROM T_Cell_Culture WHERE CC_Name = KEY','name');
INSERT INTO loadable_entities VALUES(4,'REQUESTED RUN BATCH','requested_run_batch','','SELECT ID FROM T_Requested_Run_Batches WHERE Batch = KEY','ID');
INSERT INTO loadable_entities VALUES(5,'DATASET','dataset','','SELECT Dataset_ID AS ID FROM T_Dataset WHERE Dataset_Num = KEY','name');
INSERT INTO loadable_entities VALUES(6,'LC COLUMN','lc_column','','SELECT  ID FROM T_LC_Column WHERE SC_Column_Number = KEY','name');
INSERT INTO loadable_entities VALUES(7,'BOGUS','bogus','','SELECT ID FROM T_Bogus WHERE Name = KEY','name');
INSERT INTO loadable_entities VALUES(8,'REFERENCE COMPOUND','reference_compound','','SELECT Compound_ID FROM T_Reference_Compound WHERE Compound_ID = KEY','Compound_ID');
INSERT INTO loadable_entities VALUES(9,'EXPERIMENT PLEX MEMBERS TSV','experiment_plex_members_tsv','','SELECT Plex_Exp_ID FROM T_Experiment_Plex_Members WHERE  Plex_Exp_ID = KEY','Plex_Exp_ID');
COMMIT;
