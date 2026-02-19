<?php
namespace App\Models\SMAQC;

use CodeIgniter\Model;

/**
 * CodeIgniter model for a SMAQC metric
 *
 * @author Trevor Owen <trevor.owen@email.wsu.edu>
 */
class MetricModel extends MetricBase
{
    /**
     * Constructor
     *
     * The contructor for MetricModel simply calls the constructor for the base
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
     * Compute the median of the values in metricdata, limiting to date within the specified date range
     *
     * @param datetime $windowStartDate: The start date; unix date/time
     * @param datetime $windowEndDate:   The end date; unix date/time
     *
     * Returns the median or NULL if no data in the time range
     */
    function compute_windowed_median($windowStartDate, $windowEndDate)
    {
        $dataCount = count($this->metricdata);

        $dataInWindow = array();

        for($i = 0; $i < $dataCount; $i++)
        {
            if ($this->metricdata[$i][0] >= $windowStartDate && $this->metricdata[$i][0] <= $windowEndDate)
            {
                $dataInWindow[] = $this->metricdata[$i][1];
            }
        }

        if (count($dataInWindow) > 0)
            return $this->compute_median($dataInWindow);
        else
            return NULL;
    }

     /*
     * Compute the median median of the values in $values
     *
     */
    function compute_median($values)
    {
        $count = count($values);
        $median = 0;

        switch ($count)
        {
            case 0:
                $median = 0;
                break;

            case 1:
                $median = $values[0];
                break;

            default:
                sort($values);

                $midpoint = intval($count / 2);

                if($count % 2 == 0) {
                    $median = ($values[$midpoint] + $values[$midpoint-1]) / 2;
                } else {
                    $median = $values[$midpoint];
                }

                break;
        }

        return $median;
    }

     /*
     * Compute the median absolute deviation (MAD) of the values in metricdata, limiting to date within the specified date range
     *
     * @param datetime $windowStartDate: The start date; unix date/time
     * @param datetime $windowEndDate:   The end date; unix date/time
     * @param float $medianInWindow:        Median of the values in the window; compute using compute_windowed_median prior to calling this function
     *
     * Returns the median absolute deviation or NULL if no data in the time range
     */
    function compute_windowed_mad($windowStartDate, $windowEndDate, $medianInWindow)
    {
        // Method described at http://en.wikipedia.org/wiki/Median_absolute_deviation

        $dataCount = count($this->metricdata);
        $median = 0;

        // $residuals holds the absolute value of the residuals (deviations) from medianInWindow
        $residuals = array();

        for($i = 0; $i < $dataCount; $i++)
        {
            if ($this->metricdata[$i][0] >= $windowStartDate && $this->metricdata[$i][0] <= $windowEndDate)
            {
                $residuals[] = abs($this->metricdata[$i][1] - $medianInWindow);
            }
        }

         $median = $this->compute_median($residuals);

         return $median;
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
        $s0 = count($this->dateList);

        // Calculate median absolute deviation using the provided window size
        if($s0 > 0)
        {
            $medianInWindow = 0.0;
            $mad = 0.0;

            // Uncomment to debug
            // echo "Date, MedianInWindow, MAD, LowerBoundMAD, UpperBoundMAD<br>";

            for($dateIndex = 0; $dateIndex < $s0; $dateIndex++)
            {
                if(strstr($metric, 'qcdm') !== FALSE)
                {
                    // The metric is QCDM
                    // Use a limit customized for the given instrument

                    // Javascript likes milliseconds, so multiply $date by 1000 when appending to the array
                    $this->stddevlower[] = array(
                        $this->dateList[$dateIndex] * 1000,
                        $this->limit
                        );

                    continue;
                }

                // The metric is not QCDM
                // Compute the median value within a time period

                // Get the date to the left by the window radius
                $sqlDateTimeLeftUnix = strtotime('-' . $this->windowRadius . ' day', $this->dateList[$dateIndex]);

                // Get the date to the right by the window radius
                $sqlDateTimeRightUnix = strtotime($this->windowRadius . ' day', $this->dateList[$dateIndex]);

                // Compute the median of the metric values over the date range (using both good and "low quality" datasets)
                $medianInWindow = $this->compute_windowed_median($sqlDateTimeLeftUnix, $sqlDateTimeRightUnix);

                if (is_null($medianInWindow))
                    continue;

                // Javascript likes milliseconds, so multiply $date by 1000 when appending to the array
                $this->plotdata_average[] = array(
                    $this->dateList[$dateIndex] * 1000,
                    $medianInWindow
                    );

                // Compute the median absolute deviation over the date range
                $mad = $this->compute_windowed_mad($sqlDateTimeLeftUnix, $sqlDateTimeRightUnix, $medianInWindow);

                $lowerBoundMAD = $medianInWindow - (1.5 * $mad);
                $upperBoundMAD = $medianInWindow + (1.5 * $mad);

                if ($lowerBoundMAD < 0)
                    $lowerBoundMAD = 0;

                // Javascript likes milliseconds, so multiply $date by 1000 when appending to the array
                $this->stddevlower[] = array(
                    $this->dateList[$dateIndex] * 1000,
                    $lowerBoundMAD
                    );

                $this->stddevupper[] = array(
                    $this->dateList[$dateIndex] * 1000,
                    $upperBoundMAD
                    );

                // Uncomment to debug
                // echo date('m/d/Y H:i:s', $this->dateList[$dateIndex]) . ", " . $medianInWindow . ", " . $mad . ", " . $lowerBoundMAD . ", " . $upperBoundMAD . "<br>";

            } // End of for loop
        } // End of calculating stddev
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
