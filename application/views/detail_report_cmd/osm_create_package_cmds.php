
<div style='padding:5px 0px 5px 5px;'>
<a href="#" onclick="revealOsmPackageCreateSection()">Create OSM Package...</a>
</div>
<div id='package_entry_section' style='display:none'>
<iframe src="<?= site_url() ?>/osm_package/create" height='700px' width='80%' id='embedded_page'>
</iframe>
</div>

<div style='height: 1em;'></div>

<script type="text/javascript">

function revealOsmPackageCreateSection() {
	var iframe = document.getElementById('embedded_page');
	var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
	var hdr = innerDoc.getElementById('hdrContainer');
	$(hdr).hide();
	Effect.toggle('package_entry_section', 'appear', { duration: 0.5 }); 
	return false;
}

</script>

