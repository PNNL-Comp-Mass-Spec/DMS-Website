<?php
namespace App\Validation;

/**
 * Validation preformatting methods.
 * Each method specified here must also exist as a method in DmsRules, but simply return 'true'.
 * This is to avoid validation failures.
 */
class DmsValidationPreformat {

    /**
     * Called for every field
     * Convert exotic characters to plain ASCII and trim whitespace off ends
     * @param type $str
     * @return type
     */
    function trim($str) : string {
        $scrubbed = trim(iconv("utf-8", "ASCII//TRANSLIT", $str));
//      $_POST[$this->_current_field] = $str;
        return $scrubbed;
    }

    function default_value($str, string $default) : string {
        return $str;
    }

    function normalize_delimited_list($str) : string {
        /* $_POST[$this->_current_field] = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str); */
        return $str;
    }
}
?>
