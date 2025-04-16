<?php
namespace App\Validation;

/**
 * DMS custom validation rules
 */
class DmsValidationRules
{
    /**
     * Dummy method to allow use in DmsValidationPreformat without validation errors
     * @param type $str
     * @return type
     */
    function trim($str) : bool
    {
//      $scrubbed = trim(iconv("utf-8", "ASCII//TRANSLIT", $str));
//      $_POST[$this->_current_field] = $str;
        return true;
    }

    function char_set($str, string $fields, array $data, string &$error = null) : bool
    {
        $charSet = explode(',', $fields)[0];
        $result = (preg_match("/^[$charSet]+$/", $str)) ? true : false;
        if (!$result)
        {
            $error = "The %s field must contain only characters from '$charSet'";
        }
        return $result;
    }

    function char_set_base($str, string $fields, array $data, string &$error = null) : bool
    {
        $charSet = explode(',', $fields)[0];
        $pattern = "/^([" . $charSet . "])+$/";
        $result = (!preg_match("$pattern", $str)) ? false : true;
        if (!$result)
        {
            $error = "The %s field must contain only characters from '$charSet'";
        }
        return $result;
    }

    function name_space($str, string &$error = null) : bool
    {
        $result = (!preg_match("/^([ a-zA-Z0-9_-])+$/", $str)) ? false : true;
        if (!$result)
        {
            $error = "The %s field must contain only letters, numbers, space, dash, or underscore";
        }
        return $result;
    }

    function os_filename($str, string &$error = null) : bool
    {
        // Note that we do not allow periods to prevent folder names from containing periods and to prevent filenames from containing a period before the file extension
        $result = (!preg_match("/^([ a-zA-Z0-9_!@#$%^&(){}\[\];,-])+$/", $str)) ? false : true;
        if (!$result)
        {
            $error = "The %s field cannot contain any of these characters: \\ / : * ? . \" ' < > |";
        }
        return $result;
    }

    function work_package($str, string &$error = null) : bool
    {
        $parm = '[A-Za-z][A-Za-z0-9]{5}';
        $result = (preg_match("/$parm/", $str)) ? true : false;
        if (!$result)
        {
            $error = "The %s field must be a valid work package format";
        }
        return $result;
    }

    function default_value($str) : bool
    {
        return true;
    }

    function normalize_delimited_list($str) : bool
    {
        /* $_POST[$this->_current_field] = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str); */
        return true;
    }

    function not_contain($str, string $fields, array $data, string &$error = null) : bool
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field)
        {
            if (strpos($str, $field) === true)
            {
                $error = "The %s field can not contain the word '$field'";
                return false;
            }
        }

        return true;
    }

    function valid_date($str, string &$error = null) : bool
    {
        $t = strtotime($str);
        if (!$t)
        {
            $error = "Could not recognize '$str' in %s field as a valid date";
            return false;
        }
        else
        {
//          $_POST[$this->_current_field] = date('n/j/Y', $t);
            return true;
        }
    }
}
?>
