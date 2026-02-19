<?php
namespace App\Models\SMAQC;

use CodeIgniter\Model;

/**
 * CodeIgniter model for a SMAQC metric
 *
 * @author Trevor Owen <trevor.owen@email.wsu.edu>
 */
class MetricModel_StDev extends MetricBase
{
    /**
     * Constructor
     *
     * The contructor for MetricModel_StDev simply calls the constructor for the base
     * class (MetricBase). All initialization of the class must be done using the
     * initialize function. The reasoning for this has to do with the way CI
     * loads models in the controller (they cannot take arguments).
     *
     * @return MetricModel
     */
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Compute the average of the values in metricdata, limiting to date within the specified date range
     *
     * @param datetime $windowStartDate: The start date; unix date/time
     * @param datetime $windowEndDate:   The end date; unix date/time
     *
     * Returns the average or NULL if no data in the time range
     */
    function compute_windowed_average($windowStartDate, $windowEndDate)
    {
        $dataCount = count($this->metricdata);

        $sum = 0.0;
        $count = 0;

        for($i = 0; $i < $dataCount; $i++)
        {
            if ($this->metricdata[$i][0] >= $windowStartDate && $this->metricdata[$i][0] <= $windowEndDate)
            {
                $sum += $this->metricdata[$i][1];
                $count += 1;
            }
        }

        if ($count > 0)
            return $sum / $count;
        else
            return NULL;
    }

    /*
     * Compute the standard deviation of the values in metricdata, limiting to date within the specified date range
     *
     * @param datetime $windowStartDate: The start date; unix date/time
     * @param datetime $windowEndDate:   The end date; unix date/time
     * @param float $avgInWindow:        Average of the values in the window; compute using compute_windowed_average prior to calling this function
     *
     * Returns the standard deviation or NULL if no data in the time range
     */
    function compute_windowed_stdev($windowStartDate, $windowEndDate, $avgInWindow)
    {
        // This method of computing standard deviation is used in Microsoft Excel for the StDev() function
        // It is also listed on Wikipedia: http://en.wikipedia.org/wiki/Standard_deviation

        $dataCount = count($this->metricdata);

        // $sumSquares holds the sum of (x - average)^2
        $sumSquares = 0.0;
        $count = 0;

        $stdev = NULL;

        for($i = 0; $i < $dataCount; $i++)
        {
            if ($this->metricdata[$i][0] >= $windowStartDate && $this->metricdata[$i][0] <= $windowEndDate)
            {
                $sumSquares += pow($this->metricdata[$i][1] - $avgInWindow, 2);
                $count += 1;
            }
        }

        if ($count == 1)
        {
            $stdev = 0.0;
        }

        if ($count > 1)
        {
            // The standard deviation is the square root of $sumSquares divided by n-1
            $stdev = sqrt($sumSquares / ($count - 1));
        }

        return $stdev;
    }

    /*
     * Uses $windowSize and $this->unixstartdate/$this->unixenddate to set appropriate values for $this->querystartdate and $this->queryenddate
     *
     * @param int $windowSize the number of days before/after the start/end dates to use for filtering
     */
    protected function setQueryWindow($windowSize)
    {
        $this->setQueryWindowDefault($windowSize);
    }

    /*
     * Process the data returned by the query and populate arrays $this->metricdata, $this->plotdata, $this->plotDataBad, $this->plotDataPoor
     *
     * @param string $metric The name of the metric.
     */
    protected function processMetricData($metric)
    {
        $this->processMetricDataDefault($metric);
    }

    /*
     * Compute the statistics and populate arrays $this->plotdata_average, $this->stddevupper, $this->stddevlower
     *
     * @param string $metric The name of the metric.
     */
    protected function computeStatistics($metric)
    {
        $s0 = count($this->plotdata);

        // calculate stddev using the provided window size
        if($s0 > 0)
        {
            $avg = 0.0;
            $stdev = 0.0;

            for($i = 0; $i < $s0; $i++)
            {
                // get the date to the left by the window radius
                $sqlDateTimeLeftUnix = strtotime('-' . $this->windowRadius . ' day', $this->plotdata[$i][0]/1000);
                $sqlDateTimeLeft = date('Y-m-d H:i:s', $sqlDateTimeLeftUnix);

                // get the date to the right by the window radius
                $sqlDateTimeRightUnix = strtotime($this->windowRadius . ' day', $this->plotdata[$i][0]/1000);
                $sqlDateTimeRight = date('Y-m-d H:i:s', $sqlDateTimeRightUnix);

                // get the average over the date range
                // This will just us the range even with the poor data sets//

                $builder = $this->db->table('v_dataset_qc_metrics_export');
                $builder->selectAvg($metric, 'avg');
                $builder->where('instrument', $$this->instrument);
                $builder->where('acq_time_start >=', $sqlDateTimeLeft);
                $builder->where('acq_time_start <=', $sqlDateTimeRight);
                $avg = $builder->get()->getRow()->avg;

                $this->plotdata_average[] = array(
                    $this->plotdata[$i][0],
                    $avg
                    );

                /*
                // Compute average via code
                //this just gets us the datasets that are good

                $avg = $this->compute_windowed_average($sqlDateTimeLeftUnix, $sqlDateTimeRightUnix);

                if (!is_null($avg))
                {
                    $this->plotdata_average[] = array(
                        $this->plotdata[$i][0],
                        $avg
                        );
                }
                */

                // get the standard deviation over the date range

                /*
                ** Could compute the standard deviation by querying the database, but this is very slow
                $builder = $this->db->table('v_dataset_qc_metrics');
                $builder->select('STDEV(' . $metric . ') as stddev');
                $builder->where('instrument', $instrument);
                $builder->where('acq_time_start >=', $sqlDateTimeLeft);
                $builder->where('acq_time_start <=', $sqlDateTimeRight);
                $stddev = $builder->get()->getRow()->stddev;
                */

                if (!is_null($avg))
                {
                    if(strstr($metric,'qcdm') !== FALSE)
                    {
                        // Gives the limit depending on the instrument that is being run
                        $stddev = $this->compute_windowed_stdev($sqlDateTimeLeftUnix, $sqlDateTimeRightUnix, $avg);

                        $this->stddevlower[] = array(
                            $this->plotdata[$i][0],
                            $this->limit
                            );
                    }
                    else
                    {
                        // Compute the standard deviation via code
                        $stddev = $this->compute_windowed_stdev($sqlDateTimeLeftUnix, $sqlDateTimeRightUnix, $avg);

                        $this->stddevupper[] = array(
                            $this->plotdata[$i][0],
                            $avg + ($stddev)
                            );

                        $lowerBoundStDev = $avg - ($stddev);
                        if ($lowerBoundStDev < 0)
                            $lowerBoundStDev = 0;

                        $this->stddevlower[] = array(
                            $this->plotdata[$i][0],
                            $lowerBoundStDev
                            );
                    }
                }

            } // end of loop
        } // end of calculating stddev
    }

    /**
     * Initializer for the Metric model
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
    public function init($instrument, $metric, $start, $end, $windowSize = 20, $datasetFilter = '')
    {
        return $this->initBase($instrument, $metric, $start, $end, $windowSize, $datasetFilter);
    }
}
?>
