<?php

// --------------------------------------------------------------------
function get_user() {
    $user = '(unknown)';
    $serverUser = \Config\Services::superglobals()->server("REMOTE_USER");
    if (isset($serverUser)) {
        $user = str_replace('@PNL.GOV', '', $serverUser);
    }
    return $user;
}
