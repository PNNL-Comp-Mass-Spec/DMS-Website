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


