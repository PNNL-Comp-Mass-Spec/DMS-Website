<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    // --------------------------------------------------------------------
    public function message_box($heading, $message, $title = '') {
        $data['title'] = ($title) ? $title : $heading;
        $data['heading'] = $heading;
        $data['message'] = $message;
        echo view('message_box', $data);
    }

    /**
     * Load named library and initialize it with given config info
     * @param string $lib_name Library name, including list_report, detail_report, paging_filter, sorting_filter, column_filter, secondary_filter
     * @param string $config_name Config name, e.g. list_report
     * @param string $config_source Source, e.g. dataset, experiment, campaign
     * @param boolean $options Custom options flag
     * @return boolean
     */
    public function load_lib($lib_name, $config_name, $config_source, $options = false) {
        $localName = lcfirst($lib_name);
        if (property_exists($this, $localName)) {
            return true;
        }
        // Load then initialize the model
        $libPath = "\\App\\Libraries\\$lib_name";
        $this->$localName = new $libPath();
        if (!method_exists($this->$localName, 'init')) {
            return true;
        }

        if ($options === false) {
            return $this->$localName->init($config_name, $config_source, $this);
        } else {
            return $this->$localName->init($config_name, $config_source, $this, $options);
        }
    }

    /**
     * Load named model (with given local name) and initialize it with given config info
     * @param string $model_name Module name, e.g. G_model, Q_model
     * @param string $local_name Local name, e.g. gen_model for G_model; model for Q_model
     * @param string $config_name Config type; typically na for G_model; list_report (or similar) for Q_model
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    public function load_mod($model_name, $local_name, $config_name, $config_source) {
        if (property_exists($this, $local_name)) {
            return true;
        }
        // Dynamically load and initialize the model
        $this->$local_name = model('App\\Models\\'.$model_name);
        if (method_exists($this->$local_name, 'init')) {
            return $this->$local_name->init($config_name, $config_source);
        } else {
            return true;
        }
    }

    /**
     * Updates the database search path for Postgres connections. Does nothing for SQL Server connections
     * @param BaseConnection $db
     * @return boolean
     */
    public function updateSearchPath($db) {
        helper(['database']);
        update_search_path($db);
    }

    /**
     * Check permissions
     * Verify (all):
     * - action is allowed for the page family
     * - user has at least basic access to website
     * - user has necessary permission if action is a restricted one
     * Present message box if access check fails and $output_message is true
     * @param string $action
     * @param boolean $output_message When true, update the message box with "Access Denied"
     * @return boolean
     */
    public function check_access($action, $output_message = true) {
        helper('user');
        $user = get_user();

        $this->load_mod('G_model', 'gen_model', 'na', $this->my_tag);

        if ($this->gen_model->error_text) {
            if ($output_message) {
                $this->message_box('Error', $this->gen_model->error_text);
            }
            return false;
        }

        $result = $this->gen_model->check_permission($user, $action, $this->my_tag, $this);

        if ($result === true) {
            return true;
        } else {
            if ($output_message) {
                $this->message_box('Access Denied', $result);
            }
            return false;
        }
    }
}
