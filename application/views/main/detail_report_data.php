<?php if(isset($message)):?>
<?= $message ?>
<?php else: ?>
<?= make_detail_report_section($columns, $fields, $hotlinks, $my_tag, $id, $show_entry_links, $show_create_links) ?>
<?php endif; ?>