<?php

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
     * Return true if $needle starts with $haystack
     * @param type $haystack
     * @param type $needle
     * @return type
     */
    function StartsWith($haystack, $needle)
    {
         $length = strlen($needle);
         return (substr($haystack, 0, $length) === $needle);
    }
    