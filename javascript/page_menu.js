
function showHideAllMenuBlocks(mode, label) {
	var blks = $('div.qs_more');
	blks.each(function(idx, s){
			$(this).css('display', mode);
			var id = '#' + s.id + '_ctl';
			$(id).html(label);
		});
}
function showHideMenuBlock(name) {
	var block = $(name);
	var ctl_name = name + '_ctl';
	var ctl = $(ctl_name);
	var dsp = 'none';
	var label = '';
	if(block.css('display') == 'none') {
		dsp = 'block';
		label = 'Less...';
	} else
	if(block.css('display') == 'block') {
		dsp= 'none';
		label = 'More...';
	}
	showHideAllMenuBlocks('none', 'More...');
	block.css('display', dsp);
	ctl.html(label);
}
function showHideMenuDiagram() {
	var ds = $('#diagram_section');
	var ms = $('#menu_sections');
	if(ds.css('display') == "none") {
		ds.css('display', "block");
		ms.css('display', "none");
		$('#diag_ctl_label').html('Show Section Menus');
	} else {
		ds.css('display', "none");
		ms.css('display', "block");
		$('#diag_ctl_label').html('Show Diagram Menus');
	}
}
$(document).ready(function (){showHideAllMenuBlocks('none', 'More...')});

function showFlyMenu(section_name) {
	hideFlyMenus();
	section_name = section_name.replace(' ', '\\ ');
	$('#'+section_name).css('display', "block");
}
function hideFlyMenus() {
	$('div.fly_box').each(function(idx, s){
			$(this).css('display', 'none');
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
$(document).ready(function (){showFlyMenu('splash_message')});
