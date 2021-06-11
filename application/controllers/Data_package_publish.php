<?php
require("Base_controller.php");

class Data_package_publish extends Base_controller {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "data_package_publish";
        $this->my_title = "Publish Data Package";

        $this->load->model('M_data_package_publish', 'model', true);
    }

    // --------------------------------------------------------------------
    // receive manifest of files (via POST) and send to download server
    function submit()
    {
        $manifest = $this->input->post('manifest');
        send_manifest_to_download_server($manifest);
    }

    // --------------------------------------------------------------------
    // send file download manifest to download server web service
    private function send_manifest_to_download_server($manifest)
    {
        // FUTURE: change code to actually send to web service
        // for now, just dump the manifest to output as plain text
        header("Content-type: text/plain");
        return $manifest;
    }

/*
    // --------------------------------------------------------------------
    // send manifest of files (and email addresses for notification) to download server
    function download($data_package_ID)
    {
        // FUTURE: pull data $data_package_ID from POST instead of URL

        // FUTURE: provide for coarse filtering of paths

        $dp_mdata = $this->model->get_data_package_metadata($data_package_ID);
        $share_path = $this->model->get_data_package_share_folder_paths($data_package_ID);
        $job_paths = $this->model->get_data_package_job_results_folder_paths($data_package_ID);
        $ds_paths = $this->model->get_data_package_dataset_folder_paths($data_package_ID);

        // FUTURE: pull email info from POST
        $emails = json_decode('[
            { "usage":"originator", "address":"grkiebel@pnnl.gov" },
            { "usage":"recipient", "address":"grkiebel@pnnl.gov" },
            { "usage":"recipient", "address":"kenneth.auberry@pnnl.gov" }
        ]',true);

        $manifest = '';
        $manifest .= $this->format_results($dp_mdata, "package", "general");
        $manifest .= $this->format_results($emails, "email", "notifications");
        $manifest .= "<paths>\n";
        $manifest .= $this->format_results($share_path, "data_package_path");
        $manifest .= $this->format_results($job_paths, "job_path");
        $manifest .= $this->format_results($ds_paths, "dataset_path");
        $manifest .= "</paths>\n";

        $response = $this->send_manifest_to_download_server($manifest);
        echo $response;
    }

*/
    // --------------------------------------------------------------------
    // convert an array of rows (as arrays) into XML text
    // where each row becomes an XML element with the fields as attributes
    private function format_results($result, $row_element, $wrapper_element = '') {
        $xml = '';
        $xml .= ($wrapper_element == '')?'':"<$wrapper_element>\n";
        foreach($result as $row) {
            $xml .= "<$row_element ";
            foreach($row as $field => $value) {
                $xml .= "$field=\"$value\" ";
            }
            $xml .= " />\n";
        }
        $xml .= ($wrapper_element == '')?'':"</$wrapper_element>\n";
        return $xml;
    }

    // --------------------------------------------------------------------
    // send XML data package description to collaboration website server
    function collaboration($data_package_ID)
    {
        // FUTURE: pull data $data_package_ID from POST instead of URL

        // FUTURE: provide for coarse filtering of paths

        // FUGURE: include biomaterial and dataset factors

        $dp_mdata = $this->model->get_data_package_metadata($data_package_ID);
        $exp_mdata = $this->model->get_data_package_experiment_metadata($data_package_ID);
        $ds_mdata = $this->model->get_data_package_dataset_metadata($data_package_ID);
        $job_mdata = $this->model->get_data_package_job_metadata($data_package_ID);

        $description = '';
        $description .= $this->format_results($dp_mdata, "package", "general");
        $description .= $this->format_results($exp_mdata, "experiment", "experiments");
        $description .= $this->format_results($ds_mdata, "dataset", "datasets");
        $description .= $this->format_results($job_mdata, "job", "jobs");
        echo $this->send_description_to_collaboration_server($description);
    }

    // --------------------------------------------------------------------
    // send data package description to collaboration server web service
    private function send_description_to_collaboration_server($description)
    {
        // FUTURE: change code to actually send to web service
        // for now, just dump the manifest to output as plain text
        header("Content-type: text/plain");
        return $description;
    }


}
?>
