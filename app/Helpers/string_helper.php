<?php

if (! function_exists('IsNullOrWhiteSpace'))
{
    /**
     * Return True if the variable is null or empty (aka IsNullOrEmpty)
     * @param string|null $variable
     * @return type
     */
    function IsNullOrWhiteSpace(?string $variable) {
        return (!isset($variable) || trim($variable) === '');
    }
}

if (! function_exists('IsNotWhitespace'))
{
    /**
     * Return True if the variable is defined and is not whitespace
     * @param string|null $variable
     * @return type
     */
    function IsNotWhitespace(?string $variable) {
        return (isset($variable) && trim($variable) !== '');
    }
}

if (! function_exists('GetNullIfBlank'))
{
    /**
     * Return null if the variable is not defined or is whitespace
     * @param string|null $variable
     * @return type
     */
    function GetNullIfBlank(?string $variable) {
        if (IsNotWhitespace($variable)) {
            return $variable;
        }

        return null;
    }
}

if (! function_exists('StartsWith'))
{
    /**
     * Return true if $haystack starts with $needle
     * @param type $haystack
     * @param type $needle
     * @return type
     */
    function StartsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

if (! function_exists('EndsWith'))
{
    /**
     * Return true if $haystack ends with $needle
     * @param type $haystack
     * @param type $needle
     * @return type
     */
    function EndsWith($haystack, $needle) {
        if (strlen($needle) > strlen($haystack)) {
            return false;
        }

        $length = strlen($needle);
        return (substr($haystack, strlen($haystack) - $length, $length) === $needle);
    }
}
?>
