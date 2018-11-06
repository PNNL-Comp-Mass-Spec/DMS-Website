
<?php
    // Links and buttons for various pages, including https://dms2.pnl.gov/instrument_usage_report/grid
    // Button actions are handled by javascript/data_grid.js
?>

<div id='delimited_text_ctl_panel' class='ctl_panel'>
<?= general_visibility_control('Import/Export Delimited Text', 'delimited_text_panel', 'delimited text import and export') ?>
</div>

<div id='delimited_text_panel' class='ctl_panel'>
<div class='ctl_panel'>
<span class='ctls'>
    <a id='import_grid_btn' href='javascript:void(0)' title='Replace current contents of grid from delimited text' >Import</a> grid contents from delimited text
</span>
<span class='ctls'>
    <a id='update_grid_btn' href='javascript:void(0)' title='Update current contents of grid from delimited text' >Update</a> grid contents from delimited text
</span>
<span class='ctls'>
    <a id='export_grid_btn' href='javascript:void(0)' title='Copy current contents of grid to delimited text'>Export</a> grid contents to delimited text
</span>
</div>

<div>
<textarea id="delimited_text" name="delimited_text" cols="100" rows="5" ></textarea>
</div>
</div>
