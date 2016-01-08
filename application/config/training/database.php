<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['active_r'] TRUE/FALSE - Whether to load the active record class
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
*/

$active_group = "default";

$db['default']['hostname'] = "Gigasax_ODBC";
$db['default']['username'] = "dmswebuser";
$db['default']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['default']['database'] = "DMS5_Beta";
$db['default']['dbdriver'] = "mssql";
$db['default']['dbprefix'] = "";
$db['default']['active_r'] = TRUE;
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";

$db['package']['hostname'] = "Gigasax_ODBC";
$db['package']['username'] = "DMSWebUser";
$db['package']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['package']['database'] = "DMS_Data_Package_Beta";
$db['package']['dbdriver'] = "mssql";
$db['package']['dbprefix'] = "";
$db['package']['active_r'] = TRUE;
$db['package']['pconnect'] = TRUE;
$db['package']['db_debug'] = FALSE;
$db['package']['cache_on'] = FALSE;
$db['package']['cachedir'] = "";

$db['ontology']['hostname'] = "Gigasax_ODBC";
$db['ontology']['username'] = "dmswebuser";
$db['ontology']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['ontology']['database'] = "Ontology_Lookup";
$db['ontology']['dbdriver'] = "mssql";
$db['ontology']['dbprefix'] = "";
$db['ontology']['active_r'] = TRUE;
$db['ontology']['pconnect'] = TRUE;
$db['ontology']['db_debug'] = TRUE;
$db['ontology']['cache_on'] = FALSE;
$db['ontology']['cachedir'] = "";

?>
