<?php
namespace App\Controllers;

class Run_tracking extends DmsBase {

    var $maxNormalInterval = 90;

    function __construct()
    {
        $this->my_tag = "run_tracking";
        $this->my_title = "Run Tracking";
    }

    // --------------------------------------------------------------------
    // If we don't have a complete URL, make it so and redirect
    // otherwise return the parameters from the complete URL
    private function check_initial_conditions()
    {
        // Get what we can from URL
        $uri = $this->request->uri;

        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $instrument = $uri->getSegment(3, '');
        $year = $uri->getSegment(4, date('Y'));
        $month = $uri->getSegment(5, date('n'));

        // URL did not contain an instrument parameter
        // so choose the first instrument in the list
        if(!$instrument) {
            $il = $this->get_instrument_list();
            $instrument = $il[0]['name'];
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
        $ns = $this->request->uri->getTotalSegments();
        if($ns < 5) {
            $url = $this->my_tag . "/cal/$instrument/$year/$month";
            redirect()->to(site_url($url));
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

        $prefs = $this->calendar_prefs($instrument);
        $this->calendar = new \App\Libraries\Calendar($prefs);

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

        // Labelling information for view
        $data['title'] = "Instrument Usage ($instrument)";
        $data['heading'] = $data['title'];

        // Link to list report
        $data['tracking_link'] = site_url($this->my_tag . "/report/$instrument/$year/$month");

        // Link to operations/config logs list report
        $data['log_link'] = site_url("run_op_logs/report/$instrument/$year/$month/-");

        // Link to usage report report
        $data['report_link'] = site_url("usage_reporting/param/$instrument/$year/$month/details");

        // Link to ERS report report
        $data['ers_link'] = site_url("instrument_usage_report/report/$year/$month/$instrument");

        $data['calendar'] = $this->calendar;

        echo view('usage_tracking/cal2', $data);
    }

    // --------------------------------------------------------------------
    private
    function add_day_log_link(&$calendarData, $instrument, $month, $year, $days_in_month)
    {
        $logLink = site_url("run_op_logs/report/$instrument/$year/$month/");
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
        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);

        $sql = <<<EOD
SELECT *
FROM  get_run_tracking_monthly_info('$instrument', '$year', '$month', '') AS GT
EOD;
// seq, id, dataset, day, duration, interval, time_start, time_end, instrument
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    // --------------------------------------------------------------------
    private
    function get_long_interval_threshold()
    {
        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);
        $query = $this->db->query('SELECT threshold_minutes as threshold FROM V_Long_Interval_Threshold');
        $row = $query->getRow();
        return $row->threshold;
    }

    // --------------------------------------------------------------------
    private
    function get_instrument_list()
    {
        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);
        $sql = " SELECT * FROM V_Instrument_Tracked ORDER BY Reporting";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

        // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function make_instrument_selector($instruments, $instrument, $year, $month)
    {
        helper(['form']);
        $emslLabel = 'EMSL';
        $dmsLabel = 'DMS';
        $trkdLabel = 'Tracked';
        $options = array($emslLabel => array(), $dmsLabel => array() );
        if($instruments) {
            foreach($instruments as $item) {
                $inst = $item['name'];
                //$inst = $item;
                $link = site_url($this->my_tag . "/cal/$inst/$year/$month");
                $rpt = $item['reporting'];
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
        $selected = site_url($this->my_tag . "/cal/$instrument/$year/$month");
        $id = 'instrument_sel';
        $js = "id='$id' onChange='dmsjs.goToSelectedPage(\"$id\");'";
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

        // Calculate totals for acquisition runs
        $runCount = 0;
        $runDuration = 0;
        $normalIntervalCount = 0;
        $normalIntervalDuration = 0;
        foreach($runs as $run) {
            $duration = $run['duration'];
            $interval = $run['interval'];
            $runCount++;
            $runDuration += $duration;
            if($interval <= $this->maxNormalInterval) {
                $runDuration += $interval;
                $normalIntervalCount++;
                $normalIntervalDuration += $interval;
            }
        }

        // Calculate average normal interval
        $avgNormalInterval = ($normalIntervalCount) ? $normalIntervalDuration / $normalIntervalCount : 0;

        // Calculate totals for long intervals
        $intervalCount = 0;
        $intervalDuration = 0;
        foreach($intervals as $interval) {
            $intervalCount++;
            $intervalDuration += $interval['interval'];
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
            $day = $row["day"];
            $duration = $row["duration"];
            $interval = $row["interval"];
            if($duration > 0) {
                $runs[] = array("day"=>$day, "duration"=>$duration, "interval"=>$interval);
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
            $id = $row['id'];
            $day = $row["day"];
            $interval = $row["interval"];
            $commentState = $row['comment_state'];
            $comment = $row['comment'];
            $dt = new \DateTime();
            if (is_string($row['time_end'])) {
                $dt = strtotime($row['time_end']);
            }
            else {
                $dt = $row['time_end'];
            }
            $hour = date("g A", $dt);
            if($interval > $this->maxNormalInterval) {
                $intervals[] = array("id"=>$id, "day"=>$day, "interval"=>$interval, "hour"=>$hour, "commentState"=>$commentState, "comment"=>$comment);
            }
        }
        return $intervals;
    }

    // FUTURE: Move to helper or library
    // --------------------------------------------------------------------
    private
    function build_calendar_data($runs, $intervals)
    {
        // Roll up number of runs and total duration for each day
        $runTotals = array();
        foreach($runs as $run) {
            $day = $run['day'];
            $duration = $run['duration'];
            $interval = $run['interval'];
            if(!array_key_exists($day, $runTotals)) {
                $runTotals[$day] = array("runs"=>0, "duration"=>0);
            }
            $runTotals[$day]["runs"] += 1;
            $runTotals[$day]["duration"] += $duration;
            if($interval <= $this->maxNormalInterval) {
                $runTotals[$day]["duration"] += $interval;
            }
        }

        // Add rolled up run count and duration to calendar display data
        $calendarData = array();
        foreach($runTotals as $day=>$total) {
            $nRuns = $total["runs"];
            $duration = $total["duration"];
            $calendarData[$day] = "<div>Runs:&nbsp;$nRuns</div><div>Duration:&nbsp;$duration</div>";
        }

        // Add long intervals to calendar display data
        foreach($intervals as $interval) {
            $id = $interval['id'];
            $tip = "[$id] " . $interval['comment'];
            $link = site_url("run_interval/edit/" . $interval['id']);
            $day = $interval['day'];
            $int = $interval['interval'];
            $hour = $interval['hour'];
            $commentState = $interval['commentState'];
            $attbrs = "class='boink'";
            if(!array_key_exists($day, $calendarData)) {
                $calendarData[$day] = '';
            }
            if($commentState == 'x') {
                $calendarData[$day] .= "<div>$hour:&nbsp;$int</div>";
            } else {
                $color = ($commentState == '+')?"green":"red";
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
