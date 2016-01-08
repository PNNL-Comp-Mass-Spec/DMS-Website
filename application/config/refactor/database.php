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
$db['default']['database'] = "DMS5";
$db['default']['dbdriver'] = "mssql";
$db['default']['dbprefix'] = "";
$db['default']['active_r'] = TRUE;
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";

$db['broker']['hostname'] = "Gigasax_ODBC";
$db['broker']['username'] = "DMSWebUser";
$db['broker']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['broker']['database'] = "DMS_Pipeline";
$db['broker']['dbdriver'] = "mssql";
$db['broker']['dbprefix'] = "";
$db['broker']['active_r'] = TRUE;
$db['broker']['pconnect'] = FALSE;
$db['broker']['db_debug'] = FALSE;
$db['broker']['cache_on'] = FALSE;
$db['broker']['cachedir'] = "";

$db['package']['hostname'] = "Gigasax_ODBC";
$db['package']['username'] = "DMSWebUser";
$db['package']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['package']['database'] = "DMS_Data_Package";
$db['package']['dbdriver'] = "mssql";
$db['package']['dbprefix'] = "";
$db['package']['active_r'] = TRUE;
$db['package']['pconnect'] = TRUE;
$db['package']['db_debug'] = FALSE;
$db['package']['cache_on'] = FALSE;
$db['package']['cachedir'] = "";

$db['capture']['hostname'] = "Gigasax_ODBC";
$db['capture']['username'] = "dmswebuser";
$db['capture']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['capture']['database'] = "DMS_Capture";
$db['capture']['dbdriver'] = "mssql";
$db['capture']['dbprefix'] = "";
$db['capture']['active_r'] = TRUE;
$db['capture']['pconnect'] = TRUE;
$db['capture']['db_debug'] = TRUE;
$db['capture']['cache_on'] = FALSE;
$db['capture']['cachedir'] = "";


$db['prism_ifc']['hostname'] = "Pogo_ODBC";
$db['prism_ifc']['username'] = "mtuser";
$db['prism_ifc']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['prism_ifc']['database'] = "PRISM_IFC";
$db['prism_ifc']['dbdriver'] = "mssql";
$db['prism_ifc']['dbprefix'] = "";
$db['prism_ifc']['active_r'] = TRUE;
$db['prism_ifc']['pconnect'] = TRUE;
$db['prism_ifc']['db_debug'] = TRUE;
$db['prism_ifc']['cache_on'] = FALSE;
$db['prism_ifc']['cachedir'] = "";

$db['prism_rpt']['hostname'] = "Pogo_ODBC";
$db['prism_rpt']['username'] = "mtuser";
$db['prism_rpt']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['prism_rpt']['database'] = "PRISM_RPT";
$db['prism_rpt']['dbdriver'] = "mssql";
$db['prism_rpt']['dbprefix'] = "";
$db['prism_rpt']['active_r'] = TRUE;
$db['prism_rpt']['pconnect'] = TRUE;
$db['prism_rpt']['db_debug'] = TRUE;
$db['prism_rpt']['cache_on'] = FALSE;
$db['prism_rpt']['cachedir'] = "";

?>
