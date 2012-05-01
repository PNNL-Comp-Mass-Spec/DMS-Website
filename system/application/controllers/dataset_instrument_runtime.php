<?php
require("base_controller.php");

class dataset_instrument_runtime extends Base_controller {
	
	var $maxNormalInterval = 30;
	
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_instrument_runtime";
		$this->my_title = "Dataset Instrument Runtime";
	}

	// --------------------------------------------------------------------
	function bar($instrument, $year, $month)
	{
		$data['nav_bar_menu_items']= $this->setup_basic_dms_page();
		
		$prefs = $this->calendar_prefs($instrument);
		$prefs['next_prev_url'] = site_url($this->my_tag . "/bar/$instrument"); 
		$this->load->library('calendar', $prefs);

		$days_in_month = $this->calendar->get_total_days($month, $year);
		$start = "$month/1/$year";
		$end = "$month/$days_in_month/$year";
		
		$info = $this->get_run_info($instrument, $start, $end);			
		$rows = $this->segregate_run_info_by_days($info, $days_in_month);
		$scale = 3;
		$calendarData = array();
		foreach(range(1, $days_in_month) as $day) {
			$calendarData[$day] = $this->render_daily_bargraph($rows[$day], $day, $scale);
		}
		$data['calendarData'] = $calendarData;

		$data['year'] = $year;
		$data['month'] = $month;

		// labelling information for view
		$data['title'] = "Instrument Usage ($instrument)";
		$data['heading'] = $data['title'];

		$this->load->vars($data);
		$this->load->view('usage_tracking/cal');
		
	}

	// --------------------------------------------------------------------
	private
	function get_run_info($instrument, $start, $end)
	{
		$this->load->database();
		
		$sql = <<<EOD
SELECT  
		TD.Dataset_ID AS ID ,
		DATEPART(HOUR, TD.Acq_Time_Start) * 60 + DATEPART(MINUTE, TD.Acq_Time_Start) AS Start,
		TD.Acq_Length_Minutes AS Duration, 
		TD.Interval_to_Next_DS AS Interval,
		DATEPART(DAY, TD.Acq_Time_Start) AS [Day],
		DATEPART(MONTH, TD.Acq_Time_Start) AS [Month]
FROM    T_Dataset TD
		INNER JOIN T_Instrument_Name TIN ON TD.DS_instrument_name_ID = TIN.Instrument_ID
WHERE	'$start' <= TD.Acq_Time_Start AND TD.Acq_Time_Start <= '$end'
		AND TIN.IN_name = '$instrument'
ORDER BY Start
EOD;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}

		// --------------------------------------------------------------------
	private
	function get_run_info_2($instrument, $start, $end)
	{
		$this->load->database();
		
		$sql = <<<EOD
SELECT 
DATEPART(DAY, Time_Start) AS [Day], * 
FROM  dbo.GetDatasetInstrumentRuntime('$start', '$end', '$instrument', 'No Intervals') AS GT
EOD;

		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	// --------------------------------------------------------------------
	private
	function segregate_run_info_by_days($rows, $days) {
		$rows_by_day = array();
		
		foreach(range(1, $days) as $day) {
			$rows_by_day[$day] = array();
		}
		foreach($rows as $row) {
			$dom = $row['Day'];
			$rows_by_day[$dom][] = $row;
		}
		return $rows_by_day;
	}

	// --------------------------------------------------------------------
	private
	function render_daily_bargraph($rows, $day, $scale)
	{
		$s = "";
		$runClass = 'p2';
		$intervalClass = 'p3';
		$maxHeight = ceil(1440 / $scale);
		$temp = 0;
		$temp2 = 0;

		$s .= "<ul class='barGraph'>\n";  				
 		
		foreach($rows as $row) {
			$id = $row['ID'];
			$dom = $row['Day'];
		
			$start = $row['Start'];
			$duration = $row['Duration'];
			$interval = $row['Interval'];
			$top = ceil($start / $scale);
			$height = ceil($duration / $scale);
			
			// FUTURE: reach back for long interval from previous day that overlaps into beginning of current day
			
			$temp += $temp2;
			$ht = ($top + $height > $maxHeight)? $maxHeight - $top : $height;
			$s .= "<li class='$runClass' style='top:${top}px; height:${ht}px; left:${temp}px;'>$id - $duration</li>\n";
			if($interval > 30) {
				$temp += $temp2;
				$top = $top + $height;
				$height = ceil($interval / $scale);
				$ht = ($top + $height > $maxHeight)? $maxHeight - $top : $height;
				$s .= "<li class='$intervalClass' style='top:${top}px; height:${ht}px; left:${temp}px;'>$id - $interval</li>\n";
			}
		}
		$s .= "</ul>\n";
		return $s;
	}

	// --------------------------------------------------------------------
	private
	function setup_basic_dms_page()
	{
		$this->load->helper(array('form', 'user', 'dms_search', 'menu'));
		$this->load->model('dms_statistics', 'model', TRUE);
		$this->load->model('dms_menu', 'menu', TRUE);
		$nav_bar_menu_items= get_nav_bar_menu_items('Statistics');
		return $nav_bar_menu_items;
	}
	
	// --------------------------------------------------------------------
	function cal($instrument, $year, $month)
	{
		$data['nav_bar_menu_items']= $this->setup_basic_dms_page();
		
		$this->load->library('calendar', $this->calendar_prefs($instrument));
		$prefs = $this->calendar_prefs($instrument);
		$this->load->library('calendar', $prefs);
		
		$days_in_month = $this->calendar->get_total_days($month, $year);
		
		$start = "$month/1/$year";
		$end = "$month/$days_in_month/$year";
		
		$result = $this->get_run_info_2($instrument, $start, $end);
		
		$intervals = $this->collect_long_intervals_by_day($result);
		
		$runs = $this->collect_run_duration_by_day($result);
		
		$calendarData = $this->build_calendar_data($runs, $intervals);
		$data['calendarData'] = $calendarData;

		$data['year'] = $year;
		$data['month'] = $month;

		// labelling information for view
		$data['title'] = "Instrument Usage ($instrument)";
		$data['heading'] = $data['title'];
		$data['rollup'] = $this->stats($days_in_month, $runs, $intervals);

		$this->load->vars($data);
		$this->load->view('usage_tracking/cal2');
	}
	
	// FUTURE: Move to helper or library
	// --------------------------------------------------------------------
	private
	function stats($days_in_month, $runs, $intervals)
	{
		$s = '';
		$totalMinutesAvialable = $days_in_month * 1440;

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
		
//		$unaccounted = $totalMinutesAvialable - ($runDuration + $intervalDuration);
		
		$s .= "<div>" . "Total Runs: $runCount" . "</div>";
		$s .= "<div>" . "Total Run Duration: $runDuration" . " (" . number_format(100 * $runDuration/$totalMinutesAvialable) . "%)" . "</div>";
		$s .= "<div>" . "Average Normal Interval: " . number_format($avgNormalInterval) . "</div>";
		$s .= "<div>" . "Total Long Intervals: $intervalCount" . "</div>";
		$s .= "<div>" . "Total Long Interval Duration: $intervalDuration" . " (" . number_format(100 * $intervalDuration/$totalMinutesAvialable) . "%)" . "</div>";
//		$s .= "<div>" . "Unaccounted: $unaccounted" . " (" . number_format(100 * $unaccounted/$totalMinutesAvialable) . "%)" . "</div>";
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
			$day = $row["Day"];
			$interval = $row["Interval"];
			$dt = strtotime($row['Time_End']);
			$hour = date("g A", $dt);
			if($interval > $this->maxNormalInterval) {
				$intervals[] = array("Day"=>$day, "Interval"=>$interval, "Hour"=>$hour);
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
			$calendarData[$day] = "<div>Runs:$nRuns &nbsp; Duration:$duration</div>";
		}
		
		// add long intervals to calendar display data
		foreach($intervals as $interval) {
			$day = $interval['Day'];
			$int = $interval['Interval'];
			$hour = $interval['Hour'];
			if(!array_key_exists($day, $calendarData)) {
				$calendarData[$day] = '';
			}
			$calendarData[$day] .= "<div>$hour:&nbsp;<a href='javascript:void(0);' title='Hovering on the actual link would show you the details of the long interval.  Clicking it would take you to a page where you could edit a comment about what caused it'>$int</a></div>";
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
		
		$prefs['show_next_prev'] = TRUE;
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