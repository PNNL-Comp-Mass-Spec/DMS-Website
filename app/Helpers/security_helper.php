<?php

use Config\Services;

/**
 * CodeIgniter Security Helpers, copied from CodeIgniter 3
 */

// ------------------------------------------------------------------------

if ( ! function_exists('xss_clean'))
{
	/**
	 * XSS Filtering
	 * Converted from CodeIgniter 3's Security class, because they dropped xss_clean (as one person said, 'There is no xss_clean function for CI4 because that is the wrong way to prevent XSS.')
	 * Used here because it fills the gap during upgrade from CodeIgniter 3 to CodeIgniter 4, and I don't have time right now to properly do what those people who say it is the wrong way say should be done.
	 *
	 * @param	string	$str
	 * @param	bool	$is_image	whether or not the content is an image file
	 * @return	string
	 */
	function xss_clean($str, $is_image = FALSE)
	{
		if (is_null($str)) {
			return $str;
		}

		return Services::xss_security()->xss_clean($str, $is_image);
	}
}

