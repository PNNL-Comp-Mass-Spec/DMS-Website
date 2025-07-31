<?php

// --------------------------------------------------------------------
// Methods to remember parameter settings between page visits
// --------------------------------------------------------------------

/**
 * Save options to the session cache
 * @param string $name
 * @param mixed $obj
 */
function save_to_cache(string $name, $obj) {
    $_SESSION[$name] = serialize($obj);
}

/**
 * Load options from the session cache
 * @param string $name
 * @return mixed|bool
 */
function get_from_cache(string $name) {
    if (isset($_SESSION[$name])) {
        $state = unserialize($_SESSION[$name]);
        return $state;
    } else {
        return false;
    }
}

/**
 * Clear the cache
 * @param string $name
 */
function clear_cache(string $name) {
    if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
    }
}
