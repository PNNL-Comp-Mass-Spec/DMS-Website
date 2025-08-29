<?php
/*
|--------------------------------------------------------------------------
| Per-environment config variables for DMS
|--------------------------------------------------------------------------
*/

$appConfig = [];

// Is the user accessing DMS from bionet?
//$server_bionet = stripos($_SERVER["SERVER_NAME"], ".bionet") !== false;

//if ($server_bionet) {
//    $appConfig['pwiki'] = 'http://prismwiki.bionet/wiki/';
//}
//else {
//    $appConfig['pwiki'] = 'https://prismwiki.pnl.gov/wiki/';
//}
$appConfig['wikiHelpLinkPrefix'] = 'DMS_Help_for_';

$appConfig['version_color_code'] = 'LightGreen';
$appConfig['version_label'] = 'Development';

$appConfig['modify_config_db_enabled'] = true;

$appConfig['file_attachment_archive_root_path'] = "/mnt/dms_attachments/";

$appConfig['file_attachment_local_root_path'] = "/files1/dms_attachments/";

$appConfig['model_config_path'] = "./model_config/" ;

$appConfig['dms_inst_source_url'] = "http://gigasax.pnl.gov" ;

return $appConfig;

?>
