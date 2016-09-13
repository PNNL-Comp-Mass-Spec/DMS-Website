PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE 'table_edit_col_defs' (id INTEGER PRIMARY KEY,  "config_table" text, "config_col" text, "type" text, "value" text );
INSERT INTO "table_edit_col_defs" VALUES(1,'detail_report_hotlinks','LinkType','literal_list','{"list":["color_label","detail-report","glossary_entry","href-folder","link_list","link_table","literal_link","markup","masked_link","tabular_list","xml_params"]}');
INSERT INTO "table_edit_col_defs" VALUES(2,'detail_report_hotlinks','Placement','literal_list','{"list":["labelCol", "valueCol"]}');
INSERT INTO "table_edit_col_defs" VALUES(3,'detail_report_hotlinks','WhichArg','dms_view_cols','{"view":"detail_report_data_table"}');
INSERT INTO "table_edit_col_defs" VALUES(4,'detail_report_hotlinks','name','dms_view_cols','{"view":"detail_report_data_table"}');
INSERT INTO "table_edit_col_defs" VALUES(5,'form_field_choosers','Delimiter','default_value','{"value":","}');
INSERT INTO "table_edit_col_defs" VALUES(6,'form_field_choosers','field','config_col_values','{"table":"form_fields", "column":"name"}');
INSERT INTO "table_edit_col_defs" VALUES(7,'form_field_choosers','type','literal_list','{"list":["picker.replace", "list-report.helper",  "picker.prevDate"]}');
INSERT INTO "table_edit_col_defs" VALUES(8,'form_field_options','field','config_col_values','{"table":"form_fields", "column":"name"}');
INSERT INTO "table_edit_col_defs" VALUES(9,'form_field_options','type','literal_list','{"list":["default_function", "section",  "enable",  "auto_format",  "hide",  "permission"]}');
INSERT INTO "table_edit_col_defs" VALUES(10,'list_report_hotlinks','LinkType','literal_list','{"list":["bifold_choice","CHECKBOX","checkbox_json","color_label","column_tooltip","copy_from","href-folder","image_link","inplace_edit","invoke_entity","invoke_multi_col","link_list","literal_link","markup","masked_href-folder","masked_link", "min_col_width", "row_to_json","row_to_url","select_case","update_opener"]}');
INSERT INTO "table_edit_col_defs" VALUES(11,'list_report_hotlinks','WhichArg','default_value','{"value":"value"}');
INSERT INTO "table_edit_col_defs" VALUES(12,'list_report_hotlinks','name','dms_view_cols','{"view":"list_report_data_table"}');
INSERT INTO "table_edit_col_defs" VALUES(13,'list_report_primary_filter','cmp','literal_list','{"list":["ContainsText", "DoesNotContainText", "EarlierThan", "Equals", "GreaterThan", "GreaterThanOrEqualTo", "LaterThan", "LessThan", "LessThanOrEqualTo", "MatchesText", "MatchesTextOrBlank", "MostRecentWeeks", "NotEqual", "StartsWithText"]}');
INSERT INTO "table_edit_col_defs" VALUES(14,'list_report_primary_filter','col','dms_view_cols','{"view":"list_report_data_table"}');
INSERT INTO "table_edit_col_defs" VALUES(15,'list_report_primary_filter','type','default_value','{"value":"text"}');
INSERT INTO "table_edit_col_defs" VALUES(16,'primary_filter_choosers','Delimiter','default_value','{"value":","}');
INSERT INTO "table_edit_col_defs" VALUES(17,'primary_filter_choosers','field','config_col_values','{"table":"list_report_primary_filter", "column":"name"}');
INSERT INTO "table_edit_col_defs" VALUES(18,'primary_filter_choosers','type','literal_list','{"list":["picker.replace", "list-report.helper",  "picker.prevDate"]}');
INSERT INTO "table_edit_col_defs" VALUES(19,'form_fields','type','literal_list','{"list":["text", "text-if-new", "area", "hidden", "non-edit", "file"]}');
INSERT INTO "table_edit_col_defs" VALUES(20,'form_fields','rules','default_value','{"value":"trim"}');
INSERT INTO "table_edit_col_defs" VALUES(21,'detail_report_commands','Type','literal_list','{"list":["cmd_op", "call",  "copy_from"]}');
INSERT INTO "table_edit_col_defs" VALUES(22,'entry_commands','type','literal_list','{"list":["cmd", "override",  "retarget"]}');
INSERT INTO "table_edit_col_defs" VALUES(23,'external_sources','type','literal_list','{"list":["ColName", "PostName",  "Literal" ]}
');
INSERT INTO "table_edit_col_defs" VALUES(24,'general_params','name','literal_list','{"list":[ "base_table", "my_db_group", "list_report_autoload", "list_report_cmds", "list_report_cmds_url", "list_report_data_cols", "list_report_data_order_by", "list_report_data_sort_col", "list_report_data_sort_dir", "list_report_data_table", "list_report_helper_multiple_selection", "list_report_sproc", "detail_report_aux_info_target", "detail_report_cmds", "detail_report_data_cols", "detail_report_data_id_col", "detail_report_data_table", "entry_page_data_cols", "entry_page_data_id_col", "entry_page_data_table", "entry_sproc", "entry_submission_cmds", "operations_sproc", "post_submission_detail_id", "post_submission_link", "post_submission_link_tag", "rss_data_table", "rss_description", "rss_item_link", "alternate_title_create", "alternate_title_edit", "alternate_title_export", "alternate_title_param", "alternate_title_report", "alternate_title_search", "alternate_title_show" ]}');
CREATE TABLE table_def_sql ( id INTEGER PRIMARY KEY,   "config_table" TEXT, "value" TEXT );
INSERT INTO "table_def_sql" VALUES(1,'general_params','CREATE TABLE general_params ( "name" text, "value" text );');
INSERT INTO "table_def_sql" VALUES(2,'list_report_hotlinks','CREATE TABLE list_report_hotlinks ( id INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Options" text );');
INSERT INTO "table_def_sql" VALUES(3,'list_report_primary_filter','CREATE TABLE list_report_primary_filter ( id INTEGER PRIMARY KEY,  "name" text, "label" text, "size" text, "value" text, "col" text, "cmp" text, "type" text, "maxlength" text, "rows" text, "cols" text );');
INSERT INTO "table_def_sql" VALUES(4,'primary_filter_choosers','CREATE TABLE primary_filter_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text );');
INSERT INTO "table_def_sql" VALUES(5,'detail_report_hotlinks','CREATE TABLE detail_report_hotlinks ( idx INTEGER PRIMARY KEY,  "name" text, "LinkType" text, "WhichArg" text, "Target" text, "Placement" text, "id" text );');
INSERT INTO "table_def_sql" VALUES(6,'detail_report_commands','CREATE TABLE detail_report_commands ( id INTEGER PRIMARY KEY,  "name" text, "Type" text, "Command" text, "Target" text, "Tooltip" text, "Prompt" text );');
INSERT INTO "table_def_sql" VALUES(7,'form_fields','CREATE TABLE form_fields ( id INTEGER PRIMARY KEY, "name"  text, "label" text, "type" text, "size" text, "maxlength" text, "rows" text, "cols" text, "default" text, "rules" text);');
INSERT INTO "table_def_sql" VALUES(8,'form_field_options','CREATE TABLE form_field_options ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "parameter" text );');
INSERT INTO "table_def_sql" VALUES(9,'form_field_choosers','CREATE TABLE form_field_choosers ( id INTEGER PRIMARY KEY,  "field" text, "type" text, "PickListName" text, "Target" text, "XRef" text, "Delimiter" text, "Label" text);');
INSERT INTO "table_def_sql" VALUES(10,'entry_commands','CREATE TABLE entry_commands ( id INTEGER PRIMARY KEY,  "name" text, "type" text, "label" text, "tooltip" text, "target" text );');
INSERT INTO "table_def_sql" VALUES(12,'sproc_args','CREATE TABLE sproc_args ( id INTEGER PRIMARY KEY, "field" text, "name" text, "type" text, "dir" text, "size" text, "procedure" text);');
INSERT INTO "table_def_sql" VALUES(13,'external_sources','CREATE TABLE external_sources ( id INTEGER PRIMARY KEY,  "source_page" text, "field" text, "type" text, "value" text );');
INSERT INTO "table_def_sql" VALUES(14,'utility_queries','CREATE TABLE utility_queries ( id integer PRIMARY KEY, name text, label text, db text, "table" text, columns text, sorting text, filters text, hotlinks text );');
CREATE TABLE table_def_description ( id INTEGER PRIMARY KEY,   "config_table" TEXT,"value" TEXT );
INSERT INTO "table_def_description" VALUES(1,'general_params','List Of key/value parameters that define primary aspects of page family');
INSERT INTO "table_def_description" VALUES(2,'list_report_hotlinks','Defines hotlink fields for the list report page');
INSERT INTO "table_def_description" VALUES(3,'list_report_primary_filter','Defines the primary filter fields for the list report page');
INSERT INTO "table_def_description" VALUES(4,'detail_report_hotlinks','Defines hotlink fields for the detail report page');
INSERT INTO "table_def_description" VALUES(5,'sproc_args','Defines arguments for stored procedures (entry and operations).  The field column in this table names the associated row in either the form_fields or operations_fields tables.');
INSERT INTO "table_def_description" VALUES(6,'form_fields','Parameters that define the field on the entry page for the page family');
INSERT INTO "table_def_description" VALUES(7,'form_field_choosers','Defines choosers for fields defined in form_fields table');
INSERT INTO "table_def_description" VALUES(8,'form_field_options','Defines optional behaviors for fields defined in form_fields table');
INSERT INTO "table_def_description" VALUES(9,'primary_filter_choosers','Defines any choosers for the fields defined in the list_report_primary_filter table');
INSERT INTO "table_def_description" VALUES(10,'detail_report_commands','Defines command buttons that are added to the detail report page');
INSERT INTO "table_def_description" VALUES(11,'entry_commands','Defines command buttons that supplement or override the "Create" or "Update" buttons on the entry page');
INSERT INTO "table_def_description" VALUES(13,'external_sources','Defines how the entry page for this page family can import values from an entity in another page family');
INSERT INTO "table_def_description" VALUES(14,'utility_queries','Miscelleneous queries for specialized uses for the page family');
COMMIT;
