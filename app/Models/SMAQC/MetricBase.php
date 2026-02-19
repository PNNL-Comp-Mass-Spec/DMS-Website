<?php
namespace App\Models\SMAQC;

use CodeIgniter\Model;

/**
 * CodeIgniter base model for a SMAQC metric.
 *
 * @author Trevor Owen <trevor.owen@email.wsu.edu>
 */
abstract class MetricBase extends Model
{
    /**
     * The name of the instrument.
     * @var string
     */
    protected $instrument;

    /**
     * Optional dataset name filter
     * @var string
     */
    protected $datasetfilter;

    /**
     * The name of the metric.
     * @var string
     */
    protected $metric;

    /**
     * The QCDM threshold for flagging data as bad
     * @var float
     */
    protected $limit;

    /**
     * The units for the metric
     * A string that is retrieved from a database.
     * @var string
     */
    protected $metric_units;

    /**
     * The definition of the metric.
     * A string that is retrieved from a database.
     * @var string
     */
    protected $definition;

    /**
     * The start date for grabbing metrics.
     * This should be a human readable string of the format m-d-Y.
     * (Example: 11-11-2011)
     * @var string
     */
    protected $querystartdate;

    /**
     * The end date for grabbing metrics.
     * This should be a human readable string of the format m-d-Y.
     * (Example: 11-11-2011)
     * @var string
     */
    protected $queryenddate;

    /**
     * The start date for plotting metrics.; unix datetime
     * @var int
     */
    protected $unixstartdate;

    /**
     * The end date for plotting metrics.; unix datetime
     * @var int
     */
    protected $unixenddate;

    /**
     * The results of querying the database for the metric values.
     * The type is what is returned by a call to CI's Active Record db->get().
     * @var object
     */
    protected $data;

    /**
     * An array of (x,y) values for the metric being plotted; includes data
     * outside the date range being plotted (to allow for more accurate computation of median and MAD, or avg and stdev)
     * The x value is a unix timestamp, in seconds
     * The y value is the metric value
     * metricdata[$i][0] is date
     * metricdata[$i][1] is the metric vaue
     * metricdata[$i][2] is the fractionset number
     * @var array
     */
    protected $metricdata;

    /**
     * An array of (x,y) values
     * The x value is a time/date in milliseconds.
     * The y values are the metric values to plot
     * @var array
     */
    protected $plotdata;

    /**
     * An array of (x,y) values
     * The x value is a time/date in milliseconds.
     * The y values are the metric values to plot purple because the data is not released
     * @var array
     */
    protected $plotDataBad;

    /**
     * An array of (x,y) values
     * The x value is a time/date in milliseconds.
     * The y values are the metric values to plot orange because the QCDM (or QC-ART) value is past a threshold
     * @var array
     */
    protected $plotDataPoor;

    /**
     * An array of (x,y) values
     * The x value is a time/date in milliseconds.
     * The median metric value across a moving window, or across the datasets in a given fraction set
     * @var array
     */
    protected $plotdata_average;

    /**
     * An array of (x,y) values
     * The x value is a time/date in milliseconds.
     * The y value is the upper bound standard deviation value to plot
     * QC-ART: The y value 6.55, plotted with a red line, indicating a threshold for very bad scores
     * @var array
     */
    protected $stddevupper;

    /**
     * An array of (x,y) values
     * The x value is a time/date in milliseconds.
     * The y value is the lower bound standard deviation value to plot
     * However, when the metric is QCDM, this array is used to track the threshold (limit) for in control vs. out of control
     * QC-ART: The y value 4, plotted with a yellow line, indicating a threshold for poor scores
     * @var array
     */
    protected $stddevlower;

    /**
     * List of dates for which metric data exists
     *
     * @var List<int>
     */
    protected $dateList;

    /**
     * Date window radius; not used by QC-ART (it does not need it in separate functions)
     *
     * @var int
     */
    protected $windowRadius;

    /**
     * Constructor
     *
     * The contructor for MetricBase simply calls the constructor for the base
     * class (Model). All initialization of the class must be done using the
     * initialize function. The reasoning for this has to do with the way CI
     * loads models in the controller (they cannot take arguments).
     *
     * @return MetricBase
     */
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Uses $windowSize and $this->unixstartdate/$this->unixenddate to set appropriate values for $this->querystartdate and $this->queryenddate
     *
     * @param int $windowSize the number of days before/after the start/end dates to use for filtering
     */
    abstract protected function setQueryWindow($windowSize);

    /*
     * Uses $windowSize and $this->unixstartdate/$this->unixenddate to set appropriate values for $this->querystartdate and $this->queryenddate
     *
     * @param int $windowSize the number of days before/after the start/end dates to use for filtering
     */
    protected function setQueryWindowDefault($windowSize)
    {
        // windowRadius is how many days to the left/right to average around
        $this->windowRadius = (int)($windowSize / 2);

        if ($this->windowRadius < 1)
            $this->windowRadius = 1;

        // Set the query start date to $this->windowRadius days prior to $start
        $this->querystartdate  = date("Y-m-d", strtotime('-' . $this->windowRadius . ' day', $this->unixstartdate));
        $this->queryenddate    = date("Y-m-d", strtotime(      $this->windowRadius . ' day', $this->unixenddate));
    }

    /*
     * Reads the needed metric data from the database and stores the query result in $this->data
     *
     * @param string $metric The name of the metric.
     *
     */
    private function readDataForMetric($metric)
    {
        // Build the query to get all the metric points in the specified range, for example
        // SELECT dataset_id,
        //        acq_time_start,
        //        ms1_1,
        //        dataset_rating,
        //        dataset,
        //        quameter_job,
        //        smaqc_job,
        //        quameter_last_affected,
        //        smaqc_last_affected,
        //        dataset_rating_id,
        //        qcdm
        // FROM v_dataset_qc_metrics_export
        // WHERE instrument = 'Exploris04' AND
        //       acq_time_start >= '2025-02-02' AND
        //       acq_time_start <= '2025-06-03' AND
        //       ms1_1 IS NOT NULL
        // ORDER BY acq_time_start DESC;

        $columns = array(
                         'acq_time_start',
                         'dataset_id',
                         'dataset',
                         'quameter_job',
                         'smaqc_job',
                         'quameter_last_affected',
                         'smaqc_last_affected',
                         'dataset_rating',
                         'dataset_rating_id',
                         $metric,
                         'qcdm'
                        );

        $builder = $this->db->table('v_dataset_qc_metrics_export');
        $builder->select(join(',', $columns));
        $builder->where('instrument =', $this->instrument);
        $builder->where('acq_time_start >=', $this->querystartdate);
        $builder->where('acq_time_start <=', $this->queryenddate . 'T23:59:59.999');
        $valueNotNull = $metric . " IS NOT NULL";
        $builder->where($valueNotNull);

        if (strlen($this->datasetfilter) > 0)
        {
            $builder->like('dataset', $this->datasetfilter);
        }

        $builder->orderBy('acq_time_start', 'desc');

        // Run the query, we may not actually need to store this in the model, but for now we will
        $this->data = $builder->get();
    }

    /*
     * Process the data returned by the query and populate arrays $this->metricdata, $this->plotdata, $this->plotDataBad, $this->plotDataPoor
     *
     * @param string $metric The name of the metric.
     */
    abstract protected function processMetricData($metric);

    /*
     * Process the data returned by the query and populate arrays $this->metricdata, $this->plotdata, $this->plotDataBad, $this->plotDataPoor, and $this->dateList
     *
     * @param string $metric The name of the metric.
     */
    protected function processMetricDataDefault($metric)
    {
        $this->dateList = array();              // List of dates for which metric data exists

        // Get just the data we want for plotting
        foreach($this->data->getResult() as $row)
        {
            // Skip the value if it's null (no longer necessary in June 2025 since we now filter out null values using the where clause)
            if(is_null($row->$metric))
            {
                continue;
            }

            // Need to convert the date from the mssql format to one that jqplot will like

            // Cutoff fractional seconds, leaving only the date data we want
            $pattern = '/:[0-9][0-9][0-9]/';
            $date = preg_replace($pattern, '', $row->acq_time_start);

            $date = strtotime($date);

            $datasetIsBad = 0;

            if(strstr($row->dataset,'QC_Shew') !== FALSE)
            {
                // QC_Shew dataset
                if ($row->qcdm > $this->limit)
                {
                    $datasetIsBad = 1;
                }
            }

            if ($row->dataset_rating_id >= -5 && $row->dataset_rating_id <= 1)
            {
                $datasetIsBad = 2;
            }

            if ($datasetIsBad == 0 || $datasetIsBad == 1)
            {
                // Add the value to the metricdata array
                $this->metricdata[] = array($date, $row->$metric);
            }

            // Add the value to the plotdata array if it is within the user-specified plotting range
            if ($date >= $this->unixstartdate && $date <= $this->unixenddate)
            {
                if ($datasetIsBad != 0)
                {
                    if($datasetIsBad == 1)
                    {
                        // Dataset with poor QCDM score
                        // JavaScript likes milliseconds, so multiply $date by 1000 when appending to the array
                        $this->plotDataPoor[] = array($date * 1000, $row->$metric, $row->dataset);
                    }
                    if($datasetIsBad == 2)
                    {
                        // Not Released dataset
                        // JavaScript likes milliseconds, so multiply $date by 1000 when appending to the array
                        $this->plotDataBad[] = array($date * 1000, $row->$metric, $row->dataset);
                    }
                }
                else
                {
                    // JavaScript likes milliseconds, so multiply $date by 1000 when appending to the array
                    $this->plotdata[] = array($date * 1000, $row->$metric, $row->dataset);
                }

                // Append to $this->dateList if a new date
                // First round $date to the midnight of the given day
                $dateMidnight = strtotime("0:00", $date);
                if (count($this->dateList) == 0)
                {
                    // Data is returned from v_dataset_qc_metrics_export sorted descending
                    // Thus, add one day past $this->dateList so that the average and trend lines extend past the last data point
                    $this->dateList[] = strtotime('+1 day', $dateMidnight);
                    $this->dateList[] = $dateMidnight;
                }
                else {
                    if ($this->dateList[count($this->dateList)-1] != $dateMidnight)
                        $this->dateList[] = $dateMidnight;
                }
            }
        }
    }

    /*
     * Compute the statistics and populate arrays $this->plotdata_average, $this->stddevupper, $this->stddevlower
     *
     * @param string $metric The name of the metric.
     */
    abstract protected function computeStatistics($metric);

    /**
     * Initializer for the MetricBase model
     *
     * Gets all of the needed values for the class/model from the database.
     * Calculates any values that need calculating.
     *
     * @param string $instrument The name of the instrument.
     * @param string $metric The name of the metric.
     * @param string $start A human readable string for the start of the date range. Assumed to be in m-d-Y format. (Example: 11-11-2011)
     * @param string $end A human readable string for the end of the date range. Assumed to be in m-d-Y format. (Example: 12-12-2012)
     * @param int $windowSize Window (in days) of extra information to include outside of $start and $end
     * @param string $datasetFilter Optional dataset name filter
     *
     * @return array|boolean An array containing error information if there is
     * an error, FALSE otherwise.
     * Error Array Format: ['type' => string, 'value' => string]
     */
    protected function initBase($instrument, $metric, $start, $end, $windowSize = 20, $datasetFilter = '')
    {
        // Change the string format of the dates, as strtotime doesn't work right with -'s
        $start = str_replace('-', '/', $start);
        $end   = str_replace('-', '/', $end);

        // Set all the proper values
        $this->instrument = $instrument;
        $this->metric     = $metric;

        $this->unixstartdate  = strtotime($start);
        $this->unixenddate    = strtotime($end);

        // Set the query start date and end date
        $this->setQueryWindow($windowSize);

        // Use a limit customized for the given instrument
        // This value is not used by QC-ART
        // Default to 0.25 if the instrument is not recognized
        $this->limit = 0.25;

        if(strstr($instrument,'Exact') !== FALSE)
        {
            $this->limit = 0.07;
        }
        if(strstr($instrument,'LTQ_2') !== FALSE || strstr($instrument,'LTQ_3') !== FALSE || strstr($instrument,'LTQ_4') !== FALSE || strstr($instrument,'LTQ_FB1') !== FALSE || strstr($instrument,'LTQ_ETD_1') !== FALSE)
        {
            // Old: $this->limit = 0.05;
            $this->limit = 0.1;
        }
        if(strstr($instrument,'LTQ_Orb') !== FALSE || strstr($instrument,'Orbi_FB1') !== FALSE || strstr($instrument,'LTQ_FT1') !== FALSE)
        {
            $this->limit = 0.23;
        }
        if(strstr($instrument,'VOrbi') !== FALSE || strstr($instrument,'VPro') !== FALSE || strstr($instrument,'External_Orb') !== FALSE)
        {
            $this->limit = 0.11;
            // Old: $this->limit = 0.2;
        }

        $this->datasetfilter  = $datasetFilter;

        // Check to see that this is a valid instrument/metric
        $builder = $this->db->table('v_dataset_qc_metrics_export');
        $builder->where('instrument', $instrument);

        $query = $builder->get(1);

        if($query->getNumRows() < 1)
        {
            return array("type" => "instrument", "value" => $instrument);
        }

        if(!$this->db->fieldExists($metric, 'v_dataset_qc_metrics_export'))
        {
            return array("type" => "metric", "value" => $metric);
        }

        // Lookup the Description, purpose, units, and Source for this metric
        $builder = $this->db->table('v_dataset_qc_metric_definitions');
        $builder->select('description, purpose, units, source');
        $builder->where('metric', $metric);
        $query = $builder->get(1);

        if($query->getNumRows() < 1)
        {
            $this->definition = $metric . " (definition not found in DB)";
        }
        else
        {
            $row = $query->getRow();
            $this->definition = $metric . " (" . $row->source . "): " . $row->description . " <br>" . $row->purpose;
            $this->metric_units = $row->units;

            if(strstr($metric,'qcdm') !== FALSE)
            {
                // QCDM metric
                if(strstr($instrument,'Exact') !== FALSE)
                {
                    $this->definition .= "Metrics used: MS1_TIC_Q2, MS1_Density_Q1";
                }
                else if(strstr($instrument,'LTQ_2') !== FALSE || strstr($instrument,'LTQ_3') !== FALSE || strstr($instrument,'LTQ_4') !== FALSE || strstr($instrument,'LTQ_FB1') !== FALSE || strstr($instrument,'LTQ_ETD_1') !== FALSE)
                {
                    $this->definition .= "Metrics used: XIC_WideFrac, MS2_Density_Q1, P_2C";
                }
                else if(strstr($instrument,'LTQ_Orb') !== FALSE || strstr($instrument,'Orbi_FB1') !== FALSE || strstr($instrument,'LTQ_FT1') !== FALSE)
                {
                    $this->definition .= "Metrics used: XIC_WideFrac, MS1_TIC_Change_Q2, MS1_Density_Q1, MS1_Density_Q2, DS_2A, P_2B, P_2A, DS_2B";
                }
                else if(strstr($instrument,'VOrbi') !== FALSE || strstr($instrument,'VPro') !== FALSE || strstr($instrument,'External_Orb') !== FALSE)
                {
                    $this->definition .= "Metrics used: XIC_WideFrac, MS2_Density_Q1, MS1_2B, P_2B, P_2A, DS_2B";
                }
                else
                {
                    $this->definition .= "Metrics used: Not defined";
                }
            }
        }

        // Read all the metric points in the specified range from the database
        $this->readDataForMetric($metric);

        // Initialize the data arrays so that we can append data
        $this->metricdata = array();
        $this->plotdata = array();
        $this->plotDataBad = array();           // Not Released (aka bad)
        $this->plotDataPoor = array();          // QCDM/QC-ART value out-of-range (aka low quality)

        $this->processMetricData($metric);

        $this->plotdata_average = array();
        $this->stddevupper = array();
        $this->stddevlower = array();

        $this->computeStatistics($metric);

        // Check to see if there were any data points in the date range
        if(count($this->plotdata) < 1)
        {
            // Put an empty array in there so that jqplot will display properly,
            // and not break JavaScript on the page
            $this->plotdata[] = array();
        }

        if(count($this->plotDataBad) < 1)
        {
            // Put an empty array in there so that jqplot will display properly,
            // and not break JavaScript on the page
            $this->plotDataBad[] = array();
        }

        if(count($this->plotDataPoor) < 1)
        {
            // Put an empty array in there so that jqplot will display properly,
            // and not break JavaScript on the page
            $this->plotDataPoor[] = array();
        }

        /* get the average (we'll use the selectAvg() call for now, as it
           deals with nulls, but we may want to do this in php instead of using
           the db */
        /*
        ** Not used
        **
        $builder = $this->db->table('v_dataset_qc_metrics_export');
        $builder->selectAvg($metric, 'avg');
        $builder->where('acq_time_start >=', $this->querystartdate);
        $builder->where('acq_time_start <=', $this->queryenddate . 'T23:59:59.999');
        $builder->where('instrument', $instrument);
        $this->average = $builder->get()->getRow()->avg;
        */

        return FALSE; // No errors, so return false
    }

    /**
     * Bundles the results of the processing into a single object and returns it
     *
     * @return \App\Libraries\SMAQCMetricData
     */
    public function getData()
    {
        $data = new \App\Libraries\SMAQCMetricData();
        $data->queryResults = $this->data;
        $data->definition = $this->definition;

        // Put everything for javascript plotting into json encoded arrays
        $data->plotData = json_encode($this->plotdata);
        $data->plotDataAverage = json_encode($this->plotdata_average);
        $data->stdDevUpper = json_encode($this->stddevupper);
        $data->stdDevLower = json_encode($this->stddevlower);
        $data->plotDataBad = json_encode($this->plotDataBad);
        $data->plotDataPoor = json_encode($this->plotDataPoor);
        $data->metricUnits = json_encode($this->metric_units);

        return $data;
    }
}
?>
