<div id='fly_master_list' >
<?= make_fly_master_list($qs_section_defs); ?>
</div> <!-- end 'fly_master_list' -->

<div id='fly_section'>
<?= make_fly_section_layout($qs_section_defs); ?>
<div id='splash_message' class='fly_box'>
<? $this->load->view("page_menu/$splash_view_name") ?>
</div>
</div> <!-- end 'fly_section' -->

