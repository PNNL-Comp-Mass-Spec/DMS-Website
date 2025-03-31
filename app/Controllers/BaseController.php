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
    public $my_tag = "";
    public $my_title = "";

    // Model refs
    public $aux_info_model = null;
    public $data_model = null;
    public $detail_model = null;
    public $form_model = null; // Directly assigned; could be 'private set' with PHP 8.4
    public $gen_model = null;
    public $input_model = null;
    public $link_model = null;
    public $model = null;
    public $sproc_model = null; // Directly assigned; could be 'private set' with PHP 8.4

    // Library refs
    public $entry_form = null; // Directly assigned; could be 'private set' with PHP 8.4

    // Filter library refs
    public $column_filter = null;
    public $paging_filter = null;
    public $primary_filter = null;
    public $secondary_filter = null;
    public $sorting_filter = null;

    public $help_page_link = null;
    public $menu = null;
    public $choosers = null;
    public $preferences = null;
    public $auth = null;

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
     * @param object $local_ref Local reference
     * @param string $config_name Config name, e.g. list_report
     * @param string $config_source Source, e.g. dataset, experiment, campaign
     * @param boolean $options Custom options flag
     * @return boolean
     */
    public function loadLibrary($lib_name, &$local_ref, $config_name, $config_source, $options = false) {
        if (isset($local_ref)) {
            return true;
        }
        // Load then initialize the model
        $libPath = "\\App\\Libraries\\$lib_name";
        $local_ref = new $libPath();
        if (!method_exists($local_ref, 'init')) {
            return true;
        }

        if ($options === false) {
            return $local_ref->init($config_name, $config_source, $this);
        } else {
            return $local_ref->init($config_name, $config_source, $this, $options);
        }
    }

    /**
     * Load Entry_form to $this->entry_form and initializes it with given config info
     * @param string $config_name Config name, e.g. list_report
     * @param string $config_source Source, e.g. dataset, experiment, campaign
     * @param boolean $options Custom options flag
     * @return boolean
     */
    public function loadEntryFormLibrary($config_name, $config_source) {
        return $this->loadLibrary('Entry_form', $this->entry_form, $config_name, $config_source);
    }

    /**
     * Get named library and initialize it with given config info
     * @param string $lib_name Library name, including list_report, detail_report, paging_filter, sorting_filter, column_filter, secondary_filter
     * @param string $config_name Config name, e.g. list_report
     * @param string $config_source Source, e.g. dataset, experiment, campaign
     * @param boolean $options Custom options flag
     * @return boolean
     */
    public function getLibrary($lib_name, $config_name, $config_source, $options = false) {
        $this->loadLibrary($lib_name, $local_ref, $config_name, $config_source, $options);
        return $local_ref;
    }

    /**
     * Load named model (with given local name) and initialize it with given config info
     * @param string $model_name Module name, e.g. G_model, Q_model
     * @param object $local_ref Local reference, e.g. $this->gen_model for G_model; $this->model for Q_model
     * @param string $config_name Config type; typically na for G_model; list_report (or similar) for Q_model
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    public function loadModel($model_name, &$local_ref, $config_name, $config_source) {
        if (isset($local_ref)) {
            return true;
        }
        // Dynamically load and initialize the model
        $local_ref = model('App\\Models\\'.$model_name);
        if (method_exists($local_ref, 'init')) {
            return $local_ref->init($config_name, $config_source);
        } else {
            return true;
        }
    }

    /**
     * Load E_model to $this->form_model and initialize it with given config info
     * @param string $config_name Config type; typically na for E_model
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    public function loadFormModel($config_name, $config_source) {
        return $this->loadModel('E_model', $this->form_model, $config_name, $config_source);
    }

    /**
     * Load G_model to $this->gen_model and initialize it with given config info
     * @param string $config_name Config type; typically na for G_model
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    public function loadGeneralModel($config_name, $config_source) {
        return $this->loadModel('G_model', $this->gen_model, $config_name, $config_source);
    }

    /**
     * Load S_model to $this->sproc_model and initialize it with given config info
     * @param string $config_name Config type
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    public function loadSprocModel($config_name, $config_source) {
        return $this->loadModel('S_model', $this->sproc_model, $config_name, $config_source);
    }

    /**
     * Get named model and initialize it with given config info
     * @param string $model_name Module name, e.g. G_model, Q_model
     * @param string $config_name Config type; typically na for G_model; list_report (or similar) for Q_model
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return object model
     */
    public function getModel($model_name, $config_name, $config_source) {
        $this->loadModel($model_name, $local_ref, $config_name, $config_source);
        return $local_ref;
    }

    /**
     * Updates the database search path for Postgres connections. Does nothing for SQL Server connections
     * @param BaseConnection $db
     * @return void
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

        $this->loadGeneralModel('na', $this->my_tag);

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
