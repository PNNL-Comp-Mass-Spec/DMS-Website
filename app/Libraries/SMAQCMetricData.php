<?php
namespace App\Libraries;

/**
 * SMAQC metric data for plotting
 */
class SMAQCMetricData
{
    /**
     * Metric query result data from the database
     * The results of querying the database for the metric values.
     * @var object
     */
    public $queryResults;

    /**
     * The definition of the metric.
     * A string that is retrieved from a database.
     * @var string
     */
    public string $definition = '';

    /**
     * The units for the metric
     * A string that is retrieved from a database.
     * @var string
     */
    public string $metricUnits = '';

    /**
     * A JSON encoded array of (x,y) values for javascript plotting.
     * The x value is a time/date in milliseconds.
     * The y values are the metric values to plot
     * @var string
     */
    public string $plotData = '';

    /**
     * A JSON encoded array of (x,y) values for javascript plotting.
     * The x value is a time/date in milliseconds.
     * The y values are the metric values to plot purple because the data is not released
     * @var string
     */
    public string $plotDataBad = '';

    /**
     * A JSON encoded array of (x,y) values for javascript plotting.
     * The x value is a time/date in milliseconds.
     * The y values are the metric values to plot orange because the QCDM (or QC-ART) value is past a threshold
     * @var string
     */
    public string $plotDataAverage = '';

    /**
     * A JSON encoded array of (x,y) values for javascript plotting.
     * The x value is a time/date in milliseconds.
     * The median metric value across a moving window, or across the datasets in a given fraction set
     * @var string
     */
    public string $plotDataPoor = '';

    /**
     * A JSON encoded array of (x,y) values for javascript plotting.
     * The x value is a time/date in milliseconds.
     * The y value is the upper bound standard deviation value to plot
     * QC-ART: The y value 6.55, plotted with a red line, indicating a threshold for very bad scores
     * @var string
     */
    public string $stdDevUpper = '';

    /**
     * A JSON encoded array of (x,y) values for javascript plotting.
     * The x value is a time/date in milliseconds.
     * The y value is the lower bound standard deviation value to plot
     * However, when the metric is QCDM, this array is used to track the threshold (limit) for in control vs. out of control
     * QC-ART: The y value 4, plotted with a yellow line, indicating a threshold for poor scores
     * @var string
     */
    public string $stdDevLower = '';
}
?>
