<?php

    // Include the String operations methods
    require_once(BASEPATH . '../application/libraries/String_operations.php');

    /**
     * Check for the field either matching a special tag or containing a special tag
     * String comparisons are case sensitive
     * @param type $value
     * @return type
     */
    function convert_special_values($value)
    {
        // Check the field fully matching a special tag
        switch($value) {
            case "__ThisYear__":
                return date("Y");
            case "__LastYear__":
                return date("Y", strtotime("last year"));
            case "__ThisMonth__":
                return  date("n");
            case "__LastMonth__":
                return date("n", strtotime("last month"));
            case "__ThisWeek__":
                return date("W");
            case "__LastWeek__":
                return date("W", strtotime("last week"));
        }

        // Check for special tags at the start
        if (StartsWith($value, "StartsWith__")) {
            // Use a backtick to signify that the value must start with the value
            $newValue = str_replace("StartsWith__", "`", $value);
        } else if (StartsWith($value, "ExactMatch__")) {
            // Use a tilde to signify that the value must exactly match the value
            $newValue = str_replace("ExactMatch__", "~", $value);
        } else if (StartsWith($value, "IsBlank__")) {
            // Use \b to indicate that the field must be empty
            $newValue = str_replace("IsBlank__", "\b", $value);
        } else if (StartsWith($value, "NoMatch__")) {
            // Use a colon to signify that the value cannot contain the value
            $newValue = str_replace("NoMatch__", ":", $value);
        } else {
            $newValue = $value;
        }

        // Check for the special Wildcard tag in the middle (allow both __Wildcard__ and __WildCard__)
        // If found, replace with a percent sign to signify a wildcard match
        $finalValue = str_ireplace("__Wildcard__", "%", $newValue);

        return $finalValue;
    }

