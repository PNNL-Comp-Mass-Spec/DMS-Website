<?php
namespace App\Models;

use CodeIgniter\Model;

class Dms_preferences extends Model {

    const range_sep = '-';
    const list_sep = ', ';

    var $storage_name = 'dms_preferences';
    // In this array, validation holds either the separator inserted between the min and max allowed values
    // or the separator between items in a string array
    var $settings_defaults = array(
        'list_report_rows' => array(
            'label' => 'List Report Rows per Page',
            'description' => 'The default number of rows to display per list report page',
            'value' => '125',
            'validation' => Dms_preferences::range_sep,
            'allowed_values' => array(5, 1500),
        ),
        'max_report_rows' => array(
            'label' => 'Max List Report Rows per Page',
            'description' => 'The maximum number of rows that a list report page can be set to display',
            'value' => '1500',
            'validation' => Dms_preferences::range_sep,
            'allowed_values' => array(5, 4000),
        ),
        // Deprecated since unused:
        /* 'minimum_col_width' => array(
          'label' => 'Minimum Column Width',
          'description' => 'The default number of characters in a collapsed list report column',
          'value' => '10',
          'validation' => Dms_preferences::range_sep,
          'allowed_values' => array(3,20),
          ),
         */
        'date_display_format' => array(
            'label' => 'Date Display format',
            'description' => "Display format for dates",
            'value' => 'US_Standard_12hr',
            'validation' => Dms_preferences::list_sep,
            'allowed_values' => array('US_Standard_12hr', 'US_Standard_24hr', 'yyyy-mm-dd hh:mm:ss', 'yyyy-mm-dd hh:mm'),
        ),
            // Deprecated since unused:
            /*
              'remember_list_report_settings' => array(
              'label' => 'Remember List Report Settings',
              'description' => "Automatically remember list report page settings between visits",
              'value' => 'Yes',
              'validation' => Dms_preferences::list_sep,
              'allowed_values' => array('Yes', 'No'),
              ),
              'num_of_minimized_fields' => array(
              'label' => 'Minimized List Report Primary Filter Fields',
              'description' => "Number of primary filter fields displayed when list report primary filter is minimized",
              'value' => '4',
              'validation' => Dms_preferences::range_sep,
              'allowed_values' => array(1,15),
              ),
             */
    );
    var $settings = array();
    var $date_formats = array(
        'US_Standard_24hr' => 'n/j/Y H:i:s',
        'US_Standard_12hr' => 'n/j/Y g:i A',
        'yyyy-mm-dd hh:mm:ss' => 'Y-m-d H:i:s',
        'yyyy-mm-dd hh:mm' => 'Y-m-d H:i'
    );

    // --------------------------------------------------------------------
    function validate_parameter($param, $value) {
        $s = '';
        if (isset($this->settings_defaults[$param])) {
            $p = $this->settings_defaults[$param]['label'];
            $vt = $this->settings_defaults[$param]['validation'];
            $av = $this->settings_defaults[$param]['allowed_values'];
            switch ($vt) {
                case Dms_preferences::range_sep:
                    $v = (int) $value;
                    $l = (int) ($av[0]);
                    $u = (int) ($av[1]);
                    if ($l > $v || $u < $v) {
                        $s = "Input '$value' was out of range for parameter '$p'";
                    }
                    break;
                case Dms_preferences::list_sep:
                    // Uncomment the following to see the contents of the allowed values array $av
                    // echo var_dump($av)."<hr>";
                    if (!in_array($value, $av)) {
                        $s = "Input '$value' was not in list of acceptable values for parameter '$p'";
                    }
                    break;
            }
        } else {
            $s = "Could not get definition for '$param'";
        }
        return $s;
    }

    // --------------------------------------------------------------------
    function __construct() {
        //Call the Model constructor
        parent::__construct();
        $this->initialize();
    }

    // --------------------------------------------------------------------
    function get_date_format_string() {
        $format_name = $this->settings['date_display_format']['value'];
        if (!array_key_exists($format_name, $this->date_formats)) {
            $format_name = 'US_Standard_24hr';
        }
        return $this->date_formats[$format_name];
    }

    // --------------------------------------------------------------------
    function initialize() {
        $CI =& get_instance();
        helper('cookie');
        helper('user');

        $this->settings = $this->settings_defaults;
        $state = $this->load_defaults();
        if ($state) {
            foreach ($this->settings as $name => $def) {
                if (isset($state[$name])) {
                    $this->settings[$name]['value'] = $state[$name];
                }
            }
        }
    }

    // --------------------------------------------------------------------
    function get_preferences() {
        return $this->settings;
    }

    // --------------------------------------------------------------------
    function get_preference($param) {
        if (isset($this->settings[$param])) {
            return $this->settings[$param]['value'];
        } else {
            return false;
        }
    }

    // --------------------------------------------------------------------
    function set_preference($param, $value) {
        if (array_key_exists($param, $this->settings)) {
            $r = $this->validate_parameter($param, $value);
            if ($r == '') {
                $this->settings[$param]['value'] = $value;
                $this->save_defaults();
            }
            return $r;
        } else {
            return "Parameter '$param' not recognized";
        }
    }

    // --------------------------------------------------------------------
    function save_defaults() {
        $state = array();
        foreach ($this->settings as $name => $def) {
            $state[$name] = $def['value'];
        }
        $s = serialize($state);
        $_SESSION[$this->storage_name] = $s;
        $this->save_user_prefs_to_cookie($s);
    }

    // --------------------------------------------------------------------
    function load_defaults() {
        if (isset($_SESSION[$this->storage_name])) {
            $state = $_SESSION[$this->storage_name];
            return unserialize($state);
        } else {
            $s = $this->load_user_prefs_from_cookie();
            if ($s) {
                $s = unserialize($s);
            }
            return $s;
        }
    }

    // --------------------------------------------------------------------
    function clear_saved_defaults() {
        $_SESSION[$this->storage_name] = serialize(array());
        $this->save_user_prefs_to_cookie(false);
    }

    /**
     * Save the user preferences as a cookie
     * @param type $ser_state Serialized state, or false if deleting the cookie
     * @param type $cookie_life The number of seconds until expiration
     */
    function save_user_prefs_to_cookie($ser_state, $cookie_life = '2073600') {
        $user_name = get_user();
        if (!$ser_state) {
            delete_cookie($user_name);
        } else {
            $cookie = array(
                'name' => $user_name,
                'value' => $ser_state,
                'expire' => $cookie_life
            );
            set_cookie($cookie);
        }
    }

    // --------------------------------------------------------------------
    function load_user_prefs_from_cookie() {
        $user_name = get_user();
        return get_cookie($user_name);
    }
}
?>
