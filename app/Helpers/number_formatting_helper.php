<?php

/**
 * Look for item $itemName in the Options array in $colSpec
 * If found, return its value, otherwise return $valueIfMissing
 * @param type $colSpec
 * @param string $itemName
 * @param string $valueIfMissing
 * @return type
 */
function getOptionValue($colSpec, $itemName, $valueIfMissing = "") {
    if (array_key_exists('Options', $colSpec)) {
        $options = $colSpec['Options'];
        if ($options != null &&
                is_array($colSpec['Options']) &&
                array_key_exists($itemName, $colSpec['Options'])) {
            return $colSpec['Options'][$itemName];
        }
    }
    return $valueIfMissing;
}

/**
 * Convert a value to a string, rounding to the number of decimal points defined by the Decimals option in the page config
 * @param type $value Value to convert
 * @param type $colSpec Column specification
 * @param type $alwaysAddCommas When true, always group numbers to the left of the decimal point using commas
 *                              When false, only do so if the Commas option is defined and has a non-zero value
 * @return type
 */
function valueToString($value, $colSpec, $alwaysAddCommas) {

    if (!is_numeric($value)) {
        return $value;
    }

    if ($alwaysAddCommas === true) {
        $decimals = getOptionValue($colSpec, 'Decimals', '0');
    } else {
        $decimals = getOptionValue($colSpec, 'Decimals', '-1');
    }

    if ($decimals === '-1') {
        // Do not format
        return $value;
    }

    // Convert from a string to a float
    $valueNum = floatval($value);

    if ($alwaysAddCommas) {
        $addCommaFlag = '1';
    } else {
        $addCommaFlag = getOptionValue($colSpec, 'Commas', '0');
    }

    if (strlen($addCommaFlag) > 0 && $addCommaFlag !== '0') {
        // Add commas for the thousands separator
        $formattedValue = number_format($valueNum, $decimals);
        $maxLength = 15;
    } else {

        // Construct the format string
        // For example, if $decimals is 3, $formatString will be '%.3f'
        $formatString = '%.' . $decimals . 'f';
        $formattedValue = sprintf($formatString, $valueNum);

        $newDecimals = $decimals;
        while (strlen($formattedValue) >= 10 && $newDecimals > 0) {
            // Show fewer decimal points for large numbers
            $newDecimals--;
            $formatString = '%.' . $newDecimals . 'f';
            $formattedValue = sprintf($formatString, $valueNum);
        }

        $maxLength = 10;
    }

    if (strlen($formattedValue) >= $maxLength) {
        // Use exponential notation (scientific notation)
        // Construct the format string
        // For example, if $decimals is 3, $formatString will be '%.3e'
        $formatString = '%.' . $decimals . 'e';

        $formattedValue = sprintf($formatString, $valueNum);

        if (preg_match('/00+e/', $formattedValue)) {
            // Number has numerous zeroes before the "e", for example 1.00e+20 or 1.400e+20
            // Remove the extra zeroes
            $formattedValue = preg_replace('/00+e/', 'e', $formattedValue);

            // If the number is now of the form 1.e+20 add a zero after the decimal point, giving 1.0e+20
            $formattedValue = preg_replace("/.e/", ".0e", $formattedValue);
        }

        return $formattedValue;
    }

    if (preg_match('/\.[0-9]*0$/', $formattedValue) && !preg_match('/e/i', $formattedValue)) {
        // The value has multiple zeroes after the decimal point, for example 34.4300 or 82.000
        // (the third preg_match excludes numbers with an e, which is used for exponential notation)

        $charsToKeep = strlen($formattedValue);
        while ($charsToKeep > 1 && substr($formattedValue, $charsToKeep - 1, 1) === "0") {
            $charsToKeep--;
        }

        if (substr($formattedValue, $charsToKeep - 1, 1) === ".") {
            // Remove the trailing decimal place
            $charsToKeep--;
        }

        $formattedValue = substr($formattedValue, 0, $charsToKeep);
    }

    if ($formattedValue === '-0') {
        return '0';
    }

    return $formattedValue;
}
?>
