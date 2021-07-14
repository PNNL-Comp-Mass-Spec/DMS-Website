<?php
namespace App\Libraries;

/**
 * Return True if the variable is null or empty (aka IsNullOrEmpty)
 * @param type $variable
 * @return type
 */
function IsNullOrWhiteSpace($variable) {
    return (!isset($variable) || trim($variable) === '');
}

/**
 * Return True if the variable is defined and is not whitespace
 * @param type $variable
 * @return type
 */
function IsNotWhitespace($variable) {
    return (isset($variable) && trim($variable) !== '');
}

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
?>
