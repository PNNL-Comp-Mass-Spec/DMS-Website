<?php
namespace App\Controllers;

class Sample_prep_biomaterial_location extends DmsBase {
    function __construct()
    {
        $this->my_tag = "sample_prep_biomaterial_location";
        $this->my_title = "Sample Prep Biomaterial Location";
    }

    // --------------------------------------------------------------------
    function check_biomaterial($sproc_name = 'operations_sproc')
    {
            echo "<p>You have selected the 'Closed' state and there is biomaterial associated with this prep request.</p>";
            echo "<p>Would you like to change the state setting to special 'Closed (containers and material)' setting instead?</p>";
            echo "<p>This will retire the material and containers that are associated only with this prep request, and the request will be left in the 'Closed' state</p>";
            echo "<div style='padding-top:5px;font-weight:bold;'>";
            echo " <a href='javascript:void(0)' onclick='doSubmit(\"change\")' >Change And Continue Update</a>  &nbsp;  &nbsp; ";
            echo " <a href='javascript:void(0)' onclick='doSubmit(\"\")' >Don't Change And Continue Update</a>  &nbsp;  &nbsp; ";
            echo " <a href='javascript:void(0)' onclick='doCancel()' >Cancel Update</a>";
            echo "</div>";
    }
}
?>
