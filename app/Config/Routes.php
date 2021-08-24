<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Gen');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Gen::index');


// Define aliases that redirect to list reports

$routes->get('biomaterial', 'Cell_culture::index');
$routes->get('biomaterial/report/(:any)', 'Cell_culture::report/$1');
$routes->get('biomaterial/show/(:any)', 'Cell_culture::show/$1');

$routes->get('data_package_datasets', 'Data_package_dataset::index');
$routes->get('data_package_datasets/report/(:any)', 'Data_package_dataset::report/$1');

$routes->get('data_package_analysis_job', 'Data_package_analysis_jobs::index');
$routes->get('data_package_analysis_job/report/(:any)', 'Data_package_analysis_jobs::report/$1');

$routes->get('mc', 'Mc_enable_control_by_manager::index');
$routes->get('manager_control', 'Mc_enable_control_by_manager::index');

$routes->get('residues', 'Residue::index');

$routes->get('charge_codes', 'Charge_code::index');
$routes->get('work_package', 'Charge_code::index');
$routes->get('work_packages', 'Charge_code::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
