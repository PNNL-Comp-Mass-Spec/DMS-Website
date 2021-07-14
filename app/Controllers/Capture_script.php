<?php
namespace App\Controllers;

class Capture_script extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "capture_script";
        $this->my_title = "Capture Script";
    }
/*
    // --------------------------------------------------------------------
    // display contents of given script as graph
    function dot($scriptName)
    {
        helper(['url', 'string', 'export']);
        $this->model = model('App\\Models\\'.$this->my_model);
        $this->model->make_db_connection();

        // get script XML
        $this->db->select('Contents');
        $this->db->from('T_Scripts');
        $this->db->where('Script', $scriptName);
        $query = $this->db->get();

        $result = $query->row();
        $script = $result->Contents;

        // build contents of dot file
        $s = convert_script_to_dot($script);

//      echo "<pre>$s</pre>";

        // set up file names
        $dir = "tmpfiles/";
        $fn = $dir.$scriptName.'.dot';
        $typ = "png";
        $fo = $dir.$scriptName.'.'.$typ;

        // create dot file
        file_put_contents($fn, $s);

        // generate graph image from dot file
        $output = shell_exec("dot -T$typ -o $fo $fn");
        echo "<pre>$output</pre>";

        // display graph image
        echo "<h2>Diagram of $scriptName Script</h2>";
        echo '<img src="'.base_url().$fo.'" ></img>';
    }
*/
}
?>
