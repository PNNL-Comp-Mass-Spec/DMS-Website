
function showHideAllMenuBlocks(mode, label) {
	$('div.qs_more').each(function(s){
			s.style.display=mode;
			var id = s.id + '_ctl';
			$(id).innerHTML = label;
		});
}
function showHideMenuBlock(name) {
	var block = $(name);
	var ctl_name = name + '_ctl';
	var ctl = $(ctl_name);
	var dsp = 'none';
	var label = '';
	if(block.style.display == 'none') {
		dsp = 'block';
		label = 'Less...';
	} else
	if(block.style.display == 'block') {
		dsp= 'none';
		label = 'More...';
	}
	showHideAllMenuBlocks('none', 'More...');
	block.style.display = dsp;
	ctl.innerHTML = label;
}
function showHideMenuDiagram() {
	var ds = $('#diagram_section');
	var ms = $('#menu_sections');
	if(ds.style.display == "none") {
		ds.style.display = "block"
		ms.style.display = "none"
		$('#diag_ctl_label').innerHTML = 'Show Section Menus';
	} else {
		ds.style.display = "none"
		ms.style.display = "block"
		$('#diag_ctl_label').innerHTML = 'Show Diagram Menus';
	}
}
Event.observe(window, 'load', function(){showHideAllMenuBlocks('none', 'More...')});

function showFlyMenu(section_name) {
	hideFlyMenus();
	$(section_name).style.display = "block";
}
function hideFlyMenus() {
	$('div.fly_box').each(function(s){
			s.style.display='none';
		});
}
// show section popup, allowing for activation delay with cancel
var tx;
function showFlyMenuOnDelay(category) {
	var fn = "showFlyMenu('" + category + "')";
	tx = setTimeout(fn,250);
}
function cancelShowFlyMenuOnDelay() {
	clearTimeout(tx);
}
Event.observe(window, 'load', function(){showFlyMenu('splash_message')});
