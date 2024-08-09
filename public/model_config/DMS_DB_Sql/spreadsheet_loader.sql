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
INSERT INTO loadable_entities VALUES(1,'EXPERIMENT','experiment','Experiment','SELECT id AS id FROM v_experiment_report WHERE experiment = KEY','name','Config_source "experiment" refers to the experiment page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/experiment.db/sproc_args');
INSERT INTO loadable_entities VALUES(2,'REQUESTED RUN','requested_run','','SELECT request AS id FROM v_requested_run_helper_list_report WHERE name = KEY','id','Config_source "requested_run" refers to the requested_run page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/requested_run.db/sproc_args');
INSERT INTO loadable_entities VALUES(3,'BIOMATERIAL','biomaterial','Biomaterial','SELECT id AS id FROM v_biomaterial WHERE name = KEY','name','Config_source "biomaterial" refers to the biomaterial page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/biomaterial.db/sproc_args');
INSERT INTO loadable_entities VALUES(4,'REQUESTED RUN BATCH','requested_run_batch','','SELECT id AS id FROM v_requested_run_batch_picklist WHERE batch = KEY','id','Config_source "requested_run_batch" refers to the requested_run_batch page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/requested_run_batch.db/sproc_args');
INSERT INTO loadable_entities VALUES(5,'DATASET','dataset','','SELECT dataset_id AS id FROM v_dataset WHERE dataset = KEY','name','Config_source "dataset" refers to the dataset page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/dataset.db/sproc_args');
INSERT INTO loadable_entities VALUES(6,'LC COLUMN','lc_column','','SELECT column_id AS id FROM v_lc_column_list_report WHERE column_name = KEY','name','Config_source "lc_column" refers to the lc_column page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/lc_column.db/sproc_args');
INSERT INTO loadable_entities VALUES(7,'BOGUS','bogus','','SELECT id AS id FROM t_bogus WHERE name = KEY','name',NULL);
INSERT INTO loadable_entities VALUES(8,'REFERENCE COMPOUND','reference_compound','','SELECT compound_id AS compound_id FROM t_reference_compound WHERE compound_id = KEY','compound_id','Config_source "reference_compound" refers to the reference_compound page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/reference_compound.db/sproc_args');
INSERT INTO loadable_entities VALUES(9,'EXPERIMENT PLEX MEMBERS TSV','experiment_plex_members_tsv','','SELECT plex_exp_id AS plex_exp_id FROM t_experiment_plex_members WHERE plex_exp_id = KEY','plex_exp_id','Config_source "experiment_plex_members_tsv" refers to the experiment_plex_members_tsv page family, referencing the stored procedure defined at https://dmsdev.pnl.gov/config_db/edit_table/experiment_plex_members_tsv.db/sproc_args');
COMMIT;
