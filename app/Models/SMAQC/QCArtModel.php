<?php
namespace App\Models\SMAQC;

use CodeIgniter\Model;

/**
 * CodeIgniter model for the QC-ART metric.
 */
class QCArtModel extends MetricBase
{
    /**
     * Array tracking date values and fraction set numbers
     * Used to compute (and plot) the average QC-ART value for each fraction set
     * $fractionSetList[i][0] is date
     * $fractionSetList[i][1] is fractionSet number
     * @var array
     */
    private $fractionSetList = array();

    /**
     * QC-ART threshold for very bad scores
     * @var float
     */
    private $qcArtRedThreshold = 6.55;

    /**
     * QC-ART threshold for poor scores
     * @var float
     */
    private $qcArtYellowThreshold = 4;

    /**
     * Constructor
     *
     * The contructor for QCArtModel simply calls the constructor for the base
     * class (MetricBase). All initialization of the class must be done using the
     * initialize function. The reasoning for this has to do with the way CI
     * loads models in the controller (they cannot take arguments).
     *
     * @return QCArtModel
     */
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Compute the average value for data in metricdata that comes from the same fraction set
     *
     * @param int $fractionSetFilter: The fraction set to filter on
     *
     * Returns the average or NULL if no matching data
     */
    function compute_fractionset_average($fractionSetFilter, $fractionSetDateSeconds)
    {
        $dataCount = count($this->metricdata);

        $runningSum = 0.0;
        $countToAverage = 0;

        for($i = 0; $i < $dataCount; $i++)
        {
            if ($this->metricdata[$i][2] == $fractionSetFilter)
            {
                if ($this->DateDiffDays($this->metricdata[$i][0], $fractionSetDateSeconds) < 30)
                {
                    $runningSum += $this->metricdata[$i][1];
                    $countToAverage += 1;
                }
            }
        }

        if ($countToAverage > 0)
            return $runningSum / (float)$countToAverage;
        else
            return NULL;

    }

    // Returns the number of days between two second-based dates
    // Always returns a positive number
    function DateDiffDays($dateOneSeconds, $dateTwoSeconds)
    {
        $dateDiffSeconds = abs($dateOneSeconds - $dateTwoSeconds);

        return $dateDiffSeconds / 86400.0;
    }

    /*
     * Uses $windowSize and $this->unixstartdate/$this->unixenddate to set appropriate values for $this->querystartdate and $this->queryenddate
     *
     * @param int $windowSize the number of days before/after the start/end dates to use for filtering
     */
    protected function setQueryWindow($windowSize)
    {
        // Do not load data outside of start or end (now stored as unix timestamps in $this->unixstartdate and $this->unixenddate)
        $windowRadiusLeft = 0;
        $windowRadiusRight = 1;

        // Set the query start date to $windowRadius days prior to $start
        $this->querystartdate  = date("Y-m-d", strtotime('-' . $windowRadiusLeft  . ' day', $this->unixstartdate));
        $this->queryenddate    = date("Y-m-d", strtotime(      $windowRadiusRight . ' day', $this->unixenddate));
    }

    /*
     * Process the data returned by the query and populate arrays $this->metricdata, $this->plotdata, $this->plotDataBad, $this->plotDataPoor
     *
     * @param string $metric The name of the metric.
     */
    protected function processMetricData($metric)
    {
        // QC-ART threshold for very bad scores
        $this->qcArtRedThreshold = 6.55;

        // QC-ART threshold for poor scores
        $this->qcArtYellowThreshold = 4;

        // This array tracks date values and fraction set numbers
        // Used to compute (and plot) the average QC-ART value for each fraction set
        // $fractionSetList[i][0] is date
        // $fractionSetList[i][1] is fractionSet number
        $this->fractionSetList = array();

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

            if (!is_null($row->qcart) && $row->qcart >= $this->qcArtRedThreshold)
            {
                $datasetIsBad = 1;
            }

            if ($row->dataset_rating_id >= -5 && $row->dataset_rating_id <= 1)
            {
                $datasetIsBad = 2;
            }

            // Parse out the fraction set, for example "40" from
            // TEDDY_DISCOVERY_SET_40_23_30Nov15_Frodo_15-08-38

            $fractionSetForDataset = 0;
            $patternSetNumber = '/_SET_([0-9]+)_/';
            if (preg_match($patternSetNumber, $row->dataset, $matches)) {
                $fractionSetForDataset = (int)$matches[1];
            }

            // Uncomment to debug
            // else
            //    echo "No match to " . patternSetNumber . " for " . $row->dataset . "<br>";

            if ($datasetIsBad == 0 || $datasetIsBad == 1)
            {
                // Add the value to the metricdata array
                $this->metricdata[] = array($date, $row->$metric, $fractionSetForDataset);
            }

            // Add the value to the plotdata array if it is within the user-specified plotting range
            if ($date >= $this->unixstartdate && $date <= $this->unixenddate)
            {
                if ($datasetIsBad != 0)
                {
                    if($datasetIsBad == 1)
                    {
                        // Dataset with QC-ART score over the threshold
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

                // Append to $this->fractionSetList
                $this->fractionSetList[] = array($date, $fractionSetForDataset);
            }
        }
    }

    /*
     * Compute the statistics and populate arrays $this->plotdata_average, $this->stddevupper, $this->stddevlower
     *
     * @param string $metric The name of the metric.
     */
    protected function computeStatistics($metric)
    {
        $s0 = count($this->fractionSetList);

        // Calculate the average QC-ART value for each fraction set
        if($s0 > 0)
        {
            $cachedFractionSet = 0;
            $cachedAverage = 0.0;
            $cachedFractionSetDateSeconds = 0;

            // Uncomment to debug
            // echo "DataIndex, Date, FractionSet, FractionSetAverage<br>";

            for($dataIndex = 0; $dataIndex < $s0; $dataIndex++)
            {
                // Compute the average for the fraction set

                $currentFractionSetDate = $this->fractionSetList[$dataIndex][0];
                $currentFractionSet = $this->fractionSetList[$dataIndex][1];

                if ($currentFractionSet == 0)
                {
                    // Uncomment to debug
                    // echo $dataIndex . ", " . date('m/d/Y H:i:s', $currentFractionSetDate) . ", " . $currentFractionSet . ", InvalidFractionSet<br>";
                    continue;
                }

                // Javascript likes milliseconds, so multiply $date by 1000 when appending to the array
                $currentDateMillisec = $currentFractionSetDate * 1000;

                if ($cachedFractionSet != $currentFractionSet || $this->DateDiffDays($cachedFractionSetDateSeconds, $currentFractionSetDate) > 30) {

                    $newAverage = $this->compute_fractionset_average($currentFractionSet, $currentFractionSetDate);

                    /*
                    if ($cachedFractionSet != 0)
                    {
                        // Add some additional values to make the line be a step functin
                        $midPointDateMillisec = (int)(($cachedFractionSetDateSeconds * 1000 + $currentDateMillisec) / 2.0);

                        $leftPoint = $midPointDateMillisec - 3600000;
                        if ($leftPoint < $cachedFractionSetDateSeconds * 1000 + 10000)
                            $leftPoint = $cachedFractionSetDateSeconds * 1000 + 10000;

                        $rightPoint = $midPointDateMillisec + 3600000;
                        if ($rightPoint > $currentDateMillisec - 10000)
                            $rightPoint = $currentDateMillisec - 10000;

                        $this->plotdata_average[] = array(
                        $leftPoint,
                        $cachedAverage
                        );

                        echo $dataIndex . ", " . date('m/d/Y H:i:s', $leftPoint/1000.0) . ", " . $cachedFractionSet . ", " . $cachedAverage . " (filler)<br>";

                        $this->plotdata_average[] = array(
                        $rightPoint,
                        $newAverage
                        );

                        echo $dataIndex . ", " . date('m/d/Y H:i:s', $rightPoint/1000.0) . ", " . $currentFractionSet . ", " . $newAverage . " (filler)<br>";

                    }
                    */

                    $cachedFractionSet = $currentFractionSet;
                    $cachedFractionSetDateSeconds = $currentFractionSetDate;

                    $cachedAverage = $newAverage;
                }

                if (is_null($cachedAverage))
                    continue;

                $this->plotdata_average[] = array(
                    $currentDateMillisec,
                    $cachedAverage
                    );

                $this->stddevlower[] = array(
                    $currentDateMillisec,
                    $this->qcArtYellowThreshold
                    );

                $this->stddevupper[] = array(
                    $currentDateMillisec,
                    $this->qcArtRedThreshold
                    );

                // Uncomment to debug
                // echo $dataIndex . ", " . date('m/d/Y H:i:s', $currentFractionSetDate) . ", " . $cachedFractionSet . ", " . $cachedAverage . "<br>";

            } // end of loop
        } // end of calculating stddev
    }

    /**
     * Initializer for the QC-ART model
     *
     * Gets all of the needed values for the class/model from the database.
     * Calculates any values that need calculating.
     *
     * @param string $instrument The name of the instrument.
     * @param string $metric The name of the metric (should always be "QCART" for now)
     * @param string $start A human readable string for the start of the date range. Assumed to be in m-d-Y format. (Example: 11-11-2011)
     * @param string $end A human readable string for the end of the date range. Assumed to be in m-d-Y format. (Example: 12-12-2012)
     * @param string $datasetFilter Optional dataset name filter
     *
     * @return array|boolean An array containing error information if there is
     * an error, FALSE otherwise.
     * Error Array Format: ['type' => string, 'value' => string]
     */
    public function init($instrument, $metric, $start, $end, $datasetFilter = '')
    {
        return $this->initBase($instrument, $metric, $start, $end, 0, $datasetFilter);
    }
}
?>
