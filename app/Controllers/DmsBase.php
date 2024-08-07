<?php
namespace App\Controllers;

/**
 * Class DmsBase, DMS base class for most controllers
 */

class DmsBase extends BaseController
{

    var $my_tag = "";

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend DmsBase.
     *
     * @var array
     */
    protected $helpers = ['url'];

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

    }

    // --------------------------------------------------------------------
    function index()
    {
        $this->load_mod('G_model', 'gen_model', 'na', $this->my_tag);

        if ($this->gen_model->error_text) {
            if ($output_message) {
                $this->message_box('Error', $this->gen_model->error_text);
            }

            // Failed to load G_model for page; just do the original action
            return redirect()->to(site_url($this->my_tag.'/report'));
        }

        $actions = $this->gen_model->get_actions();

        if ($actions['report']) {
            return redirect()->to(site_url($this->my_tag.'/report'));
        } else if ($actions['param']) {
            return redirect()->to(site_url($this->my_tag.'/param'));
        } else if ($actions['create']) {
            return redirect()->to(site_url($this->my_tag.'/create'));
        }

        // None of the above exist: just do the original action
        return redirect()->to(site_url($this->my_tag.'/report'));
    }

    // --------------------------------------------------------------------
    // Entry page section
    // --------------------------------------------------------------------

    /**
     * Create an entry page to make a new record in the database
     */
    function create()
    {
        $page_type = 'create';
        if (!$this->check_access('create')) {
            return;
        }
        $this->load_lib('Entry', 'na', $this->my_tag);
        $this->entry->create_entry_page($page_type);
    }

    /**
     * Create an entry page to edit an existing record in the database
     * @param type $id
     * @return type
     */
    function edit($id = '')
    {
        if(!$id) {
            $this->message_box('Edit Error', 'No object ID was given');
            return;
        }
        $page_type = 'edit';
        if (!$this->check_access('enter')) {
            return;
        }
        $this->load_lib('Entry', 'na', $this->my_tag);
        $this->entry->create_entry_page($page_type);
    }

    /**
     * Create or update entry in database from entry page form fields in POST:
     * @category AJAX
     */
    function submit_entry_form()
    {
        if (!$this->check_access('enter')) {
            return;
        }
        $this->load_lib('Entry', 'na', $this->my_tag);
        $this->entry->submit_entry_form();
    }

    // --------------------------------------------------------------------
    // List report page section
    // --------------------------------------------------------------------

    /**
     * action for "report" format of list report
     */
    function report()
    {
        if (!$this->check_access('report')) {
            return;
        }
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->list_report('report');
        return;
    }

    /**
     * Action for "search" version of list report
     */
    function search()
    {
        if (!$this->check_access('report')) {
            return;
        }
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->list_report('search');
        return;
    }

    /**
     * Make filter section for list report page:
     * Returns HTML containing filter components arranged in the specified format
     * @param string $filter_display_mode
     * @category AJAX
     */
    function report_filter($filter_display_mode = 'advanced')
    {
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->report_filter($filter_display_mode);
    }

    /**
     * Returns the HTML for a query filter comparison field selector for the given column name
     * @param type $column_name
     * @category AJAX
     */
    function get_sql_comparison($column_name)
    {
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->get_sql_comparison($column_name);
    }

    /**
     * Returns HTML displaying the list report data rows for inclusion in list report page
     * @param type $option
     * @category AJAX
     */
    function report_data($option = 'rows')
    {
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->report_data($option);
    }

    /**
     * Returns HTML displaying supplemental information about page for inclusion in list report page
     * @param type $what_info
     * @category AJAX
     */
    function report_info($what_info)
    {
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->report_info($what_info);
    }

    /**
     * returns HTML for the paging display and control element for inclusion in report pages
     * @category AJAX
     */
    function report_paging()
    {
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->report_paging();
    }

    /**
     * Export list report
     * @param string $format
     */
    function export($format)
    {
        $this->load_lib('List_report', 'list_report', $this->my_tag);
        $this->list_report->export($format);
    }

    // --------------------------------------------------------------------
    // Detail report page section
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    function show($id)
    {
        if (!$this->check_access('show')) {
            return;
        }
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->detail_report($id);
    }

    /**
     * Show the data with minimal formatting (no headers, but does have "Edit, Copy and New")
     * For example http://dms2.pnl.gov/param_file/show_data/3287
     * Actual data loading occurs in method detail_report_data in file Detail_report.php
     * @param type $id
     */
    function show_data($id)
    {
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->detail_report_data($id);
    }

    /**
     * Make a page to show a detailed report for the single record identified by the the user-supplied id
     * Typically accessed using a call like http://dms2.pnl.gov/param_file/show/3287
     * @param string $id
     * @return type
     */
    function detail_report($id)
    {
        if (!$this->check_access('show')) {
            return;
        }
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->detail_report($id);
    }

    /**
     * Get detail report data for specified entity
     * @param string $id
     * @category AJAX
     */
    function detail_report_data($id)
    {
        $show_entry_links = $this->check_access('enter', false);
        $show_create_links = $this->check_access('create', false);

        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->detail_report_data($id, $show_entry_links, $show_create_links);
    }

    /**
     * Returns SQL for detail report
     * @param string $id
     * @category AJAX
     */
    function detail_sql($id)
    {
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->detail_sql($id);
    }

    /**
     * Get aux info controls associated with specified entity
     * @param type $id
     * @category AJAX
     */
    function detail_report_aux_info_controls($id)
    {
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->detail_report_aux_info_controls($id);
    }

    /**
     * Export detailed report for the single record identified by the the user-supplied id
     * @param string $id
     * @param string $format
     */
    function export_detail($id, $format)
    {
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->export_detail($id, $format);
    }

    /**
     * Export spreadsheet template for the single record identified by the the user-supplied id
     * @param string $id
     * @param string $format
     */
    function export_spreadsheet($id, $format, $rowStyle = false, $ext = "tsv")
    {
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->export_spreadsheet($id, $format, $rowStyle, $ext);
    }

    /**
     * Display contents of given script as graph
     * @param string $scriptName
     */
    function dot($scriptName)
    {
        $this->load_lib('Detail_report', 'detail_report', $this->my_tag);
        $this->detail_report->dot($scriptName, $this->my_tag);
    }

    // --------------------------------------------------------------------
    // Param report (stored procedure based list report) section
    // --------------------------------------------------------------------

    /**
     * Sets up a page that contains an entry form defined by the
     * E_model for the config db which will be used to get data
     * rows in HTML via and AJAX call to the param_data function.
     * @return type
     */
    function param()
    {
        if (!$this->check_access('param')) {
            return;
        }
        $this->load_lib('Param_report', 'list_report_sproc', $this->my_tag);
        $this->param_report->param();
    }

    /**
     * Returns HTML data row table of data returned by stored procedure
     * @return type
     * @category AJAX
     */
    function param_data()
    {
        if (!$this->check_access('param')) {
            return;
        }
        $this->load_lib('Param_report', 'list_report_sproc', $this->my_tag);
        $this->param_report->param_data();
    }

    /**
     * Returns HTML for the paging display and control element
     * for inclusion in param report pages
     * @category AJAX
     */
    function param_paging()
    {
        $this->load_lib('Param_report', 'list_report_sproc', $this->my_tag);
        $this->param_report->param_paging();
    }

    /**
     * Returns HTML displaying supplemental information about page for inclusion in param report page
     * @param type $what_info
     * @category AJAX
     */
    function param_info($what_info)
    {
        $this->load_lib('Param_report', 'param_report', $this->my_tag);
        $this->param_report->param_info($what_info);
    }

    /**
     * Returns HTML for defining custom filters
     * @category AJAX
     */
    function param_filter()
    {
        $this->load_lib('Param_report', 'list_report_sproc', $this->my_tag);
        $this->param_report->param_filter();
    }
    // --------------------------------------------------------------------
    // Export param report
    function export_param($format)
    {
        if (!$this->check_access('param')) {
            return;
        }
        $this->load_lib('Param_report', 'list_report_sproc', $this->my_tag);
        $this->param_report->export_param($format);
    }

    // --------------------------------------------------------------------
    // 'operations' style stored procedure functions section
    // --------------------------------------------------------------------

    /**
     * Invokes the stored procedure given by $sproc_name and returns simple JSON response.
     * @param type $sproc_name
     * @category AJAX
     */
    function call($sproc_name = 'operations_sproc')
    {
        $this->load_lib('Operation', 'na', $this->my_tag);
        $response = $this->operation->internal_operation($sproc_name);
//      $response->parms = $this->operation->get_params();
        echo json_encode($response);
    }


    /**
     * Invokes the stored procedure given by $sproc_name and returns simple JSON response.
     * (someday) allow name of stored procedure to be passed as part of POST
     * @param type $sproc_name
     * @category AJAX
     */
    function exec($sproc_name = 'operations_sproc')
    {
//      if(!$this->check_access('??')) return;
//      $uri = $this->request->uri;
//      // Don't trigger an exception if the segment index is too large
//      $uri->setSilent();
//      $sproc_name = $uri->getSegment(3, '');
        $this->load_lib('Operation', 'na', $this->my_tag);
        $response = $this->operation->internal_operation($sproc_name);
        if($response->result == 0) {
            if (empty($response->message))
                $response->message = "Operation was successful";
            else
                $response->message = "Operation was successful: " . $response->message;
        } else {
            $response->message = "Update failed: " . $response->message;
        }
        echo json_encode($response);
    }

    /**
     * Invokes the model's 'operation' stored procedure and returns simple text response.
     * @return type
     * @category AJAX
     */
    function operation()
    {
        if (!$this->check_access('operation')) {
            return;
        }
        $this->load_lib('Operation', 'na', $this->my_tag);
        $response = $this->operation->internal_operation('operations_sproc');
        if($response->result != 0) {
            echo "Update failed. " . $response->message;
        } else {
            if (empty($response->message))
                echo "Operation was successful (row refresh may be required).";
            else
                echo "Operation was successful: " . $response->message;
        }
    }

    /**
     * Invokes the model's 'operation' stored procedure and returns simple text response.
     * This is a thin wrapper over the internal function "internal_operation"
     * @return type
     * @category AJAX
     */
    function command()
    {
        if (!$this->check_access('operation')) {
            return;
        }
        $this->load_lib('Operation', 'na', $this->my_tag);
        $response = $this->operation->internal_operation('operations_sproc');
        if($response->result != 0) {
            echo "Update failed. " . $response->message;
        } else {
            if (empty($response->message))
                echo "Operation was successful";
            else
                echo "Operation was successful: " . $response->message;
        }
    }

    // --------------------------------------------------------------------
    // Miscelleneous section
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    function get_basic_nav_bar_items()
    {
        helper(['user', 'dms_search', 'menu']);
        $this->menu = model('App\Models\Dms_menu');
        return get_nav_bar_menu_items('', $this);
    }

    // --------------------------------------------------------------------
    // http://dmsdev.pnl.gov/controller/data/<output format>/<query name>/<filter value>/.../<filter value>
    // --------------------------------------------------------------------
    function data()
    {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->load_lib('General_query', '', ''); // $config_name, $config_source
        $input_parms = $this->general_query->setup_query_for_dmsBase();
        $this->general_query->output_result($input_parms->output_format);
    }

    /**
     * Clears cached session variables
     * (someday) handle param reports?
     * @param type $page_type
     * @category AJAX
     */
    function defaults($page_type) //'Param_Pages''list_report_sproc'   'list_report'
    {
        $this->saved_settings = new \App\Libraries\Saved_settings($this);
        $this->saved_settings->defaults($page_type, $this->my_tag);
    }

    /**
     * RSS Feed (not implemented)
     */
    function rss()
    {
        // (someday) make RSS export work or remove it
        echo "This is not implemented";
    }

    /**
     * Set custom list of columns to display for this list report
     * (implemented via a different mechanism)
     */
    function columns()
    {
        echo "This is not implemented";
    }

    /**
     * Show a help page
     * (implemented via a different mechanism)
     */
    function help_page()
    {
        echo "This is not implemented";
    }

    /**
     * Show the contents of a variable using var_dump() but use html formatting
     * From: http://php.net/manual/en/function.var-dump.php
     * User: b dot bergloev at gmail dot com
     * @param type $input
     * @param type $collapse
     */
    static function var_dump_ex($input, $collapse=false) {
        $recursive = function($data, $level=0) use (&$recursive, $collapse) {
            global $argv;

            $isTerminal = isset($argv);

            if (!$isTerminal && $level == 0 && !defined("DUMP_DEBUG_SCRIPT")) {
                define("DUMP_DEBUG_SCRIPT", true);

                echo '<script language="Javascript">function toggleDisplay(id) {';
                echo 'var state = document.getElementById("container"+id).style.display;';
                echo 'document.getElementById("container"+id).style.display = state == "inline" ? "none" : "inline";';
                echo 'document.getElementById("plus"+id).style.display = state == "inline" ? "inline" : "none";';
                echo '}</script>'."\n";
            }

            $type = !is_string($data) && is_callable($data) ? "Callable" : ucfirst(strtolower(gettype($data)));
            $type_data = null;
            $type_color = null;
            $type_length = null;

            switch ($type) {
                case "String":
                    $type_color = "green";
                    $type_length = strlen($data);
                    $type_data = "\"" . htmlentities($data) . "\""; break;

                case "Double":
                case "Float":
                    $type = "Float";
                    $type_color = "#0099c5";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data); break;

                case "Integer":
                    $type_color = "red";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data); break;

                case "Boolean":
                    $type_color = "#92008d";
                    $type_length = strlen($data);
                    $type_data = $data ? "true" : "false"; break;

                case "Null":
                    $type_length = 0; break;

                case "Array":
                    $type_length = count($data);
            }

            if (in_array($type, array("Object", "Array"))) {
                $notEmpty = false;

                foreach($data as $key => $value) {
                    if (!$notEmpty) {
                        $notEmpty = true;

                        if ($isTerminal) {
                            echo $type . ($type_length !== null ? "(" . $type_length . ")" : "")."\n";

                        } else {
                            $id = substr(md5(rand().":".$key.":".$level), 0, 8);

                            echo "<a href=\"javascript:toggleDisplay('". $id ."');\" style=\"text-decoration:none\">";
                            echo "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>";
                            echo "</a>";
                            echo "<span id=\"plus". $id ."\" style=\"display: " . ($collapse ? "inline" : "none") . ";\">&nbsp;&#10549;</span>";
                            echo "<div id=\"container". $id ."\" style=\"display: " . ($collapse ? "" : "inline") . ";\">";
                            echo "<br />";
                        }

                        for ($i=0; $i <= $level; $i++) {
                            echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }

                        echo $isTerminal ? "\n" : "<br />";
                    }

                    for ($i=0; $i <= $level; $i++) {
                        echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    echo $isTerminal ? "[" . $key . "] => " : "<span style='color:black'>[" . $key . "]&nbsp;=>&nbsp;</span>";

                    call_user_func($recursive, $value, $level+1);
                }

                if ($notEmpty) {
                    for ($i=0; $i <= $level; $i++) {
                        echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    if (!$isTerminal) {
                        echo "</div>";
                    }

                } else {
                    echo $isTerminal ?
                            $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " :
                            "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";
                }

            } else {
                echo $isTerminal ?
                        $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " :
                        "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";

                if ($type_data != null) {
                    echo $isTerminal ? $type_data : "<span style='color:" . $type_color . "'>" . $type_data . "</span>";
                }
            }

            echo $isTerminal ? "\n" : "<br />";
        };

        call_user_func($recursive, $input);
    }
}
?>
