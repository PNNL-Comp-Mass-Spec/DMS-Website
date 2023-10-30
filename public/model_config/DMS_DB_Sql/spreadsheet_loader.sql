PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "loadable_entities" (
	"id"	INTEGER,
	"entity_type"	TEXT,
	"config_source"	TEXT,
	"aux_info_target"	TEXT,
	"existence_check_sql"	TEXT,
	"key"	TEXT,
	"comment"	TEXT,
	PRIMARY KEY("id")
);
INSERT INTO loadable_entities VALUES(1,'EXPERIMENT','experiment','Experiment','SELECT Exp_ID AS ID FROM T_Experiments WHERE Experiment_Num = KEY','name','Config_source "experiment" refers to the experiment page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/experiment.db/sproc_args');
INSERT INTO loadable_entities VALUES(2,'REQUESTED RUN','requested_run','','SELECT ID FROM T_Requested_Run WHERE RDS_Name = KEY','ID','Config_source "requested_run" refers to the requested_run page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/requested_run.db/sproc_args');
INSERT INTO loadable_entities VALUES(3,'BIOMATERIAL','biomaterial','Biomaterial','SELECT CC_ID AS ID FROM T_Cell_Culture WHERE CC_Name = KEY','name','Config_source "biomaterial" refers to the biomaterial page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/biomaterial.db/sproc_args');
INSERT INTO loadable_entities VALUES(4,'REQUESTED RUN BATCH','requested_run_batch','','SELECT ID FROM T_Requested_Run_Batches WHERE Batch = KEY','ID','Config_source "requested_run_batch" refers to the requested_run_batch page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/requested_run_batch.db/sproc_args');
INSERT INTO loadable_entities VALUES(5,'DATASET','dataset','','SELECT Dataset_ID AS ID FROM T_Dataset WHERE Dataset_Num = KEY','name','Config_source "dataset" refers to the dataset page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/dataset.db/sproc_args');
INSERT INTO loadable_entities VALUES(6,'LC COLUMN','lc_column','','SELECT  ID FROM T_LC_Column WHERE SC_Column_Number = KEY','name','Config_source "lc_column" refers to the lc_column page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/lc_column.db/sproc_args');
INSERT INTO loadable_entities VALUES(7,'BOGUS','bogus','','SELECT ID FROM T_Bogus WHERE Name = KEY','name',NULL);
INSERT INTO loadable_entities VALUES(8,'REFERENCE COMPOUND','reference_compound','','SELECT Compound_ID FROM T_Reference_Compound WHERE Compound_ID = KEY','Compound_ID','Config_source "reference_compound" refers to the reference_compound page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/reference_compound.db/sproc_args');
INSERT INTO loadable_entities VALUES(9,'EXPERIMENT PLEX MEMBERS TSV','experiment_plex_members_tsv','','SELECT Plex_Exp_ID FROM T_Experiment_Plex_Members WHERE  Plex_Exp_ID = KEY','Plex_Exp_ID','Config_source "experiment_plex_members_tsv" refers to the experiment_plex_members_tsv page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/experiment_plex_members_tsv.db/sproc_args');
DELETE FROM sqlite_sequence;
COMMIT;
