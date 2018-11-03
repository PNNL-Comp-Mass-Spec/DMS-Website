<?php
// --------------------------------------------------------------------
// general query - return data directly using query model
// --------------------------------------------------------------------

/**
 * Query def
 * @category Helper Class
 */
class General_query_def {
    var $output_format = 'tsv';
    var $q_name = '';
    var $config_source = '';
    var $filter_values = array();
}

/**
 * Return data directly using query model
 */
class General_query {

    protected $config_source = '';
    protected $config_name = '';

    protected $tag = '';
    protected $title = '';
    
    // --------------------------------------------------------------------
    function __construct()
    {
    }
    
    // --------------------------------------------------------------------
    function init($config_name, $config_source)
    {
        $this->config_name = $config_name;
        $this->config_source = $config_source;
    }

    /**
     * Extract parameters from input URL segments and return object 
     * @return \General_query_def
     */
    function get_query_values_from_url()
    {
        $CI = &get_instance();
        $CI->load->helper(array('url'));

        $p = new General_query_def();
        $p->output_format = $CI->uri->segment(3);
        $p->q_name = $CI->uri->segment(4);
        $p->config_source = $CI->uri->segment(5);
        $p->filter_values = array_slice($CI->uri->segment_array(), 5);
        return $p;
    }

    // --------------------------------------------------------------------
    function setup_query_for_base_controller()
    {
        $CI = &get_instance();      
        $CI->load->helper(array('url'));

        $input_parms = new General_query_def();
        $input_parms->config_source = $CI->my_tag;
        $input_parms->output_format = $CI->uri->segment(3);
        $input_parms->q_name = $CI->uri->segment(4);
        $input_parms->filter_values = array_slice($CI->uri->segment_array(), 4);

        $this->setup_query($input_parms);
        return $input_parms;
    }

    /**
     * Called by client controller to execute query via q_model and return result in format
     * as specified by input_params object (of class General_query_def)
     * @param type $input_parms
     */
    function setup_query($input_parms)
    {
        $CI = &get_instance();      
        $CI->cu->load_mod('q_model', 'model', $input_parms->q_name, $input_parms->config_source);
        $this->add_filter_values_to_model_predicate($input_parms->filter_values, $CI->model);
    }

    /**
     * Merge input values in URL segment order with filter spec in order
     * and add results to model as predicate items
     * @param type $filter_values
     * @param type $model
     */
    private
    function add_filter_values_to_model_predicate($filter_values, $model)
    {
        $filter_specs = $model->get_primary_filter_specs();
        $i = 0;
        foreach(array_values($filter_specs) as $pi) {
            if($i >= count($filter_values)) {
                break;
            }
            
            $val = $filter_values[$i];
            if($val != '-') {
                $rel = ($pi['cmp'] == 'Rp')?'ARG':'AND';
                $model->add_predicate_item( $rel, $pi['col'], $pi['cmp'], $val);
            }
            $i++;
        }
    }
    
    /**
     * Output a result in the specified format
     * @param type $output_format
     */
    function output_result($output_format)
    {
        $CI = &get_instance();      
        $model = $CI->model;
        
        $pageTitle = $this->config_source;
        
        switch(strtolower($output_format)) {
            case 'dump':
                $CI->load->helper('test');
                dump_q_model($model);
                break;
            case 'sql':
                echo $model->get_sql();
                break;
            case 'json':
                $query = $model->get_rows();
                echo json_encode($query->result());     
                break;
            case 'tsv':
                $query = $model->get_rows();
                $result = $query->result_array();
                $this->tsv($result);
                break;
            case 'html':
            case 'table':
                $query = $model->get_rows();
                $result = $query->result_array();
                $this->html_table($result, $pageTitle);
                break;
            case 'xml':
            case 'xml_dataset':
                $query = $model->get_rows();
                $result = $query->result_array();
                $this->xml_dataset($result, $pageTitle);
                break;
        }
    }
    
    /**
     * Show results as TSV
     * @param type $result
     */
    function tsv($result)
    {
        $headers = ''; 

        header("Content-type: text/plain");

        // field headers
        foreach(array_keys(current($result)) as $field_name){
            $headers .= $field_name . "\t";
        }
        echo $headers."\n";

        // field data
        foreach($result as $row) {
            $line = '';
            foreach($row as $name => $value) {      // $name is the key, $value is the value
                if (!isset($value) || $value == "") {
                     $value = "\t";
                } else {
                     $value .= "\t";
                }       
                $line .= $value;
            }
            echo trim($line)."\n";
        }
    }
    
    /**
     * Show results as an HTML-formatted table
     */
    function html_table($result, $pageTitle)
    {
        $headers = ''; 

        header("Content-type: text/html");

        echo "<html><head><title>$pageTitle</title></head>\n";

        if (count($result) == 0) {
            echo "<p>No results were found</p>\n";
            echo "</body></html>\n";
            return;
        }
        
        // field headers
        foreach(array_keys(current($result)) as $field_name){
            $headers .= "<th>$field_name</th>";
        }
        
        echo "<table border='1' style='border: 2px solid black;'>$headers\n";

        // field data
        foreach($result as $row) {
            $line = '<tr>';
            foreach($row as $name => $value) {      // $name is the key, $value is the value
                if (!isset($value) || $value == "") {
                     $value = "";
                }       
                $line .= "<td>$value</td>";
            }
            echo trim($line)."</tr>\n";
        }
        
        echo "</table>\n";
        
        echo "</body></html>\n";
    }
    
    /**
     * Show results as XML
     * @param type $result
     * @param type $table
     */
    function xml_dataset($result, $table = 'TX')
    {       
        header("Content-type: text/plain");

        echo "<data>\n";
        
        // field data
        foreach($result as $row) {
            $line = '';
            $line .= "<$table>";
            foreach($row as $name => $value) {
                $parsedValue = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                $line .= "<$name>".$parsedValue."</$name>";
            }
            $line .= "</$table>";
            echo trim($line)."\n";
        }
        
        echo "</data>\n";
    }
    
}
