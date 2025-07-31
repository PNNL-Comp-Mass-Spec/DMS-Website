<?php
namespace App\Controllers;

class Chooser extends BaseController {

    protected $helpers = ['url', 'text', 'form'];

    // --------------------------------------------------------------------
    function index()
    {
        echo "CI Rocks!";
    }

    /**
     *  This returns HTML for a drop-down selector and suitable options
     *  for the specified chooser_name.  It is suitable for AJAX
     * @param string $target_field_name
     * @param string $chooser_name
     * @param string $mode
     */
    function get_chooser($target_field_name, $chooser_name, $mode)
    {
        echo $this->getChoosers()->get_chooser($target_field_name, $chooser_name, $mode);
    }

    // --------------------------------------------------------------------
    function get_chooser_list()
    {
        echo "<table>\n";
        foreach($this->getChoosers()->get_chooser_names() as $chooser_name) {
            $url = site_url("chooser/get_chooser/preview/$chooser_name/replace");
            echo "<tr>";
            echo "<td><a href='$url'>$chooser_name</a></td>\n";
            echo "<td>".$this->getChoosers()->get_chooser('preview', $chooser_name, 'replace')."</td>";
            echo "</tr>";
        }
        echo "</table>\n";
    }

    /**
     * This returns list of selections for the specified chooser_name.  It is suitable for AJAX
     * @param string $chooser_name
     */
    function get_choices($chooser_name)
    {
        $x = array_keys( $this->getChoosers()->get_choices($chooser_name, false) );
        \Config\Services::response()->setContentType("application/json");
        echo json_encode($x);
    }

    /**
     * This returns list of selections for the specified chooser_name, as key-value pairs.  It is suitable for AJAX
     * @param string $chooser_name
     */
    function get_choices_kv($chooser_name)
    {
        $x = $this->getChoosers()->get_choices($chooser_name, false);
        \Config\Services::response()->setContentType("application/json");
        echo json_encode($x);
    }

    /**
     * This returns list of selections for the specified chooser_name.  It is suitable for AJAX
     * @param string $chooser_name
     * @param string $filter_value
     */
    function json($chooser_name, $filter_value = '')
    {
        if(!$filter_value) {
            $filter_value = $this->request->getPost('filter_values');
        }
        $x = $this->getChoosers()->get_filtered_choices($chooser_name, $filter_value);
        \Config\Services::response()->setContentType("application/json");
        echo json_encode($x);
    }
}
?>
