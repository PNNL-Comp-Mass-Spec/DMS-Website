<?php
namespace App\Controllers;

use App\Controllers;

/**
 * Modified from SMAQC
 */

class Nom_stats extends BaseController
{
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url'];

    /**
     * List of instrument names that have NOM QC metrics
     *
     * @var list<string>
     */
    private array $instrumentlist;

    /**
     * List of metric names
     *
     * @var list<string>
     */
    private array $metriclist;

    /**
     * Metric names and short descriptions
     *
     * @var array<string, string>
     */
    private array $metricShortDescription;

    var $defaultstartdate;
    var $defaultenddate;
    var $DEFAULTWINDOWSIZE = 45;
    var $DEFAULTUNIT = "days";

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.:
        // $this->session = \Config\Services::session();

        $this->defaultstartdate = date("m-d-Y", strtotime("-4 months"));
        $this->defaultenddate   = date("m-d-Y", time());
        $this->metriclist       = array();
        $this->metricShortDescription = array();
        $this->instrumentlist   = array();

        $db = \Config\Database::connect();

        // get a full list of the metric names
        foreach($db->getFieldNames('v_dataset_nom_stats_export') as $field)
        {
            // exclude fields that aren't actually metrics
            $ignoredfields = array(
                                    "instrument_group",
                                    "instrument",
                                    "acq_time_start",
                                    "dataset_id",
                                    "dataset",
                                    "dataset_rating",
                                    "dataset_rating_id",
                                    "nom_annotation_job",
                                    "nom_stats_last_affected",
//                                    "smaqc_job",
//                                    "smaqc_last_affected",
                                    "psm_source_job",
                                    "separation_type"
                                  );

            if(!in_array($field, $ignoredfields))
            {
                $this->metriclist[] = $field;
            }
        }

        // Get the Short Description for each metric
        $builder = $db->table('v_dataset_nom_stats_definitions');
        $builder->select('metric, short_description');
        $builder->orderBy("metric", "asc");
        $result = $builder->get()->getResult();

        foreach($result as $row)
        {
            $this->metricShortDescription[$row->metric] = $row->short_description;
        }

        // get a full list of the instruments
        $builder = $db->table('v_dataset_nom_stats_instruments');
        $builder->select('instrument');
        $builder->distinct();
        $builder->orderBy("instrument", "asc");
        $result = $builder->get()->getResult();

        foreach($result as $row)
        {
            $this->instrumentlist[] = $row->instrument;
        }
    }

    function index()
    {
        $data['title']          = " NOM Stats ";
        $data['startdate']      = $this->defaultstartdate;
        $data['enddate']        = $this->defaultenddate;
        $data['metriclist']     = $this->metriclist;
        $data['metricShortDescription'] = $this->metricShortDescription;
        $data['instrumentlist'] = $this->instrumentlist;
        $data['windowsize']     = $this->DEFAULTWINDOWSIZE;
        $data['datasetfilter']  = '';
        $data['includegraph']   = false;

        echo view('NOMQC/headView.php', $data);
        echo view('NOMQC/leftMenuView', $data);
        echo view('NOMQC/topMenuView' , $data);
        echo view('NOMQC/mainView'    , $data);
        echo view('NOMQC/footView.php', $data);
    }

    public function instrument()
    {
        // Display list of QC metric names and descriptions
        // Example URL:      https://dms2.pnl.gov/smaqc/instrument/VOrbiETD04                           (prior to February 2026, http://prismsupport.pnl.gov/smaqc/index.php/smaqc/instrument/VOrbiETD04)
        // auto-expanded to  https://dms2.pnl.gov/smaqc/instrument/VOrbiETD04/window/45/unit/datasets

        // Required URL parameters:
        // instrument: the name of the instrument

        // Optional URL parameters:
        // window: window size for calculating average and standard deviation
        // unit: days or datasets (for the window)
        // filterDS: used to select datasets based on a SQL 'LIKE' match
        // ignoreDS: used to exclude datasets based on a SQL 'LIKE' match

        $needRedirect = false;  // use this variable to redirect to new URL if default parameters are used

        // use an array of defaults for the uri-to-assoc() call, if not supplied in the URI, the value will be set to false
        $defaultURI = array('instrument', 'window', 'unit');

        $URI_array = $this->uri_to_assoc(1, $defaultURI);

        $includedDatasets = array();
        $excludedDatasets = array();

        // make sure user supplied an instrument name, redirect to home page if not
        if($URI_array["instrument"] === false)
        {
            return redirect()->to(site_url('nom_stats/'));
        }

        //TODO: check for valid instrument name (is it in the DB?)

        // set default window size if need be
        if($URI_array["window"] === false)
        {
            $needRedirect = true;
            $URI_array["window"] = $this->DEFAULTWINDOWSIZE;
        }

        // set default unit if need be
        if($URI_array["unit"] === false)
        {
            $needRedirect = true;
            $URI_array["unit"] = "datasets";
        }

        // get the filter list if supplied
        if(!empty($URI_array["filterDS"]))
        {
            $includedDatasets = explode(",", $URI_array["filterDS"]);
            //TODO: add WHERE LIKE to query
        }

        // get the ignore list if supplied
        if(!empty($URI_array["ignoreDS"]))
        {
            $excludedDatasets = explode(",", $URI_array["ignoreDS"]);
            //TODO: add WHERE NOT LIKE to query
        }

        // redirect if default values are to be used
        if($needRedirect)
        {
            return redirect()->to(site_url('nom_stats/' . $this->assoc_to_uri($URI_array)));
        }

        // set the data that we will have access to in the view
        $data['title'] = $URI_array["instrument"];
        $data['instrument'] = $URI_array["instrument"];
        $data['datasetfilter'] = $includedDatasets;
        $data['datasetignore'] = $excludedDatasets;

        $data['metriclist'] = $this->metriclist;
        $data['metricShortDescription'] = $this->metricShortDescription;
        $data['instrumentlist'] = $this->instrumentlist;

        $data['unit'] = $URI_array["unit"];

        // remove these later
        $data['startdate'] = $this->defaultstartdate;
        $data['enddate']   = $this->defaultenddate;

        $data['windowsize'] = (int)$URI_array["window"];

        $instrumentModel = new \App\Models\NOMQC\InstrumentModel();

        $error = $instrumentModel->init(
            $URI_array["instrument"],
            $data['unit'],
            $data['windowsize']
        );

        if($error)
        {
            $redirecturlparts = array(
                "nom_stats",
                "invaliditem",
                $error["type"],
                $error["value"]
            );

            return redirect()->to(site_url(join('/', $redirecturlparts)));
        }

        $results = $instrumentModel->getData();

        $data['metricDescriptions']  = $results->metricDescriptions;
        $data['metricCategories']    = $results->metricCategories;
        $data['metricSources']       = $results->metricSources;
        //$data['latestmetrics']       = $results->latestMetrics;
        $data['definition']          = $results->definition;

        $data['includegraph'] = false;

        // load the views
        echo view('NOMQC/headView', $data);
        echo view('NOMQC/instrumentView', $data);
    }

    public function metric()
    {
        // Plot the given metric vs. time
        // Example URL:      https://dms2.pnl.gov/smaqc/metric/C_1A/inst/VOrbiETD04                     (prior to February 2026, http://prismsupport.pnl.gov/smaqc/index.php/smaqc/metric/C_1A/inst/VOrbiETD04)
        // auto-expanded to  https://dms2.pnl.gov/smaqc/metric/C_1A/inst/VOrbiETD04/from/10-19-2025/to/02-19-2026/window/45/unit/datasets

        // Required URL parameters:
        // metric: the name of the metric
        // instrument: the name of the instrument

        // Required With Defaults:
        // from: the beginning date for selecting datasets
        // to: the ending date for selecting datasets
        // window: window size for calculating average and standard deviation
        // unit: days or datasets (for the window)

        // Optional URL parameters:
        // filterDS: used to select datasets based on a SQL 'LIKE' match
        // ignoreDS: used to exclude datasets based on a SQL 'LIKE' match

        // use an array of defaults for the uri-to-assoc() call, if not supplied in the URI, the value will be set to false
        $defaultURI = array('metric', 'inst', 'from', 'to', 'window', 'unit');

        $URI_array = $this->uri_to_assoc(1, $defaultURI);

        $needRedirect = false;

        $datasetFilter = "";
        $excludedDatasets = "";

        // make sure user supplied a metric name, redirect to home page if not
        if(empty($URI_array["metric"]))
        {
            return redirect()->to(site_url('nom_stats/'));
        }

        //TODO: check for valid metric name (is it in the DB?)

        // make sure user supplied an instrument name, redirect to home page if not
        if(empty($URI_array["inst"]))
        {
            return redirect()->to(site_url('nom_stats/'));
        }

        //TODO: check for valid instrument name (is it in the DB?)

        // set default from and to dates if need be
        if(empty($URI_array["from"]) || empty($URI_array["to"]))
        {
            $needRedirect = true;
            $URI_array["from"] = $this->defaultstartdate;
            $URI_array["to"]   = $this->defaultenddate;
        }

        // set default window size if need be
        if(empty($URI_array["window"]))
        {
            $needRedirect = true;
            $URI_array["window"] = $this->DEFAULTWINDOWSIZE;
        }

        // set default unit if need be
        if(empty($URI_array["unit"]))
        {
            $needRedirect = true;
            $URI_array["unit"] = "datasets";
        }

        // get the filter list if supplied
        if(!empty($URI_array["filterDS"]))
        {
            $datasetFilter = $URI_array["filterDS"];
        }

        // get the ignore list if supplied
        if(!empty($URI_array["ignoreDS"]))
        {
            $excludedDatasets = $URI_array["ignoreDS"];
            //TODO: add WHERE NOT LIKE to query
        }

        // redirect if default values are to be used
        if($needRedirect)
        {
            return redirect()->to(site_url('nom_stats/' . $this->assoc_to_uri($URI_array)));
        }

        $data['title'] = $URI_array["inst"] . ' - ' . $URI_array["metric"];
        $data['metric'] = strtolower($URI_array["metric"]);
        $data['instrument'] = $URI_array["inst"];
        $data['datasetfilter'] = $datasetFilter;
        $data['filterDS'] = $datasetFilter;
        $data['ignoreDS'] = $excludedDatasets;

        $data['metriclist'] = $this->metriclist;
        $data['metricShortDescription'] = $this->metricShortDescription;
        $data['instrumentlist'] = $this->instrumentlist;

        $data['startdate'] = date("m-d-Y", strtotime(str_replace('-', '/', $URI_array["from"])));
        $data['enddate']   = date("m-d-Y", strtotime(str_replace('-', '/', $URI_array["to"])));

        $data['windowsize'] = (int)$URI_array["window"];
        $data['unit'] = $URI_array["unit"];

        $metricModel = new \App\Models\NOMQC\MetricModel();

        // TODO: add support for excluded datasets

        $error = $metricModel->init(
            $URI_array["inst"],
            strtolower($URI_array["metric"]),
            $data['startdate'],
            $data['enddate'],
            $data['windowsize'],
            $datasetFilter
        );

        if($error)
        {
            $redirecturlparts = array(
                "nom_stats",
                "invaliditem",
                $error["type"],
                $error["value"]
            );

            return redirect()->to(site_url(join('/', $redirecturlparts)));
        }

        $results = $metricModel->getData();

        $data['metrics']          = $results->queryResults;
        $data['definition']       = $results->definition;
        $data['plotdata']         = $results->plotData;
        $data['plotDataBad']      = $results->plotDataBad;
        $data['plotDataPoor']     = $results->plotDataPoor;
        $data['plotdata_average'] = $results->plotDataAverage;
        $data['stddevupper']      = $results->stdDevUpper;
        $data['stddevlower']      = $results->stdDevLower;
        $data['metric_units']     = $results->metricUnits;

        $data['includegraph'] = true;

        // load the views
        echo view('NOMQC/headView.php', $data);
        echo view('NOMQC/metricView', $data);
    }

    public function invaliditem($requesteditemtype = NULL, $name = NULL)
    {
        $data['title']      = " NOM Stats ";
        $data['startdate']  = $this->defaultstartdate;
        $data['enddate']    = $this->defaultenddate;

        $data['includegraph'] = false;

        $data['metriclist']     = $this->metriclist;
        $data['metricShortDescription']     = $this->metricShortDescription;
        $data['instrumentlist'] = $this->instrumentlist;

        $msg = "The requested #' does not exist.";

        if(($requesteditemtype == "instrument") && !empty($name))
        {
            $data['message'] = str_replace("#", "instrument '" . $name, $msg);
        }
        else if(($requesteditemtype == "metric") && !empty($name))
        {
            $data['message'] = str_replace("#", "metric '" . $name, $msg);
        }
        else
        {
            $data['message'] = "The page you requested was not found.";
        }

        echo view('NOMQC/headView.php', $data);
        echo view('NOMQC/leftMenuView', $data);
        echo view('NOMQC/topMenuView', $data);
        echo view('NOMQC/invalidItemView', $data);
        echo view('NOMQC/footView.php', $data);
    }

    private function uri_to_assoc(int $skip = 3, array $defaultKeys = array()) : array
    {
        $segments = array_slice($this->request->getUri()->getSegments(), $skip);
        $itemCount = max(count($segments), count($defaultKeys));
        $values = array();
        for ($i = 0; $i < count($segments); $i += 2)
        {
            $key = "";
            $value = "";
            if (array_key_exists($i, $segments))
            {
                $key = $segments[$i];
            }
            else if (array_key_exists($i, $defaultKeys))
            {
                $values[$defaultKeys[$i]] = false;
            }

            if (array_key_exists($i + 1, $segments))
            {
                $values[$key] = $segments[$i + 1];
            }
            else
            {
                $values[$key] = null;
            }
        }

        foreach ($defaultKeys as $key)
        {
            if (!array_key_exists($key, $values))
            {
                $values[$key] = false;
            }
        }

        return $values;
    }

    private function assoc_to_uri(array $items) : string
    {
        $uri = "";
        foreach ($items as $key => $value)
        {
            if (mb_strlen($uri) == 0)
            {
                $uri = $key . "/" . $value;
            }
            else
            {
                $uri .= "/" . $key . "/" . $value;
            }
        }

        return $uri;
    }
}
?>
