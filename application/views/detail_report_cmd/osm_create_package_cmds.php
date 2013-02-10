
<div style='padding:5px 0px 5px 5px;'>
<span class='LRcmd_cartouche' ><?= detail_report_cmd_link("Create OSM Package", "packages.revealOsmPackageCreateSection()") ?></span>
</div>
<div id='package_entry_section' style='display:none'>
<iframe src="<?= site_url() ?>/osm_package/create" height='700px' width='80%' id='embedded_page'>
</iframe>
</div>

<div style='height: 1em;'></div>

<script src="<?= base_url().'javascript/packages.js' ?>"></script>
