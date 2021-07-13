<?php
namespace App\Controllers;

// Include the String operations methods
require_once(BASEPATH . '../application/libraries/String_operations.php');

class Instrument_usage_report extends Grid {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_usage_report";
        $this->my_title = "Instrument Usage";
    }

    // --------------------------------------------------------------------
    // Overrides index() in Grid.php
    function index()
    {
        // Don't show the "Editing Grid Demonstration Pages".
        // Redirect to the appropriate grid editing page
        redirect($this->my_tag.'/grid');
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
        $instrument = $this->input->post("instrument");
        $usage = $this->input->post("usage");
        $proposal = $this->input->post("proposal");
        $year = $this->input->post("year");
        $month = $this->input->post("month");

        $this->my_tag = "instrument_usage";
        $this->load->database();
        $this->db->select('Seq , [EMSL Inst ID], Instrument , Type , CONVERT(VARCHAR(16), Start, 101) AS Start , Minutes , Proposal , Usage , Users , Operator , Comment , Dataset_ID as ID, Validation', false);
        $this->db->from("V_Instrument_Usage_Report_List_Report");

        if(IsNotWhitespace($instrument)) $this->db->where("Instrument in ($instrument)");
        if(IsNotWhitespace($usage)) $this->db->where("Usage in ($usage)");
        if(IsNotWhitespace($proposal)) $this->db->where("Proposal", $proposal);
        if(IsNotWhitespace($year)) $this->db->where("Year", $year);
        if(IsNotWhitespace($month)) $this->db->where("Month", $month);

        $this->grid_data_from_query();
    }

    // --------------------------------------------------------------------
    function ws()
    {
        $year = $this->uri->segment(3, date(''));
        $month = $this->uri->segment(4, date(''));
        $instrument = $this->uri->segment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_usage_data($instrument, $yearVal, $monthVal);
        $this->export_to_tab_delimited_text($result);
    }

    // --------------------------------------------------------------------
    private
    function get_usage_data($instrument, $year, $month)
    {
        $this->load->database();

/*
        // Query method #1

        $sql = <<<EOD
SELECT *
FROM  V_Instrument_Usage_Report_Export
WHERE [Year] = $year AND [Month] = $month
ORDER BY [Instrument], [Year], [Month], [Start]
EOD;
        $query = $this->db->query($sql);
        $result = $query->result_array();
*/

        // Query method #2
        $result = array();

        $where_array = array("Year" => (int)$year, "Month" => (int)$month);
        $query = $this->db->
            where($where_array)->
            order_by('Instrument', 'ASC')->
            order_by('Year', 'ASC')->
            order_by('Month', 'ASC')->
            order_by('Start', 'ASC')->
            get("V_Instrument_Usage_Report_Export");

        if($query && $query->num_rows() > 0) {
          $result = $query->result_array();
        }

        return $result;
    }

    // --------------------------------------------------------------------
    function daily()
    {
        $year = $this->uri->segment(3, date(''));
        $month = $this->uri->segment(4, date(''));
        $instrument = $this->uri->segment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_daily_data($instrument, $yearVal, $monthVal, false);
        $this->export_to_tab_delimited_text($result);

    }

    // --------------------------------------------------------------------
    function dailydetails()
    {
        $year = $this->uri->segment(3, date(''));
        $month = $this->uri->segment(4, date(''));
        $instrument = $this->uri->segment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_daily_data($instrument, $yearVal, $monthVal, true);
        $this->export_to_tab_delimited_text($result);

    }

    // --------------------------------------------------------------------
    private
    function get_daily_data($instrument, $year, $month, $showDetails)
    {
        $this->load->database();

        if ($showDetails) {
        	$udf = "dbo.GetEMSLInstrumentUsageDailyDetails";
        } else {
        	$udf = "dbo.GetEMSLInstrumentUsageDaily";
        }

        $sql = "SELECT * FROM $udf($year, $month) WHERE NOT EMSL_Inst_ID Is Null ORDER BY Instrument, Start";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    // --------------------------------------------------------------------
    function rollup()
    {
        $year = $this->uri->segment(3, date(''));
        $month = $this->uri->segment(4, date(''));
        $instrument = $this->uri->segment(5, '');

        $yearVal = $this->validate_year($year);
        $monthVal = $this->validate_month($month);

        $result = $this->get_rollup_data($instrument, $yearVal, $monthVal);
        $this->export_to_tab_delimited_text($result);

    }

    // --------------------------------------------------------------------
    private
    function get_rollup_data($instrument, $year, $month)
    {
        $this->load->database();

        $sql = "SELECT * FROM dbo.GetEMSLInstrumentUsageRollup($year, $month) WHERE NOT EMSL_Inst_ID Is Null ORDER BY DMS_Instrument, [Month], [Day]";
        $query = $this->db->query($sql);
        $result = $query->result_array();
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
