<script type="text/javascript">

function load_script_diagram() {
	var scriptName = $('#lnk_ID').html();
	if(scriptName) {
		var url = '<?= site_url() ?>pipeline_script/dot/' + scriptName
		var container = 'script_diagram_container';
		p = {};
		new Ajax.Request(url, {
			parameters: p,
			onSuccess: function(transport) {
				$(container).html(transport.responseText);
			}
		});	
	}
}
</script>

<a href="javascript:load_script_diagram()">Script...</a>
<div id="script_diagram_container">
</div>