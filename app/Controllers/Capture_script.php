<?php
namespace App\Controllers;

class Capture_script extends DmsBase {
    function __construct()
    {
        $this->my_tag = "capture_script";
        $this->my_title = "Capture Script";
    }
/*
    // --------------------------------------------------------------------
    // Display contents of given script as graph
    function dot($scriptName)
    {
        helper(['url', 'text', 'export']);
        $model = model('App\\Models\\'.$this->my_model);
        $model->make_db_connection();

        // Get script XML
        // TODO: Setup $db
        $builder = $db->table('v_capture_scripts');
        $builder->select('contents');
        $builder->where('script', $scriptName);
        $query = $builder->get();

        $result = $query->getRow();
        $script = $result->Contents;

        // Build contents of dot file
        $s = convert_script_to_dot($script);

//      echo "<pre>$s</pre>";

        // Set up file names
        $dir = "generated/";
        $fn = $dir.$scriptName.'.dot';
        $typ = "png";
        $fo = $dir.$scriptName.'.'.$typ;

        // Create dot file
        file_put_contents($fn, $s);

        // Generate graph image from dot file
        $output = shell_exec("dot -T$typ -o $fo $fn");
        echo "<pre>$output</pre>";

        // Display graph image
        echo "<h2>Diagram of $scriptName Script</h2>";
        echo '<img src="'.base_url($fo).'" ></img>';
    }
*/
}
?>
