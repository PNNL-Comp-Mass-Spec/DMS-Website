<?php

// --------------------------------------------------------------------
// Methods to remember parameter settings between page visits
// --------------------------------------------------------------------

/**
 * Save options to the session cache
 * @param type $name
 * @param type $obj
 */
function save_to_cache($name, $obj) {
    $_SESSION[$name] = serialize($obj);
}

/**
 * Load options from the session cache
 * @param type $name
 * @return bool
 */
function get_from_cache($name) {
    if (isset($_SESSION[$name])) {
        $state = unserialize($_SESSION[$name]);
        return $state;
    } else {
        return false;
    }
}

/**
 * Clear the cache
 * @param type $name
 */
function clear_cache($name) {
    if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
    }
}
