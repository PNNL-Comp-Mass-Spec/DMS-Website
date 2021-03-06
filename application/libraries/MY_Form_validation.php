<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Form validation functions
 */
class MY_Form_validation extends CI_Form_validation {

    /**
     * Constructor
     */
    function __construct() {
        // Call the parent constructor
        parent::__construct();
    }

    /**
     * Called for every field
     * Convert exotic characters to plain ASCII and trim whitespace off ends
     * @param type $str
     * @return type
     */
    function trim($str) {
        $scrubbed = trim(iconv("utf-8", "ASCII//TRANSLIT", $str));
//      $_POST[$this->_current_field] = $str;
        return $scrubbed;
    }

    function char_set($str, $parm) {
        $charSet = $parm;
        $result = (preg_match("/^[$charSet]+$/", $str)) ? true : false;
        if (!$result) {
            $this->set_message('char_set', "The %s field must contain only characters from '$charSet'");
        }
        return result;
    }

    function char_set_base($str, $parm) {
        $charSet = $parm . 'a-zA-Z0-9_-';
        $pattern = "/^([" . $charSet . "])+$/";
        $result = (!preg_match("$pattern", $str)) ? false : true;
        if (!$result) {
            $this->set_message('char_set_base', "The %s field must contain only characters from '$charSet'");
        }
        return $result;
    }

    function name_space($str) {
        $result = (!preg_match("/^([ a-zA-Z0-9_-])+$/", $str)) ? false : true;
        if (!$result) {
            $this->set_message('name_space', "The %s field must contain only letters, numbers, space, dash, or underscore");
        }
        return $result;
    }

    function os_filename($str) {
        // Note that we do not allow periods to prevent folder names from containing periods and to prevent filenames from containing a period before the file extension
        $result = (!preg_match("/^([ a-zA-Z0-9_!@#$%^&(){}\[\];,-])+$/", $str)) ? false : true;
        if (!$result) {
            $this->set_message('os_filename', "The %s field cannot contain any of these characters: \\ / : * ? . \" ' < > |");
        }
        return $result;
    }

    function work_package($str) {
        $parm = '[A-Za-z][A-Za-z0-9]{5}';
        $result = (preg_match("/$parm/", $str)) ? true : false;
        if (!$result) {
            $this->set_message('work_package', "The %s field must be a valid work package format");
        }
        return $result;
    }

    function default_value($str, $parm) {
        return true;
    }

    function normalize_delimited_list($str, $parm) {
        /* $_POST[$this->_current_field] = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str); */
        return true;
    }

    function not_contain($str, $parm) {
        if (strpos($str, $parm) === false) {
            return true;
        } else {
            $this->set_message('not_contain', "The %s field can not contain the word '$parm'");
            return false;
        }
    }

    function valid_date($parm) {
        $t = strtotime($parm);
        if (!$t) {
            $this->set_message('valid_date', "Could not recognize '$parm' in %s field as a valid date");
            return false;
        } else {
//          $_POST[$this->_current_field] = date('n/j/Y', $t);
            return true;
        }
    }

}
