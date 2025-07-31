<?php

if (! function_exists('IsNullOrWhiteSpace'))
{
    /**
     * Return True if the variable is null or empty (aka IsNullOrEmpty)
     * @param string|null $variable
     * @return bool
     */
    function IsNullOrWhiteSpace(?string $variable): bool
    {
        return (!isset($variable) || trim($variable) === '');
    }
}

if (! function_exists('IsNotWhitespace'))
{
    /**
     * Return True if the variable is defined and is not whitespace
     * @param string|null $variable
     * @return bool
     */
    function IsNotWhitespace(?string $variable): bool
    {
        return (isset($variable) && trim($variable) !== '');
    }
}

if (! function_exists('GetNullIfBlank'))
{
    /**
     * Return null if the variable is not defined or is whitespace
     * @param string|null $variable
     * @return string|null
     */
    function GetNullIfBlank(?string $variable): ?string
    {
        if (IsNotWhitespace($variable))
        {
            return $variable;
        }

        return null;
    }
}

if (! function_exists('StartsWith'))
{
    /**
     * Return true if $haystack starts with $needle
     * @param string|null $haystack
     * @param string|null $needle
     * @return bool
     */
    function StartsWith(?string $haystack, ?string $needle): bool
    {
        if (is_null($haystack) || is_null($needle))
        {
            return false;
        }

        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

if (! function_exists('EndsWith'))
{
    /**
     * Return true if $haystack ends with $needle
     * @param string|null $haystack
     * @param string|null $needle
     * @return bool
     */
    function EndsWith(?string $haystack, ?string $needle): bool
    {
        if (is_null($haystack) || is_null($needle) || strlen($needle) > strlen($haystack))
        {
            return false;
        }

        $length = strlen($needle);
        return (substr($haystack, strlen($haystack) - $length, $length) === $needle);
    }
}
?>
