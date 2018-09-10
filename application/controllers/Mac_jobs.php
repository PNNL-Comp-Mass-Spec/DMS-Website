<?php
require("Base_controller.php");

class Mac_jobs extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mac_jobs";
        $this->my_title = "MAC Job";
    }

    // --------------------------------------------------------------------
    // return supplemental form for job parameter editing
    // AJAX
    // TODO: generic enough for libraries/entry.php? Or new library?
    function parameter_form($default_key = '')
    {
        // get basic parameter definitions using the lookup key
        $def_params = array();
        if($default_key) {
            $xml_def = $this->get_param_definitions($default_key, $this->my_tag);
            $def_params = $this->extract_params_from_xml($xml_def);
        }

        // any special display features?
        $display_params = array();
        // TODO: special display definitions in config db

        $params = $this->merge_params($def_params, $display_params);

        // use parameter set XML to build supplemental form
        if(!empty($params)) {
            echo "<h3>$default_key</h3>";
            echo $this->build_param_entry_form($params, $default_key);
        } else {
            $lnk = "<a href='javascript:void(0)' onclick='entry.mac_jobs.load_param_form()' >here</a>";
            if(!$default_key) {
                $scripts = $this->get_scripts_with_param_definitions($this->my_tag);
                echo "<div>Click one of the scripts below to show form for entering parameters:</div>";
                echo "<ul>";
                foreach($scripts as $dt) {
                    $tmpl = $dt['Name'];
                    $desc = $dt['Description'];
                    echo "<li><a href='javascript:void(0)' onclick='entry.mac_jobs.choose_template(\"$tmpl\")'>$tmpl</a> $desc</li>";
                }
                echo "</ul>";
            }
        }
    }

    // --------------------------------------------------------------------
    // get list of scripts with parameters defined
    private
    function get_scripts_with_param_definitions($config_source, $config_name = 'parameter_scripts')
    {
        $this->cu->load_mod('q_model', 'swp_model', $config_name, $config_source);
        $query = $this->swp_model->get_rows('filtered_and_paged');
        return $query->result_array();
    }

    // --------------------------------------------------------------------
    // merge parameter definitions, current values, and special display definitions
    // into single array of parameter field definitions
    // TODO: move this to some other module
    private
    function merge_params($def_params, $display_params)
    {
        $p = $def_params;

        foreach($p as $key => &$def) {
            if(array_key_exists($key, $display_params)) {
                // ??
            } else {
                // default field display parameters
                $def['type'] = 'text';
                $def['size'] = '120';
            }
        }
        return array_values($p);
    }

    // --------------------------------------------------------------------
    // TODO: move this to some other module
    private
    function get_param_values($id, $config_source, $config_name = 'parameter_values')
    {
        $xml = '';
        if($id) {
            $this->cu->load_mod('q_model', 'data_model', $config_name, $config_source);
            $result_row = $this->data_model->get_item($id);
            $xml = $result_row['params'];
        }
        return $xml;
    }

    // --------------------------------------------------------------------
    // get definition of parameters for key
    // TODO: move this to some other module
    private
    function get_param_definitions($id, $config_source, $config_name = 'parameter_definitions')
    {
        $xml = '';
        if($id) {
            $this->cu->load_mod('q_model', 'def_model', $config_name, $config_source);
            $result_row = $this->def_model->get_item($id);
            $xml = $result_row['params'];
        }
        return $xml;
    }

    // --------------------------------------------------------------------
    // get array of parameters from given XML
    // with section/name/value properties for each parameter
    // TODO: move this to some other module
    private
    function extract_params_from_xml($xml)
    {
        $result = array();
        if($xml) {
            $dom = new DomDocument();
            $dom->loadXML('<root>'.$xml.'</root>');
            $xp = new domxpath($dom);
            $params = $xp->query("//Param");

            foreach ($params as $param) {
                $a = array();
                $a['name'] = $param->getAttribute('Name');
                $a['value'] = $param->getAttribute('Value');
                $a['label'] = $param->getAttribute('Label');
                $a['chooser'] = $param->getAttribute('Chooser');
                //              $a['Reqd'] = $param->getAttribute('Reqd');
//              $a['step'] = $param->getAttribute('Step');
//              $a['user'] = $param->getAttribute('User');
                $key = $a['name'];
                $result[$key] = $a;
            }
        }
        return $result;
    }

    // --------------------------------------------------------------------
    // given array of parameters, return HTML
    // for supplemental form to edit them
    // TODO: move this to some other module (libraries/entry_form?)
    private
    function build_param_entry_form($params, $script)
    {
        $this->load->model('dms_chooser', 'choosers');
        $this->load->helper(array('url', 'string', 'form'));
        $str = "";
        $header_style = "font-weight:bold;";
        if(!empty($params)) {

            $show_class = 'show_input';
            $hide_class = 'hide_input';

            $str .= "<table class='EPag'>\n";

            $str .= "<tr>";
            $str .= "<th>Parameter Name</th>";
            $str .= "<th>Parameter Value</th>";
            $str .= "<th></th>";
            $str .= "</tr>\n";

            foreach($params as $param) {
                $value = $param['value'];
                $name = $param['name'];
                $label = $param['label'];
                $chooser = $param['chooser'];

                $help_link = $this->build_wiki_help_link($script, $name);

                // place row fields in table cells in table row
                $str .= "<tr>";
                $str .= "<td>${help_link}<span> " . $label . "</span></td>";
                $str .= "<td><input name='$name' id='$name' size='120' maxlength='4096' value='$value' /></td>";
                $str .= "<td>". $this->choosers->make_chooser($name, 'picker.replace', $chooser, '', '', '', '') . "</td>";
                $str .= "</tr>\n";
            }
        }
        $str .= "</table>\n";
        return $str;
    }

    // -----------------------------------
    //
    private
    function build_wiki_help_link($script, $label)
    {
        $s = "";
        $file_tag = $this->my_tag;
        $nsLabel = str_replace(" ", "_", $label);
        $CI =& get_instance();
        $pwiki = $CI->config->item('pwiki');
        $wiki_helpLink_prefix = $CI->config->item('wikiHelpLinkPrefix');
        $href = "${pwiki}${wiki_helpLink_prefix}${file_tag}_${script}#${nsLabel}";
        $s .= "<a class=help_link target = '_blank' title='Click to bring up PRISM Wiki help page' href='".$href."'><img src='" . base_url(). "/images/help.png' border='0' ></a>";
        return $s;
    }
}
?>
