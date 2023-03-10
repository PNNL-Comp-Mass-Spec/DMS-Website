
<!-- check permission and limit visibility (deprecated option) -->

<?php if ($check_access('operation', false)): ?>
    <div id='osm_cmd_container' style='margin-top:4px;'>
    <input class='button lst_cmd_btn' type='button' value='Delete Package' onclick='packages.updateOSMPackage("<?= $id ?>", "delete")' title="Delete this OSM Package" />
    </div>
<?php endif; ?>


<div id='entry_update_status' style="margin: 10px;"></div>
</div>

<?php // Import packages.js ?>
<?php echo view('resource_links/packages') ?>
