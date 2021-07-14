<?php

// --------------------------------------------------------------------
function dump_e_model($mod) {
    echo '<hr>';
    echo "Test of e_model<br>";
    echo 'q_name: ' . $mod->get_config_name() . '<br>';
    echo 'config_source: ' . $mod->get_config_source() . '<br>';
    echo '<hr>';

    $which_ones = array('fields', 'rules', 'specs', 'load_key', 'enable_spec', 'entry_commands');
    $form_def = $mod->get_form_def($which_ones);
//print_r($form_def);
    /*
      echo ' entry_commands <br>';
      print_r($form_def->entry_commands());
      echo '<hr>';

      //      echo 'get_external_source_field_map <br>';
      //      print_r($mod->get_external_source_field_map($source_name));
      //      echo '<hr>';

      echo ' field_names <br>';
      print_r($form_def->fields);
      echo '<hr>';

      echo ' field_validation_rules <br>';
      print_r($form_def->rules);
      echo '<hr>';

      echo ' field_specifications <br>';
      print_r($form_def->specs);
      echo '<hr>';

      echo ' enable_field_specifications <br>';
      print_r($form_def->enable_spec);
      echo '<hr>';
     */
    if (property_exists($form_def, 'fields')) {
        echo 'fields';
        echo ":<br>";
        print_r($form_def->fields);
        echo '<hr>';
    }
    if (property_exists($form_def, 'rules')) {
        echo 'rules';
        echo ":<br>";
        print_r($form_def->rules);
        echo '<hr>';
    }
    if (property_exists($form_def, 'specs')) {
        echo 'specs';
        echo ":<br>";
        print_r($form_def->specs);
        echo '<hr>';
    }
    if (property_exists($form_def, 'load_key')) {
        echo 'load_key';
        echo ":<br>";
        print_r($form_def->load_key);
        echo '<hr>';
    }
    if (property_exists($form_def, 'enable_spec')) {
        echo 'enable_spec';
        echo ":<br>";
        print_r($form_def->enable_spec);
        echo '<hr>';
    }
    if (property_exists($form_def, 'entry_commands')) {
        echo 'entry_commands';
        echo ":<br>";
        print_r($form_def->entry_commands);
        echo '<hr>';
    }
}

/**
 * Display interesting parameters from q_model object
 * and use it to get rows (and display in plain HTML table)
 * @param type $mod
 * @param type $option
 * @param type $dump_rows
 */
function dump_q_model($mod, $option = 'filtered_and_paged', $dump_rows = true) {

    echo "Test of q_model<br>";
    echo 'q_name: ' . $mod->get_config_name() . '<br>';
    echo 'config_source: ' . $mod->get_config_source() . '<br>';
    echo '<hr>';

    echo 'primary_filter_specs from model <br>';
    print_r($mod->get_primary_filter_specs());
    echo '<hr>';

    echo 'Column info cache: (' . $mod->get_column_info_cache_name() . ') <br>';
    print_r($mod->get_column_info_cache_data());
    echo '<hr>';

    echo 'query_parts from model: <br>';
    $qp = $mod->get_query_parts();
    echo'dbn: ' . $qp->dbn . '<br>';
    echo'table: ' . $qp->table . '<br>';
    echo'columns: ' . $qp->columns . '<br>';
    echo'predicates: <br>';
    print_r($qp->predicates);
    echo '<br>';
    echo'sorting_items: <br>';
    print_r($qp->sorting_items);
    echo '<hr>';

    $query = $mod->get_rows($option);

    echo 'SQL: <br>';
    echo $mod->get_main_sql();
    echo '<br>';
    echo 'Base SQL: <br>';
    echo $mod->get_base_sql();
    echo '<hr>';

    echo 'Cached total Rows: <br>';
    echo print_r($mod->get_cached_total_rows());
    echo '<hr>';

    echo 'Total Rows: <br>';
    echo 'Rows available without paging limits: ' . $mod->get_total_rows();
    echo '<hr>';
    if ($dump_rows) {
        echo 'Rows: <br>';
        $CI =& get_instance();
        $CI->load->library('table');
        $CI->table->set_template(
                array(
                    'table_open' => '<table border="1" cellpadding="2" cellspacing="2">'
                )
        );
        //      $this->table->set_empty("&nbsp;");
        echo $CI->table->generate($query);
    }
}

// --------------------------------------------------------------------
function dump_s_model($mod) {
    echo "Test of s_model<br>";
    echo 's_name: ' . $mod->get_config_name() . '<br>';
    echo 'config_source: ' . $mod->get_config_source() . '<br>';
    echo 'sproc_name: ' . $mod->get_sproc_name() . '<br>';
    echo '<hr>';

    echo 'sproc args from model <br>';
    print_r($mod->get_sproc_args());
    echo '<hr>';

    echo 'Bound argumentst:<br>';
    print_r($mod->get_parameters());
    echo '<hr>';

    echo 'Column info:<br>';
    print_r($mod->get_column_info());
    echo '<hr>';

    $total_rows = $mod->get_total_rows();
    echo 'Rows: (' . $total_rows . ')<br>';
    $CI =& get_instance();
    $rows = $mod->get_rows();
    if (empty($rows)) {
        echo 'No rows found<br>';
    } else {
        $CI->load->library('table');
        $CI->table->set_template(
                array(
                    'table_open' => '<table border="1" cellpadding="2" cellspacing="2">'
                )
        );
        //      $this->table->set_empty("&nbsp;");
        echo $CI->table->generate($rows);
    }
}

// --------------------------------------------------------------------
function dump_r_model($mod) {
    echo '<hr>';
    echo "Test of r_model<br>";
    echo 'config_name: ' . $mod->get_config_name() . '<br>';
    echo 'config_source: ' . $mod->get_config_source() . '<br>';
    echo "<hr>\n";

    $hl = $mod->get_list_report_hotlinks();
    $jl = json_encode($hl);
    echo " list_report_hotlinks <br>\n";
    print_r($hl);
    echo "<hr>\n";
    echo $jl;
    echo "<hr>\n";
    $hl_restored = json_decode($jl, true);
    echo print_r($hl_restored);
    echo "<hr>\n";

    $hd = $mod->get_detail_report_hotlinks();
    $jd = json_encode($hd);
    echo " detail_report_hotlinks <br>\n";
    print_r($hd);
    echo "<hr>\n";
    echo $jd;
    echo "<hr>\n";
}
