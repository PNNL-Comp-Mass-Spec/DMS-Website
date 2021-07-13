<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|   ['dsn']      The full DSN string describe a connection to the database.
|   ['hostname'] The hostname of your database server.
|   ['username'] The username used to connect to the database
|   ['password'] The password used to connect to the database
|   ['database'] The name of the database you want to connect to
|   ['dbdriver'] The database driver. e.g.: mysqli.
|           Currently supported:
|                cubrid, ibase, mssql, mysql, mysqli, oci8,
|                odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|   ['dbprefix'] You can add an optional prefix, which will be added
|                to the table name when using the  Query Builder class
|   ['pconnect'] true/false - Whether to use a persistent connection
|   ['db_debug'] true/false - Whether database errors should be displayed.
|   ['cache_on'] true/false - Enables/disables query caching
|   ['cachedir'] The path to the folder where cache files should be stored
|   ['char_set'] The character set used in communicating with the database
|   ['dbcollat'] The character collation used in communicating with the database
|                NOTE: For MySQL and MySQLi databases, this setting is only used
|                as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|                (and in table creation queries made with DB Forge).
|                There is an incompatibility in PHP with mysql_real_escape_string() which
|                can make your site vulnerable to SQL injection if you are using a
|                multi-byte character set and are running versions lower than these.
|                Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|   ['swap_pre'] A default table prefix that should be swapped with the dbprefix
|   ['encrypt']  Whether or not to use an encrypted connection.
|
|           'mysql' (deprecated), 'sqlsrv' and 'pdo/sqlsrv' drivers accept true/false
|           'mysqli' and 'pdo/mysql' drivers accept an array with the following options:
|
|               'ssl_key'    - Path to the private key file
|               'ssl_cert'   - Path to the public key certificate file
|               'ssl_ca'     - Path to the certificate authority file
|               'ssl_capath' - Path to a directory containing trusted CA certificats in PEM format
|               'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|               'ssl_verify' - true/false; Whether verify the server certificate or not ('mysqli' only)
|
|   ['compress'] Whether or not to use client compression (MySQL only)
|   ['stricton'] true/false - forces 'Strict Mode' connections
|                           - good for ensuring strict SQL while developing
|   ['ssl_options'] Used to set various SSL options that can be used when making SSL connections.
|   ['failover'] array - A array with 0 or more data for connections if the main should fail.
|   ['save_queries'] true/false - Whether to "save" all executed queries.
|               NOTE: Disabling this will also effectively disable both
|               $this->db->last_query() and profiling of DB queries.
|               When you run a query, with this setting set to true (default),
|               CodeIgniter will store the SQL statement for debugging purposes.
|               However, this may cause high memory usage, especially if you run
|               a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = 'default';
$query_builder = true;

/*
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => '',
    'password' => '',
    'database' => '',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => true
);
*/

// Use sqlsrv with PHP 7 on Apache 2.4
// Use mssql  with PHP 5 on Apache 2.2
$mssqlsrvDbDriver = "sqlsrv";

$db['default']['hostname'] = "CBDMS";
$db['default']['username'] = "dmswebuser";
$db['default']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['default']['database'] = "DMS5";
$db['default']['dbdriver'] = $mssqlsrvDbDriver;
$db['default']['dbprefix'] = "";
$db['default']['active_r'] = true;
$db['default']['pconnect'] = false;
$db['default']['db_debug'] = false;
$db['default']['cache_on'] = false;
$db['default']['cachedir'] = "";

$db['broker']['hostname'] = "CBDMS";
$db['broker']['username'] = "dmswebuser";
$db['broker']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['broker']['database'] = "DMS_Pipeline";
$db['broker']['dbdriver'] = $mssqlsrvDbDriver;
$db['broker']['dbprefix'] = "";
$db['broker']['active_r'] = true;
$db['broker']['pconnect'] = false;
$db['broker']['db_debug'] = false;
$db['broker']['cache_on'] = false;
$db['broker']['cachedir'] = "";

$db['package']['hostname'] = "CBDMS";
$db['package']['username'] = "dmswebuser";
$db['package']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['package']['database'] = "DMS_Data_Package";
$db['package']['dbdriver'] = $mssqlsrvDbDriver;
$db['package']['dbprefix'] = "";
$db['package']['active_r'] = true;
$db['package']['pconnect'] = true;
$db['package']['db_debug'] = false;
$db['package']['cache_on'] = false;
$db['package']['cachedir'] = "";

$db['capture']['hostname'] = "CBDMS";
$db['capture']['username'] = "dmswebuser";
$db['capture']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['capture']['database'] = "DMS_Capture";
$db['capture']['dbdriver'] = $mssqlsrvDbDriver;
$db['capture']['dbprefix'] = "";
$db['capture']['active_r'] = true;
$db['capture']['pconnect'] = true;
$db['capture']['db_debug'] = false;
$db['capture']['cache_on'] = false;
$db['capture']['cachedir'] = "";

$db['prism_ifc']['hostname'] = "Pogo";
$db['prism_ifc']['username'] = "mtuser";
$db['prism_ifc']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['prism_ifc']['database'] = "PRISM_IFC";
$db['prism_ifc']['dbdriver'] = $mssqlsrvDbDriver;
$db['prism_ifc']['dbprefix'] = "";
$db['prism_ifc']['active_r'] = true;
$db['prism_ifc']['pconnect'] = true;
$db['prism_ifc']['db_debug'] = true;
$db['prism_ifc']['cache_on'] = false;
$db['prism_ifc']['cachedir'] = "";

$db['prism_rpt']['hostname'] = "Pogo";
$db['prism_rpt']['username'] = "mtuser";
$db['prism_rpt']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['prism_rpt']['database'] = "PRISM_RPT";
$db['prism_rpt']['dbdriver'] = $mssqlsrvDbDriver;
$db['prism_rpt']['dbprefix'] = "";
$db['prism_rpt']['active_r'] = true;
$db['prism_rpt']['pconnect'] = true;
$db['prism_rpt']['db_debug'] = true;
$db['prism_rpt']['cache_on'] = false;
$db['prism_rpt']['cachedir'] = "";

$db['ontology']['hostname'] = "CBDMS";
$db['ontology']['username'] = "dmswebuser";
$db['ontology']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['ontology']['database'] = "Ontology_Lookup";
$db['ontology']['dbdriver'] = $mssqlsrvDbDriver;
$db['ontology']['dbprefix'] = "";
$db['ontology']['active_r'] = true;
$db['ontology']['pconnect'] = true;
$db['ontology']['db_debug'] = true;
$db['ontology']['cache_on'] = false;
$db['ontology']['cachedir'] = "";

$db['manager_control']['hostname'] = "CBDMS";
$db['manager_control']['username'] = "dmswebuser";
$db['manager_control']['password'] = "see_repo_DMS2_DatabaseConfigFiles";
$db['manager_control']['database'] = "Manager_Control";
$db['manager_control']['dbdriver'] = $mssqlsrvDbDriver;
$db['manager_control']['dbprefix'] = "";
$db['manager_control']['active_r'] = true;
$db['manager_control']['pconnect'] = true;
$db['manager_control']['db_debug'] = true;
$db['manager_control']['cache_on'] = false;
$db['manager_control']['cachedir'] = "";
?>
