<?php
require("Base_controller.php");

class Run_tracking extends Base_controller {

    var $maxNormalInterval = 90;

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "run_tracking";
        $this->my_title = "Run Tracking";
    }

    // --------------------------------------------------------------------
    // if we don't have a complete URL, make it so and redirect
    // otherwise return the parameters from the complete URL
    private function check_initial_conditions()
    {
        // get what we can from URL
        $instrument = $this->uri->segment(3, '');
        $year = $this->uri->segment(4, date('Y'));
        $month = $this->uri->segment(5, date('n'));

        // URL did not contain an instrument parameter
        // so choose the first instrument in the list
        if(!$instrument) {
            $il = $this->get_instrument_list();
            $instrument = $il[0]['Name'];
        }

        // Validate the month
        if(is_numeric($month)) {
            if((int)$month < 1) {
                $month = '1';
            } else {
                $month = (int)$month;
            }
        } else {
            $month = '1';
        }

        // URL was incomplete - construct one and redirect to it
        $ns = $this->uri->total_segments();
        if($ns < 5) {
            $url = $this->my_tag . "/cal/$instrument/$year/$month";
            redirect($url);
        }
        // URL was complete - return URL parameters
        return array(
            "inst" => $instrument,
            "year" => $year,
            "month" => $month
        );
    }

    // --------------------------------------------------------------------
    function cal()
    {
        $iym = $this->check_initial_conditions();
        $instrument = $iym["inst"];
        $year = $iym["year"];
        $month = $iym["month"];

        // Validate the month
        if(is_numeric($month)) {
            if((int)$month < 1) {
                $month = '1';
            } else {
                $month = (int)$month;
            }
        } else {
            $month = '1';
        }

        $data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

        $this->load->library('calendar', $this->calendar_prefs($instrument));
        $prefs = $this->calendar_prefs($instrument);
        $this->load->library('calendar', $prefs);

        $days_in_month = $this->calendar->get_total_days($month, $year);

        $calendarData = array();
        $data['rollup'] = '';

        if($instrument) {
            $result = $this->get_run_info_3($instrument, $year, $month);
            $this->maxNormalInterval = $this->get_long_interval_threshold();
            $intervals = $this->collect_long_intervals_by_day($result);
            $runs = $this->collect_run_duration_by_day($result);
            $calendarData = $this->build_calendar_data($runs, $intervals);
            $this->add_day_log_link($calendarData, $instrument, $month, $year, $days_in_month);
            $data['rollup'] = $this->stats($days_in_month, $runs, $intervals);
        }
        $data['calendarData'] = $calendarData;

        $instruments = $this->get_instrument_list($year, $month);
        $data['instrument_list'] = $this->make_instrument_selector($instruments, $instrument, $year, $month);

        $data['year'] = $year;
        $data['month'] = $month;

        // labelling information for view
        $data['title'] = "Instrument Usage ($instrument)";
        $data['heading'] = $data['title'];

        // link to list report
        $data['tracking_link'] = site_url() . $this->my_tag . "/report/$instrument/$year/$month";

        // link to operations/config logs list report
        $data['log_link'] = site_url() . "run_op_logs/report/$instrument/$year/$month/-";

        // link to usage report report
        $data['report_link'] = site_url() . "usage_reporting/param/$instrument/$year/$month/details";

        // link to ERS report report
        $data['ers_link'] = site_url() . "instrument_usage_report/report/$year/$month/$instrument";

        $this->load->vars($data);
        $this->load->view('usage_tracking/cal2');
    }

    // --------------------------------------------------------------------
    private
    function add_day_log_link(&$calendarData, $instrument, $month, $year, $days_in_month)
    {
        $logLink = site_url() . "run_op_logs/report/$instrument/$year/$month/";
        foreach(range(1, $days_in_month) as $day) {
            if(array_key_exists($day, $calendarData)) {
                $link = $logLink . $day;
                $calendarData[$day] .= "<div><a href='$link'>Log</a></div>";
            }
        }
    }

    // --------------------------------------------------------------------
    private
    function get_run_info_3($instrument, $year, $month)
    {
        $this->load->database();

        $sql = <<<EOD
SELECT *
FROM  dbo.GetRunTrackingMonthlyInfo('$instrument', '$year', '$month', '') AS GT
EOD;
// Seq, ID, Dataset, [Day], Duration, Interval, Time_Start, Time_End, Instrument
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    // --------------------------------------------------------------------
    private
    function get_long_interval_threshold()
    {
        $this->load->database();
        $query = $this->db->query('SELECT dbo.GetLongIntervalThreshold() AS Threshold');
        $row = $query->row();
        return $row->Threshold;
    }

    // --------------------------------------------------------------------
    private
    function get_instrument_list()
    {
        $this->load->database();
        $sql = " SELECT * FROM V_Instrument_Tracked ORDER BY Reporting";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

        // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function make_instrument_selector($instruments, $instrument, $year, $month)
    {
        $this->load->helper(array('form'));
        $emslLabel = 'EMSL';
        $dmsLabel = 'DMS';
        $trkdLabel = 'Tracked';
        $options = array($emslLabel => array(), $dmsLabel => array() );
        if($instruments) {
            foreach($instruments as $item) {
                $inst = $item['Name'];
                //$inst = $item;
                $link = site_url() . $this->my_tag . "/cal/$inst/$year/$month";
                $rpt = $item['Reporting'];
                switch($rpt[0]) {
                    case 'E':
                    $options[$emslLabel][$link] =  "$inst ($rpt)";
                        break;
                    case 'P':
                    $options[$dmsLabel][$link] =  "$inst ($rpt)";
                        break;
                    case 'T':
                    $options[$trkdLabel][$link] =  "$inst ($rpt)";
                        break;
                }
/*
                if($rpt[0] == 'E') {
                    $options[$emslLabel][$link] =  "$inst ($rpt)";
//                  $options[$link] = "$inst ($rpt)";
                } else {
                    $options[$dmsLabel][$link] =  "$inst ($rpt)";
                }
 */
            }
        }
        $selected = site_url() . $this->my_tag . "/cal/$instrument/$year/$month";
        $id = 'instrument_sel';
        $js = "id='$id' onChange='gamma.goToSelectedPage(\"$id\");'";
        ksort($options[$emslLabel]);
        ksort($options[$dmsLabel]);
        return form_dropdown($id, $options, $selected, $js);
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function stats($days_in_month, $runs, $intervals)
    {
        $s = '';
        $totalMinutesAvailable = $days_in_month * 1440;

        if($totalMinutesAvailable==0){
            $totalMinutesAvailable = 1;
        }

        // calculate totals for acquisition runs
        $runCount = 0;
        $runDuration = 0;
        $normalIntervalCount = 0;
        $normalIntervalDuration = 0;
        foreach($runs as $run) {
            $duration = $run['Duration'];
            $interval = $run['Interval'];
            $runCount++;
            $runDuration += $duration;
            if($interval <= $this->maxNormalInterval) {
                $runDuration += $interval;
                $normalIntervalCount++;
                $normalIntervalDuration += $interval;
            }
        }

        // calculate average normal interval
        $avgNormalInterval = ($normalIntervalCount) ? $normalIntervalDuration / $normalIntervalCount : 0;

        // calculate totals for long intervals
        $intervalCount = 0;
        $intervalDuration = 0;
        foreach($intervals as $interval) {
            $intervalCount++;
            $intervalDuration += $interval['Interval'];
        }



        $s .= "<div>" . "Total Runs: $runCount" . "</div>";
        $s .= "<div>" . "Total Run Duration: $runDuration" . " (" . number_format(100 * $runDuration/$totalMinutesAvailable) . "%)" . "</div>";
        $s .= "<div>" . "Average Normal Interval: " . number_format($avgNormalInterval) . "</div>";
        $s .= "<div>" . "Total Long Intervals: $intervalCount" . "</div>";
        $s .= "<div>" . "Total Long Interval Duration: $intervalDuration" . " (" . number_format(100 * $intervalDuration/$totalMinutesAvailable) . "%)" . "</div>";
//      $unaccounted = $totalMinutesAvailable - ($runDuration + $intervalDuration);
//      $s .= "<div>" . "Unaccounted: $unaccounted" . " (" . number_format(100 * $unaccounted/$totalMinutesAvailable) . "%)" . "</div>";
        return $s;
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function collect_run_duration_by_day($rows)
    {
        $runs = array();
        reset($rows);
        foreach($rows as $row) {
            $day = $row["Day"];
            $duration = $row["Duration"];
            $interval = $row["Interval"];
            if($duration > 0) {
                $runs[] = array("Day"=>$day, "Duration"=>$duration, "Interval"=>$interval);
            }
        }
        return $runs;
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function collect_long_intervals_by_day($rows)
    {
        $intervals = array();
        reset($rows);
        foreach($rows as $row) {
            $id = $row['ID'];
            $day = $row["Day"];
            $interval = $row["Interval"];
            $comState = $row['CommentState'];
            $comment = $row['Comment'];
            $dt = new DateTime();
            if (is_string($row['Time_End'])) {
                $dt = strtotime($row['Time_End']);
            }
            else {
                $dt = $row['Time_End'];
            }
            $hour = date("g A", $dt);
            if($interval > $this->maxNormalInterval) {
                $intervals[] = array("ID"=>$id, "Day"=>$day, "Interval"=>$interval, "Hour"=>$hour, "ComState"=>$comState, "Comment"=>$comment);
            }
        }
        return $intervals;
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function build_calendar_data($runs, $intervals)
    {
        // roll up number of runs and total duration for each day
        $runTotals = array();
        foreach($runs as $run) {
            $day = $run['Day'];
            $duration = $run['Duration'];
            $interval = $run['Interval'];
            if(!array_key_exists($day, $runTotals)) {
                $runTotals[$day] = array("Runs"=>0, "Duration"=>0);
            }
            $runTotals[$day]["Runs"] += 1;
            $runTotals[$day]["Duration"] += $duration;
            if($interval <= $this->maxNormalInterval) {
                $runTotals[$day]["Duration"] += $interval;
            }
        }

        // add rolled up run count and duration to calendar display data
        $calendarData = array();
        foreach($runTotals as $day=>$total) {
            $nRuns = $total["Runs"];
            $duration = $total["Duration"];
            $calendarData[$day] = "<div>Runs:&nbsp;$nRuns</div><div>Duration:&nbsp;$duration</div>";
        }
        // add long intervals to calendar display data
        foreach($intervals as $interval) {
            $id = $interval['ID'];
            $tip = "[$id] " . $interval['Comment'];
            $link = site_url() . "run_interval/edit/" . $interval['ID'];
            $day = $interval['Day'];
            $int = $interval['Interval'];
            $hour = $interval['Hour'];
            $comState = $interval['ComState'];
            $attbrs = "class='boink'";
            if(!array_key_exists($day, $calendarData)) {
                $calendarData[$day] = '';
            }
            if($comState == 'x') {
                $calendarData[$day] .= "<div>$hour:&nbsp;$int</div>";
            } else {
                $color = ($comState == '+')?"green":"red";
                $calendarData[$day] .= "<div style='color:$color'>$hour:&nbsp;<a href='$link' title='$tip'>$int</a></div>";
            }
        }
        return $calendarData;
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function calendar_display($instrument, $year, $month, $calendarData)
    {
        echo $this->calendar->generate($year, $month, $calendarData);
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function calendar_prefs($instrument)
    {
        $prefs['day_type'] = 'long';

        $prefs['show_next_prev'] = true;
        $prefs['next_prev_url'] = site_url($this->my_tag . "/cal/$instrument");

        $label = '<span style="font-size:1.5em;" >{day}</span><br>';
        $content = '{content}<br>';
        $populated = $label . $content;
        $empty =  $label;
        $previous = '<a href="{previous_url}">&lt; Previous</a>';
        $next = '<a href="{next_url}">Next &gt;</a>';
        $heading = '<h3>{heading}</h3>';

        $template = '

           {table_open}<table class="runTable" border="1" cellpadding="1" cellspacing="1">{/table_open}

           {heading_row_start}<tr>{/heading_row_start}

           {heading_previous_cell}<th>@previous@</th>{/heading_previous_cell}
           {heading_title_cell}<th colspan="{colspan}">@heading@</th>{/heading_title_cell}
           {heading_next_cell}<th>@next@</th>{/heading_next_cell}

           {heading_row_end}</tr>{/heading_row_end}

           {week_row_start}<tr>{/week_row_start}
           {week_day_cell}<td class="weekDays" >{week_day}</td>{/week_day_cell}
           {week_row_end}</tr>{/week_row_end}

           {cal_row_start}<tr>{/cal_row_start}
           {cal_cell_start}<td style="vertical-align:top; padding:4px;">{/cal_cell_start}

           {cal_cell_content}@content@{/cal_cell_content}
           {cal_cell_content_today}@content@{/cal_cell_content_today}

           {cal_cell_no_content}@empty@{/cal_cell_no_content}
           {cal_cell_no_content_today}@empty@{/cal_cell_no_content_today}

           {cal_cell_blank}&nbsp;{/cal_cell_blank}

           {cal_cell_end}</td>{/cal_cell_end}
           {cal_row_end}</tr>{/cal_row_end}

           {table_close}</table>{/table_close}
        ';

        $template = str_replace('@content@', $populated, $template);
        $template = str_replace('@empty@', $empty, $template);
        $template = str_replace('@previous@', $previous, $template);
        $template = str_replace('@next@', $next, $template);
        $template = str_replace('@heading@', $heading, $template);

        $prefs['template'] = $template;
        return $prefs;
    }
}
?>
