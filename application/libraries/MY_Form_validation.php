<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
	}

	// --------------------------------------------------------------------
	// validation functions
	// --------------------------------------------------------------------

	// called for every field
	// convert exotic characters to plan ASCII and trim whitespaced off ends
	function trim($str)
	{
		$str = trim(iconv("utf-8", "ASCII//TRANSLIT", $str));
//		$_POST[$this->_current_field] = $str;
		return $str;
	}

	function char_set($str, $parm)
	{
		$charSet = $parm;
		$result = (preg_match("/^[$charSet]+$/", $str)) ? TRUE : FALSE;
		if(!$result) {
			$this->set_message('char_set', "The %s field must contain only characters from '$charSet'");
		}
		return result;
	}

	function char_set_base($str, $parm)
	{
		$charSet = $parm . 'a-zA-Z0-9_-';
		$pattern = "/^([".$charSet."])+$/";
		$result = ( ! preg_match("$pattern", $str)) ? FALSE : TRUE;
		if(!$result) {
			$this->set_message('char_set_base', "The %s field must contain only characters from '$charSet'");
		}
		return $result;
	}

	function name_space($str)
	{
		$result = ( ! preg_match("/^([ a-zA-Z0-9_-])+$/", $str)) ? FALSE : TRUE;
		if(!$result) {
			$this->set_message('name_space', "The %s field must contain only letters, numbers, space, dash, or underscore");
		}
		return $result;
	}

	function os_filename($str)
	{
		// Note that we do not allow periods to prevent folder names from containing periods and to prevent filenames from containing a period before the file extension
		$result = ( ! preg_match("/^([ a-zA-Z0-9_!@#$%^&(){}\[\];,-])+$/", $str)) ? FALSE : TRUE;
		if(!$result) {
			$this->set_message('os_filename', "The %s field cannot contain any of these characters: \\ / : * ? . \" ' < > |");
		}
		return $result;
	}

	function work_package($str)
	{
		$parm = '[A-Za-z][A-Za-z0-9]{5}';
		$result = (preg_match("/$parm/", $str)) ? TRUE : FALSE;
		if(!$result) {
			$this->set_message('work_package', "The %s field must be a valid work package format");
		}
		return $result;
	}

	function default_value($str, $parm)
	{
		return TRUE;
	}

	function normalize_delimited_list($str, $parm)
	{
		/* $_POST[$this->_current_field] = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str); */
		return TRUE;
	}

	function not_contain($str, $parm)
	{
		if (strpos($str, $parm) === FALSE) {
			return TRUE;
		} else {
			$this->set_message('not_contain', "The %s field can not contain the word '$parm'");
			return FALSE;
		}
	}
	function valid_date($parm)
	{
		$t = strtotime($parm);
		if(!$t) {
			$this->set_message('valid_date', "Could not recognize '$parm' in %s field as a valid date");
			return FALSE;
		} else {
//			$_POST[$this->_current_field] = date('n/j/Y', $t);
			return TRUE;
		}
	}
}
?>