<?php
namespace App\Controllers;

class Aux_info_def extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "aux_info_def";
        $this->my_title = "Aux Info Definition";
    }

    // --------------------------------------------------------------------
    function test($config_name = 'aux_info_targets',  $id = '')
    {
        $this->cu = new \App\Libraries\Controller_utility();

        $this->cu->load_mod('q_model', 'data_model', $config_name, $this->my_tag);
        $filter_specs = $this->data_model->get_primary_filter_specs();
        foreach($filter_specs as $spec) {
            $this->data_model->add_predicate_item('AND', $spec['col'], $spec['cmp'], $id);
        }
        $rows = $this->data_model->get_rows('filtered_and_sorted')->getResultArray();

        $options = array();
        foreach($rows as $row) {
            $options[$row['ID']] = $row['Name'];
        }
        if(empty($options)) {
            echo "(none)";
        } else {
            helper(['form']);
            $fn = "getChildren(\"$config_name\")";
            $sz = count($options);
            echo form_multiselect('bob', $options, '', "size='$sz' id='$config_name' onclick='$fn'");
            if($id) {
                echo "<a href='javascript:void(0)' onclick='addNewMember(\"$config_name\", \"$id\")' >add new member to $config_name </a>";
            }
        }
    }


    // --------------------------------------------------------------------
    function def()
    {
        $data['title'] = 'Aux Info Definition';
        echo view('special/aux_info_def', $data);
    }
}
?>
