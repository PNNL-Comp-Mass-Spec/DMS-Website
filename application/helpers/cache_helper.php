<?php
    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }

    // --------------------------------------------------------------------
    // remember parameter settings between page visits
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
function save_to_cache($name, $obj)
    {
        $_SESSION[$name] =  serialize($obj);
    }

    // --------------------------------------------------------------------
    function get_from_cache($name)
    {
        if (isset($_SESSION[$name])) {
            $state = unserialize($_SESSION[$name]);
            return $state;
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------
    function clear_cache($name)
    {
        if(isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }
