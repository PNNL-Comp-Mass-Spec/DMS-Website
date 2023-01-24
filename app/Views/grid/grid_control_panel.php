
<?php
    // Buttons for various pages, including https://dms2.pnl.gov/instrument_usage_report/grid
    // Button actions are handled by javascript/data_grid.js
?>

<div id='ctl_panel' class='ctl_panel'>

<span class='ctls'>
    <a id='reload_btn' title='Load data into editing grid' class='button' href='javascript:void(0)' >Reload</a>
</span>

<span class='ctls' id='add_col_ctl_panel'>
    <a class='button' id='add_column_btn' href='javascript:void(0)' >Add</a> <span id='add_column_legend'>a new column named:</span>
    <input id='add_column_name' type='text' size="20"></input>
</span>

<span id='save_ctls' class='ctls'>
    <a  id='save_btn' class='button' href='javascript:void(0)' >Save Changes</a>
</span>

</div>
