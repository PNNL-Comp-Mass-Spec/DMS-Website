<?php
namespace App\Libraries;

// --------------------------------------------------------------------
// general query - return data directly using query model
// --------------------------------------------------------------------

/**
 * Query definition
 * @category Helper Class
 */
class General_query_def {

    var $output_format = 'tsv';
    var $q_name = '';
    var $config_source = '';
    var $filter_values = array();
    var $offset = 1;
    var $rows = 100;
    var $sort_col = '';
    var $sort_direction = 'DESC';

}

/**
 * Return data directly using query model
 */
class General_query {

    protected $config_source = '';
    protected $config_name = '';
    protected $tag = '';
    protected $title = '';

    /**
     * Constructor
     */
    function __construct() {
        // Include app/helpers/wildcard_conversion_helper.php
        helper('wildcard_conversion');
    }

    /**
     * Initialize the class
     * @param type $config_name
     * @param type $config_source
     * @param type $controller
     */
    function init($config_name, $config_source, $controller) {
        $this->config_name = $config_name;
        $this->config_source = $config_source;

        $this->controller = $controller;
    }

    /**
     * Extract parameters from input URL segments and return object
     * @return \General_query_def
     */
    function get_query_values_from_url() {
        helper(['url', 'security']);
        $uri = current_uri(true);
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();

        $p = new General_query_def();
        $p->output_format = $uri->getSegment(3);
        $p->q_name = $uri->getSegment(4);
        $p->config_source = $uri->getSegment(5);
        $p->filter_values = array_slice($uri->getSegments(), 5);

        // Look for custom paging values specified after the question mark
        //
        // For example, with https://dms2.pnl.gov/data/ax/table/list_report/dataset/-/-/-/VOrbi05/QC_Shew_13?Offset=25&Rows=50&SortCol=ID&SortDir=ASC
        // we are filtering on:
        //   Instrument:      VOrbi05
        //   Experiment:      QC_Shew_13
        // And paging with:
        //   Offset:          25
        //   RowsToDisplay:   50
        //   Sort column:     ID
        //   Sort directtion: Ascending

        $request = \Config\Services::request();
        $offset = xss_clean($request->getGet('Offset'));
        $rows = xss_clean($request->getGet('Rows'));
        $sortCol = xss_clean($request->getGet('SortCol'));
        $sortDir = xss_clean($request->getGet('SortDir'));

        // Validate that the offset is an integer
        if (filter_var($offset, FILTER_VALIDATE_INT) !== false) {
            $p->offset = (int) $offset;
        } else {
            $p->offset = 1;
        }

        // Validate that rows is an integer
        if (filter_var($rows, FILTER_VALIDATE_INT) !== false) {
            $p->rows = (int) $rows;
        } else {
            $p->rows = 100;
        }

        // Possibly override the sort column
        if ($sortCol) {
            $p->sort_col = $sortCol;
        }

        if ($sortDir) {
            // Check whether $sortDir starts with Asc or Desc

            if (strncmp(strtolower($sortDir), "asc", 3) === 0) {
                $p->sort_direction = 'ASC';
            }

            if (strncmp(strtolower($sortDir), "desc", 4) === 0) {
                $p->sort_direction = 'DESC';
            }
        }

        return $p;
    }

    /**
     * Setup the query
     * @return \General_query_def
     */
    function setup_query_for_dmsBase() {
        helper(['url']);
        $uri = current_uri(true);
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();

        $input_params = new General_query_def();
        $input_params->config_source = $this->controller->my_tag;
        $input_params->output_format = $uri->getSegment(3);
        $input_params->q_name = $uri->getSegment(4);
        $input_params->filter_values = array_slice($uri->getSegments(), 4);

        $this->setup_query($input_params);
        return $input_params;
    }

    /**
     * Called by client controller to execute query via Q_model and return result in format
     * as specified by the input_params object (of class General_query_def)
     * @param type $input_params
     */
    function setup_query($input_params) {
        $this->controller->load_mod('Q_model', 'model', $input_params->q_name, $input_params->config_source);
        $this->add_filter_values_to_model_predicate($input_params->filter_values, $this->controller->model);
        $this->configure_paging($input_params, $this->controller->model);

        $this->controller->model->convert_wildcards();
    }

    /**
     * Merge input values in URL segment order with filter spec in order
     * and add results to model as predicate items
     * @param type $filter_values
     * @param type $model
     */
    private function add_filter_values_to_model_predicate($filter_values, $model) {
        $filter_specs = $model->get_primary_filter_specs();
        $i = 0;
        foreach (array_values($filter_specs) as $pi) {
            if ($i >= count($filter_values)) {
                break;
            }

            $val = $filter_values[$i];
            if ($val != '-') {

                // Boolean operator
                $rel = ($pi['cmp'] == 'Rp') ? 'ARG' : 'AND';

                // Column name to filter on
                $col = $pi['col'];

                // Comparison mode
                $cmp = $pi['cmp'];

                // Value to filter on
                $val = decode_special_values($val);

                $model->add_predicate_item($rel, $col, $cmp, $val);
            }
            $i++;
        }
    }

    /**
     * Configure paging
     * @param type $input_params
     * @param type $model
     */
    private function configure_paging($input_params, $model) {
        /*
          echo "<pre>";
          echo "Offset:         $input_params->offset \n";
          echo "Rows:           $input_params->rows\n";
          echo "Sort col:       $input_params->sort_col\n";
          echo "Sort direction: $input_params->sort_direction";
          echo "</pre>\n";
         */

        $model->add_paging_item($input_params->offset, $input_params->rows);
        $model->add_sorting_item($input_params->sort_col, $input_params->sort_direction);
    }

    /**
     * Output a result in the specified format
     * @param type $output_format
     */
    function output_result($output_format) {
        $model = $this->controller->model;

        $pageTitle = $this->config_source;

        switch (strtolower($output_format)) {
            case 'dump':
                helper('test');
                dump_q_model($model);
                break;
            case 'sql':
                echo $model->get_sql();
                break;
            case 'count':
                $query = $model->get_rows();
                $rows = $query->getResultArray();
                echo "rows:" . count($rows);
                break;
            case 'json':
                $query = $model->get_rows();
                echo json_encode($query->getResult());
                break;
            case 'tsv':
                $query = $model->get_rows();
                $result = $query->getResultArray();
                $this->tsv($result);
                break;
            case 'html':
            case 'table':
                $query = $model->get_rows();
                $result = $query->getResultArray();
                $this->html_table($result, $pageTitle);
                break;
            case 'xml':
            case 'xml_dataset':
                $query = $model->get_rows();
                $result = $query->getResultArray();
                $this->xml_dataset($result, $pageTitle);
                break;
        }
    }

    /**
     * Show results as TSV
     * @param array $result
     */
    function tsv($result) {
        $headers = '';

        header("Content-type: text/plain");

        if (count($result) == 0) {
            // No results
            echo "No results found\n";
            return;
        }

        // field headers
        foreach (array_keys(current($result)) as $field_name) {
            $headers .= $field_name . "\t";
        }
        echo $headers . "\n";

        // field data
        foreach ($result as $row) {
            $line = '';
            foreach ($row as $name => $value) {      // $name is the key, $value is the value
                if (!isset($value) || $value == "") {
                    $value = "\t";
                } else {
                    $value .= "\t";
                }
                $line .= $value;
            }
            echo trim($line) . "\n";
        }
    }

    /**
     * Show results as an HTML-formatted table
     * @param type $result
     * @param type $pageTitle
     * @return type
     */
    function html_table($result, $pageTitle) {
        $headers = '';

        header("Content-type: text/html");

        echo "<html><head><title>$pageTitle</title></head>\n";

        if (count($result) == 0) {
            echo "<p>No results were found</p>\n";
            echo "</body></html>\n";
            return;
        }

        // field headers
        foreach (array_keys(current($result)) as $field_name) {
            $headers .= "<th>$field_name</th>";
        }

        echo "<table border='1' style='border: 2px solid black;'>$headers\n";

        // field data
        foreach ($result as $row) {
            $line = '<tr>';
            foreach ($row as $name => $value) {      // $name is the key, $value is the value
                if (!isset($value) || $value == "") {
                    $value = "";
                }
                $line .= "<td>$value</td>";
            }
            echo trim($line) . "</tr>\n";
        }

        echo "</table>\n";

        echo "</body></html>\n";
    }

    /**
     * Show results as XML
     * @param type $result
     * @param type $table
     */
    function xml_dataset($result, $table = 'TX') {
        header("Content-type: text/plain");

        echo "<data>\n";

        // field data
        foreach ($result as $row) {
            $line = '';
            $line .= "<$table>";
            foreach ($row as $name => $value) {
                $parsedValue = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                $line .= "<$name>" . $parsedValue . "</$name>";
            }
            $line .= "</$table>";
            echo trim($line) . "\n";
        }

        echo "</data>\n";
    }
}
?>
