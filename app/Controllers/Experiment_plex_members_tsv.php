<?php
namespace App\Controllers;

class Experiment_plex_members_tsv extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experiment_plex_members_tsv";
        $this->my_title = "Experiment Plex Members TSV";
    }
}
?>
