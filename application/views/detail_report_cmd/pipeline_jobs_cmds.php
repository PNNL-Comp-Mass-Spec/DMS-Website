<script type="text/javascript">

function load_script_diagram() {
	var scriptName = $('#lnk_ID').html();
	if(scriptName) {
		var url = gamma.pageContext.site_url + 'pipeline_script/dot/' + scriptName
		gamma.loadContainer(url, {}, 'script_diagram_container'); 
	}
}
</script>

<a href="javascript:load_script_diagram()">Script...</a>
<div id="script_diagram_container">
</div>