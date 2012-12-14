<script type="text/javascript">
gOpenMenuID = ''; // id of menu that is exposed
function expose_menu(menu_id) {
	gOpenMenuID = menu_id;
	var m = $(menu_id);
	m.style.display = 'block';
}
// listener for clicks - close all exposed menus
// except the one that is supposed to be open 
// (click that opens it will pass through here)  Future: maybe put a stop on the expose_menu?
function hide_exposed_menus(e) {
	var el = Event.element(e);
	var pe = Event.findElement(e, 'div');
	if(el.tagName.toLowerCase() != 'a' || pe.id != 'menu') gOpenMenuID = '';
	var menu_list = $('.ddm');
	menu_list.each(function(x){if(x.id != gOpenMenuID) x.style.display = 'none'});	
}
// set listener for clicks
Event.observe(document.body, 'click', hide_exposed_menus.bindAsEventListener());
</script>

<div id="hdrContainer">
<div id="menu">
<ul id="nav">
<? $index = 0; ?>
<? nav_bar_layout($nav_bar_menu_items, $index) ?>
</ul>
<span class="phVersion" ><?= make_search_form() ?></span>
</div>
</div>

<div id="hdrEnd">
<form action="<?= site_url()?>gen" method="post" name="OFS" id="OFS">
<input type="hidden" name="page" value="">
</form>
<?= make_version_banner() ?>
<div>


