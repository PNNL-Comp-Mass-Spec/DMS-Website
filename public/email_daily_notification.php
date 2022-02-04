<?php

/**
 * This file is invoked via a daily crontab job that runs as root
 * (see /var/spool/cron/root; edit with sudo crontab -e)
 * The code invokes http://dms2.pnl.gov/notification/email
 * to send notification e-mails to users who have signed up to receive them
 *
 * NOTE: This works using CodeIgniter 4 CLI commands: https://codeigniter4.github.io/userguide/cli/cli.html
 *
 */

// remove execution timeout
set_time_limit(0);

/*
 * --------------------------------------------------------------------
 * Call a specific controller/method using CLI
 * --------------------------------------------------------------------
 */

// See 'parseCommand()' in vendor/codeigniter4/framework/system/HTTP/CLIRequest.php
// Currently uses $_SERVER["argv"] to get PHP command line arguments, which includes the script name
// Other option is getopt, but they don't use it due to issues.
// Without setting this, we would need to call this script from command line using "php email_daily_notification.php notification email"
$_SERVER["argv"] = array("email_daily_notification.php", "notification", "email");

/*
 * --------------------------------------------------------------------
 * Explicitly define some $_SERVER variables
 * --------------------------------------------------------------------
 */

// Used by Email.php
$_SERVER["SERVER_NAME"] = 'dms2.pnl.gov';

$_SERVER["SCRIPT_FILENAME"] = pathinfo(__FILE__, PATHINFO_BASENAME);

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Ensure the current directory is pointing to the front controller's directory
chdir(__DIR__);

// Load our paths config file
// This is the line that might need to be changed, depending on your folder structure.
$pathsConfig = FCPATH . '../app/Config/Paths.php';
// ^^^ Change this if you move your application folder
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();

// Location of the framework bootstrap file.
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app       = require realpath($bootstrap) ?: $bootstrap;

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */
$app->run();
