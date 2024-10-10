<?php

namespace Config;

// Create a new instance of our RouteCollection class.
// \App\Config\Services::routes() overrides the default Services::routes()
// to return an instance of \App\Services\RouteCollection, which provides
// additional route-adding functions
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Gen::index');


// Define aliases (synonyms) that redirect to list reports
// getAlias() is defined in app/Services/RouteCollection.php. It provides a single-line method
// for creating an alias that allows a user to supply an old or potentially shortened URL,
// and go to the correct controller. addAlias() and matchAlias() are also available.
// Compared to get(), add(), and match(), the ...alias() function only needs the
// alias name and the target class name; regex matching is used to convert everything after
// the alias name to the respective function (with data) in the target class.

$routes->getAlias('analysis_jobs', 'Analysis_job');

$routes->getAlias('analysis_request', 'Analysis_job_request');

$routes->getAlias('cart_config', 'Lc_cart_configuration');

$routes->getAlias('cell_culture', 'Biomaterial');

$routes->getAlias('data_package_datasets', 'Data_package_dataset');

$routes->getAlias('data_package_analysis_job', 'Data_package_analysis_jobs');
$routes->getAlias('data_package_jobs', 'Data_package_analysis_jobs');

$routes->getAlias('dataset_files', 'Dataset_file');

$routes->getAlias('dataset_id', 'Datasetid');

$routes->getAlias('dataset_tracking', 'Tracking_dataset');

$routes->getAlias('datasets', 'Dataset');

$routes->getAlias('disposition', 'Dataset_disposition');

$routes->getAlias('eus_user', 'Eus_users');

$routes->getAlias('file_attachments', 'File_attachment');

$routes->getAlias('instrument_op_history', 'Instrument_operation_history');
$routes->getAlias('instrument_ops_history', 'Instrument_operation_history');

$routes->getAlias('lc_cart_config', 'Lc_cart_configuration');

$routes->getAlias('mass_correction_factor', 'Mass_correction_factors');

$routes->getAlias('material_locations', 'Material_location');

$routes->getAlias('mc', 'Mc_enable_control_by_manager');
$routes->getAlias('manager_control', 'Mc_enable_control_by_manager');

$routes->getAlias('protein_collections', 'Protein_collection');

$routes->getAlias('residues', 'Residue');

$routes->getAlias('reporter_ions', 'Sample_label_reporter_ions');

$routes->getAlias('settings_file', 'Settings_files');

$routes->getAlias('upload', 'Spreadsheet_loader');

$routes->getAlias('charge_codes', 'Charge_code');
$routes->getAlias('work_package', 'Charge_code');
$routes->getAlias('work_packages', 'Charge_code');
