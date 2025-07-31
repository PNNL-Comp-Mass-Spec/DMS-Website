<?php
namespace App\Controllers;

class Analysis_job_request_psm extends DmsBase {
    function __construct()
    {
        $this->my_tag = "analysis_job_request_psm";
        $this->my_title = "PSM Analysis Job Request";
    }

    // --------------------------------------------------------------------
    private
    function get_defaults_from_db()
    {
        $operation = $this->getLibrary('Operation', 'na', $this->my_tag);
        $response = $operation->internal_operation('operations_sproc');
        $response->parms = $operation->get_params();
        return $response;
    }

    // --------------------------------------------------------------------
    function get_defaults()
    {
        $results = $this->get_defaults_from_db();
        $metadata_tab = $this->make_metadata_table($results->parms->metadata);
        $supplemental_form = $this->make_supplemental_param_form($results->parms->defaults, $results->result);
        $message = $this->make_message($results->message);
        echo $metadata_tab . $supplemental_form . $message;
    }

    // --------------------------------------------------------------------
    private
    function make_message($message)
    {
        $s = '';
        $s .= "<div>$message</div>";
        return $s;
    }

    // --------------------------------------------------------------------
    private
    function make_metadata_table($metadata)
    {
        if(!$metadata) {
            return "";
        }

        $s = '';
        $s .= "<table class='EPag'>";
        $md_list = explode('|', $metadata);
        $header = true;
        
        foreach($md_list as $md) {
            $kv = explode(':', $md);
            if(count($kv) == 3) {
                $k = $kv[0];
                $v = $kv[1];
                $c = $kv[2];
                if($header) {
                    $s .= "<tr><th>$k</th><th>$v</th><th>$c</th></tr>\n";
                    $header = false;
                } else {
                    $s .= "<tr><td>$k</td><td>$v</td><td>$c</td></tr>\n";
                }
            }
        }
        $s .= "</table>\n";
        return $s;
    }

    // --------------------------------------------------------------------
    private
    function make_supplemental_param_form($default_values, $result)
    {
        $dv_list = explode('|', $default_values);
        $dvs = array();
        foreach($dv_list as $dv) {
            $kv = explode(":", $dv);
            if(count($kv) == 2) {
                $dvs[$kv[0]] = $kv[1];
            }
        }

        $code = ($result == 0)?'success':'failure';

        $s = '';
        $s .= "<form id='suggested_values'>";
        $s .= "<input type='hidden' name='return_code' id='return_code' value='$code' />";
        foreach($dvs as $name => $val) {
            $s .= "<input type='hidden' name='suggested_{$name}' id='suggested_{$name}' value='$val' />";
        }
        $s .= "</form>\n";

        return $s;
    }
}
?>
