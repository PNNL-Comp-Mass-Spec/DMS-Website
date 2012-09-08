<?php if(isset($message)):?>
<?= $message ?>
<?php else: ?>
<?= make_detail_report_section($fields, $hotlinks, $my_tag, $id, $show_entry_links) ?>
<?php endif; ?>