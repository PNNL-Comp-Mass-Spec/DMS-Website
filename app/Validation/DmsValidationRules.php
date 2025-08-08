<?php
namespace App\Validation;

/**
 * DMS custom validation rules
 */
class DmsValidationRules
{
    /**
     * NOTE: See app/Language/en/Validation.php for error strings for 2-parameter rules
     * Also see https://codeigniter4.github.io/userguide/libraries/validation.html#setting-custom-error-messages
     * and https://codeigniter4.github.io/userguide/libraries/validation.html#creating-custom-rules
     * for information on custom rules and the options used in error strings
     * IMPORTANT: to use the {field}, {param}, and {value} tags in error messages, the error message must be 
     * set in app/Language/en/Validation.php, not by setting the value of $error.
     */

    /**
     * Dummy method to allow use in DmsValidationPreformat without validation errors
     * @param string $str
     * @return bool
     */
    function trim($str) : bool
    {
//      $scrubbed = trim(iconv("utf-8", "ASCII//TRANSLIT", $str));
//      $_POST[$this->_current_field] = $str;
        return true;
    }

    function char_set($str, string $params, array $data, ?string &$error = null, string $field = '') : bool
    {
        $charSet = explode(',', $params)[0];
        $result = (preg_match("/^[$charSet]+$/", $str)) ? true : false;
        if (!$result)
        {
            $error = "The $field field must contain only characters from '$charSet'";
        }
        return $result;
    }

    function char_set_base($str, string $params, array $data, ?string &$error = null, string $field = '') : bool
    {
        $charSet = explode(',', $params)[0];
        $pattern = "/^([" . $charSet . "])+$/";
        $result = (!preg_match("$pattern", $str)) ? false : true;
        if (!$result)
        {
            $error = "The $field field must contain only characters from '$charSet'";
        }
        return $result;
    }

    function name_space($str, ?string &$error = null) : bool
    {
        $result = (!preg_match("/^([ a-zA-Z0-9_-])+$/", $str)) ? false : true;
        //if (!$result)
        //{
        //    $error = "The {field} field must contain only letters, numbers, space, dash, or underscore";
        //}
        return $result;
    }

    function os_filename($str, ?string &$error = null) : bool
    {
        // Note that we do not allow periods to prevent folder names from containing periods and to prevent filenames from containing a period before the file extension
        $result = (!preg_match("/^([ a-zA-Z0-9_!@#$%^&(){}\[\];,-])+$/", $str)) ? false : true;
        //if (!$result)
        //{
        //    $error = "The {field} field cannot contain any of these characters: \\ / : * ? . \" ' < > |";
        //}
        return $result;
    }

    function work_package($str, ?string &$error = null) : bool
    {
        $parm = '[A-Za-z][A-Za-z0-9]{5}';
        $result = (preg_match("/$parm/", $str)) ? true : false;
        //if (!$result)
        //{
        //    $error = "The {field} field must be a valid work package format";
        //}
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

    function not_contain($str, string $params, array $data, ?string &$error = null, string $field = '') : bool
    {
        $params = explode(',', $params);

        foreach ($params as $param)
        {
            if (strpos($str, $param) !== false)
            {
                $error = "The $field field can not contain the word '$param'";
                return false;
            }
        }

        return true;
    }

    function valid_date($str, ?string &$error = null) : bool
    {
        $t = strtotime($str);
        if (!$t)
        {
            //$error = "Could not recognize '$str' in {field} field as a valid date"; // "Could not recognize '{value}' in {field} field as a valid date"
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
