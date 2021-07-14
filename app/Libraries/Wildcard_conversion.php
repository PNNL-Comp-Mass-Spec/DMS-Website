<?php
namespace App\Libraries;

// Include the String operations methods
require_once(BASEPATH . '../application/libraries/String_operations.php');

/**
 * Check for the field either matching a special tag or containing a special tag
 * String comparisons are case sensitive
 * @param type $value
 * @return type
 */
function convert_special_values($value) {
    // Check the field fully matching a special tag
    switch ($value) {
        // Year only
        case '__ThisYear__':
            return date('Y');
        case '__LastYear__':
            return date('Y', strtotime('last year'));

        // Month only
        case '__ThisMonth__':
            return date('n');
        case '__LastMonth__':
            return date('n', strtotime('last month'));

        // Week of year
        case '__ThisWeek__':
            return date('W');
        case '__LastWeek__':
            return date('W', strtotime('last week'));

        // January 1 for this year or last year
        case '__StartThisYear__':
            return date('Y-m-d', strtotime(date('Y-01-01')));
        case '__StartLastYear__':
            return date('Y-m-d', strtotime((date('Y') - 1) . '-01-01'));

        // Today's date or the date one year ago
        case '__Today__':
            return date('Y-m-d');
        case '__TodayLastYear__':
            return date('Y-m-d', strtotime('last year'));

        case 'IsBlank__':
            // Use \b to indicate that the field must be empty
            return "\b";
    }

    // Check for special tags at the start
    if (StartsWith($value, 'StartsWith__')) {
        // Use a backtick to signify that the value must start with the value
        $newValue = str_replace('StartsWith__', '`', $value);
    } else if (StartsWith($value, 'ExactMatch__')) {
        // Use a tilde to signify that the value must exactly match the value
        $newValue = str_replace('ExactMatch__', '~', $value);
    } else if (StartsWith($value, 'NoMatch__')) {
        // Use a colon to signify that the value cannot contain the value
        $newValue = str_replace('NoMatch__', ':', $value);
    } else {
        $newValue = $value;
    }

    // Check for special tags in the middle (case insensitive matching)
    // Replace the special tags with the corresponding character: %, [, or ]
    // % signifies a wildcard match
    // Square brackets are used to define a range of characters, e.g. [5-8]
    
    $newValue2 = str_ireplace('__Wildcard__', '%', $newValue);
    $newValue3 = str_ireplace('__LeftBracket__', '[', $newValue2);
    $finalValue = str_ireplace('__RightBracket__', ']', $newValue3);

    return $finalValue;
}
?>
