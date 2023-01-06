<?php
namespace App\Controllers;

class Instrument_usage_report extends Grid {
    function __construct()
    {
        // Call the parent (Grid) constructor
        parent::__construct();

        $this->my_tag = "instrument_usage_report";
        $this->my_title = "Instrument Usage";

        // Include the String operations methods
        $this->helpers = array_merge($this->helpers, ['string']);
    }

    // --------------------------------------------------------------------
    // Overrides index() in Grid.php
    function index()
    {
        // Don't show the "Editing Grid Demonstration Pages".
        // Redirect to the appropriate grid editing page
        return redirect()->to(site_url($this->my_tag.'/grid'));
    }

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    function grid() {
//      $this->my_tag = "instrument_usage";
//      $this->my_title = "Instrument Usage Report";
        $save_url = 'instrument_usage_report/operation';
        $data_url = 'instrument_usage_report/grid_data';
        $this->grid_page('instrument_usage', $save_url, $data_url);
    }
    // --------------------------------------------------------------------
    function grid_data() {
        $instrument = $this->request->getPost("instrument");
        $usage = $this->request->getPost("usage");
        $proposal = $this->request->getPost("proposal");
        $year = $this->request->getPost("year");
        $month = $this->request->getPost("month");

        $this->my_tag = "instrument_usage";
        $this->db = \Config\Database::connect();
        $builder = $this->db->table("v_instrument_usage_report_list_report");
        $builder->select('seq, emsl_inst_id, instrument, type, start, minutes, proposal, usage, users, operator, comment, dataset_id as id, validation', false);

        if(IsNotWhitespace($instrument)) $builder->where("instrument in ($instrument)");
        if(IsNotWhitespace($usage)) $builder->where("usage in ($usage)");
        if(IsNotWhitespace($proposal)) $builder->where("proposal", $proposal);
        if(IsNotWhitespace($year)) $builder->where("year", $year);
        if(IsNotWhitespace($month)) $builder->where("month", $month);

        $this->grid_data_from_query($builder);
    }

    // --------------------------------------------------------------------
    function ws()
    {
        $uri = $this->request->uri;
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $year = $uri->getSegment(3, date(''));
        $month = $uri->getSegment(4, date(''));
        $instrument = $uri->getSegment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_usage_data($instrument, $yearVal, $monthVal);
        $this->export_to_tab_delimited_text($result);
    }

    // --------------------------------------------------------------------
    private
    function get_usage_data($instrument, $year, $month)
    {
        $this->db = \Config\Database::connect();

/*
        // Query method #1

        $sql = <<<EOD
SELECT *
FROM  V_Instrument_Usage_Report_Export
WHERE Year = $year AND Month = $month
ORDER BY Instrument, Year, Month, Start, Type, Seq
EOD;
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
*/

        // Query method #2
        $result = array();

        $where_array = array("year" => (int)$year, "month" => (int)$month);
        $query = $this->db->
            table("v_instrument_usage_report_export")->
            where($where_array)->
            orderBy('instrument', 'ASC')->
            orderBy('year', 'ASC')->
            orderBy('month', 'ASC')->
            orderBy('start', 'ASC')->
            orderBy('type', 'ASC')->
            orderBy('seq', 'ASC')->
            get();

        if($query && $query->getNumRows() > 0) {
          $result = $query->getResultArray();
        }

        return $result;
    }

    // --------------------------------------------------------------------
    function daily()
    {
        $uri = $this->request->uri;
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $year = $uri->getSegment(3, date(''));
        $month = $uri->getSegment(4, date(''));
        $instrument = $uri->getSegment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_daily_data($instrument, $yearVal, $monthVal, false);
        $this->export_to_tab_delimited_text($result);

    }

    // --------------------------------------------------------------------
    function dailydetails()
    {
        $uri = $this->request->uri;
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $year = $uri->getSegment(3, date(''));
        $month = $uri->getSegment(4, date(''));
        $instrument = $uri->getSegment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_daily_data($instrument, $yearVal, $monthVal, true);
        $this->export_to_tab_delimited_text($result);

    }

    // --------------------------------------------------------------------
    private
    function get_daily_data($instrument, $year, $month, $showDetails)
    {
        $this->db = \Config\Database::connect();

        if ($showDetails) {
            $udf = "GetEMSLInstrumentUsageDailyDetails";
        } else {
            $udf = "GetEMSLInstrumentUsageDaily";
        }

        $sql = "SELECT * FROM $udf($year, $month) WHERE NOT EMSL_Inst_ID Is Null ORDER BY Instrument, Start, Type, Seq";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    // --------------------------------------------------------------------
    function rollup()
    {
        $uri = $this->request->uri;
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $year = $uri->getSegment(3, date(''));
        $month = $uri->getSegment(4, date(''));
        $instrument = $uri->getSegment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_rollup_data($instrument, $yearVal, $monthVal);
        $this->export_to_tab_delimited_text($result);

    }

    // --------------------------------------------------------------------
    private
    function get_rollup_data($instrument, $year, $month)
    {
        $this->db = \Config\Database::connect();

        $sql = "SELECT * FROM GetEMSLInstrumentUsageRollup($year, $month) WHERE NOT EMSL_Inst_ID Is Null ORDER BY DMS_Instrument, Month, Day, Usage";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    // --------------------------------------------------------------------
    //
    private
    function export_to_tab_delimited_text($result)
    {
        $headers = '';
        $data = '';

        $cols = array_keys(current($result));

        $headers = implode("\t", $cols);

        // field data
        foreach($result as $row) {
            $line = '';
            foreach($cols as $name) {
                $value = $row[$name];
                if (!isset($value) || $value == "") {
                     $value = "\t";
                }
                else {
                     $value .= "\t";
                }
                $line .= $value;
            }
            $data .= trim($line)."\n";
        }

        $data = str_replace("\r","",$data);

        header("Content-type: text/plain");
//      header("Content-Disposition: attachment; filename=$filename.txt");
        echo "$headers\n$data";
    }

    private
    function validate_year($year)
    {
        if (empty($year)) {
            $year = (int)date('Y');
        }

        if(is_numeric($year)) {
            if((int)$year < 1970) {
                $yearVal = (int)date('Y');
            } else {
                $yearVal = (int)$year;
            }
        } else {
            $yearVal = (int)date('Y');
        }

        return $yearVal;
    }

    private
    function validate_month($month)
    {
        if (empty($month)) {
            $month = (int)date('m');
        }

        if(is_numeric($month)) {
            if((int)$month < 1) {
                $monthVal = 1;
            } else {
                $monthVal = (int)$month;
            }
        } else {
            $monthVal = 1;
        }

        return $monthVal;
    }
}
?>
