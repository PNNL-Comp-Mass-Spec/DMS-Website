<?php

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
|   ['DSN']      The full DSN string describe a connection to the database.
|   ['hostname'] The hostname of your database server.
|   ['username'] The username used to connect to the database
|   ['password'] The password used to connect to the database
|   ['database'] The name of the database you want to connect to
|   ['port']     The database port number. To use this value you have to add a line to the database config array.
|   ['DBDriver'] The database driver. e.g.: MySQLi.
|           Currently supported:
|                MySQLi, Postgre, SQLite3, SQLSRV
|   ['DBPrefix'] You can add an optional prefix, which will be added
|                to the table name when using the  Query Builder class
|   ['pConnect'] true/false - Whether to use a persistent connection
|   ['schema']   The database schema, default value varies by driver. Used by PostgreSQL and SQLSRV drivers.
|   ['DBDebug']  true/false - Whether database errors should be displayed.
|   ['charset']  The character set used in communicating with the database
|   ['DBCollat'] The character collation used in communicating with the database
|                NOTE: Only used in the �MySQLi� driver.
|   ['swapPre']  A default table prefix that should be swapped with the DBPrefix
|   ['encrypt']  Whether or not to use an encrypted connection.
|
|           'SQLSRV' and 'pdo/sqlsrv' drivers accept true/false
|           'MySQLi' and 'pdo/mysql' drivers accept an array with the following options:
|
|               'ssl_key'    - Path to the private key file
|               'ssl_cert'   - Path to the public key certificate file
|               'ssl_ca'     - Path to the certificate authority file
|               'ssl_capath' - Path to a directory containing trusted CA certificats in PEM format
|               'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|               'ssl_verify' - true/false; Whether verify the server certificate or not ('mysqli' only)
|
|   ['compress'] Whether or not to use client compression (MySQL only)
|   ['strictOn'] true/false - forces 'Strict Mode' connections
|                           - good for ensuring strict SQL while developing
|   ['failover'] array - A array with 0 or more data for connections if the main should fail.
*/

/*
return [
    'default'] = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => '',
        'password' => '',
        'database' => '',
        'port'     => '',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => array(),
    ],
];
*/

return [
    'default' => [
        'hostname' => "CBDMS",
        'username' => "dmswebuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "DMS5",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'broker' => [
        'hostname' => "CBDMS",
        'username' => "dmswebuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "DMS_Pipeline",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'package' => [
        'hostname' => "CBDMS",
        'username' => "dmswebuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "DMS_Data_Package",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'capture' => [
        'hostname' => "CBDMS",
        'username' => "dmswebuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "DMS_Capture",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'prism_ifc' => [
        'hostname' => "Pogo",
        'username' => "mtuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "PRISM_IFC",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'prism_rpt' => [
        'hostname' => "Pogo",
        'username' => "mtuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "PRISM_RPT",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'ontology' => [
        'hostname' => "CBDMS",
        'username' => "dmswebuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "Ontology_Lookup",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],

    'manager_control' => [
        'hostname' => "CBDMS",
        'username' => "dmswebuser",
        'password' => "see_repo_DMS2_DatabaseConfigFiles",
        'database' => "Manager_Control",
        'DBDriver' => "SQLSRV",
        'DBPrefix' => "",
        'pConnect' => false,
        'DBDebug'  => false,
    ],
];

?>
