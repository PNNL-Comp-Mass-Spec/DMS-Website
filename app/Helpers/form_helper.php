<?php

/**
 * Additional Form Helper methods to load with the CodeIgniter Form Helper
 * These are 'updated' helpers from CodeIgniter 3, and renamed to avoid conflict with CodeIgniter 4 form_helper functions
 */

// ------------------------------------------------------------------------

if ( ! function_exists('validation_error_format'))
{
	/**
	 * Validation Error String
	 *
	 * Returns the error for a specific form field. This is a helper for the
	 * form validation class.
	 *
	 * @param	\CodeIgniter\Validation\ValidationInterface $validation
	 * @param	string $field
	 * @param	string $prefix
	 * @param	string $suffix
	 * @return	string
	 */
	function validation_error_format(\CodeIgniter\Validation\ValidationInterface $validation, string $field = '', string $prefix = '', string $suffix = '')
	{
        $str = $validation->getError($field);

        if ($str === '')
        {
            return '';
        }

        return $prefix . $str . $suffix;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('validation_errors_format'))
{
	/**
	 * Validation Errors String
	 *
	 * Returns all the errors associated with a form submission. This is a helper
	 * function for the form validation class.
	 *
	 * @param	\CodeIgniter\Validation\ValidationInterface $validation
	 * @param	string $prefix
	 * @param	string $suffix
	 * @return	string
	 */
	function validation_errors_format(\CodeIgniter\Validation\ValidationInterface $validation, string $prefix = '', string $suffix = '')
	{
        $errors = $validation->getErrors();
		// No errors, validation passes!
		if (count($errors) === 0)
		{
			return '';
		}

		// Generate the error string
		$str = '';
		foreach ($errors as $field => $val)
		{
			if ($val !== '')
			{
				$str .= $prefix . $val . $suffix."\n";
			}
		}

		return $str;
	}
}
