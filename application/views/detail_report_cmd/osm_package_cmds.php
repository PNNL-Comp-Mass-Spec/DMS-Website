
<!-- check permission and limit visibility -->

<?php if ($this->cu->check_access('operation', false)): ?>
<div id='osm_cmd_container' style='margin-top:4px;'>
<input class='button lst_cmd_btn' type='button' value='Delete Package' onclick='packages.updateOSMPackage("<?= $id ?>", "delete")' title="Delete this OSM Package" />
</div>
<?php endif; ?>


<div id='entry_update_status' style="margin: 10px;"></div>
</div>

<script src="<?= base_url().'javascript/packages.js?version=101' ?>"></script>

