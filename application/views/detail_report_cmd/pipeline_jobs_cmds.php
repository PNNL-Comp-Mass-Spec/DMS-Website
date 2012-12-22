<script type="text/javascript">

function load_script_diagram() {
	var scriptName = $('#lnk_ID').html();
	if(scriptName) {
		var url = gamma.global.site_url + 'pipeline_script/dot/' + scriptName
		p = {};
		$.post(url, p, function (data) {
			    $('#script_diagram_container').html(data);
			}
		);
	}
}
</script>

<a href="javascript:load_script_diagram()">Script...</a>
<div id="script_diagram_container">
</div>